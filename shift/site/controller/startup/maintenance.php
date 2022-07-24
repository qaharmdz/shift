<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Core\Http;

class Maintenance extends Mvc\Controller
{
    public function index()
    {
        if (!$this->config->getBool('system.site.maintenance')) {
            return null;
        }

        $route = $this->request->getString(
            'query.route',
            $this->config->get('root.action_default')
        );

        if (str_starts_with($route, 'startup/')) {
            $route = $this->config->get('root.app_error');
        }

        $ignore = array(
            'common/language/language',
            'common/currency/currency'
        );

        // Show site if logged in as admin
        $this->user = new \Cart\User($this->registry);

        if (!in_array($route, $ignore) && !$this->user->isLogged()) {
            return new Http\Dispatch('common/maintenance');
        }
    }
}
