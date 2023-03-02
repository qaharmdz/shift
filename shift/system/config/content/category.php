<?php

declare(strict_types=1);

// Default setting
$_['form'] = [
    'category_id' => 0,
    'parent_id'   => 0,
    'taxonomy'    => 'content_category',
    'sort_order'  => 0,
    'status'      => 0,
    'created'     => null,
    'updated'     => null,

    'sites'       => [0],
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
        'robots'              => '',
        'post_per_page'       => 10,
        'post_lead'           => 2,
        'post_lead_excerpt'   => 101, // words
        'post_column'         => 2,
        'post_column_excerpt' => 48, // words
        'post_order'          => '',
        'custom_code'         => '', // js, css
    ],
    'relation'    => [],
];
