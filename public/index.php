<?php

declare(strict_types=1);

require_once realpath(__DIR__ . './') . '/path.php';

// Configuration
if (is_file(PATH_SHIFT . 'config.php')) {
    require_once PATH_SHIFT . 'config.php';
}

// Install
if (!defined('DIR_APPLICATION')) {
    header('Location: install/');
    exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');
