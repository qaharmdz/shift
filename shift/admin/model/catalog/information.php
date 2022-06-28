<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Catalog;

use Shift\System\Core\Mvc;

class Information extends Mvc\Model
{
    public function addInformation(array $data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "information SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");

        $information_id = (int)$this->db->insertId();

        foreach ($data['information_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . $information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['information_store'])) {
            foreach ($data['information_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . $information_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        if (isset($data['information_layout'])) {
            foreach ($data['information_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout SET information_id = '" . $information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        if (isset($data['keyword'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . $information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('information');

        return $information_id;
    }

    public function editInformation(int $information_id, array $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "information SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE information_id = '" . $information_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . $information_id . "'");

        foreach ($data['information_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . $information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . $information_id . "'");

        if (isset($data['information_store'])) {
            foreach ($data['information_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . $information_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . $information_id . "'");

        if (isset($data['information_layout'])) {
            foreach ($data['information_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout SET information_id = '" . $information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . $information_id . "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . $information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('information');
    }

    public function deleteInformation(int $information_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE information_id = '" . $information_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . $information_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . $information_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . $information_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . $information_id . "'");

        $this->cache->delete('information');
    }

    public function getInformation(int $information_id)
    {
        $query = $this->db->get("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . $information_id . "') AS keyword FROM " . DB_PREFIX . "information WHERE information_id = '" . $information_id . "'");

        return $query->row;
    }

    public function getInformations(array $data = array())
    {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sort_data = array(
                'id.title',
                'i.sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY id.title";
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
            $information_data = $this->cache->get('information.' . (int)$this->config->get('config_language_id'));

            if (!$information_data) {
                $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

                $information_data = $query->rows;

                $this->cache->set('information.' . (int)$this->config->get('config_language_id'), $information_data);
            }

            return $information_data;
        }
    }

    public function getInformationDescriptions(int $information_id)
    {
        $information_description_data = array();

        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . $information_id . "'");

        foreach ($query->rows as $result) {
            $information_description_data[$result['language_id']] = array(
                'title'            => $result['title'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword']
            );
        }

        return $information_description_data;
    }

    public function getInformationStores(int $information_id)
    {
        $information_store_data = array();

        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . $information_id . "'");

        foreach ($query->rows as $result) {
            $information_store_data[] = $result['store_id'];
        }

        return $information_store_data;
    }

    public function getInformationLayouts(int $information_id)
    {
        $information_layout_data = array();

        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . $information_id . "'");

        foreach ($query->rows as $result) {
            $information_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $information_layout_data;
    }

    public function getTotalInformations()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information");

        return $query->row['total'];
    }

    public function getTotalInformationsByLayoutId(int $layout_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }
}
