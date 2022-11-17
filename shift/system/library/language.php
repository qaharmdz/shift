<?php

declare(strict_types=1);

namespace Shift\System\Library;

use Shift\System\Core;

/**
 * Non-geographic language:
 * - Two characters language code "ISO 639-1" http://www.loc.gov/standards/iso639-2/php/code_list.php
 *
 * Geographical language:
 * - Language code "ISO 639-1" as prefix
 * - "ISO 3166-1 Alpha-2" country code as suffix https://en.wikipedia.org/wiki/ISO_3166-1
 *   - Filename: en_us, en_sg
 *   - Language code: en-US, en-SG
 */
class Language extends Core\Bags
{
    public function __construct(string $default = 'en')
    {
        $this->set([
            '_param'     => [
                'default' => $default,
                'active'  => $default,
                'loaded'  => [],
            ],
        ]);
    }

    public function get($key = null, $default = null)
    {
        $default = $default ?: 'i18n_' . $key;

        return parent::get($key, $default);
    }

    public function load(string $path, string $group = '')
    {
        $pathKey = str_replace(['/', '\\', ' '], '.', $path);
        if ($this->has('_param.loaded.' . $pathKey)) {
            return $this->get('_param.loaded.' . $pathKey);
        }

        $paths = array_unique([
            PATH_APP . 'language/' . $this->get('_param.default') . '/' . $path . '.php',
            PATH_APP . 'language/' . $this->get('_param.active') . '/' . $path . '.php'
        ]);

        $data = [];
        foreach ($paths as $path) {
            if (is_file($path)) {
                $_ = [];
                require $path;

                $data = array_replace_recursive($data, $_);
            }
        }

        if (!$data) {
            throw new \InvalidArgumentException(sprintf('Unable to locate Language file "%s".', $path));
        }

        $this->set('_param.loaded.' . $pathKey, $data);
        $this->replaceRecursive(($group ? [$group => $data] : $data));

        return $data;
    }
}
