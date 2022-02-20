<?php

declare(strict_types=1);

require_once realpath(__DIR__ . './') . '/path.php';

define('APP_FOLDER', 'catalog'); // TODO: front
define('APP_URL_PATH', '');

if (!is_file(PATH_SHIFT . 'config.php')) {
    header('Location: install/');
    exit;
}

$rootConfig = require_once PATH_SHIFT . 'config.php';

require_once PATH_SHIFT . 'system/startup.php';

// var_dump(get_defined_constants(true)['user']);

$kernel = new Shift\System\Kernel();
echo $kernel->init(APP_FOLDER)->run();
