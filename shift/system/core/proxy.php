<?php

declare(strict_types=1);

namespace Shift\System\Core;

/**
 * A substitute for a real service object.
 */
class Proxy
{
    public function __get(string $key)
    {
        return $this->{$key} ?? null;
    }

    public function __set(string $key, mixed $callback)
    {
        $this->{$key} = $callback;
    }

    public function __call(string $key, array $params)
    {
        if (isset($this->{$key})) {
            return call_user_func($this->{$key}, $params);
        } else {
            $trace = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 3);

            throw new \BadMethodCallException(sprintf('Undefined "%s::%s" in %s line %s.', $this->{'_class'}, $key, $trace[0]['file'], $trace[0]['line']));
        }
    }
}
