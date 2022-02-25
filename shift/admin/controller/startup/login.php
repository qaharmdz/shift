<?php

declare(strict_types=1);

class ControllerStartupLogin extends Controller
{
    public function index()
    {
        $route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset'
        );

        // User
        $this->registry->set('user', new Cart\User($this->registry));

        if (!$this->user->isLogged() && !in_array($route, $ignore)) {
            return new Action('common/login');
        }

        if (isset($this->request->get['route'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            if (
                !in_array($route, $ignore)
                && (
                    $this->session->isEmpty('token')
                    || empty($this->request->get['token'])
                    || ($this->request->get['token'] != $this->session->get('token', 'x'))
                )
            ) {
                return new Action('common/login');
            }
        } else {
            if (
                $this->session->isEmpty('token')
                || empty($this->request->get['token'])
                || ($this->request->get['token'] != $this->session->get('token', 'x'))
            ) {
                return new Action('common/login');
            }
        }
    }
}
