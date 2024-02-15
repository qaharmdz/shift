<?php

declare(strict_types=1);

namespace Shift\Site\Model\Content;

use Shift\System\Mvc;

class Category extends Mvc\Model {
    public function getCategory(int $category_id): array
    {
        $data = $this->db->get(
            "SELECT t.*, t.term_id as category_id, tc.*, sr.site_id
            FROM `" . DB_PREFIX . "term` t
                LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (t.term_id = tc.term_id AND tc.language_id = :langId?i)
                LEFT JOIN `" . DB_PREFIX . "site_relation` sr ON (t.term_id = sr.taxonomy_id AND sr.taxonomy = 'content_category')
            WHERE t.term_id = :termId?i
                AND t.taxonomy = 'content_category'
                AND t.status = 1
                AND sr.site_id = :siteId?i",
            [
                'termId' => $category_id,
                'langId' => $this->config->getInt('env.language_id'),
                'siteId' => $this->config->getInt('env.site_id'),
            ]
        )->row;

        if ($data) {
            // Metas
            $data['meta'] = [];
            $metas = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "term_meta` tm WHERE tm.term_id = ?i",
                [$category_id]
            )->rows;

            foreach ($metas as $meta) {
                $data['meta'][$meta['key']] = $meta['encoded'] ? json_decode($meta['value'], true) : $meta['value'];
            }
        }

        return $data;
    }

    public function getCategories(array $filters = []): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data = $this->cache->get('content.categories' . $argsHash, []);

        if (!$data) {
            $filters = array_merge([
                'parent_id' => 0,
                'limit'     => $this->config->getInt('env.limit'),
                'order_by'  => '',
            ], $filters);

            $sql = "SELECT t.*, tc.*";
            $sql .= " FROM `" . DB_PREFIX . "term` t";
            $sql .= "   LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (t.term_id = tc.term_id AND tc.language_id = " . $this->config->getInt('env.language_id') . ")";
            $sql .= " WHERE t.taxonomy = 'content_category'";
            $sql .= "   AND t.status = 1";
            $sql .= " GROUP BY t.term_id";
            $sql .= " ORDER BY " . ($filters['order_by'] ?: 't.sort_order ASC, tc.title ASC');
            $sql .= " LIMIT 0, " . (int) $filters['limit'];

            $data = $this->db->get($sql)->rows;

            $this->cache->set('content.categories.' . $argsHash, $data, tags: ['content.categories', 'content.category']);
        }

        return $data;
    }
}
