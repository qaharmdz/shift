<?php

declare(strict_types=1);

namespace Shift\System\Core;

/**
 * Dot notation access to array bags.
 *
 * - The \Adbar\Dot public methods:
 *   - add($keys, $value = null)
 *   - all()
 *   - clear($keys = null)
 *   - delete($keys)
 *   - exists($array, $key)
 *   - flatten($delimiter = '.', $items = null, $prepend = '')
 *   - get($key = null, $default = null)
 *   - getArrayItems($items)
 *   - has($keys)
 *   - isEmpty($keys = null)
 *   - merge($key, $value = [])
 *   - mergeRecursive($key, $value = [])
 *   - mergeRecursiveDistinct($key, $value = [])
 *   - pull($key = null, $default = null)
 *   - push($key, $value = null)
 *   - replace($key, $value = [])
 *   - set($keys, $value = null)
 *   - setArray($items)
 *   - setReference(array &$items)
 *   - toJson($key = null, $options = 0)
 * .
 */
class Bags extends \Adbar\Dot {
    /**
     * Recursively replace a given array or a Dot object.
     *
     * @param array|string $key
     * @param array        $value
     */
    public function replaceRecursive($key, $value = [])
    {
        if (is_array($key)) {
            $this->items = array_replace_recursive($this->items, $key);
        } elseif (is_string($key)) {
            $items = (array) $this->get($key);
            $value = array_replace_recursive($items, $this->getArrayItems($value));

            $this->set($key, $value);
        } elseif ($key instanceof self) {
            $this->items = array_replace_recursive($this->items, $key->all());
        }
    }

    /**
     * Return the given item as integer.
     *
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return int
     */
    public function getInt($key, $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    /**
     * Return the given item as string.
     *
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return int
     */
    public function getString($key, $default = ''): string
    {
        return (string) $this->get($key, $default);
    }

    /**
     * Return the given item as array.
     *
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return int
     */
    public function getArray($key, $default = []): array
    {
        return (array) $this->get($key, $default);
    }

    /**
     * Return the given item as boolean.
     *
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return bool
     */
    public function getBool($key, $default = false): bool
    {
        $trueVal = ['enable', 'active'];
        $input = $this->get($key, $default);

        if (is_string($input) && in_array(strtolower($input), $trueVal)) {
            $input = true;
        }

        return filter_var($input, \FILTER_VALIDATE_BOOLEAN);
    }
}
