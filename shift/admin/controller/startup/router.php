<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Exception;

class Router extends Mvc\Controller
{
    public function index()
    {
        // $this->router->addUrlGenerator($this);
    }

    public function generateAlias(string $route, string $args = '', int $language_id = 0): string
    {
        // ...
    }
}
