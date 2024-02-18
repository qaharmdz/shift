<?php

declare(strict_types=1);

// Site
$_['url_host'] = '';
$_['locale'] = 'en';

// Application
$_['app_controller'] = 'startup/app';
$_['app_startup'] = [];
$_['app_error'] = 'error/notfound';
$_['app_event'] = [];

$_['route_default'] = 'page/home';

$_['cache_driver'] = 'Files';
$_['cache_ttl'] = 1800; // in seconds

// Configuration
$_['database'] = [
    'config'               => [
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'database'  => '',
        'port'      => 3306,
        'socket'    => null,
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_520_ci',
    ],
    'prefix'               => 'sf_',
    'group_concat_max_len' => 1024 * 5,
    'modes'                => [
        'STRICT_TRANS_TABLES',
        'NO_ZERO_IN_DATE',
        'NO_ZERO_DATE',
        'NO_ENGINE_SUBSTITUTION',
        'ERROR_FOR_DIVISION_BY_ZERO',
    ],
];

$_['session'] = [
    'session_name'  => 'ShiftSessionId',
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
