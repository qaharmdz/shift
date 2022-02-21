<?php

declare(strict_types=1);

namespace Shift\System\Core\Autoload;

class ClassLoader
{
    protected $classMap = [];

    public function __construct(
        protected \Composer\Autoload\ClassLoader $loader,
        protected string $baseDir
    ) {
        $composerClassMap = $this->loader->getClassMap();
        $this->classMap = array_diff_ukey($composerClassMap, $composerClassMap['psr4lower'], 'strcasecmp');
    }

    /**
     * Replace \Composer\Autoload\ClassLoader.
     */
    public function register()
    {
        $this->loader->unregister();
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Loads the given class or interface.
     *
     * Fallback to composer autoloader.
     *
     * @param  string    $class The name of the class
     * @return true|null True if loaded, null otherwise
     */
    public function loadClass(string $class)
    {
        if ($file = $this->findFile($class)) {
            \Composer\Autoload\includeFile($file);

            return true;
        }

        return $this->loader->loadClass($class);
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     * @return string|false The path if found, false otherwise
     */
    public function findFile(string $class)
    {
        // Class map lookup
        $namespace = strtolower($class);
        if (isset($this->classMap['psr4lower'][$namespace])) {
            return $this->classMap['psr4lower'][$namespace];
        }

        if (isset($this->classMap[$class])) {
            return $this->classMap[$class];
        }

        // PSR-4-lower lookup
        $logicalPath = substr(
            strtr($namespace, '\\', '/') . '.php',
            6 // strlen(Shift\System\Autoload\Psr4Lower::$prefixPsr4)
        );

        if (is_file($file = $this->baseDir . $logicalPath)) {
            return $file;
        }

        return false;
    }
}
