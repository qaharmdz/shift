<?php

declare(strict_types=1);

namespace Shift\System\Mvc;

class View
{
    private array $config = [];
    protected $global;
    protected $twig;

    public function __construct(array $configuration = [])
    {
        $this->setConfig($configuration);
    }

    public function setConfig(array $configuration = [])
    {
        $this->config = array_replace_recursive(
            [
                'debug'         => false,
                'timezone'      => 'UTC',
                'path_view'     => '',
                'path_cache'    => '',
                'theme_default' => '',
                'theme_active'  => '',
            ],
            $this->config,
            $configuration
        );
    }

    public function getConfig($key = null, $default = null)
    {
        if (!$key) {
            return $this->config;
        }

        return $this->config[$key] ?? $default;
    }


    /**
     * Global variable available in all templates and macros
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setGlobal(string $key, $value)
    {
        if (!isset($this->global[$key])) {
            $this->global[$key] = $value;
        }
    }

    /**
     * Template path
     *
     * @return array
     */
    public function getTemplatePath(): array
    {
        return array_unique([
            $this->config['path_view'] . ($this->config['theme_active'] ? $this->config['theme_active'] . DS : ''),
            $this->config['path_view'] . ($this->config['theme_default'] ? $this->config['theme_default'] . DS : ''),
            $this->config['path_view'],
        ]);
    }

    /**
     * Instantiate Twig environment
     *
     * @return object Twig environment
     */
    public function init()
    {
        $loader   = new \Twig\Loader\FilesystemLoader($this->getTemplatePath());
        $twig     = new \Twig\Environment($loader, [
            'charset'          => 'UTF-8',
            'autoescape'       => [$this, 'escapeByFileExtension'],
            'debug'            => $this->config['debug'],
            'auto_reload'      => $this->config['debug'],
            'strict_variables' => $this->config['debug'],
            'cache'            => $this->config['debug'] ? false : $this->config['path_cache'],
        ]);

        $twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone($this->config['timezone']);
        // $twig->addExtension(new \Twig\Extension\StringLoaderExtension());
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->addGlobal('app', $this->global);

        return $twig;
    }

    public function render(string $template, array $data = [])
    {
        if (!$this->twig) {
            $this->twig = $this->init();
        }

        return $this->twig->render($template . '.twig', $data);
    }

    /**
     * @see \Twig\FileExtensionEscapingStrategy::guess
     */
    public function escapeByFileExtension(string $name)
    {
        if (\in_array(substr($name, -1), ['/', '\\'])) {
            return 'html'; // return html for directories
        }

        if ('.twig' === substr($name, -5)) {
            $name = substr($name, 0, -5);
        }

        $extension = pathinfo($name, \PATHINFO_EXTENSION);

        switch ($extension) {
            case 'js':
                return 'js';

            case 'css':
                return 'css';

            case 'tpl':
            case 'txt':
                return false;

            default:
                return 'html';
        }
    }

    public function clearCache()
    {
        if (file_exists($this->config['path_cache'])) {
            $dirIterator = new \RecursiveDirectoryIterator($this->config['path_cache'], \FilesystemIterator::SKIP_DOTS);
            $nodes = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($nodes as $node) {
                $node->isDir() ? rmdir($node->getRealPath()) : unlink($node->getRealPath());
            }
        }
    }
}
