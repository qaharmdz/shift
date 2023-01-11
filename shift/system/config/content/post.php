<?php

declare(strict_types=1);

// Default admin setting
$_['form'] = [
    'post_id'     => 0,
    'parent_id'   => 0,
    'taxonomy'    => 'post',
    'user_id'     => 0,
    'category_id' => 0,
    'categories'  => [],
    'visibility'  => 'public', // public, protected (password), private (user backend)
    'sort_order'  => 0,
    'status'      => 'draft', // publish, pending, draft, trash
    'created'     => null,
    'updated'     => null,
    'publish'     => null,
    'unpublish'   => null,

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
        'robots'      => 'global',
        'custom_code' => '', // js, css
    ],
];
