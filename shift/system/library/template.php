<?php

declare(strict_types=1);

class Template
{
    private $adaptor;

    public function __construct()
    {
        $this->adaptor = new \Template\PHP();
    }

    public function set($key, $value)
    {
        $this->adaptor->set($key, $value);
    }

    public function render($template)
    {
        return $this->adaptor->render($template);
    }
}
