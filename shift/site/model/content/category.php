<?php

declare(strict_types=1);

namespace Shift\Site\Model\Content;

use Shift\System\Mvc;

class Category extends Mvc\Model
{
    public function getCategories(array $filters = []): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('content.categories' . $argsHash, []);

        if (!$data) {
            $filters = array_merge([
                'parent_id' => 0,
                'limit'     => 8,
            ], $filters);

            $sql = "SELECT t.*, tc.*";
            $sql .= " FROM `" . DB_PREFIX . "term` t";
            $sql .= "   LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (t.term_id = tc.term_id AND tc.language_id = "  . $this->config->getInt('env.language_id') . ")";
            $sql .= " WHERE t.taxonomy = 'content_category'";
            $sql .= "   AND t.status = 1";
            $sql .= " GROUP BY t.term_id";
            $sql .= " ORDER BY t.parent_id ASC, t.sort_order ASC";
            $sql .= " LIMIT 0, " . (int)$filters['limit'];

            $data = $this->db->get($sql)->rows;

            $this->cache->set('content.categories.' . $argsHash, $data, tags: ['content.categories', 'content.category']);
        }
        return $data;
    }
}
