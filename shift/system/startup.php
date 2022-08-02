<?php

declare(strict_types=1);

if (!is_array($rootConfig)) {
    exit('(╯°□°）╯︵ ┻━┻');
}

define('VERSION', '0.1.0-a.1'); // Staging: a.*, b.*, rc.*
list($major, $minor, $patch, $pre) = array_map('intval', explode('.', VERSION));
define('VERSION_ID', (($major * 10000) + ($minor * 100) + $patch));

if (version_compare(PHP_VERSION, '8.1.0', '>=') === false) {
    exit('Shift CMS require a PHP version 8.1.0+. You are running version ' . PHP_VERSION . '.');
}

mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');
error_reporting(E_ALL);
ini_set('display_errors', '1');

//=== Protocols
$secure = false;
if (!empty($rootConfig['force_ssl'])) {
    $secure = true;
} elseif (
    (!empty($_SERVER['secure']) && ($_SERVER['secure'] === 'on' || $_SERVER['secure'] !== 'off'))
    || $_SERVER['SERVER_PORT'] == 443
) {
    $secure = true;
} elseif (
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
) {
    $secure = true;
}

$_SERVER['SECURE']      = $secure;
$_SERVER['HTTPS']       = $secure ? 'on' : 'off';
$_SERVER['PROTOCOL']    = $secure ? 'https://' : 'http://';
$_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

//=== Define
if ($rootConfig) {
    define('URL_APP', $_SERVER['PROTOCOL'] . $rootConfig['url_host'] . APP_URL_PATH);
    define('URL_SITE', $_SERVER['PROTOCOL'] . $rootConfig['url_host']);
}

// DIR
define('PATH_APP', PATH_SHIFT . APP_FOLDER . DS);
define('PATH_SITE', PATH_SHIFT . 'site/');
define('PATH_LANGUAGE', PATH_SHIFT . APP_FOLDER . DS . 'language' . DS);

// TODO: $this->view->setTemplatePath()
if (APP_FOLDER == 'site') {
    define('PATH_TEMPLATE', PATH_SHIFT . APP_FOLDER . DS . 'view' . DS . 'theme' . DS);
} else {
    define('PATH_TEMPLATE', PATH_SHIFT . APP_FOLDER . DS . 'view' . DS . 'template' . DS);
}

define('PATH_SYSTEM', PATH_SHIFT . 'system' . DS);
define('PATH_TEMP', PATH_SHIFT . 'temp' . DS);
define('DIR_MEDIA', PATH_PUBLIC . 'media' . DS);

// DB
if ($rootConfig) {
    define('DB_PREFIX', $rootConfig['database']['table']['prefix']);
}

//=== Autoloader
$loader = require PATH_SHIFT . 'vendor/autoload.php';

// psr4-lower generated by Shift\System\Autoload\Psr4Lower::classMap
if (is_file(PATH_SYSTEM . 'autoloader/classmap_psr4_lowercase.php')) {
    $psr4lower = require_once PATH_SYSTEM . 'autoloader/classmap_psr4_lowercase.php';
    $loader->addClassMap($psr4lower);
}

$loaderPsr4Lower = new Shift\System\Autoloader\ClassLoader($loader, PATH_SHIFT);
$loaderPsr4Lower->register();
