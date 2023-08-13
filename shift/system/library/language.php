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
    /**
     * Default language code always 'en' as a fallback.
     */
    public function __construct()
    {
        $this->set([
            '_param'     => [
                'default' => 'en',
                'active'  => 'en',
                'loaded'  => [],
            ],
        ]);
        $this->load('en');
    }

    public function get($key = null, $default = null)
    {
        $default = $default ?: 'i18n_' . $key;

        return parent::get($key, $default);
    }

    public function all()
    {
        $languages = $this->items;
        unset($languages['_param']);

        return $languages;
    }

    /**
     * Language file loader
     *
     * @param  string $path     Language path
     * @param  string $group    Recommend: camelCase
     * @return array
     */
    public function load(string $path, string $group = ''): array
    {
        $pathKey = str_replace(['/', '\\', ' '], '.', $path);
        if ($this->has('_param.loaded.' . $pathKey)) {
            return $this->get('_param.loaded.' . $pathKey);
        }

        $paths = [
            0 => PATH_APP . 'language/' . $this->get('_param.default') . '/' . $path . '.php',
            5 => PATH_SHIFT . 'extensions/language/' . $this->get('_param.default') . '/' . APP_FOLDER . '/' . $path . '.php',
            9 => PATH_SHIFT . 'extensions/language/' . $this->get('_param.active') . '/' . APP_FOLDER . '/' . $path . '.php',
        ];

        if ($this->get('_param.default') !== $this->get('_param.active') && $this->get('_param.active') === $path) {
            unset($paths[0]);
            unset($paths[5]);
        }

        if (str_starts_with($path, 'extensions/')) {
            $parts = array_map('strtolower', array_filter(explode('/', $path)));
            list($ext, $extType, $extCodename) = $parts;

            if (count($parts) === 3) {
                $parts[] = $extCodename;
            }
            $extFile = implode('/', array_slice($parts, 3));

            $extPath = strtr('extensions/:type/:codename/:app_folder/language/', [
                ':type'       => $extType,
                ':codename'   => $extCodename,
                ':app_folder' => APP_FOLDER,
            ]);

            $paths = $paths + [
                3 => PATH_SHIFT . $extPath . $this->get('_param.default') . '/' . $extFile . '.php',
                7 => PATH_SHIFT . $extPath . $this->get('_param.active') . '/' . $extFile . '.php',
            ];
            ksort($paths);
        }

        $data = [];
        foreach (array_unique($paths) as $_path) {
            if (is_file($_path)) {
                $_ = [];
                require $_path;

                $data = array_replace_recursive($data, $_);
            }
        }

        if (!$data) {
            throw new \InvalidArgumentException(sprintf('Unable to load language "%s".', $path));
        }

        $this->set('_param.loaded.' . $pathKey, $data);
        $this->replaceRecursive(($group ? [$group => $data] : $data));

        return $data;
    }
}
