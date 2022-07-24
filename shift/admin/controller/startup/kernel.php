<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

class Kernel extends Mvc\Controller
{
    public function index()
    {
        $route = $this->request->getString(
            'query.route',
            $this->config->get('root.action_default')
        );

        if (str_starts_with($route, 'startup/')) {
            throw new \InvalidArgumentException('Oops!');
        }

        // Sanitize the call
        $route = preg_replace(['#[^a-zA-Z0-9/]#', '#/+#'], ['', '/'], $route);

        $dispatch = new Http\Dispatch($route);
        $output = $dispatch->execute();

        return $output;
    }
}
