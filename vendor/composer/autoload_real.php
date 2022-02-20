<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit8dfff14abde0cc6748f5cc6347d025bf
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

        spl_autoload_register(array('ComposerAutoloaderInit8dfff14abde0cc6748f5cc6347d025bf', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit8dfff14abde0cc6748f5cc6347d025bf', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInit8dfff14abde0cc6748f5cc6347d025bf::getInitializer($loader)();

        $loader->register(true);

        return $loader;
    }
}
