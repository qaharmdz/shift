<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Event;

use Shift\System\Core\Mvc;

class Theme extends Mvc\Controller
{
    public function index(&$view, &$data, &$output)
    {
        // if (!$this->config->get($this->config->get('config_theme') . '_status')) {
        //     exit('Error: A theme has not been assigned to this store!');
        // }

        // This is only here for compatibility with older extensions
        // if (substr($view, -3) == 'tpl') {
        //     $view = substr($view, 0, -3);
        // }

        if ($this->config->get('config_theme') == 'theme_default' || $this->config->get('config_theme') == 'themedefault') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = $this->config->get('config_theme');
        }

        if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
            $view = $theme . '/template/' . $view;
        } else {
            $view = 'default/template/' . $view;
        }
    }
}
