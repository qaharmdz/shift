<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitec3ddbcfb6fb1e1b644ccfec9082eb1e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitec3ddbcfb6fb1e1b644ccfec9082eb1e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitec3ddbcfb6fb1e1b644ccfec9082eb1e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e::getInitializer($loader));

        $loader->register(true);

        $includeFiles = \Composer\Autoload\ComposerStaticInitec3ddbcfb6fb1e1b644ccfec9082eb1e::$files;
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequireec3ddbcfb6fb1e1b644ccfec9082eb1e($fileIdentifier, $file);
        }

        return $loader;
    }
}

/**
 * @param string $fileIdentifier
 * @param string $file
 * @return void
 */
function composerRequireec3ddbcfb6fb1e1b644ccfec9082eb1e($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

        require $file;
    }
}
