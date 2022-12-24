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
                    ['category_id' => (int)$item]
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

    public function getCategory(int $category_id): array
    {
        //
    }

    public function getTotal(): int
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "term` WHERE `taxonomy` = 'post_category'")->row['total'];
    }

    public function deleteCategories(array $categories): void
    {
        $this->db->delete(DB_PREFIX . 'term', ['category_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_content', ['category_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_meta', ['category_id' => $categories]);
        $this->db->delete(DB_PREFIX . 'term_relation', ['category_id' => $categories]);

        $this->cache->delete('categories');
    }
}
