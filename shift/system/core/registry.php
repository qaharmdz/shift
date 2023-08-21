<?php

declare(strict_types=1);

namespace Shift\System\Core;

/**
 * Sites instance of objects
 */
class Registry
{
    protected array $storage = [];
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
    public function set(string $key, object $value): object|null
    {
        if (!$this->has($key)) {
            return $this->storage[$key] = $value;
        }

        return null;
    }

    /**
     * Return object of a given key.
     */
    public function get(string $key): object
    {
        if (!$this->has($key)) {
            $trace = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 3);

            throw new \InvalidArgumentException(sprintf('Call to undefined registry key "%s" in %s line %s.', $key, $trace[1]['file'], $trace[1]['line']));
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
