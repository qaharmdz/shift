<?php

declare(strict_types=1);

require_once realpath(__DIR__ . './../') . '/path.php';

mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
    $protocol = 'https://';
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

define('HTTP_SERVER', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_SHIFT', $protocol . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

// DIR
define('DIR_APPLICATION', PATH_SHIFT . 'install/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');

define('DIR_SYSTEM', PATH_SHIFT . 'system/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_SYSTEM . 'storage/cache/');

define('DIR_IMAGE', PATH_PUBLIC . 'image/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('install');
