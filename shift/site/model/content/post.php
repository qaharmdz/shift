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
                LEFT JOIN `" . DB_PREFIX . "site_relation` sr ON (p.post_id = sr.taxonomy_id AND sr.taxonomy = 'content_post')
            WHERE p.post_id = :postId?i
                AND p.taxonomy = 'content_post'
                AND p.visibility = :visibility?s
                AND p.status = :status?s
                AND (p.publish IS NULL OR p.publish <= NOW())
                AND (p.unpublish IS NULL OR p.unpublish >= NOW())
                AND sr.site_id = :siteId?i",
            [
                'postId'     => $post_id,
                'langId'     => $this->config->getInt('env.language_id'),
                'siteId'     => $this->config->getInt('env.site_id'),
                'visibility' => 'public', // TODO: check visibility usergroup, password
                'status'     => 'publish', // TODO: permission to view pending or draft
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

    public function getPosts(array $filters = []): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('content.posts' . $argsHash, []);

        if (!$data) {
            $filters = array_merge([
                'category_id' => 0,
                'page'        => 1,
                'start'       => 0,
                'limit'       => $this->config->getInt('env.limit'),
            ], $filters);

            $sql = "SELECT p.*, sr.site_id, pc.*";
            $sql .= " FROM `" . DB_PREFIX . "post` p";
            $sql .= "   LEFT JOIN `" . DB_PREFIX . "post_content` pc ON (p.post_id = pc.post_id AND pc.language_id = "  . $this->config->getInt('env.language_id') . ")";
            $sql .= "   LEFT JOIN `" . DB_PREFIX . "site_relation` sr ON (p.post_id = sr.taxonomy_id AND sr.taxonomy = 'content_post')";
            if ($filters['category_id']) {
                $sql .= "   LEFT JOIN `" . DB_PREFIX . "term_relation` tr ON (p.post_id = tr.taxonomy_id AND tr.taxonomy = 'content_post')";
            }
            $sql .= " WHERE p.taxonomy = 'content_post'";
            if ($filters['category_id']) {
                $sql .= "   AND (p.term_id = " . (int)$filters['category_id'] . " OR tr.term_id = " . (int)$filters['category_id'] . ")";
            }
            $sql .= "   AND p.visibility = 'public'"; // TODO: check visibility usergroup, password
            $sql .= "   AND p.status = 'publish'"; // TODO: permission to view pending or draft
            $sql .= "   AND (p.publish IS NULL OR p.publish <= NOW()) AND (p.unpublish IS NULL OR p.unpublish >= NOW())";
            $sql .= "   AND sr.site_id = " . $this->config->getInt('env.site_id');
            $sql .= " GROUP BY p.post_id";
            $sql .= " ORDER BY p.sort_order ASC, p.updated DESC"; // TODO: post order
            $sql .= " LIMIT " . (int)$filters['start'] . ", " . (int)$filters['limit'];

            $data = $this->db->get($sql)->rows;

            $this->cache->set('content.posts.' . $argsHash, $data, tags: ['content.posts']);
        }

        return $data;
    }
}
