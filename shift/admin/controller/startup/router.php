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

    public function generateAlias(string $route, string $args = '', int $language_id = 0): string
    {
        if ($this->session->get('token')) {
            $args = $args . '&token=' . $this->session->getString('token');
        }

        return URL_APP . 'r/' . $route . ($args ? '&' . trim($args, '&') : '');
    }
}
