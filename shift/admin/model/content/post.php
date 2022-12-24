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
            'alias'     => 'ra.alias',
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
                LEFT JOIN `" . DB_PREFIX . "route_alias` ra ON (ra.param = 'post_id' AND ra.value = p.post_id AND ra.language_id = " . $this->config->getInt('env.language_id') . ")
                LEFT JOIN `" . DB_PREFIX . "term` t ON (t.term_id = p.term_id)
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

        if (in_array($type, ['enabled', 'disabled'])) {
            $status = $type == 'enabled' ? 1 : 0;

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

    public function getPost(int $post_id): array
    {
        //
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

        $this->cache->delete('posts');
    }
}
