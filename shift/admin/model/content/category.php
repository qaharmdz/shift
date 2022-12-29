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
            'alias'       => 'ra.alias',
            'order'       => 't.sort_order AS `order`',
            'status'      => 't.status',
            'created'     => 't.created',
            'updated'     => 't.updated'
        ];
        $filterMap = $columnMap;
        $filterMap['category_id'] = 't.term_id';
        $filterMap['title']       = 'CONCAT(tc.title, " ", ra.alias)';

        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "term` t
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = t.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")
                LEFT JOIN `" . DB_PREFIX . "route_alias` ra ON (ra.param = 'category_id' AND ra.value = t.term_id AND ra.language_id = " . $this->config->getInt('env.language_id') . ")"
            . " WHERE t.`taxonomy` = 'post_category'"
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

            foreach ($items as $item) {
                $this->db->set(
                    DB_PREFIX . 'term',
                    [
                        'status'  => $status,
                        'updated' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'term_id'  => (int)$item,
                        'taxonomy' => 'post_category',
                    ]
                );

                if ($this->db->affectedRows()) {
                    $_items[] = $item;
                }
            }
        }

        if ($type == 'delete') {
            $this->deleteCategories($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function addCategory(array $data)
    {
        $this->db->add(
            DB_PREFIX . 'term',
            [
                'parent_id'  => $data['parent_id'],
                'taxonomy'   => 'post_category',
                'sort_order' => $data['sort_order'],
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
        // $this->db->set(
        //     DB_PREFIX . 'term',
        //     [
        //         'parent_id'  => $data['parent_id'],
        //         'taxonomy'   => 'post_category',
        //         'sort_order' => $data['sort_order'],
        //         'status'     => (int)$data['status'],
        //         'updated'    => date('Y-m-d H:i:s'),
        //     ],
        //     ['term_id' => $term_id]
        // );
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

        $this->load->model('setting/site');
        $sites = $this->model_setting_site->getSites();

        foreach ($sites as $site) {
            $alias = '';
            foreach ($data['alias'] as $language_id => $alias) {
                if (!$alias) {
                    continue;
                }

                $_aliasCount = $this->db->get(
                    "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "route_alias` WHERE `site_id` = ?i AND `language_id` = ?i AND `alias` = ?s",
                    [$site['site_id'], $language_id, $alias]
                )->row['total'];

                if ($_aliasCount) {
                    $alias = $alias . '-' . $category_id . '-' . $site['site_id'] . '-' . $language_id;
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
        // $this->load->model('setting/site');
        $this->load->model('extension/language');

        $default   = $this->config->getArray('content.category.form');
        // $sites     = $this->model_setting_site->getSites();
        $languages = $this->model_extension_language->getLanguages();

        foreach ($languages as $language) {
            $default['content'][$language['language_id']] = $default['content'][0];
            $default['alias'][$language['language_id']]   = '';
        }
        // foreach ($sites as $site) {
        //     foreach ($languages as $language) {
        //         $default['alias'][$site['site_id']][$language['language_id']] = '';
        //     }
        // }

        $data = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "term` t WHERE t.term_id = ?i AND t.taxonomy = ?s",
            [$category_id, 'post_category']
        )->row;

        if (!empty($data['term_id'])) {
            $data['category_id'] = $data['term_id'];

            // Multi-language content
            $data['content'] = $this->db->query("SELECT * FROM `" . DB_PREFIX . "term_content` tm WHERE tm.term_id = ?i ORDER BY tm.language_id ASC", [$category_id])->row;
            foreach ($languages as $language) {
                $data['content'][$content['language_id']] = array_replace($default['content'][0], $content);
            }

            // Multi-language alias
            $aliases = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `route` = ?s AND `param` = ?s AND `value` = ?i",
                ['content/category', 'category_id', $category_id]
            )->rows;
            foreach ($aliases as $alias) {
                $data['alias'][$alias['language_id']] = $alias['alias'];
                // $data['alias'][$alias['site_id']][$alias['language_id']] = $alias['alias'];
            }

            // Metas
            $data['meta'] = [];
            $metas = $this->db->query("SELECT * FROM `" . DB_PREFIX . "term_meta` tm WHERE tm.term_id = ?i", [$category_id]);
            foreach ($metas as $meta) {
                $data['meta'][$meta['key']] = $meta['encoded'] ? json_encode($meta['value'], true) : $meta['value'];
            }
        }

        return array_replace($default, $data);
    }

    public function getTotal(): int
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "term` WHERE `taxonomy` = 'post_category'")->row['total'];
    }

    public function deleteCategories(array $categories): void
    {
        $this->db->delete(DB_PREFIX . 'term', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_content', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_meta', ['term_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_relation', ['term_id' => $categories]);

        $this->cache->delete('categories');
    }
}
