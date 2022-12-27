<?php

declare(strict_types=1);

// Default admin setting
$_['form'] = [
    'category_id' => 0,
    'parent_id'   => 0,
    'taxonomy'    => 'post_category',
    'sort_order'  => 0,
    'status'      => 0,
    'created'     => null,
    'updated'     => null,

    'alias'       => [],
    'content'     => [
        0 => [
            'title'            => '',
            'content'          => '',
            'meta_title'       => '',
            'meta_description' => '',
            'meta_keyword'     => '',
        ],
    ],
    'meta'        => [
        'robots'             => 'global',
        'post_per_page'      => 10,
        'post_lead'          => 2,
        'post_lead_excerpt'  => 100, // words
        'post_column'        => 2,
        'post_column_excerp' => 45, // words
        'post_order'         => 'global',
        'custom_code'        => '', // js, css
    ],
    'relation'    => [],
];
