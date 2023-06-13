<?php

declare(strict_types=1);

namespace Shift\System\Library;

use claviska\SimpleImage;

/**
 * TODO: issue fromFile-bestFit and fromFile-resize is not load from cached
 */
class Image extends SimpleImage
{
    private array $config = [];
    protected $flags = [];
    protected $imageFile;
    protected $cacheInfo = [];

    public function __construct(array $configuration = [])
    {
        $this->setConfig($configuration);
    }

    public function setConfig(array $configuration = [])
    {
        $this->config = array_replace_recursive(
            [
                'quality'    => 100,
                'mimeType'   => 'image/webp',
                'path_image' => '',
                'path_cache' => '',
                'url'        => '',
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
     * Shortcut to create thumbnail and return the URL.
     *
     * @param  string      $imageFile
     * @param  int         $toWidth
     * @param  int         $toHeight
     * @param  string|null $cacheName
     *
     * @return string
     */
    public function getThumbnail(string $imageFile, int $toWidth = 400, int $toHeight = 400, string $cacheName = null): string
    {
        $this->setCacheInfo($imageFile, $toWidth, $toHeight, $cacheName);
        $result = $this->config['url'] . $this->cacheInfo['cacheUrlPath'];

        if (!$this->isCacheValid()) {
            $result = $this->fromFile($imageFile)->thumbnail($toWidth, $toHeight)->toCache($cacheName)->getUrl();
        }

        // Reset for next cache
        $this->cacheInfo = [];

        return $result;
    }

    /**
     * Add file checker relative to the config path_image.
     *
     * @param  string $imageFile
     */
    public function fromFile($imageFile)
    {
        $this->imageFile = $imageFile;

        if (!is_file($imageFile)) {
            $imageFile = $this->config['path_image'] . $imageFile;
        }

        return parent::fromFile($imageFile);
    }

    /**
     * Return the image cache URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->config['url'] . $this->imageFile;
    }

    /**
     * Save image to cache path.
     *
     * @param  string|null $cacheName
     */
    public function toCache(string $cacheName = null)
    {
        if (!isset($this->cacheInfo['cacheFilePath'])) {
            $this->setCacheInfo($this->imageFile, cacheName: $cacheName);
        }

        if (!$this->isCacheValid()) {
            $this->preparePath($this->cacheInfo['cacheFilePath']);
            $this->toFile($this->cacheInfo['cacheFilePath'], $this->config['mimeType'], $this->config['quality']);
        }

        // Update to the cache file image
        $this->imageFile = $this->cacheInfo['cacheUrlPath'];

        // Reset for next cache
        $this->cacheInfo = [];

        return $this;
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

    protected function setCacheInfo(string $imageFile, int $toWidth = null, int $toHeight = null, string $cacheName = null)
    {
        $this->cacheInfo = array_merge($this->cacheInfo, pathinfo($imageFile));

        switch ($this->config['mimeType']) {
            case 'image/gif':
                $this->cacheInfo['extension'] = 'gif';
                break;

            case 'image/jpeg':
                $this->cacheInfo['extension'] = 'jpeg';
                break;

            case 'image/png':
                $this->cacheInfo['extension'] = 'png';
                break;

            case 'image/webp':
            default:
                $this->cacheInfo['extension'] = 'webp';
                break;
        }

        $this->cacheInfo['imageFile'] = $imageFile;
        $this->cacheInfo['cacheFile'] = strtr('{filepath}-{width}x{height}.{ext}', [
            '{filepath}' => $this->cacheInfo['dirname'] . '/' . ($cacheName ?: $this->cacheInfo['filename']),
            '{width}'    => ($toWidth ?: $this->getWidth()),
            '{height}'   => ($toHeight ?: $this->getHeight()),
            '{ext}'      => $this->cacheInfo['extension'],
        ]);
        $this->cacheInfo['cacheFilePath'] = $this->config['path_cache'] . $this->cacheInfo['cacheFile'];
        $this->cacheInfo['cacheUrlPath'] = 'cache/' . $this->cacheInfo['cacheFile'];
    }

    protected function isCacheValid()
    {
        if (
            !$this->cacheInfo
            || !is_file($this->cacheInfo['cacheFilePath'])
            || filectime($this->config['path_image'] . $this->cacheInfo['imageFile']) > filectime($this->cacheInfo['cacheFilePath'])
            || (time() - filectime($this->cacheInfo['cacheFilePath'])) > (60 * 60)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Check and create image path.
     *
     * @param  string $image
     */
    protected function preparePath(string $image)
    {
        $path_image = str_replace('/', DS, dirname($image));

        if (!is_dir($path_image)) {
            mkdir($path_image, 0777, true);
        }
    }
}
