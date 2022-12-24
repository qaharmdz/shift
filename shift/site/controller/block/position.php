<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Block;

use Shift\System\Mvc;

class Position extends Mvc\Controller
{
    /**
     * Get all layout modules
     *
     * @return array
     */
    public function index(array $blocks = []): array
    {
        $this->event->emit($eventName = 'controller/block/position::blocks', [$eventName, &$blocks]);

        $terms   = ['alpha', 'topbar', 'top', 'content_top', 'sidebar_left', 'sidebar_right', 'content_bottom', 'bottom', 'footer', 'omega'];
        $blocks  = array_unique(array_merge($terms, $blocks));
        $modules = ''; // TODO: getLayoutModules();

        $data = [];
        foreach ($blocks as $position) {
            $data[$position] = '';

            if (!empty($modules[$position])) {
                foreach ($modules[$position] as $module) {
                    $data[$position] .= $this->load->controller($module);
                }
            }
        }

        return $data;
    }
}
