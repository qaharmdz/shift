<?php

declare(strict_types=1);

namespace Shift\System\Autoloader;

/**
 * @ref  \Composer\Autoload\ClassLoader
 */
class ClassLoader
{
    private $includeFile;
    protected $classMap = [];

    public function __construct(
        protected \Composer\Autoload\ClassLoader $loader,
        protected string $baseDir
    ) {
        $composerClassMap = $this->loader->getClassMap();

        $this->classMap = $composerClassMap;
        if (!empty($composerClassMap['psr4lower'])) {
            $this->classMap = array_diff_ukey($composerClassMap, $composerClassMap['psr4lower'], 'strcasecmp');
        }

        /**
         * Scope isolated include.
         *
         * Prevents access to $this/self from included files.
         *
         * @param  string $file
         * @return void
         */
        $this->includeFile = static function ($file) {
            include $file;
        };
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
     * Get all the class map
     */
    public function getClassMap()
    {
        return $this->classMap;
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
            ($this->includeFile)($file);

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

        // PSR-4-lowercase lookup
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
