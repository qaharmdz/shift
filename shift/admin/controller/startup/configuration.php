<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http\Dispatch;

class Configuration extends Mvc\Controller
{
    public function index()
    {
        //=== Settings
        $results = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "setting` WHERE site_id = '0' AND `group` = ?s ORDER BY `site_id` ASC, `group` ASC, `code` ASC, `key` ASC",
            ['system']
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
        $this->config->set('env.accessToken', $this->session->getString('token'));
        $this->config->set('env.limit', $this->config->getInt('system.setting.admin_limit', 36));
        $this->config->set('env.development', $this->config->getInt('system.setting.development', 36));

        // Apply DB setting
        $this->log->setConfig([
            'display' => $this->config->getBool('system.setting.error_display', false)
        ]);

        //=== Event
        $events = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "event` e WHERE e.emitter LIKE ?s AND e.status = 1 ORDER BY e.priority DESC, e.emitter ASC",
            ['admin/%']
        )->rows;

        foreach ($events as $event) {
            $this->event->addListener($event['emitter'], new Dispatch($event['listener']), (int)$event['priority']);
        }

        //=== Language
        $language = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "language` WHERE code = ?s",
            [$this->config->get('system.setting.admin_language', 'en')]
        )->row;

        if ($language) {
            $this->config->set('env.language_id', (int)$language['language_id']);
            $this->config->set('env.language_code', $language['code']);
        }

        $this->language->set('_param.active', $this->config->get('env.language_code'));
        $this->language->load($this->config->get('env.language_code'));

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
        ]);

        $this->view->setGlobal('config', $this->config);
        $this->view->setGlobal('document', $this->document);
        $this->view->setGlobal('language', $this->language);
        $this->view->setGlobal('router', $this->router);
    }
}
