<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

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

        if (!in_array($route, $ignore) && !$this->user->isLogged()) {
            return new Http\Dispatch('common/maintenance');
        }
    }
}
