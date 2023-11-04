<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Content;

use Shift\System\Mvc;
use Shift\System\Helper;

class Tag extends Mvc\Model
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
            'tag_id'  => 't.term_id AS tag_id',
            'title'   => 'tc.title',
            'order'   => 't.sort_order AS `order`',
            'status'  => 't.status',
            'created' => 't.created',
            'updated' => 't.updated'
        ];
        $filterMap = $columnMap;
        $filterMap['tag_id'] = 't.term_id';

        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "term` t
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = t.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")"
            . " WHERE t.`taxonomy` = 'content_tag'"
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
                    'taxonomy' => 'content_tag',
                ]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
            }
        }

        if ($type == 'delete') {
            $this->deleteTags($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function addTag(array $data): int
    {
        $this->db->add(
            DB_PREFIX . 'term',
            [
                'taxonomy'   => 'content_tag',
                'status'     => (int)$data['status'],
                'created'    => date('Y-m-d H:i:s'),
                'updated'    => date('Y-m-d H:i:s'),
            ]
        );

        $term_id = (int)$this->db->insertId();

        $this->insertData($term_id, $data);

        return $term_id;
    }

    public function addTagByTitle(string $title): int
    {
        $isExist = $this->db->get(
            "SELECT t.term_id, tc.title FROM `" . DB_PREFIX . "term` t
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = t.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")
            WHERE t.taxonomy = ?s AND tc.title = ?s",
            ['content_tag', $title]
        )->row;

        if ($isExist) {
            return $isExist['term_id'];
        }

        $data = [
            'content' => [],
            'alias'   => [],
            'status'  => 1,
        ];

        $this->load->model('extension/language');
        $languages = $this->model_extension_language->getLanguages();

        foreach ($languages as $language) {
            $data['content'][$language['language_id']] = [
                'title'   => $title,
                'content' => '',
            ];

            $data['alias'][$language['language_id']] = $title . '-' . $language['language_id'];
        }

        return $this->addTag($data);
    }

    public function editTag(int $tag_id, array $data)
    {
        $updated = $this->db->set(
            DB_PREFIX . 'term',
            [
                'taxonomy'   => 'content_tag',
                'status'     => (int)$data['status'],
                'updated'    => date('Y-m-d H:i:s'),
            ],
            ['term_id' => $tag_id]
        );

        if ($updated->affected_rows != -1) {
            $this->db->delete(DB_PREFIX . 'term_content', ['term_id' => $tag_id]);
            $this->db->delete(DB_PREFIX . 'term_meta', ['term_id' => $tag_id]);
            $this->db->delete(DB_PREFIX . 'route_alias', [
                'route' => 'content/tag',
                'param' => 'tag_id',
                'value' => $tag_id
            ]);

            $this->insertData($tag_id, $data);

            return $updated->affected_rows;
        }
    }

    protected function insertData(int $tag_id, array $data)
    {
        foreach ($data['content'] as $language_id => $content) {
            $this->db->add(
                DB_PREFIX . 'term_content',
                [
                    'term_id'     => $tag_id,
                    'language_id' => (int)$language_id,
                    'title'       => $content['title'],
                    'content'     => $content['content'],
                ]
            );
        }

        // Meta setting
        if (!empty($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                $this->db->add(
                    DB_PREFIX . 'term_meta',
                    [
                        'term_id' => $tag_id,
                        'key'     => $key,
                        'value'   => (is_array($value) ? json_encode($value) : $value),
                        'encoded' => (is_array($value) ? 1 : 0),
                    ]
                );
            }
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
                    [$site['site_id'], $alias]
                )->row['total'];

                if ($_aliasCount) {
                    $alias = $alias . '-' . $language_id;
                }

                $this->db->add(
                    DB_PREFIX . 'route_alias',
                    [
                        'site_id'     => (int)$site['site_id'],
                        'language_id' => (int)$language_id,
                        'route'       => 'content/tag',
                        'param'       => 'tag_id',
                        'value'       => $tag_id,
                        'alias'       => $alias,
                    ]
                );
            }
        }
    }

    public function getTag(int $tag_id): array
    {
        $this->load->config('content/tag');
        $this->load->model('extension/language');

        $default   = $this->config->getArray('content.tag.form');
        $languages = $this->model_extension_language->getLanguages();

        foreach ($languages as $language) {
            $default['content'][$language['extension_id']] = $default['content'][0];
            $default['alias'][$language['extension_id']]   = '';
        }

        $data = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "term` t WHERE t.term_id = ?i AND t.taxonomy = ?s",
            [$tag_id, 'content_tag']
        )->row;

        if (!empty($data['term_id'])) {
            $data['tag_id'] = $data['term_id'];

            // Multi-language content
            $contents = $this->db->get("SELECT * FROM `" . DB_PREFIX . "term_content` tc WHERE tc.term_id = ?i ORDER BY tc.language_id ASC", [$tag_id])->rows;

            $data['content'] = [];
            foreach ($contents as $content) {
                $data['content'][$content['language_id']] = array_replace($default['content'][0], $content);
            }

            // Multi-language alias
            $aliases = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `route` = ?s AND `param` = ?s AND `value` = ?i",
                ['content/tag', 'tag_id', $tag_id]
            )->rows;
            foreach ($aliases as $alias) {
                $data['alias'][$alias['language_id']] = $alias['alias'];
            }

            // Metas
            $data['meta'] = [];
            $metas = $this->db->get("SELECT * FROM `" . DB_PREFIX . "term_meta` tm WHERE tm.term_id = ?i", [$tag_id])->rows;
            foreach ($metas as $meta) {
                $data['meta'][$meta['key']] = $meta['encoded'] ? json_decode($meta['value'], true) : $meta['value'];
            }
        }

        return array_replace_recursive($default, $data);
    }

    public function getTags()
    {
        return $this->db->get("
            SELECT t.term_id, tc.title
            FROM `" . DB_PREFIX . "term` t
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (tc.term_id = t.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")
            WHERE t.`taxonomy` = 'content_tag'
            ORDER BY t.sort_order ASC, tc.title ASC
        ")->rows;
    }

    public function getTotal(): int
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "term` WHERE `taxonomy` = 'content_tag'")->row['total'];
    }

    public function deleteTags(array $tags): void
    {
        $this->db->delete(DB_PREFIX . 'term', ['term_id' => $tags]);
        $this->db->delete(DB_PREFIX . 'term_content', ['term_id' => $tags]);
        $this->db->delete(DB_PREFIX . 'term_meta', ['term_id' => $tags]);
        $this->db->delete(DB_PREFIX . 'term_relation', ['term_id' => $tags]);

        $this->cache->deleteByTags('content.tags');
    }
}
