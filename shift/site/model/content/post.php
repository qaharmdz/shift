<?php

declare(strict_types=1);

namespace Shift\Site\Model\Content;

use Shift\System\Mvc;

class Post extends Mvc\Model
{
    public function getPost(int $post_id): array
    {
        $data = $this->db->get(
            "SELECT p.*, p.term_id as category_id, pc.*, sr.site_id
            FROM `" . DB_PREFIX . "post` p
                LEFT JOIN `" . DB_PREFIX . "post_content` pc ON (p.post_id = pc.post_id AND pc.language_id = :langId?i)
                LEFT JOIN `" . DB_PREFIX . "site_relation` sr ON (p.post_id = sr.taxonomy_id AND sr.taxonomy = :siteTaxonomy?s)
            WHERE p.post_id = :postId?i
                AND p.taxonomy = :taxonomy?s
                AND p.visibility = :visibility?s
                AND p.status = :status?s
                AND (p.publish IS NULL OR p.publish <= NOW())
                AND (p.unpublish IS NULL OR p.unpublish >= NOW())
                AND sr.site_id = :siteId?i",
            [
                'taxonomy'     => 'post',
                'postId'       => $post_id,
                'langId'       => $this->config->getInt('env.language_id'),
                'siteId'       => $this->config->getInt('env.site_id'),
                'visibility'   => 'public', // TODO: check visibility usergroup, password
                'status'       => 'publish', // TODO: permission to view pending or draft
                'siteTaxonomy' => 'content_post',
            ]
        )->row;

        if ($data) {
            // Metas
            $data['meta'] = [];
            $metas = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "post_meta` pm WHERE pm.post_id = ?i",
                [$post_id]
            )->rows;
            foreach ($metas as $meta) {
                $data['meta'][$meta['key']] = $meta['encoded'] ? json_decode($meta['value'], true) : $meta['value'];

                if ($meta['key'] == 'image') {
                    if (!is_file(PATH_MEDIA . $data['meta']['image'])) {
                        $data['meta']['image'] = $default['meta']['image'];
                    }
                }
            }

            // Terms
            $data['term'] = [
                'categories' => [],
                'tags' => [],
            ];

            $categories = $this->db->get(
                "SELECT t.term_id, tc.*
                FROM `" . DB_PREFIX . "term_relation` tr
                    LEFT JOIN `" . DB_PREFIX . "term` t ON (t.term_id = tr.term_id)
                    LEFT JOIN `" . DB_PREFIX . "term_content` tc ON (t.term_id = tc.term_id AND tc.language_id = ?i)
                WHERE t.taxonomy = ?s AND t.status = 1 AND tr.taxonomy = ?s AND tr.taxonomy_id = ?i",
                [$this->config->get('env.language_id'), 'content_category', 'content_post', $post_id]
            )->rows;
            foreach ($categories as $category) {
                $data['term']['categories'][$category['term_id']] = $category;
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
        }

        return $data;
    }

    public function getPosts(
        array $filters = ['p.term_id != ?i' => -1],
        string $dataKey = 'post_id'
    ): array {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('content.posts' . $argsHash, []);

        // Default $filters
        $filters = array_merge($filters, [
            'p.taxonomy = ?s' => 'post',
            'visibility = ?s' => 'public', // TODO: check visibility usergroup, password
            'status = ?s'     => 'publish', // TODO: permission to view pending or draft
            '(p.publish IS NULL OR p.publish <= NOW())' => null,
            '(p.unpublish IS NULL OR p.unpublish >= NOW())' => null,
            'sr.site_id = ?i' => $this->config->getInt('env.site_id'),
        ]);

        $wheres = array_keys($filters);
        $params = array_values(array_filter(array_values($filters), fn ($item) => null !== $item));

        if (!$data) {
            $posts = $this->db->get(
                "SELECT p.*, p.term_id as category_id, pc.*, sr.site_id
                FROM `" . DB_PREFIX . "post` p
                    LEFT JOIN `" . DB_PREFIX . "post_content` pc ON (p.post_id = pc.post_id AND pc.language_id = "  . $this->config->getInt('env.language_id') . ")
                    LEFT JOIN `" . DB_PREFIX . "site_relation` sr ON (p.post_id = sr.taxonomy_id AND sr.taxonomy = 'content_post')
                WHERE " . implode(' AND ', $wheres) . "
                ORDER BY p.sort_order ASC, p.updated DESC",
                $params
            )->rows;

            foreach ($posts as &$result) {
                $data[$result[$dataKey]] = $result;
            }

            $this->cache->set('content.posts.' . $argsHash, $data, tags: ['content.posts']);
        }

        return $data;
    }
}
