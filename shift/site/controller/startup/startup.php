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
        $this->config->set('config_store_id', $store_id);

        if (!$query->num_rows) {
            // TODO: replace env.url_app, env.url_site, env.url_asset, env.url_media
            $this->config->set('config_url', URL_APP);
            $this->config->set('config_ssl', URL_APP);
        }

        //=== Settings
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $this->config->set($result['key'], $result['value']);
            } else {
                $this->config->set($result['key'], json_decode($result['value'], true));
            }
        }

        // Apply DB setting
        $this->log->setConfig([
            'display' => $this->config->getBool('config_error_display', false)
        ]);

        //=== Language
        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();
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
            $code = $this->config->get('config_language');
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

        // Set the config language_id
        $this->config->set('config_language_id', $languages[$code]['language_id']);

        $this->config->set('env.language_id', $languages[$code]['language_id']);
        $this->config->set('env.language_code', $code);

        //=== User
        $this->registry->set('user', new \Cart\User($this->registry));
    }
}
