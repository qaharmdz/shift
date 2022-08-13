<?php

declare(strict_types=1);

namespace Shift\System\Http;

class Router
{
    // TODO: Route schema: Validate $route request methods.
    // Prevent access to all controller public method
    protected $routeWhitelist = [];
    protected $urlGenerators = [];

    public function addUrlGenerator(object $urlGenerator)
    {
        if (method_exists($urlGenerator, 'generateAlias')) {
            $this->urlGenerators[] = $urlGenerator;
        }
    }

    public function url(string $route, string $args = '', int $language_id = 0): string
    {
        foreach ($this->urlGenerators as $generator) {
            if ($url = $generator->generateAlias($route, $args, $language_id)) {
                return $url;
            }
        }

        return URL_APP . 'r/' . $route . ($args ? '&' . trim($args, '&') : '');
    }
}
