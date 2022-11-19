<?php

declare(strict_types=1);

// Default admin setting key-value
$_['form'] = [
    // General
    'compression'       => 0,
    // 'login_session'     => 180,

    // Admin Options
    'admin_language'    => 'en',
    'admin_limit'       => 36,

    // Mail
    'mail_engine'       => 'mail',
    'smtp_host'         => '',
    'smtp_username'     => '',
    'smtp_password'     => '',
    'smtp_port'         => 25,
    'smtp_timeout'      => 300, // In seconds

    // Cache

    // Maintenance
    'display_error'     => 0,
    'development'       => 0,
];
