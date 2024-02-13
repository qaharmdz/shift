<?php

declare(strict_types=1);

namespace Shift\Site\Model\Content;

use Shift\System\Mvc;

class Tag extends Mvc\Model {
    public function getTag(int $tag_id): array
    {
        //
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
}
