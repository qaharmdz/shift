<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Core\Mvc;

class Startup extends Mvc\Controller
{
    public function index()
    {
        //=== Multi sites
        if ($this->request->getBool('server.SECURE')) {
            $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $this->db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        } else {
            $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        }

        $store_id = $this->request->get('query.store_id', 0);
        if ($query->num_rows) {
            $store_id = $query->row['store_id'];
        }
        $this->config->set('env.store_id', $store_id);

        //=== Settings
        $results = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "setting` WHERE (store_id = '0' OR store_id = ?i) AND `group` = ? ORDER BY store_id ASC",
            [$this->config->getInt('env.store_id'), 'system']
        );

        $settings = [];
        foreach ($results->rows as $row) {
            $value = $row['encoded'] ? json_decode($row['value'], true) : $row['value'];

            if ($row['code']) {
                $settings[$row['group']][$row['code']][$row['key']] = $value;
            } else {
                $settings[$row['group']][$row['key']] = $value;
            }
        }

        $this->config->set($settings);
        $this->config->set('env.limit', 25);

        // Apply DB setting
        $this->log->setConfig([
            'display' => $this->config->getBool('system.setting.error_display', false)
        ]);

        //=== Language
        $this->load->model('extension/language');

        $languages = $this->model_extension_language->getLanguages();
        $code = $this->session->get('language');

        if ($this->request->has('cookie.language') && !array_key_exists($code, $languages)) {
            $code = $this->request->get('cookie.language');
        }

        // Language Detection
        if (!$this->request->isEmpty('server.HTTP_ACCEPT_LANGUAGE') && !array_key_exists($code, $languages)) {
            $detect = '';

            $browser_languages = explode(',', $this->request->get('server.HTTP_ACCEPT_LANGUAGE'));

            // Try using local to detect the language
            foreach ($browser_languages as $browser_language) {
                foreach ($languages as $key => $value) {
                    if ($value['status']) {
                        $locale = explode(',', $value['locale']);

                        if (in_array($browser_language, $locale)) {
                            $detect = $key;
                            break 2;
                        }
                    }
                }
            }

            if (!$detect) {
                // Try using language folder to detect the language
                foreach ($browser_languages as $browser_language) {
                    if (array_key_exists(strtolower($browser_language), $languages)) {
                        $detect = strtolower($browser_language);

                        break;
                    }
                }
            }

            $code = $detect ? $detect : '';
        }

        if (!array_key_exists($code, $languages)) {
            $code = $this->config->get('system.setting.language');
        }

        if ($this->session->isEmpty('language') || $this->session->get('language') != $code) {
            $this->session->set('language', $code);
        }

        if (!$this->request->has('cookie.language') || $this->request->get('cookie.language') != $code) {
            setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->get('server.HTTP_HOST'));
        }

        // Overwrite the default language object
        $language = new \Language($code);
        $language->load($code);

        $this->registry->set('language', $language);

        $this->config->set('env.language_id', (int)$languages[$code]['language_id']);
        $this->config->set('env.language_code', $code);

        //=== User
        $this->registry->set('user', new \Cart\User($this->registry));
    }
}
