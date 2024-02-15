<?php

declare(strict_types=1);

namespace Shift\System\Core;

/**
 * A substitute for a real service object.
 */
class Proxy {
    protected array $storage = [];

    /**
     * @param  string $key
     */
    public function __get(string $key)
    {
        return $this->storage[$key] ?? null;
    }

    /**
     *
     * @param string $key
     * @param mixed  $callback
     */
    public function __set(string $key, mixed $callback)
    {
        $this->storage[$key] = $callback;
    }

    /**
     * @param  string $key
     * @param  array  $params
     */
    public function __call(string $key, array $params)
    {
        if (isset($this->storage[$key])) {
            return call_user_func($this->storage[$key], $params);
        } else {
            $trace = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 3);

            throw new \BadMethodCallException(sprintf('Undefined "%s::%s" in %s line %s.', $this->storage['class'], $key, $trace[0]['file'], $trace[0]['line']));
        }
    }
}
