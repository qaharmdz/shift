<?php

declare(strict_types=1);

require_once realpath(__DIR__ . './../') . '/path.php';

define('APP_FOLDER', 'install');
define('APP_URL_PATH', 'install/');

//=== Protocols
$protocol = 'http://';
if (
    (!empty($_SERVER['secure']) && ($_SERVER['secure'] === 'on' || $_SERVER['secure'] !== 'off'))
    || $_SERVER['SERVER_PORT'] == 443
) {
    $protocol = 'https://';
} elseif (
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
) {
    $protocol = 'https://';
}

define('HTTP_SHIFT', $protocol . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

$rootConfig = [];
if (is_file(PATH_SHIFT . 'config.php')) {
    $rootConfig = require_once PATH_SHIFT . 'config.php';
} else {
    define('HTTP_SERVER', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
}

require_once PATH_SHIFT . 'system/startup.php';

$kernel = new Shift\System\Kernel();
echo $kernel->init(APP_FOLDER)->run();
