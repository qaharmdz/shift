<?php

declare(strict_types=1);

namespace Shift\System\Core;

final class Loader
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function controller(string $route, $data = array())
    {
        $route  = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $route);
        $output = null;

        // Trigger the pre events
        $result = $this->registry->get('event')->trigger('controller/' . $route . '/before', array(&$route, &$data, &$output));

        if ($result) {
            return $result;
        }

        if (is_null($output)) {
            $action = new Http\Dispatch($route);
            $output = $action->execute(array(&$data));
        }

        // Trigger the post events
        $result = $this->registry->get('event')->trigger('controller/' . $route . '/after', array(&$route, &$data, &$output));

        if ($output instanceof Exception) {
            return false;
        }

        return $output;
    }

    public function model(string $route)
    {
        $route = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $route);
        $modelPath = 'model_' . str_replace(['/', '-', '.'], ['_', '', ''], $route);

        // Trigger the pre events
        $this->registry->get('event')->trigger('model/' . $route . '/before', array(&$route));

        if (!$this->registry->has($modelPath)) {
            $parts = array_filter(explode('/', $route));
            $class = strtolower('Shift\\' . APP_FOLDER . '\Model\\' . implode('\\', $parts));

            if (class_exists($class)) {
                $proxy  = new Proxy();
                $object = new $class();

                foreach (get_class_vars($class) as $name => $value) {
                    $proxy->{$name} = $value;
                }

                foreach (get_class_methods($class) as $method) {
                    if ((substr($method, 0, 2) != '__') && is_callable([$object, $method])) {
                        $proxy->{$method} = $this->modelCallback($this->registry, $route . '/' . $method, $object, $method);
                    }
                }

                $proxy->{'_modelPath'} = $modelPath;
                $this->registry->set($modelPath, $proxy);
            } else {
                throw new \InvalidArgumentException(sprintf('Unable to locate model "%s".', $route));
            }
        }

        // Trigger the post events
        $this->registry->get('event')->trigger('model/' . $route . '/after', array(&$route));
    }

    protected function modelCallback($registry, $route, $class, $method)
    {
        return function ($params) use ($registry, $route, $class, $method) {
            $output = null;

            // Trigger the pre events
            $result = $registry->get('event')->trigger('model/' . $route . '/before', array(&$route, &$args, &$output));

            if ($result) {
                return $result;
            }

            if (is_null($output)) {
                $output = call_user_func_array([$class, $method], $params);
            }

            // Trigger the post events
            $result = $registry->get('event')->trigger('model/' . $route . '/after', array(&$route, &$args, &$output));

            if ($result) {
                return $result;
            }

            return $output;
        };
    }

    public function view($route, $data = array())
    {
        $output = null;

        // Sanitize the call
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

        // Trigger the pre events
        $result = $this->registry->get('event')->trigger('view/' . $route . '/before', array(&$route, &$data, &$output));

        if ($result) {
            return $result;
        }

        if (!$output) {
            $template = new \Template();

            foreach ($data as $key => $value) {
                $template->set($key, $value);
            }

            $output = $template->render($route . '.tpl');
        }

        // Trigger the post events
        $result = $this->registry->get('event')->trigger('view/' . $route . '/after', array(&$route, &$data, &$output));

        if ($result) {
            return $result;
        }

        return $output;
    }

    public function library($route)
    {
        // Sanitize the call
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

        $file = DIR_SYSTEM . 'library/' . $route . '.php';
        $class = str_replace('/', '\\', $route);

        if (is_file($file)) {
            include_once($file);

            $this->registry->set(basename($route), new $class($this->registry));
        } else {
            throw new \Exception('Error: Could not load library ' . $route . '!');
        }
    }

    public function helper($route)
    {
        $file = DIR_SYSTEM . 'helper/' . preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route) . '.php';

        if (is_file($file)) {
            include_once($file);
        } else {
            throw new \Exception('Error: Could not load helper ' . $route . '!');
        }
    }

    public function config($route)
    {
        $this->registry->get('event')->trigger('config/' . $route . '/before', array(&$route));

        $this->registry->get('config')->load($route);

        $this->registry->get('event')->trigger('config/' . $route . '/after', array(&$route));
    }

    public function language($route)
    {
        $output = null;

        $this->registry->get('event')->trigger('language/' . $route . '/before', array(&$route, &$output));

        $output = $this->registry->get('language')->load($route);

        $this->registry->get('event')->trigger('language/' . $route . '/after', array(&$route, &$output));

        return $output;
    }
}
