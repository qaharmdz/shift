<?php

declare(strict_types=1);

namespace Shift\System\Core;

/**
 * Stores instance of objects
 */
class Registry
{
    protected $storage = [];
    protected static $instance = null;

    public static function init(bool $fresh = false): Registry
    {
        if (null == self::$instance || $fresh) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add a given key.
     */
    public function set(string $key, object $value): object
    {
        return $this->storage[$key] = $value;
    }

    /**
     * Return object of a given key.
     */
    public function get(string $key): object
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException('Call to undefined registry key "' . $key . '".');
        }

        return $this->storage[$key];
    }

    /**
     * Check if a given key exist.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->storage);
    }

    /**
     * Delete the given key.
     */
    public function delete(string $key): void
    {
        unset($this->storage[$key]);
    }
}
