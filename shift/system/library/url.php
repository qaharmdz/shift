<?php

declare(strict_types=1);

class Url
{
    private $url;
    private $ssl;
    private $rewrite = array();

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function addRewrite($rewrite)
    {
        $this->rewrite[] = $rewrite;
    }

    public function link($route, $args = '')
    {
        $url = $this->url . 'index.php?route=' . $route;

        if ($args) {
            if (is_array($args)) {
                $url .= '&' . http_build_query($args);
            } else {
                $url .= '&' . trim($args, '&');
            }
        }

        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }

        return $url;
    }
}
