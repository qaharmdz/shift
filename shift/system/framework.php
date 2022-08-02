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
        $this->engine();
        $this->library();

        return $this;
    }

    public function init(string $appFolder, array $rootConfig = []): Framework
    {
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

    protected function engine(): Framework
    {
        $config = $this->get('config');

        // Request
        $this->set('request', new Http\Request());

        // Router
        $this->set('router', new Http\Router(URL_APP));

        // Response
        $response = new Http\Response();
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $this->set('response', $response);

        // Event
        $event = new Core\Event($this->registry);
        $this->set('event', $event);

        // Event Register
        if ($config->has('root.action_event')) {
            foreach ($config->get('root.action_event') as $eventName => $listenerRoute) {
                $event->addListener($eventName, new Http\Dispatch($listenerRoute));
            }
        }

        // Loader
        $this->set('load', new Core\Loader($this->registry));

        // View
        $this->set('view', new Mvc\View());

        return $this;
    }

    protected function library(): Framework
    {
        $config = $this->get('config');

        // Secure
        $this->set('secure', new Library\Secure());

        // User
        $this->set('user', new Library\User($this->registry));

        // Language
        $language = new Library\Language($config->get('root.locale'));
        $language->load($config->get('root.locale'));
        $this->set('language', $language);

        // Document
        $this->set('document', new Library\Document());

        // Cache
        $this->set('cache', new Library\Cache($config->get('root.cache_driver'), [
            'path'       => PATH_TEMP . 'cache' . DS,
            'defaultTtl' => $config->get('root.cache_ttl'),
        ]));

        // Mail
        $this->set('mail', new Library\Mail(true));

        // Image
        $this->set('image', new Library\Image([
            'quality'       => 100,
            'path_image'    => DIR_MEDIA,
            'path_cache'    => DIR_MEDIA . 'cache/',
            'url'           => $config->get('env.url_media'),
        ]));

        return $this;
    }

    public function run()
    {
        $logger   = $this->get('log');
        $config   = $this->get('config');
        $request  = $this->get('request');
        $response = $this->get('response');

        try {
            $pageRoute = new Http\Dispatch($config->get('root.app_kernel'));

            foreach ($config->get('root.app_startup') as $route) {
                $dispatch = new Http\Dispatch($route);
                $result = $dispatch->execute();

                if ($result instanceof Http\Dispatch) {
                    $pageRoute = $result;
                    break;
                }
            }

            $pageRoute->execute();

        // 404 Not Found
        } catch (Exception\NotFoundHttpException | \InvalidArgumentException $e) {
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
