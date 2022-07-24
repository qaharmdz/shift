<?php

declare(strict_types=1);

namespace Shift\System\Http;

/**
 * Determine and dispatch resource from given path.
 */
class Dispatch
{
    protected string $route;
    protected string $routeMap;
    protected null|string $file = null;
    protected null|string $class = null;
    protected string $method = 'index';

    public function __construct(string $route)
    {
        $parts     = $this->routeMap($route);
        $namespace = 'Shift\\' . APP_FOLDER . '\\';
        $method    = $this->method;

        while ($parts) {
            $file = DIR_APPLICATION . implode('/', $parts) . '.php';

            if (is_file($file)) {
                $this->file  = $file;
                $this->class = strtolower($namespace . implode('\\', $parts));

                break;
            }

            $method = array_pop($parts);
        }

        if ($this->file) {
            $this->method = $method;
        }
    }

    protected function routeMap(string $route): array
    {
        $route = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $route);
        $parts = array_map('strtolower', array_filter(explode('/', $route)));

        $routeMap = strtr('controller/:folder/:file', [
            ':file'     => array_pop($parts),
            ':folder'   => implode('/', $parts),
        ]);

        $this->route    = $route;
        $this->routeMap = $routeMap;

        return explode('/', $this->routeMap);
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getData(): array
    {
        return [
            'route'     => $this->route,
            'route_map' => $this->routeMap,
            'file'      => $this->file,
            'class'     => $this->class,
            'method'    => $this->method,
        ];
    }

    public function execute(array $params = [])
    {
        if ($this->class && class_exists($this->class)) {
            $controller = new $this->class();
        } else {
            $class = $this->class ?: '???';
            throw new \InvalidArgumentException(sprintf('Unable to locate class "%s" for route "%s".', $class, $this->route));
        }

        if (!is_callable([$controller, $this->method])) {
            throw new \InvalidArgumentException(sprintf('Unable to found method "%s" in class "%s".', $this->method, $this->class));
        }

        return call_user_func_array([$controller, $this->method], array_values($params));
    }
}
