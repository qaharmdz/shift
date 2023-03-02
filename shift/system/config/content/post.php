<?php

declare(strict_types=1);

// Default setting
$_['form'] = [
    'post_id'     => 0,
    'parent_id'   => 0,
    'taxonomy'    => 'post',
    'user_id'     => 0,
    'category_id' => 0,
    'visibility'  => 'public', // public, usergroup, password
    'sort_order'  => 0,
    'status'      => 'draft', // publish, pending, draft, disabled
    'created'     => date('Y-m-d H:i:s'),
    'updated'     => null,
    'publish'     => null,
    'unpublish'   => null,

    'sites'      => [0],
    'alias'      => [],
    'content'    => [
        0 => [
            'title'            => '',
            'excerpt'          => '',
            'content'          => '',
            'meta_title'       => '',
            'meta_description' => '',
            'meta_keyword'     => '',
        ],
    ],
    'meta'        => [
        'robots'      => '',
        'comment'     => '',
        'custom_code' => '', // js, css
    ],
    'term'        => [
        'categories'  => [],
        'tags'        => [],
    ]
];
