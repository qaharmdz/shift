<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

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

        $terms   = ['alpha', 'topbar', 'sidebarleft', 'sidebarright', 'footer', 'omega'];
        $blocks  = array_unique(array_merge($terms, $blocks));
        $modules = []; // TODO: getLayoutModules();

        $this->event->emit($eventName = 'controller/block/position::modules', [$eventName, &$modules]);

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
