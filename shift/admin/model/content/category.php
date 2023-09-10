<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Content;

use Shift\System\Mvc;
use Shift\System\Helper;

class Category extends Mvc\Model
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
            'category_id' => 't.term_id AS category_id',
            'parent_id'   => 't.parent_id',
            'title'       => 'tc.title',
            'order'       => 't.sort_order AS `order`',
            'status'      => 't.status',
            'created'     => 't.created',
            'updated'     => 't.updated'
        ];
        $filterMap = $columnMap;
        $filterMap['category_id'] = 't.term_id';

        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "term` t
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = t.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")"
            . " WHERE t.`taxonomy` = 'content_category'"
                 . ($dtResult['query']['where'] ? " AND " . $dtResult['query']['where'] : "")
            . " ORDER BY " . $dtResult['query']['order']
            . " LIMIT " . $dtResult['query']['limit'];

        return $this->db->get($query, $dtResult['query']['params']);
    }

    public function dtAction(string $type, array $items): array
    {
        $_items = [];

        if (in_array($type, ['enabled', 'disabled'])) {
            $status = $type == 'enabled' ? 1 : 0;

            $this->db->set(
                DB_PREFIX . 'term',
                [
                    'status'  => $status,
                    'updated' => date('Y-m-d H:i:s'),
                ],
                [
                    'term_id'  => $items,
                    'taxonomy' => 'content_category',
                ]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
            }
        }

        if ($type == 'delete') {
            $this->deleteCategories($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function addCategory(array $data): int
    {
        $this->db->add(
            DB_PREFIX . 'term',
            [
                'parent_id'  => (int)$data['parent_id'],
                'taxonomy'   => 'content_category',
                'sort_order' => (int)$data['sort_order'],
                'status'     => (int)$data['status'],
                'created'    => date('Y-m-d H:i:s'),
                'updated'    => date('Y-m-d H:i:s'),
            ]
        );

        $term_id = (int)$this->db->insertId();

        $this->insertData($term_id, $data);

        return $term_id;
    }

    public function editCategory(int $category_id, array $data)
    {
        $updated = $this->db->set(
            DB_PREFIX . 'term',
            [
                'parent_id'  => (int)$data['parent_id'],
                'taxonomy'   => 'content_category',
                'sort_order' => (int)$data['sort_order'],
                'status'     => (int)$data['status'],
                'updated'    => date('Y-m-d H:i:s'),
            ],
            ['term_id' => $category_id]
        );

        if ($updated->affected_rows != -1) {
            $this->db->delete(DB_PREFIX . 'term_content', ['term_id' => $category_id]);
            $this->db->delete(DB_PREFIX . 'term_meta', ['term_id' => $category_id]);
            $this->db->delete(DB_PREFIX . 'site_relation', [
                'taxonomy'    => 'content_category',
                'taxonomy_id' => $category_id,
            ]);
            $this->db->delete(DB_PREFIX . 'route_alias', [
                'route' => 'content/category',
                'param' => 'category_id',
                'value' => $category_id,
            ]);

            $this->insertData($category_id, $data);

            return $updated->affected_rows;
        }
    }

    protected function insertData(int $category_id, array $data)
    {
        foreach ($data['content'] as $language_id => $content) {
            $this->db->add(
                DB_PREFIX . 'term_content',
                [
                    'term_id'          => $category_id,
                    'language_id'      => (int)$language_id,
                    'title'            => $content['title'],
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
                DB_PREFIX . 'term_meta',
                [
                    'term_id' => $category_id,
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
                    'taxonomy'    => 'content_category',
                    'taxonomy_id' => $category_id,
                ]
            );
        }

        // URL alias
        $this->load->model('setting/site');
        $sites = $this->model_setting_site->getSites();

        foreach ($sites as $site) {
            $alias = '';
            foreach ($data['alias'] as $language_id => $alias) {
                if (!$alias = sanitizeChar(strtolower($alias))) {
                    $alias = sanitizeChar(strtolower($data['content'][$language_id]['title']));
                }

                $_aliasCount = $this->db->get(
                    "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "route_alias` WHERE `site_id` = ?i AND `alias` = ?s",
                    [$site['site_id'], $language_id, $alias]
                )->row['total'];

                if ($_aliasCount) {
                    $alias = $alias . '-' . $language_id;
                }

                $this->db->add(
                    DB_PREFIX . 'route_alias',
                    [
                        'site_id'     => (int)$site['site_id'],
                        'language_id' => (int)$language_id,
                        'route'       => 'content/category',
                        'param'       => 'category_id',
                        'value'       => $category_id,
                        'alias'       => $alias,
                    ]
                );
            }
        }
    }

    public function getCategory(int $category_id): array
    {
        $this->load->config('content/category');
        $this->load->model('extension/language');

        $default   = $this->config->getArray('content.category.form');
        $languages = $this->model_extension_language->getLanguages();

        foreach ($languages as $language) {
            $default['content'][$language['extension_id']] = $default['content'][0];
            $default['alias'][$language['extension_id']]   = '';
        }

        $data = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "term` t WHERE t.term_id = ?i AND t.taxonomy = ?s",
            [$category_id, 'content_category']
        )->row;

        if (!empty($data['term_id'])) {
            $data['category_id'] = $data['term_id'];

            // Multi-language content
            $contents = $this->db->get("SELECT * FROM `" . DB_PREFIX . "term_content` tc WHERE tc.term_id = ?i ORDER BY tc.language_id ASC", [$category_id])->rows;

            $data['content'] = [];
            foreach ($contents as $content) {
                $data['content'][$content['language_id']] = array_replace($default['content'][0], $content);
            }

            // Multi-language alias
            $aliases = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `route` = ?s AND `param` = ?s AND `value` = ?i",
                ['content/category', 'category_id', $category_id]
            )->rows;
            foreach ($aliases as $alias) {
                $data['alias'][$alias['language_id']] = $alias['alias'];
            }

            // Metas
            $data['meta'] = [];
            $metas = $this->db->get("SELECT * FROM `" . DB_PREFIX . "term_meta` tm WHERE tm.term_id = ?i", [$category_id])->rows;
            foreach ($metas as $meta) {
                $data['meta'][$meta['key']] = $meta['encoded'] ? json_decode($meta['value'], true) : $meta['value'];
            }
        }

        return array_replace_recursive($default, $data);
    }

    public function getTotal(): int
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "term` WHERE `taxonomy` = 'content_category'")->row['total'];
    }

    public function deleteCategories(array $categories): void
    {
        $this->db->delete(DB_PREFIX . 'term', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_content', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_meta', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_relation', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'route_alias', [
            'route' => 'content/category',
            'param' => 'category_id',
            'value' => $categories
        ]);

        $this->cache->deleteByTags('content.categories');
    }

    /**
     * @param  string $key     returned array key
     * @param  array  $filters
     * @return array
     */
    public function getCategoryTree(
        array $lists = [],
        array $exclude = [],
        int $parent = 0,
        string $indent = '|&mdash;',
        int $level = 0
    ): array {
        $data = [];

        if (!$lists) {
            $lists = $this->db->get("
                SELECT t.term_id, t.parent_id, t.status, tc.title
                FROM `" . DB_PREFIX . "term` t
                    LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = t.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")
                WHERE t.`taxonomy` = 'content_category'
                ORDER BY t.parent_id ASC, t.sort_order ASC
            ")->rows;
        }

        foreach ($lists as $key => $category) {
            if ($category['parent_id'] == $parent) {
                if (in_array($category['term_id'], $exclude)) {
                    continue;
                }

                $data[] = [
                    'category_id' => (int)$category['term_id'],
                    'parent_id'   => (int)$category['parent_id'],
                    'title'       => $category['title'],
                    'title_level' => str_repeat($indent . ' ', $level) . $category['title'],
                    'status'      => $category['status'],
                ];

                unset($lists[$key]); // cleanup
                $data = array_merge($data, $this->getCategoryTree($lists, $exclude, $category['term_id'], $indent, $level + 1));
            }
        }

        return $data;
    }
}
