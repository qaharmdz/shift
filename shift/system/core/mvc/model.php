<?php

declare(strict_types=1);

namespace Shift\System\Core\Mvc;

use Shift\System\Core;

abstract class Model
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
