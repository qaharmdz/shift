<?php

declare(strict_types=1);

// Default setting
$_['form'] = [
    'layout_id'   => 0,
    'name'        => '',
    'status'      => 0,
    'routes'      => [],
    'placements'  => [
        'alpha'          => [],
        'topbar'         => [],
        // 'header'         => [],
        'top'            => [],
        'sidebar_left'   => ['setting' => ['node_child' => 'module']],
        'content_top'    => [],
        'content_left'   => ['setting' => ['node_child' => 'module']],
        'content_right'  => ['setting' => ['node_child' => 'module']],
        'content_bottom' => [],
        'sidebar_right'  => ['setting' => ['node_child' => 'module']],
        'bottom'         => [],
        'bottombar'      => [],
        'footer'         => [],
        'omega'          => [],
    ],
    'custom_code' => '',
];
