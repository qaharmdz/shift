<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;

class Router extends Mvc\Controller {
    public function index()
    {
        $this->router->addUrlGenerator($this);

        // Not supporting URL alias, trigger 404 not found
        if ($this->request->has('query._route_')) {
            $this->request->set('query.route', $this->request->get('query._route_'));
        }
    }

    public function generateAlias(string $route, string $args = ''): string
    {
        if ($this->session->get('access_token')) {
            $args = $args . '&access_token=' . $this->session->getString('access_token');
        }

        return URL_APP . 'r/' . $route . ($args ? '&' . trim($args, '&') : '');
    }
}
