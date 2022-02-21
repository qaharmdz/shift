<?php

/**
 * This file is part of Shift CMS.
 *
 * (c) Mudzakkir <https://github.com/qaharmdz>
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt> for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Shift\System;

class Framework
{
    private $registry;

    public function __construct()
    {
        $this->registry = new \Registry();
    }

    public function get(string $key): object
    {
        return $this->registry->get($key);
    }

    public function set(string $key, object $library)
    {
        return $this->registry->set($key, $library);
    }

    public function init(string $appFolder, array $rootConfig = []): Framework
    {
        /**
         * Config prefix:
         * - root: Setting from the config file and system/config folder
         * - env: Changeable setting represent "current" environment, ex: store_id, lang_id, lang_code
         * - system: Setting from database
         */
        $config = new Core\Config();
        $config->set('root.version', VERSION);
        $config->set('root.version_id', VERSION_ID);
        $config->load('default', 'root');
        $config->load('app/' . $appFolder, 'root');
        $config->replaceRecursive(['root' => $rootConfig]);

        $this->set('config', $config);

        d($config->all());

        //========================================

        // Event
        $event = new \Event($this->registry);
        $this->set('event', $event);

        // Event Register
        if ($config->has('action_event')) {
            foreach ($config->get('action_event') as $key => $value) {
                $event->register($key, new \Action($value));
            }
        }

        // Loader
        $loader = new \Loader($this->registry);
        $this->set('load', $loader);

        // Request
        $this->set('request', new \Request());

        // Response
        $response = new \Response();
        $response->addHeader('Content-Type: text/html; charset=utf-8');
        $this->set('response', $response);

        // Database
        if ($config->get('db_autostart')) {
            $this->set('db', new \DB(
                $config->get('db_type'),
                $config->get('db_hostname'),
                $config->get('db_username'),
                $config->get('db_password'),
                $config->get('db_database'),
                (int)$config->get('db_port')
            ));
        }

        // Session
        $session = new \Session();

        if ($config->get('session_autostart')) {
            $session->start();
        }

        $this->set('session', $session);

        // Cache
        $this->set('cache', new \Cache($config->get('root.cache_type'), $config->get('root.cache_expire')));

        // Url
        if ($config->get('url_autostart')) {
            $this->set('url', new \Url($config->get('site_base'), $config->get('site_ssl')));
        }

        // Language
        $language = new \Language($config->get('language_default'));
        $language->load($config->get('language_default'));
        $this->set('language', $language);

        // Document
        $this->set('document', new \Document());

        // Config Autoload
        if ($config->has('config_autoload')) {
            foreach ($config->get('config_autoload') as $value) {
                $loader->config($value);
            }
        }

        // Language Autoload
        if ($config->has('language_autoload')) {
            foreach ($config->get('language_autoload') as $value) {
                $loader->language($value);
            }
        }

        // Library Autoload
        if ($config->has('library_autoload')) {
            foreach ($config->get('library_autoload') as $value) {
                $loader->library($value);
            }
        }

        // Model Autoload
        if ($config->has('model_autoload')) {
            foreach ($config->get('model_autoload') as $value) {
                $loader->model($value);
            }
        }

        return $this;
    }

    public function run()
    {
        $config   = $this->get('config');
        $response = $this->get('response');

        // Front Controller
        $controller = new \Front($this->registry);

        // Pre Actions
        if ($config->has('action_pre_action')) {
            foreach ($config->get('action_pre_action') as $value) {
                $controller->addPreAction(new \Action($value));
            }
        }

        // Dispatch
        $controller->dispatch(new \Action($config->get('action_router')), new \Action($config->get('action_error')));

        // Output
        $response->setCompression($config->get('config_compression'));
        $response->output();
    }
}
