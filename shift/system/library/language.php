<?php

declare(strict_types=1);

class Language
{
    private $default = 'en-gb';
    private $locale;
    private $data = [];

    public function __construct($locale = '')
    {
        $this->locale = $locale;
    }

    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : $key);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function all()
    {
        return $this->data;
    }

    public function merge($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function load($filename)
    {
        $_ = [];
        $directories = array_unique([
            $this->default,
            $this->locale,
        ]);

        foreach ($directories as $locale) {
            $file = DIR_LANGUAGE . $locale . '/' . $filename . '.php';

            if (is_file($file)) {
                require($file);
            }
        }

        $this->merge($_);
    }
}
