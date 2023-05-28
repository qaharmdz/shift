<?php

declare(strict_types=1);

namespace Shift\System\Core;

use Shift\System\Http;

class Loader
{
    protected Event $event;

    public function __construct(protected Registry $registry)
    {
        $this->event = $registry->get('event');
    }

    public function controller(string $route, ...$params)
    {
        $route  = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $route);
        $output = null;

        $this->event->emit($eventName = 'controller/' . $route . '::before', [$eventName, &$params, &$output]);

        if (is_null($output)) {
            $output = (new Http\Dispatch($route))->execute($params);
        }

        $this->event->emit($eventName = 'controller/' . $route . '::after', [$eventName, &$params, &$output]);

        return $output;
    }

    public function model(string $path)
    {
        $path = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $path);
        $model_key = 'model_' . str_replace(['/', '-', '.'], ['_', '', ''], $path);

        if (!$this->registry->has($model_key)) {
            $parts = array_filter(explode('/', $path));
            $class = strtolower('Shift\\' . APP_FOLDER . '\Model\\' . implode('\\', $parts));

            if (str_starts_with($path, 'extensions/')) {
                list($ext, $extType, $extCodename) = $parts;

                if (count($parts) === 3) {
                    $parts[] = $extCodename;
                }
                $extFile = implode('/', array_slice($parts, 3));

                $class = strtr('Shift\Extensions\:type\:codename\:app_folder\model\:file', [
                    ':type'       => $extType,
                    ':codename'   => $extCodename,
                    ':app_folder' => APP_FOLDER,
                    ':file'       => $extFile,
                ]);
            }

            if (class_exists($class)) {
                $proxy  = new Proxy();
                $object = new $class();

                foreach (get_class_vars($class) as $name => $value) {
                    $proxy->{$name} = $value;
                }

                foreach (get_class_methods($class) as $method) {
                    if ((substr($method, 0, 2) != '__') && is_callable([$object, $method])) {
                        $proxy->{$method} = $this->modelCallback($this->event, $path . '/' . $method, $object, $method);
                    }
                }

                $proxy->{'_key'} = $model_key;
                $this->registry->set($model_key, $proxy);
            } else {
                throw new \InvalidArgumentException(sprintf('Unable to locate model "%s".', $path));
            }
        }
    }

    protected function modelCallback(Event $event, string $path, $class, $method)
    {
        return function ($params) use ($event, $path, $class, $method) {
            $result = null;

            $event->emit($eventName = 'model/' . $path . '::before', [$eventName, &$params, &$result]);

            if (is_null($result)) {
                $result = call_user_func_array([$class, $method], $params);
            }

            $event->emit($eventName = 'model/' . $path . '::after', [$eventName, &$params, &$result]);

            return $result;
        };
    }

    public function view(string $template, array $vars = [])
    {
        $output = null;

        if (empty($vars['twigTemplateFromString'])) {
            $template = preg_replace(['#[^a-zA-Z0-9._/]#', '#/+#'], ['', '/'], $template);
        }

        $this->event->emit($eventName = 'view/' . $template . '::before', [$eventName, &$vars, &$output]);

        $templateFile = $template;
        if (str_starts_with($template, 'extensions/')) {
            $parts = array_map('strtolower', array_filter(explode('/', $template)));
            list($ext, $extType, $extCodename) = $parts;

            if (count($parts) === 3) {
                $parts[] = $extCodename;
            }
            $extFile = implode('/', array_slice($parts, 3));

            $templateFile = strtr(':type/:codename/:app_folder/view/:file', [
                ':type'       => $extType,
                ':codename'   => $extCodename,
                ':app_folder' => APP_FOLDER,
                ':file'       => $extFile,
            ]);
        }

        if (is_null($output)) {
            $output = $this->registry->get('view')->render($templateFile, $vars);
        }

        $this->event->emit($eventName = 'view/' . $template . '::after', [$eventName, &$vars, &$output]);

        return $output;
    }

    public function language(string $path, string $group = '')
    {
        $path = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $path);
        $data = [];

        $this->event->emit($eventName = 'language/' . $path . '::before', [$eventName, &$data, &$group]);

        $data = $this->registry->get('language')->load($path, $group);

        $this->event->emit($eventName = 'language/' . $path . '::after', [$eventName, &$data, &$group]);

        return $data;
    }

    public function config(string $path, string $group = '')
    {
        $path  = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $path);
        $group = $group ?: str_replace('/', '.', $path);
        $data  = [];

        $this->event->emit($eventName = 'config/' . $path . '::before', [$eventName, &$data, &$group]);

        $data = $this->registry->get('config')->load($path, $group);

        $this->event->emit($eventName = 'config/' . $path . '::after', [$eventName, &$data, &$group]);

        return $data;
    }
}
