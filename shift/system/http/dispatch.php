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
    protected string $basePath;
    protected string $namespace;
    protected null|string $file = null;
    protected null|string $class = null;
    protected string $method = 'index';

    public function __construct(string $route)
    {
        $parts  = $this->routeMap($route);
        $method = $this->method;

        while ($parts) {
            $file = $this->basePath . implode('/', $parts) . '.php';

            if (is_file($file)) {
                $this->file  = $file;
                $this->class = strtolower($this->namespace . implode('\\', $parts));
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
        $parts = $extParts = array_map('strtolower', array_filter(explode('/', $route)));

        $routeMap = strtr('controller/:folder/:file', [
            ':file'     => array_pop($parts),
            ':folder'   => implode('/', $parts),
        ]);

        $this->route     = $route;
        $this->routeMap  = $routeMap;
        $this->basePath  = PATH_APP;
        $this->namespace = 'Shift\\' . APP_FOLDER . '\\';

        if ($extParts[0] === 'extensions') {
            list($ext, $extType, $extCodename) = $extParts;

            if (count($extParts) === 3) {
                $extParts[] = 'index';
            }
            $extFile = implode('/', array_slice($extParts, 2));

            $routeMap = strtr('extensions/:type/:codename/:app_folder/controller/:class_method', [
                ':type'         => $extType,
                ':codename'     => $extCodename,
                ':app_folder'   => APP_FOLDER,
                ':class_method' => $extFile,
            ]);

            $this->routeMap  = $routeMap;
            $this->basePath  = PATH_SHIFT;
            $this->namespace = 'Shift\\';
        }

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
            'routemap'  => $this->routeMap,
            'basepath'  => $this->basePath,
            'file'      => $this->file,
            'namespace' => $this->namespace,
            'class'     => $this->class,
            'method'    => $this->method,
        ];
    }

    public function execute(array $params = [])
    {
        if ($this->class && class_exists($this->class)) {
            $controller = new $this->class();
        } else {
            throw new \InvalidArgumentException(sprintf('Unable to locate class for route "%s".', $this->route));
        }

        if (!is_callable([$controller, $this->method])) {
            throw new \InvalidArgumentException(sprintf('Unable to found method "%s" in class "%s".', $this->method, $this->class));
        }

        return call_user_func_array([$controller, $this->method], array_values($params));
    }
}
