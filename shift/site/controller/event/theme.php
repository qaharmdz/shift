<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Event;

use Shift\System\Core\Mvc;

class Theme extends Mvc\Controller
{
    public function index(&$view, &$data, &$output)
    {
        $theme = $this->config->get('system.site.theme', 'default');

        if (is_file(DIR_TEMPLATE . $theme . '/template/' . $view . '.tpl')) {
            $view = $theme . '/template/' . $view;
        } else {
            $view = 'default/template/' . $view;
        }
    }
}
