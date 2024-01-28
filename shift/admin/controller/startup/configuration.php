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
            "SELECT * FROM `" . DB_PREFIX . "setting`
            WHERE (site_id = '0' OR site_id = ?i) AND `group` = ?s
            ORDER BY `site_id` ASC, `group` ASC, `code` ASC, `key` ASC",
            [$this->config->get('env.site_id', 0), 'system']
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

        $this->config->set(array_merge_recursive(
            ['system' => $this->config->get('system', [])],
            $settings
        ));
        $this->config->set('env.access_token', $this->session->getString('access_token'));
        $this->config->set('env.limit', 24);
        $this->config->set('env.development', $this->config->getInt('system.setting.development', 0));
        $this->config->set('env.datetime_format', 'Y-m-d H:i:s');

        // Apply DB setting
        $logContext = [];
        if ($this->user->get('user_id')) {
            $logContext = [
                'user_id'    => $this->user->get('user_id'),
                'name'       => $this->user->get('firstname') . ' ' . $this->user->get('lastname'),
            ];
        }

        $this->log->setConfig([
            'display' => $this->config->getBool('system.setting.error_display', false),
            'context' => array_merge(
                $logContext,
                [
                    'uri'        => htmlspecialchars_decode($_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']),
                    'referrer'   => htmlspecialchars_decode($_SERVER['HTTP_REFERER'] ?? ''),
                    'method'     => $_SERVER['REQUEST_METHOD'],
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                ]
            ),
        ]);

        if ($this->config->getBool('system.setting.development')) {
            $this->cache->setup('DevNull');
        }

        //=== Event
        $events = $this->db->get(
            "SELECT e.* FROM `" . DB_PREFIX . "event` e WHERE e.emitter LIKE ?s AND e.status = 1 ORDER BY e.priority DESC, e.emitter ASC",
            ['admin/%']
        )->rows;

        foreach ($events as $event) {
            $this->event->addListener($event['emitter'], new Dispatch($event['listener']), (int)$event['priority']);
        }

        //=== Language
        $this->load->model('extension/language');

        $languages = $this->model_extension_language->getLanguages(rkey: 'codename');
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
                setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', ini_get('session.cookie_domain'), (bool)ini_get('session.cookie_secure'));
            }
        }

        $this->config->set('env.language_id', (int)$languages[$code]['extension_id']);
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
            'debug' => $this->config->getBool('system.setting.development'),
        ]);

        $this->view->setGlobal('lib', [
            'config'   => $this->config,
            'router'   => $this->router,
            'document' => $this->document,
            'language' => $this->language,
        ]);
    }
}
