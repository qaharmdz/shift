<?php

declare(strict_types=1);

// Site
$_['url_host']          = '';
$_['locale']            = 'en';

// Application
$_['app_component']     = 'startup/component';
$_['app_startup']       = [];
$_['app_error']         = 'error/notfound';
$_['app_event']         = [];

$_['route_default']     = 'common/home';

$_['cache_driver']      = 'Files';
$_['cache_ttl']         = 1800; // in seconds

// Configuration
$_['database'] = [
    'config' => [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'shift',
        'port'     => 3306,
    ],
    'table' => [
        'prefix'   => 'sf_',
    ],
];

$_['session'] = [
    'session_name'  => 'SHIFTID',
    'use_cookies'   => '1',
    'use_trans_sid' => '0',
    'sid_length'    => rand(48, 64),
];

$_['template'] = [
    'path_view'     => PATH_APP . 'view/',
    'path_cache'    => PATH_TEMP . 'twig/',
    'theme_default' => '',
    'theme_active'  => '',
];
