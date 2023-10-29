<?php

declare(strict_types=1);

namespace Shift\System\Helper;

/**
 * Provides a dot notation access and helper functions for working with arrays.
 *
 * @link https://github.com/adbario/php-dot-notation/blob/3.x/src/Dot.php
 * @link https://github.com/laravel/framework/blob/7.x/src/Illuminate/Support/Arr.php
 * @link https://github.com/bayfrontmedia/php-array-helpers/blob/master/src/Arr.php
 */
class Arr
{
    /**
     * Checks if the given key exists in the provided array.
     *
     * @param  array      $array Array to validate
     * @param  int|string $key   The key to look for
     * @return bool
     */
    public static function exists($array, $key): bool
    {
        return array_key_exists($key, $array);
    }

    /**
     * Return the value of a given key
     *
     * @param  array           $array
     * @param  int|string|null $key
     * @param  mixed           $default
     * @return mixed
     */
    public static function get(array $array, $key = null, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !static::exists($array, $segment)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     */
    public static function set(array &$array, string $key, $value)
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            /*
             * If the key doesn't exist at this depth, an empty array is created
             * to hold the next value, allowing to create the arrays to hold final
             * values at the correct depth. Then, keep digging into the array.
             */
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Removes array items by keys
     *
     * @param  array  &$arrays
     * @param  array  $keys
     * @return array
     */
    public static function unset(array &$arrays, array $keys): array
    {
        foreach ($keys as $key) {
            unset($arrays[$key]);
        }

        return $arrays;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  array  $array
     * @param  string|array  $keys
     * @return bool
     */
    public static function has(array $array, $keys): bool
    {
        $keys = (array)$keys;

        if (!$array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (!is_array($array) || !static::exists($array, $segment)) {
                    return false;
                }

                $array = $array[$segment];
            }
        }

        return true;
    }

    /**
     * @link https://www.php.net/manual/en/function.array-chunk.php#75022
     *
     * @param  array    $list
     * @param  integer  $part
     * @return array
     */
    public static function partition(array $list, int $part = 2): array
    {
        $listlen = count($list);
        $partlen = floor($listlen / $part);
        $partrem = $listlen % $part;
        $partition = [];
        $mark = 0;

        for ($px = 0; $px < $part; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($list, (int)$mark, (int)$incr);
            $mark += $incr;
        }

        return $partition;
    }
}
