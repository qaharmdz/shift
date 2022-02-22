<?php

declare(strict_types=1);

// Site
$_['site_base']         = HTTP_SERVER;
$_['site_ssl']          = HTTPS_SERVER;

// Actions
$_['action_pre_action'] = array(
    'startup/startup',
    'startup/event',
    'startup/login',
    'startup/permission'
);

// Actions
$_['action_default'] = 'common/dashboard';
