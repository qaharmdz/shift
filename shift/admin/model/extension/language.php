<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;

class Language extends Mvc\Model
{
    /**
     * Get languages
     *
     * @param  array  $filters
     * @param  string $rkey    Return key
     * @return array
     */
    public function getLanguages(array $filters = ['status = ?i' => 1], string $rkey = 'extension_id'): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('languages.' . $argsHash, []);

        if (!$data) {
            $filters = array_merge([
                'type = ?s' => 'language',
                'install = ?i' => 1,
            ], $filters);

            $languages  = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "extension`
                WHERE " . implode(' AND ', array_keys($filters)) . "
                ORDER BY `name` ASC",
                array_values($filters)
            )->rows;

            foreach ($languages as $result) {
                $data[$result[$rkey]] = $result;
                $data[$result[$rkey]]['setting'] = json_decode($result['setting'], true);
            }

            $this->cache->set('languages.' . $argsHash, $data, tags: ['extension', 'languages']);
        }

        return $data;
    }

    /*
    public function addLanguage($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

        $this->cache->deleteByTags('languages');

        $language_id = $this->db->getLastId();

        // Banner
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "banner_image WHERE language_id = '" . (int)$this->config->get('env.language_id') . "'");

        foreach ($query->rows as $banner_image) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($banner_image['title']) . "', link = '" . $this->db->escape($banner_image['link']) . "', image = '" . $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");
        }

        $this->cache->deleteByTags('banners');

        // Download
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('env.language_id') . "'");

        foreach ($query->rows as $download) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
        }

        // Information
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$this->config->get('env.language_id') . "'");

        foreach ($query->rows as $information) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($information['title']) . "', description = '" . $this->db->escape($information['description']) . "', meta_title = '" . $this->db->escape($information['meta_title']) . "', meta_description = '" . $this->db->escape($information['meta_description']) . "', meta_keyword = '" . $this->db->escape($information['meta_keyword']) . "'");
        }

        $this->cache->deleteByTags('informations');

        return $language_id;
    }

    public function editLanguage($language_id, $data)
    {
        $language_query = $this->db->get("SELECT `code` FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        $this->db->set(
            DB_PREFIX . 'language',
            [
                'name'       => $data['name'],
                'code'       => $data['code'],
                'locale'     => $data['locale'],
                'sort_order' => $data['sort_order'],
                'status'     => (int)$data['status'],
            ],
            ['language_id' => (int)$language_id]
        );

        if ($language_query->row['code'] != $data['code']) {
            $this->db->set(
                DB_PREFIX . 'setting',
                ['value' => $data['code']],
                [
                    'group' => 'system',
                    'code'  => 'setting',
                    'key'   => 'language',
                    'value' => $language_query->row['code'],
                ]
            );
            $this->db->set(
                DB_PREFIX . 'setting',
                ['value' => $data['code']],
                [
                    'group' => 'system',
                    'code'  => 'setting',
                    'key'   => 'admin_language',
                    'value' => $language_query->row['code'],
                ]
            );
        }

        $this->cache->deleteByTags('languages');
    }

    public function deleteLanguage($language_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->deleteByTags('languages');

        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->deleteByTags('informations');
    }

    public function getLanguage($language_id)
    {
        $query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function getLanguageByCode($code)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getTotalLanguages()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language");

        return $query->row['total'];
    }
    */
}
