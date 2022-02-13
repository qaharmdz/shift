<?php

declare(strict_types=1);

require_once realpath(__DIR__ . './../') . '/path.php';

define('APP_FOLDER', 'admin');
define('APP_URL_PATH', 'admin/');

if (!is_file(PATH_SHIFT . 'config.php')) {
    header('Location: install/');
    exit;
}

$rootConfig = require_once PATH_SHIFT . 'config.php';

require_once PATH_SHIFT . 'system/startup.php';

$shift = new Framework();
echo $shift->init(APP_FOLDER)->run();
