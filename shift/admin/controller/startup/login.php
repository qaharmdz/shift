<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

class Login extends Mvc\Controller
{
    public function index()
    {
        $route = $this->request->getString('query.route');

        $ignore = array(
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset'
        );

        // User
        if (!$this->user->isLogged() && !in_array($route, $ignore)) {
            return new Http\Dispatch('common/login');
        }

        if (
            !in_array($route, $ignore)
            && (
                $this->session->isEmpty('token')
                || $this->request->isEmpty('query.token')
                || ($this->request->get('query.token', time()) != $this->session->get('token'))
            )
        ) {
            return new Http\Dispatch('common/login');
        }
    }
}
