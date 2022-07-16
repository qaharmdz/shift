<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Event;

use Shift\System\Core\Mvc;

class Theme extends Mvc\Controller
{
    public function index(&$view, &$data, &$output)
    {
        // if (!$this->config->get($this->config->get('system.setting.theme') . '_status')) {
        //     exit('Error: A theme has not been assigned to this site!');
        // }

        // This is only here for compatibility with older extensions
        // if (substr($view, -3) == 'tpl') {
        //     $view = substr($view, 0, -3);
        // }

        if ($this->config->get('system.setting.theme') == 'theme_default' || $this->config->get('system.setting.theme') == 'themedefault') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = $this->config->get('system.setting.theme');
        }

        if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
            $view = $theme . '/template/' . $view;
        } else {
            $view = 'default/template/' . $view;
        }
    }
}
