<?php

/**
 * This file is part of Shift CMS.
 *
 * Copyright (c) 2023, Mudzakkir <https://github.com/qaharmdz>
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
        //=== Root Config
        $config = new Core\Config();
        $config->set('root.version', VERSION);
        $config->set('root.version_id', VERSION_ID);

        $config->load('default', 'root');
        $config->load('app/' . $appFolder, 'root');
        $config->replaceRecursive(['root' => $rootConfig]);

        $this->set('config', $config);

        //=== Logger
        $logger = new Core\Logger();
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
        define('DB_PREFIX', $config->get('root.database.table.prefix', 'sf_'));

        //=== Session
        $this->set('session', new Core\Session($config->get('root.session')));

        //=== Environment Config
        $hostname = str_replace('www.', '', $_SERVER['HTTP_HOST'])
                    . rtrim(dirname($_SERVER['PHP_SELF'], (APP_URL_PATH ? 2 : 1)), '/') . '/';
        $site = $db->get(
            "SELECT * FROM `" . DB_PREFIX . "site` WHERE REPLACE(`url_host`, 'www.', '') LIKE ?s",
            ['%' . $hostname]
        )->row;

        if (!$site) {
            header('Location: ' . $_SERVER['PROTOCOL'] . $config->get('root.url_host'), true, 302);
            exit('╮ (. ❛ ᴗ ❛.) ╭');
        }

        define('URL_SITE', $site['url_host']);
        define('URL_APP', $site['url_host'] . APP_URL_PATH);

        $config->set('root.url_host', URL_SITE);
        $config->set('env.site_id', $site['site_id']);
        $config->set('env.app', APP_FOLDER);
        $config->set('env.url_app', URL_APP);
        $config->set('env.url_site', URL_SITE);
        $config->set('env.url_asset', URL_SITE . 'asset/');
        $config->set('env.url_media', URL_SITE . 'media/');
        $config->set('system.site.name', $site['name']);
        $config->set('system.site.url_host', $site['url_host']);

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
        foreach ($config->get('root.app_event') as $eventEmitter => $listenerRoute) {
            $event->addListener($eventEmitter, new Http\Dispatch($listenerRoute));
        }

        // Loader
        $this->set('load', new Core\Loader($this->registry));

        // View
        $this->set('view', new Mvc\View($config->get('root.template')));

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
        $language = new Library\Language();
        $this->set('language', $language);

        // Validaion assert
        $this->set('assert', new Library\Assert());

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
            'path_image'    => PATH_MEDIA,
            'path_cache'    => PATH_MEDIA . 'cache/',
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

        $request->set('query.route', $request->getString(
            'query.route',
            $config->get('root.route_default')
        ));

        try {
            if (str_starts_with($request->get('query.route'), 'startup/')) {
                throw new \Exception('Oops!');
            }

            $pageRoute = new Http\Dispatch($config->get('root.app_component'));

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
        } catch (Exception\NotFoundHttpException | \LogicException $e) {
            $logger->exceptionHandler($e);

            $request->set('query.route', $config->get('root.app_error'));
            $event = $this->get('event');

            $route  = $request->get('query.route');
            $params = [[
                'message' => $e->getMessage() . '.',
            ]];
            $output = null;

            $event->emit($eventName = 'shift/error/notfound::before', [$eventName, &$params, &$output]);
            $event->emit($eventName = 'controller/' . $route . '::before', [$eventName, &$params, &$output]);

            if (is_null($output)) {
                $dispatch = new Http\Dispatch($route);
                $dispatch->execute($params);

                $output = $response->getOutput();
            }

            $event->emit($eventName = 'controller/' . $route . '::after', [$eventName, &$params, &$output]);
            $event->emit($eventName = 'shift/error/notfound::after', [$eventName, &$params, &$output]);

        // Fallback
        } catch (\Exception $e) {
            $logger->exceptionHandler($e);

            exit('The site temporarily unavailable!');
        }

        if ($logger->getConfig('hasErrorDisplay')) {
            $config->set('system.setting.compression', 0);
        }

        // Response
        $response->setCompression($config->getInt('system.setting.compression', 0));
        return $response->send();
    }
}
