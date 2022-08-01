<?php

declare(strict_types=1);

// Configuration
if (is_file('config.php')) {
    require_once('config.php');
}

// Install
if (!defined('PATH_APP')) {
    header('Location: ../install/index.php');
    exit;
}

// Startup
require_once(PATH_SYSTEM . 'startup.php');

start('admin');
