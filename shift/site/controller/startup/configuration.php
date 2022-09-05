<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http\Dispatch;

class Configuration extends Mvc\Controller
{
    public function index()
    {
        //=== Multi sites
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "site WHERE REPLACE(`url_host`, 'www.', '') = '" . $this->db->escape(str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\')) . "'");

        $site_id = $this->request->getInt('query.site_id', 0);
        if ($query->num_rows) {
            $site_id = (int)$query->row['site_id'];
        }
        $this->config->set('env.site_id', $site_id);
        // $this->config->set('env.url_site', '...'); // TODO: multistore url_site

        //=== Settings
        foreach (['system', 'theme'] as $group) {
            $results = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "setting` WHERE (site_id = '0' OR site_id = ?i) AND `group` = ? ORDER BY `site_id` ASC, `group` ASC, `code` ASC, `key` ASC",
                [$site_id, $group]
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
        }

        $this->config->set('env.limit', 36);

        // Apply DB setting
        $this->log->setConfig([
            'display' => $this->config->getBool('system.setting.error_display', false)
        ]);

        if ($this->config->getBool('system.setting.development')) {
            $this->cache->setup('DevNull');
        }

        //=== Event
        $events = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "event` e WHERE e.app = ?s AND e.status = 1 ORDER BY e.emitter ASC, e.priority DESC",
            ['site']
        )->rows;

        foreach ($events as $event) {
            $this->event->addListener($event['emitter'], new Dispatch($event['listener']), (int)$event['priority']);
        }

        //=== Language
        $this->load->model('extension/language');

        $languages = $this->model_extension_language->getLanguages();
        $code = $this->config->get('system.site.language', 'en');

        if (count($languages) > 1) {
            $code = $this->session->get('language', $code);

            if ($this->request->has('cookie.language') && !array_key_exists($code, $languages)) {
                $code = $this->request->get('cookie.language');
            }

            if (!$this->session->has('language') || $this->session->get('language') != $code) {
                $this->session->set('language', $code);
            }

            if (!$this->request->has('cookie.language') || $this->request->get('cookie.language') != $code) {
                setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->get('server.HTTP_HOST'));
            }
        }

        $this->config->set('env.language_id', (int)$languages[$code]['language_id']);
        $this->config->set('env.language_code', $code);

        $this->language->set('_param.active', $code);
        $this->language->load($code);

        //=== Mail
        $this->mail->setConfig([
            'engine'        => $this->config->get('system.setting.mail_engine'),
            'smtp_host'     => $this->config->get('system.setting.mail_smtp_hostname'),
            'smtp_username' => $this->config->get('system.setting.mail_smtp_username'),
            'smtp_password' => $this->config->get('system.setting.mail_smtp_password'),
            'smtp_port'     => $this->config->getInt('system.setting.mail_smtp_port', 25),
            'smtp_timeout'  => $this->config->getInt('system.setting.mail_smtp_timeout', 300),
        ]);

        //=== MVC View
        $this->view->setConfig([
            'debug'        => $this->config->getBool('system.setting.development'),
            'theme_active' => $this->config->get('system.site.theme'),
        ]);

        $this->view->setGlobal('config', $this->config);
        $this->view->setGlobal('document', $this->document);
        $this->view->setGlobal('language', $this->language);
        $this->view->setGlobal('router', $this->router);
    }
}
