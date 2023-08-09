<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;

class Router extends Mvc\Controller
{
    public function index()
    {
        $this->router->addUrlGenerator($this);
    }

    public function generateAlias(string $route, string $args = ''): string
    {
        if ($this->session->get('access_token')) {
            $args = $args . '&access_token=' . $this->session->getString('access_token');
        }

        return URL_APP . 'r/' . $route . ($args ? '&' . trim($args, '&') : '');
    }
}
