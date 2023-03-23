<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Content;

use Shift\System\Mvc;
use Shift\System\Helper;

class Post extends Mvc\Model
{

    // List
    // ================================================

    /**
     * DataTables records
     *
     * @param  array  $params
     */
    public function dtRecords(array $params)
    {
        $columnMap = [
            'post_id'   => 'p.post_id',
            'title'     => 'pc.title',
            'category'  => 'tc.title AS category',
            'author'    => 'CONCAT(u.firstname, " ", u.lastname) AS author',
            'status'    => 'p.status',
            'created'   => 'p.created',
            'updated'   => 'p.updated',
            'publish'   => 'p.publish',
            'unpublish' => 'p.unpublish',
        ];
        $filterMap = $columnMap;
        $filterMap['post_id'] = 'ed.extension_data_id';

        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "post` p
                LEFT JOIN `" . DB_PREFIX . "post_content` pc ON (pc.post_id = p.post_id AND pc.language_id = " . $this->config->getInt('env.language_id') . ")
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = p.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")
                LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = p.user_id)"
            . " WHERE p.`taxonomy` = 'post'"
                 . ($dtResult['query']['where'] ? " AND " . $dtResult['query']['where'] : "")
            . " ORDER BY " . $dtResult['query']['order']
            . " LIMIT " . $dtResult['query']['limit'];

        return $this->db->get($query, $dtResult['query']['params']);
    }

    public function dtAction(string $type, array $items): array
    {
        $_items = [];

        if (in_array($type, ['publish', 'pending', 'draft', 'disabled'])) {
            $status = $type;

            foreach ($items as $item) {
                $this->db->set(
                    DB_PREFIX . 'post',
                    [
                        'status'  => $status,
                        'updated' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'post_id'  => (int)$item,
                        'taxonomy' => 'post',
                    ]
                );

                if ($this->db->affectedRows()) {
                    $_items[] = $item;
                }
            }
        }

        if ($type == 'delete') {
            $this->deletePosts($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function addPost(array $data): int
    {
        $this->db->add(
            DB_PREFIX . 'post',
            [
                'parent_id'  => 0,
                'taxonomy'   => 'post',
                'user_id'    => (int)$data['user_id'],
                'term_id'    => (int)$data['category_id'],
                'visibility' => $data['visibility'],
                'sort_order' => $data['sort_order'],
                'status'     => $data['status'],
                'created'    => date('Y-m-d H:i:s'),
                'updated'    => date('Y-m-d H:i:s'),
                'publish'    => $data['publish'] ?: null,
                'unpublish'  => $data['unpublish'] ?: null,
            ]
        );

        $post_id = (int)$this->db->insertId();

        $this->insertData($post_id, $data);

        return $post_id;
    }

    public function editPost(int $post_id, array $data)
    {
        $updated = $this->db->set(
            DB_PREFIX . 'post',
            [
                'parent_id'  => 0,
                'taxonomy'   => 'post',
                'user_id'    => (int)$data['user_id'],
                'term_id'    => (int)$data['category_id'],
                'visibility' => $data['visibility'],
                'sort_order' => $data['sort_order'],
                'status'     => $data['status'],
                'updated'    => date('Y-m-d H:i:s'),
                'publish'    => $data['publish'] ?: null,
                'unpublish'  => $data['unpublish'] ?: null,
            ],
            ['post_id' => $post_id]
        );

        if (!empty($updated->affected_rows)) {
            $this->db->delete(DB_PREFIX . 'post_content', ['post_id' => $post_id]);
            $this->db->delete(DB_PREFIX . 'post_meta', ['post_id' => $post_id]);
            $this->db->delete(DB_PREFIX . 'site_relation', [
                'taxonomy' => 'content_post',
                'taxonomy_id' => $post_id
            ]);
            $this->db->delete(DB_PREFIX . 'term_relation', [
                'taxonomy' => 'content_post',
                'taxonomy_id' => $post_id
            ]);
            $this->db->delete(DB_PREFIX . 'route_alias', [
                'route' => 'content/post',
                'param' => 'post_id',
                'value' => $post_id
            ]);

            $this->insertData($post_id, $data);

            return $updated->affected_rows;
        }
    }

    protected function insertData(int $post_id, array $data)
    {
        foreach ($data['content'] as $language_id => $content) {
            $this->db->add(
                DB_PREFIX . 'post_content',
                [
                    'post_id'          => $post_id,
                    'language_id'      => (int)$language_id,
                    'title'            => $content['title'],
                    'excerpt'          => $content['excerpt'],
                    'content'          => $content['content'],
                    'meta_title'       => $content['meta_title'],
                    'meta_description' => $content['meta_description'],
                    'meta_keyword'     => $content['meta_keyword'],
                ]
            );
        }

        // Meta setting
        foreach ($data['meta'] as $key => $value) {
            $this->db->add(
                DB_PREFIX . 'post_meta',
                [
                    'post_id' => $post_id,
                    'key'     => $key,
                    'value'   => (is_array($value) ? json_encode($value) : $value),
                    'encoded' => (is_array($value) ? 1 : 0),
                ]
            );
        }

        // Taxonomy
        foreach ($data['sites'] as $site_id) {
            $this->db->add(
                DB_PREFIX . 'site_relation',
                [
                    'site_id'     => $site_id,
                    'taxonomy'    => 'content_post',
                    'taxonomy_id' => $post_id,
                ]
            );
        }

        foreach ($data['term']['categories'] as $term_id) {
            $this->db->add(
                DB_PREFIX . 'term_relation',
                [
                    'term_id'     => $term_id,
                    'taxonomy'    => 'content_post',
                    'taxonomy_id' => $post_id,
                ]
            );
        }

        $this->load->model('content/tag');
        foreach ($data['term']['tags'] as $term_id) {
            if (!(int)$term_id) {
                $term_id = $this->model_content_tag->addTagByTitle(title: $term_id);
            }

            $this->db->add(
                DB_PREFIX . 'term_relation',
                [
                    'term_id'     => $term_id,
                    'taxonomy'    => 'content_post',
                    'taxonomy_id' => $post_id,
                ]
            );
        }

        // URL alias
        $this->load->model('setting/site');
        $sites = $this->model_setting_site->getSites();

        foreach ($sites as $site) {
            $alias = '';
            foreach ($data['alias'] as $language_id => $alias) {
                if (!$alias = str_replace(' ', '-', trim($alias))) {
                    continue;
                }

                $_aliasCount = $this->db->get(
                    "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "route_alias` WHERE `site_id` = ?i AND `language_id` = ?i AND `alias` = ?s",
                    [$site['site_id'], $language_id, $alias]
                )->row['total'];

                if ($_aliasCount) {
                    $alias = $alias . '-' . $post_id . '-' . $site['site_id'] . '-' . $language_id;
                }

                $this->db->add(
                    DB_PREFIX . 'route_alias',
                    [
                        'site_id'     => (int)$site['site_id'],
                        'language_id' => (int)$language_id,
                        'route'       => 'content/post',
                        'param'       => 'post_id',
                        'value'       => $post_id,
                        'alias'       => $alias,
                    ]
                );
            }
        }
    }

    public function getPost(int $post_id): array
    {
        $this->load->config('content/post');
        $this->load->model('extension/language');

        $default   = $this->config->getArray('content.post.form');
        $default['user_id'] = $this->user->get('user_id');

        $languages = $this->model_extension_language->getLanguages();
        foreach ($languages as $language) {
            $default['content'][$language['language_id']] = $default['content'][0];
            $default['alias'][$language['language_id']]   = '';
        }

        $data = $this->db->get(
            "SELECT p.*, p.term_id as category_id FROM `" . DB_PREFIX . "post` p WHERE p.post_id = ?i AND p.taxonomy = ?s",
            [$post_id, 'post']
        )->row;

        if (!empty($data['post_id'])) {
            // Multi-language content
            $contents = $this->db->get("SELECT * FROM `" . DB_PREFIX . "post_content` pc WHERE pc.post_id = ?i ORDER BY pc.language_id ASC", [$post_id])->rows;

            $data['content'] = [];
            foreach ($contents as $content) {
                $data['content'][$content['language_id']] = array_replace($default['content'][0], $content);
            }

            // Metas
            $data['meta'] = [];
            $metas = $this->db->get("SELECT * FROM `" . DB_PREFIX . "post_meta` pm WHERE pm.post_id = ?i", [$post_id])->rows;
            foreach ($metas as $meta) {
                $data['meta'][$meta['key']] = $meta['encoded'] ? json_decode($meta['value'], true) : $meta['value'];
            }

            // Sites
            $data['sites'] = [];
            $sites = $this->db->get(
                "SELECT sr.site_id FROM `" . DB_PREFIX . "site_relation` sr
                WHERE sr.taxonomy = ?s AND sr.taxonomy_id = ?i",
                ['content_post', $post_id]
            )->rows;
            foreach ($sites as $site) {
                $data['sites'][] = $site['site_id'];
            }

            // Terms
            $data['term'] = [];

            $categories = $this->db->get(
                "SELECT t.term_id
                FROM `" . DB_PREFIX . "term_relation` tr
                    LEFT JOIN `" . DB_PREFIX . "term` t ON (t.term_id = tr.term_id)
                WHERE t.taxonomy = ?s AND tr.taxonomy = ?s AND tr.taxonomy_id = ?i",
                ['content_category', 'content_post', $post_id]
            )->rows;
            foreach ($categories as $category) {
                $data['term']['categories'][] = $category['term_id'];
            }

            $tags = $this->db->get(
                "SELECT t.term_id
                FROM `" . DB_PREFIX . "term_relation` tr
                    LEFT JOIN `" . DB_PREFIX . "term` t ON (t.term_id = tr.term_id)
                WHERE t.taxonomy = ?s AND tr.taxonomy = ?s AND tr.taxonomy_id = ?i",
                ['content_tag', 'content_post', $post_id]
            )->rows;
            foreach ($tags as $tag) {
                $data['term']['tags'][] = $tag['term_id'];
            }


            // Multi-language alias
            $aliases = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `route` = ?s AND `param` = ?s AND `value` = ?i",
                ['content/post', 'post_id', $post_id]
            )->rows;
            foreach ($aliases as $alias) {
                $data['alias'][$alias['language_id']] = $alias['alias'];
            }
        }

        return array_replace_recursive($default, $data);
    }

    public function getTotal(): int
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "post` WHERE `taxonomy` = 'post'")->row['total'];
    }

    public function deletePosts(array $posts): void
    {
        $this->db->delete(DB_PREFIX . 'post', ['post_id' => $posts]);
        $this->db->delete(DB_PREFIX . 'post_content', ['post_id' => $posts]);
        $this->db->delete(DB_PREFIX . 'post_meta', ['post_id' => $posts]);

        $this->cache->deleteByTags('content.posts');
    }
}
