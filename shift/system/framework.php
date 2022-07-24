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
        $this->registry = Core\Registry::init();
    }

    public function get(string $key): object
    {
        return $this->registry->get($key);
    }

    public function set(string $key, object $library): object|null
    {
        return $this->registry->set($key, $library);
    }

    public function kernel(string $appFolder, array $rootConfig = []): Framework
    {
        $this->init($appFolder, $rootConfig);
        $this->core();
        $this->library();

        return $this;
    }

    public function init(string $appFolder, array $rootConfig = []): Framework
    {
        /**
         * TODO: config prefix:
         * v root: Setting from the config file and system/config folder
         * v env: Changeable setting represent "current" environment, ex: site_id, lang_id, lang_code
         * - system: Setting from database
         */
        //=== Config
        $config = new Core\Config();
        $config->set('root.version', VERSION);
        $config->set('root.version_id', VERSION_ID);

        $config->load('default', 'root');
        $config->load('app/' . $appFolder, 'root');
        $config->replaceRecursive(['root' => $rootConfig]);

        $config->set('env.app', APP_FOLDER);
        $config->set('env.url_app', URL_APP);
        $config->set('env.url_site', URL_SITE);
        $config->set('env.url_asset', URL_SITE . 'asset/');
        $config->set('env.url_media', URL_SITE . 'media/');

        $this->set('config', $config);

        //=== Logger
        $logger = new Core\Logger(['display' => true]);
        set_error_handler([$logger, 'errorHandler']);
        set_exception_handler([$logger, 'exceptionHandler']);
        register_shutdown_function([$logger, 'shutdownHandler']);
        $this->set('log', $logger);

        //=== Database
        $db = new Core\Database(...$config->get('root.database.config'));
        $db->raw('
            SET time_zone="+00:00",
                session group_concat_max_len = 102400,
                SESSION sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION";
        ');
        $this->set('db', $db);

        //=== Session
        $this->set('session', new Core\Session($config->get('root.session')));

        return $this;
    }

    protected function core(): Framework
    {
        $config = $this->get('config');

        // Request
        $this->set('request', new Core\Http\Request());

        // Router
        $this->set('router', new Core\Http\Router(URL_APP));

        // Response
        $response = new Core\Http\Response();
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $this->set('response', $response);

        // Event
        $event = new Core\Event($this->registry);
        $this->set('event', $event);

        // Event Register
        if ($config->has('root.action_event')) {
            foreach ($config->get('root.action_event') as $eventName => $listenerRoute) {
                $event->addListener($eventName, new Core\Http\Dispatch($listenerRoute));
            }
        }

        // View
        $this->set('view', new Core\Mvc\View());

        // Loader
        $this->set('load', new Core\Loader($this->registry));

        return $this;
    }

    protected function library(): Framework
    {
        $config = $this->get('config');

        // Cache
        $this->set('cache', new \Cache());

        // Language
        $language = new \Language($config->get('root.locale'));
        $language->load($config->get('root.locale'));
        $this->set('language', $language);

        // Document
        $this->set('document', new \Document());

        return $this;
    }

    public function run()
    {
        $logger   = $this->get('log');
        $config   = $this->get('config');
        $request  = $this->get('request');
        $response = $this->get('response');

        try {
            $pageRoute = new Core\Http\Dispatch($config->get('root.app_kernel'));

            foreach ($config->get('root.app_startup') as $route) {
                $dispatch = new Core\Http\Dispatch($route);
                $result = $dispatch->execute();

                if ($result instanceof Core\Http\Dispatch) {
                    $pageRoute = $result;
                    break;
                }
            }

            $pageRoute->execute();

        // 404 Not Found
        } catch (Core\Exception\NotFoundHttpException | \InvalidArgumentException $e) {
            $logger->exceptionHandler($e);
            exit('Exception: 404 not found');

        // Fallback
        } catch (Exception $e) {
            $logger->exceptionHandler($e);
            exit('The site temporarily unavailable!');
        }

        // Response
        $response->setCompression($config->getInt('system.setting.compression', 0));
        return $response->send();
    }
}
