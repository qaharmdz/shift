<?php

declare(strict_types=1);

require_once realpath(__DIR__ . './') . '/path.php';

define('APP_FOLDER', 'site');
define('APP_URL_PATH', '');

if (!is_file(PATH_SHIFT . 'config.php')) {
    header('Location: install/');
    exit;
}

$rootConfig = require_once PATH_SHIFT . 'config.php';

require_once PATH_SHIFT . 'system/startup.php';

$shift = new Shift\System\Framework();
echo $shift->kernel(APP_FOLDER, $rootConfig)->run();
