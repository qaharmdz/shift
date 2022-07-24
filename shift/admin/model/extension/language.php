<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;

class Language extends Mvc\Model
{
    public function addLanguage($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

        $this->cache->delete('language');

        $language_id = $this->db->getLastId();

        // Banner
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "banner_image WHERE language_id = '" . (int)$this->config->get('env.language_id') . "'");

        foreach ($query->rows as $banner_image) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($banner_image['title']) . "', link = '" . $this->db->escape($banner_image['link']) . "', image = '" . $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");
        }

        $this->cache->delete('banner');

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

        $this->cache->delete('information');

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

        $this->cache->delete('language');
    }

    public function deleteLanguage($language_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('language');

        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$language_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$language_id . "'");

        $this->cache->delete('information');
    }

    public function getLanguage($language_id)
    {
        $query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function getLanguages($data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "language";

            $sort_data = array(
                'name',
                'code',
                'sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order, name";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->get($sql);

            return $query->rows;
        } else {
            $language_data = $this->cache->get('language');

            if (!$language_data) {
                $language_data = array();

                $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

                foreach ($query->rows as $result) {
                    $language_data[$result['code']] = array(
                        'language_id' => $result['language_id'],
                        'name'        => $result['name'],
                        'code'        => $result['code'],
                        'locale'      => $result['locale'],
                        'image'       => $result['image'],
                        'directory'   => $result['directory'],
                        'sort_order'  => $result['sort_order'],
                        'status'      => $result['status']
                    );
                }

                $this->cache->set('language', $language_data);
            }

            return $language_data;
        }
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
}
