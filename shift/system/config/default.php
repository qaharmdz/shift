<?php

declare(strict_types=1);

// Site
$_['site_base']         = '';
$_['site_ssl']          = false;

$_['url_base']          = '';
$_['locale']            = 'en-gb';

$_['app_kernel']        = 'startup/kernel';
$_['app_startup']       = [];

// Actions
$_['action_default']    = 'common/home';
$_['action_error']      = 'error/not_found';
$_['action_event']      = array();

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
