<?php

declare(strict_types=1);

$_['app_startup'] = [
    'startup/configuration',
    'startup/router',
    // 'startup/startup', // TODO: DB table startup
    'startup/maintenance',
];

$_['template']['theme_default'] = 'base';
$_['template']['theme_active'] = 'base';
