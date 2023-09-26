<?php

declare(strict_types=1);

namespace Shift\Site\Model\Content;

use Shift\System\Mvc;

class Tag extends Mvc\Model
{
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
}
