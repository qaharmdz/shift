<?php

declare(strict_types=1);

class ControllerStartupMaintenance extends Controller
{
    public function index()
    {
        if (!$this->config->get('config_maintenance')) {
            return;
        }

        $route = $this->request->getString(
            'query.route',
            $this->config->get('root.action_default')
        );

        if (str_starts_with($route, 'startup/')) {
            $route = $this->config->get('root.action_error');
        }

        $ignore = array(
            'common/language/language',
            'common/currency/currency'
        );

        // Show site if logged in as admin
        $this->user = new Cart\User($this->registry);

        if (!in_array($route, $ignore) && !$this->user->isLogged()) {
            return new Action('common/maintenance');
        }
    }
}
