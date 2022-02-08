<?php

declare(strict_types=1);

defined('DS') or define('DS', '/'); // UNIX style directory separator
defined('PATH_ROOT') or define('PATH_ROOT', str_replace('\\', DS, realpath(__DIR__ . './../')) . DS);
defined('PATH_SHIFT') or define('PATH_SHIFT', PATH_ROOT . 'shift' . DS);
defined('PATH_PUBLIC') or define('PATH_PUBLIC', str_replace('\\', DS, realpath(__DIR__)) . DS);
