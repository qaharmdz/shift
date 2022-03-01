<?php

declare(strict_types=1);

class ControllerStartupMaintenance extends Controller
{
    public function index()
    {
        if ($this->config->get('config_maintenance')) {
            $route = $this->request->get('query.route');
            if (str_starts_with($route, 'startup/')) {
                $route = $this->config->get('root.action_default');
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
}
