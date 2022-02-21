<?php

declare(strict_types=1);

class Cache
{
    private $adaptor;

    public function __construct($expire = 3600)
    {
        $this->adaptor = new \Cache\File($expire);
    }

    public function get($key)
    {
        return $this->adaptor->get($key);
    }

    public function set($key, $value)
    {
        return $this->adaptor->set($key, $value);
    }

    public function delete($key)
    {
        return $this->adaptor->delete($key);
    }
}
