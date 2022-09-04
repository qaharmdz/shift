<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

class Authentication extends Mvc\Controller
{
    public function index()
    {
        $whitelist = [
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset',
        ];

        // Prevent loop
        if (in_array($this->request->get('query.route'), $whitelist)) {
            return;
        }

        $this->verifyAccess();
        $this->verifyPermission();
    }

    protected function verifyAccess()
    {
        if (!$this->request->is('ajax')) {
            $this->session->set('flash.auth.after_login', $this->request->get('query.route'));
        }

        switch (true) {
            case (!$this->user->isLogged() || !$this->request->has('query.token')):
                $this->session->set('flash.auth.require_login', true);
                $this->logout();
                break;

            // Check permission to access admin
            case !$this->user->get('backend'):
                $this->session->set('flash.auth.unauthorize', true);
                $this->logout();
                break;

            // Validate token
            case ($this->session->get('token', time()) !== $this->request->get('query.token', 'o_O')):
                $this->session->set('flash.auth.invalid_token', true);
                $this->logout();
                break;

            // Force logout if last activity more than 'x' minute, default 120 minute
            case (time() - $this->session->get('user_activity')) > (60 * $this->config->getInt('system.setting.login_session', (60 * 2))):
                $this->session->set('flash.auth.inactive', true);
                $this->logout();
                break;

            default:
                // Prevent session fixation. Renew session id per 30 minute
                if ((time() - $this->session->get('user_activity')) > (60 * 30)) {
                    $this->session->regenerateId();
                }

                $this->session->set('user_activity', time());
                break;
        }
    }

    protected function logout()
    {
        $this->user->logout();

        if ($this->request->is('ajax')) {
            $this->response->setOutputJson(['redirect' => $this->router->url('common/login')]);
        } else {
            $this->response->redirect($this->router->url('common/login'));
        }
    }

    protected function verifyPermission()
    {
        $route = '';
        $parts  = explode('/', $this->request->getString('query.route'));

        if (isset($parts[0])) {
            $route .= $parts[0];
        }

        if (isset($parts[1])) {
            $route .= '/' . $parts[1];
        }

        // Routes under extensions folder
        $extension = [
            'extension/dashboard',
            'extension/extension',
            'extension/module',
            'extension/theme',
        ];

        if (isset($parts[2]) && in_array($route, $extension)) {
            $route .= '/' . $parts[2];
        }

        $whitelist = [
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'page/dashboard',
            'error/notfound',
            'error/permission'
        ];

        if (!in_array($route, $whitelist) && !$this->user->hasPermission('access', $route)) {
            return new Http\Dispatch('error/permission');
        }
    }
}
