<?php

declare(strict_types=1);

namespace Shift\System\Core\Http;

// TODO: Route schema: URL pattern and request method validation
class Router
{
    protected $urlRewrite = [];

    public function addUrlRewrite(object $urlRewrite)
    {
        if (method_exists($urlRewrite, 'urlAlias')) {
            $this->urlRewrite[] = $urlRewrite;
        }
    }

    public function url(string $route, string $args = '', int $language_id = 0): string
    {
        foreach ($this->urlRewrite as $rewriter) {
            if ($url = $rewriter->urlAlias($route, $args, $language_id)) {
                return $url;
            }
        }

        return URL_APP . 'index.php?route=' . $route . ($args ? '&' . trim($args, '&') : '');
    }
}
