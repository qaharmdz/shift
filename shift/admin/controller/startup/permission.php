<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Core\{Http, Mvc};

class Permission extends Mvc\Controller
{
    public function index()
    {
        if ($this->request->has('query.route')) {
            $route = '';
            $part  = explode('/', $this->request->get('query.route', ''));

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }

            // If a 3rd part is found we need to check if its under one of the extension folders.
            $extension = array(
                'extension/dashboard',
                'extension/extension',
                'extension/module',
                'extension/theme',
            );

            if (isset($part[2]) && in_array($route, $extension)) {
                $route .= '/' . $part[2];
            }

            // We want to ingore some pages from having its permission checked.
            $ignore = array(
                'common/dashboard',
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
                return new Http\Dispatch('error/permission');
            }
        }
    }
}
