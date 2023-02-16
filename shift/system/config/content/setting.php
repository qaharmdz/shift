<?php

declare(strict_types=1);

// Default setting
$_['form'] = [
    'post_robots'      => 'index, follow',
    'post_comment'     => 'register',
    'post_custom_code' => '',

    'category_robots'              => 'index, follow',
    'category_post_per_page'       => 10,
    'category_post_lead'           => 2,
    'category_post_lead_excerpt'   => 100, // word
    'category_post_column'         => 2,
    'category_post_column_excerpt' => 48, // word
    'category_post_order'          => 'p.publish~desc',
    'category_custom_code'         => '',
];
