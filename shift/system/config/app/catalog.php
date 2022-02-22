<?php

declare(strict_types=1);

// Site
$_['site_base']        = HTTP_SERVER;
$_['site_ssl']         = HTTPS_SERVER;

// Actions
$_['action_pre_action'] = array(
    'startup/session',
    'startup/startup',
    'startup/event',
    'startup/maintenance',
    'startup/seo_url'
);

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
);
