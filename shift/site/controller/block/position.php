<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Block;

use Shift\System\Mvc;

class Position extends Mvc\Controller
{
    /**
     * Get all block Layout positions
     *
     * @return array
     */
    public function index(array $blocks = []): array
    {
        $this->event->emit($eventName = 'controller/block/position::blocks', [$eventName, &$blocks]);

        $terms = ['alpha', 'topbar', 'top', 'content_top', 'sidebar_left', 'sidebar_right', 'content_bottom', 'bottom', 'footer', 'omega'];
        $blocks = array_unique(array_merge($terms, $blocks));

        $data = [];
        foreach ($blocks as $position) {
            $data[$position] = ''; // TODO: getModulesByPosition($position);
        }

        return $data;
    }
}
