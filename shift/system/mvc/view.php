<?php

declare(strict_types=1);

namespace Shift\System\Mvc;

use Twig\TwigFilter;
use Twig\TwigFunction;

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
            PATH_SHIFT,
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

        $twig->addGlobal('shift', $this->global);
        $twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone($this->config['timezone']);

        $twig->addFunction(new TwigFunction('combine', 'array_combine'));
        $twig->addFunction(new TwigFunction('ceil', 'ceil'));
        $twig->addFunction(new TwigFunction('floor', 'floor'));
        $twig->addFunction(new TwigFunction('round', 'round'));

        $twig->addFilter(new TwigFilter('base64_decode', 'base64_decode'));
        $twig->addFilter(new TwigFilter('base64_encode', 'base64_encode'));
        $twig->addFilter(new TwigFilter('string', 'strval'));
        $twig->addFilter(new TwigFilter('integer', 'intval'));
        $twig->addFilter(new TwigFilter('float', 'floatval'));
        $twig->addFilter(new TwigFilter('values', 'array_values'));
        $twig->addFilter(new TwigFilter('unique', 'array_unique'));
        $twig->addFilter(new TwigFilter('diff', 'array_diff'));
        $twig->addFilter(new TwigFilter('intersect', 'array_intersect'));
        $twig->addFilter(new TwigFilter('replace', 'array_replace'));
        $twig->addFilter(new TwigFilter('merge_recursive', 'array_merge_recursive'));
        $twig->addFilter(new TwigFilter('replace_recursive', 'array_replace_recursive'));

        // https://twig.symfony.com/doc/3.x/functions/dump.html
        if ($this->config['debug']) {
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        return $twig;
    }

    public function render(string $template, array $data = [])
    {
        if (!$this->twig) {
            $this->twig = $this->init();
        }

        if (!empty($data['_stringTemplate'])) {
            if (!$this->twig->hasExtension('\Twig\Extension\StringLoaderExtension')) {
                $this->twig->addExtension(new \Twig\Extension\StringLoaderExtension());
            }

            $twigTemplate = $this->twig->createTemplate($template);

            return $twigTemplate->render($data);
        }

        if (str_starts_with($template, 'extensions/')) {
            $parts = array_map('strtolower', array_filter(explode('/', $template)));
            list($ext, $extType, $extCodename) = $parts;

            if (count($parts) === 3) {
                $parts[] = $extCodename;
            }
            $extFile = implode('/', array_slice($parts, 3));

            $template = strtr('extensions/:type/:codename/:app_folder/view/:file', [
                ':type'       => $extType,
                ':codename'   => $extCodename,
                ':app_folder' => APP_FOLDER,
                ':file'       => $extFile,
            ]);
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
                if ($node->getBasename() === 'index.html') {
                    continue;
                }

                $node->isDir() ? rmdir($node->getRealPath()) : unlink($node->getRealPath());
            }
        }
    }
}
