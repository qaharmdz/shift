<?php

declare(strict_types=1);

// Site
$_['site_base']         = URL_APP;
$_['site_ssl']          = URL_APP;

// Actions
$_['action_pre_action'] = array(
    'startup/startup',
    'startup/event',
    'startup/login',
    'startup/permission'
);

// Actions
$_['action_default'] = 'common/dashboard';
