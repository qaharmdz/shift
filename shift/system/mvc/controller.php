<?php

declare(strict_types=1);

namespace Shift\System\Mvc;

use Shift\System\Core;

abstract class Controller
{
    protected Core\Registry $registry;

    public function __construct()
    {
        $this->registry = Core\Registry::init();
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }
}
