<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

use Shift\System\Mvc;

class Position extends Mvc\Controller
{
    /**
     * Get all block layout positions
     *
     * @return array
     */
    public function index(array $blocks = []): array
    {
        $this->event->emit($eventName = 'controller/block/position::blocks', [$eventName, &$blocks]);

        $terms = ['alpha', 'topbar', 'sidebarleft', 'footer', 'omega'];
        $blocks = array_unique(array_merge($terms, $blocks));

        $data = [];
        foreach ($blocks as $position) {
            $data[$position] = ''; // TODO: getModulesByPosition($position);

            if ($position == 'sidebarleft') {
                $data[$position] = $this->load->controller('block/sidebarleft');
            }
        }

        return $data;
    }
}
