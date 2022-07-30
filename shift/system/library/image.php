<?php

declare(strict_types=1);

namespace Shift\System\Library;

use claviska\SimpleImage;

class Image extends SimpleImage
{
    private array $config = [];
    protected $flags = [];
    protected $imageFile;

    public function __construct(array $configuration = [])
    {
        $this->setConfig($configuration);
    }

    public function setConfig(array $configuration = [])
    {
        $this->config = array_replace_recursive(
            [
                'quality'       => 100,
                'path_image'    => '',
                'path_cache'    => '',
                'url'           => '',
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

    public function fromFile($file)
    {
        $this->imageFile = $file;

        if (!is_file($file)) {
            $file = $this->config['path_image'] . $file;
        }

        return parent::fromFile($file);
    }

    public function getUrl()
    {
        $pathInfo  = pathinfo($this->imageFile);
        $cacheFile = sprintf(
            '%s-%sx%s.%s',
            $pathInfo['dirname'] . '/' . $pathInfo['filename'],
            $this->getWidth(),
            $this->getHeight(),
            $pathInfo['extension']
        );
        $cachePath = $this->config['path_cache'] . $cacheFile;

        if (
            !is_file($cachePath)
            || filectime($this->config['path_image'] . $this->imageFile) > filectime($cachePath)
            || time() - filectime($cachePath) > (60 * 60)
        ) {
            $this->preparePath($cachePath);
            $this->toFile($cachePath, null, $this->config['quality']);
        }


        return $this->config['url'] . $cacheFile;
    }

    protected function preparePath($image)
    {
        $path_image = str_replace('/', DS, dirname($image));

        if (!is_dir($path_image)) {
            mkdir($path_image, 0777, true);
        }
    }
}
