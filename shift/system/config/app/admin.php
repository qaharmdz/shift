<?php

declare(strict_types=1);

$_['app_startup'] = [
    'startup/configuration',
    'startup/router',
    'startup/authentication',
    // 'startup/startup', // TODO: DB table startup
    'startup/asset',
];

$_['route_default'] = 'page/dashboard';
