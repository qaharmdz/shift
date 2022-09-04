<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

class Component extends Mvc\Controller
{
    public function index()
    {
        $route = $this->request->get('query.route');

        // Sanitize the call
        $route = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $route);

        $dispatch = new Http\Dispatch($route);
        $output = $dispatch->execute();

        return $output;
    }
}
