<?php

declare(strict_types=1);

// Site
$_['site_base']         = URL_APP;
$_['site_ssl']          = URL_APP;

// Actions
$_['action_default']    = 'install/step_1';
$_['action_router']     = 'startup/router';
$_['action_error']      = 'error/not_found';
$_['action_pre_action'] = array(
    'startup/language',
    'startup/upgrade',
    'startup/database'
);
