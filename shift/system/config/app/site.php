<?php

declare(strict_types=1);

// Actions
$_['app_startup']       = [
    'startup/startup',
    'startup/event',
    'startup/maintenance',
    'startup/router',
];

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
);
