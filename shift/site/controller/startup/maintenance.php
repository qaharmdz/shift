<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

class Maintenance extends Mvc\Controller {
    public function index()
    {
        if (
            $this->config->getBool('system.site.maintenance')
            && !$this->user->isLogged()
        ) {
            return new Http\Dispatch('page/maintenance');
        }
    }
}
