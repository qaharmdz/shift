<?php

declare(strict_types=1);

require_once realpath(dirname(__DIR__)) . '/path.php';

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

$rootConfig = [
    'url_host'     => $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME'], 2), '/.\\') . '/',
    'database'     => [
        'config' => [
            'host'     => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => '',
            'port'     => 3306,
        ],
        'table' => [
            'prefix'   => 'sf_',
        ],
    ]
];

require_once PATH_SHIFT . 'system/startup.php';

$shift = new Shift\System\Framework();
echo $shift->kernel(APP_FOLDER, $rootConfig)->run();
