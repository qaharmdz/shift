<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Event;

use Shift\System\Core\Mvc;

class Theme extends Mvc\Controller
{
    public function index(&$view, &$data, &$output)
    {
        $theme = $this->config->get('system.site.theme', 'base');

        if (!$this->config->getBool('theme.' . $theme . '.status', false)) {
            $theme = 'base'; // Base theme status is not important
        }

        $view = 'base/template/' . $view;
        if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
            $view = $theme . '/template/' . $view;
        }
    }
}
