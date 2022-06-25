<?php

declare(strict_types=1);

// Site
$_['site_base']         = URL_APP;
$_['site_ssl']          = URL_APP;

// Actions
$_['action_pre_action'] = array(
    'startup/startup',
    'startup/event',
    'startup/maintenance',
    'startup/seo_url'
);

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
);
