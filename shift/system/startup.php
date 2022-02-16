<?php

declare(strict_types=1);

if (!is_array($rootConfig)) {
    exit('(╯°□°）╯︵ ┻━┻');
}

define('VERSION', '1.0.0-a.1');
list($major, $minor, $patch, $pre) = array_map('intval', explode('.', VERSION));
define('VERSION_ID', (($major * 10000) + ($minor * 100) + $patch));

if (version_compare(phpversion(), '8.1.0', '<') == true) {
    exit('PHP v8.1 or greater version required!');
}

mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');
error_reporting(E_ALL);
ini_set('display_errors', '1');

//=== Protocols
$secure = false;
if (
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
    // TODO: merge the HTTP/ HTTPS
    if (APP_FOLDER == 'catalog') {
        define('HTTP_SERVER', $_SERVER['PROTOCOL'] . $rootConfig['url_base']);
        define('HTTPS_SERVER', $_SERVER['PROTOCOL'] . $rootConfig['url_base']);
    } else {
        define('HTTP_SERVER', $_SERVER['PROTOCOL'] . $rootConfig['url_base'] . APP_URL_PATH);
        define('HTTPS_SERVER', $_SERVER['PROTOCOL'] . $rootConfig['url_base'] . APP_URL_PATH);

        define('HTTP_CATALOG', $_SERVER['PROTOCOL'] . $rootConfig['url_base']);
        define('HTTPS_CATALOG', $_SERVER['PROTOCOL'] . $rootConfig['url_base']);
    }
}

// DIR
define('DIR_APPLICATION', PATH_SHIFT . APP_FOLDER . DS);
define('DIR_CATALOG', PATH_SHIFT . 'catalog/'); // TODO: DIR_SITE
define('DIR_LANGUAGE', PATH_SHIFT . APP_FOLDER . DS . 'language' . DS);

// TODO: $this->view->setTemplatePath()
if (APP_FOLDER == 'catalog') {
    define('DIR_TEMPLATE', PATH_SHIFT . APP_FOLDER . DS . 'view' . DS . 'theme' . DS);
} else {
    define('DIR_TEMPLATE', PATH_SHIFT . APP_FOLDER . DS . 'view' . DS . 'template' . DS);
}

define('DIR_SYSTEM', PATH_SHIFT . 'system' . DS);
define('DIR_CONFIG', DIR_SYSTEM . 'config' . DS);

define('DIR_STORAGE', PATH_SHIFT . 'storage' . DS);
define('DIR_CACHE', DIR_STORAGE . 'cache' . DS);
define('DIR_LOGS', DIR_STORAGE . 'logs' . DS);
define('DIR_UPLOAD', DIR_STORAGE . 'upload' . DS);

define('DIR_IMAGE', PATH_PUBLIC . 'image' . DS);

// DB
if ($rootConfig) {
    define('DB_DRIVER', 'mysqli');
    define('DB_HOSTNAME', $rootConfig['database']['config']['host']);
    define('DB_USERNAME', $rootConfig['database']['config']['username']);
    define('DB_PASSWORD', $rootConfig['database']['config']['password']);
    define('DB_DATABASE', $rootConfig['database']['config']['database']);
    define('DB_PORT', (int)$rootConfig['database']['config']['port']);
    define('DB_PREFIX', $rootConfig['database']['table']['prefix']);
}

//=== Autoloader
function autoload($class)
{
    $file  = str_replace('\\', DS, strtolower($class)) . '.php';
    $paths = array(
        DIR_SYSTEM . 'engine/' . DS,
        DIR_SYSTEM . 'library/' . DS,
    );

    foreach ($paths as $path) {
        if (is_file($path . $file)) {
            include_once $path . $file;
            return true;
        }
    }

    return false;
}

spl_autoload_register('autoload');
spl_autoload_extensions('.php');

require_once(DIR_SYSTEM . 'framework.php');

//=== Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
