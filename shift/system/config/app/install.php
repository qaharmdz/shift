<?php

declare(strict_types=1);

$_['app_startup'] = [
    'startup/configuration',
];

$_['route_default'] = 'page/license';

$_['database'] = [
    'modes' => [
        'STRICT_TRANS_TABLES',
        'NO_ZERO_IN_DATE',
        'NO_ZERO_DATE',
        'NO_ENGINE_SUBSTITUTION',
        'NO_AUTO_VALUE_ON_ZERO',
        'ERROR_FOR_DIVISION_BY_ZERO',
    ],
];
