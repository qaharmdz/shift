<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Setting;

use Shift\System\Mvc;
use Shift\System\Helper;

class Site extends Mvc\Model
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
            'site_id'  => 's.site_id',
            'name'     => 's.name',
            'url_host' => 's.url_host',
        ];
        $filterMap  = $columnMap;
        $dataTables = (new Helper\Datatables())->parse($params)->sqlQuery($filterMap)->pullData();

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "site` s"
            . ($dataTables['sql']['query']['where'] ? " WHERE " . $dataTables['sql']['query']['where'] : "")
            . " ORDER BY " . $dataTables['sql']['query']['order']
            . " LIMIT " . $dataTables['sql']['query']['limit'];

        return $this->db->get($query, $dataTables['sql']['params']);
    }

    public function dtAction(string $type, array $items): array
    {
        $_items = [];

        if (in_array($type, ['enabled', 'disabled'])) {
            $status = $type == 'enabled' ? 1 : 0;

            foreach ($items as $item) {
                $updated = $this->db->rawQuery(
                    "UPDATE `" . DB_PREFIX . "term`
                    SET status = " . (int)$status . ",
                        updated = NOW()
                    WHERE term_id = " . (int)$item . "
                        AND taxonomy = '" . $this->taxonomy . "'"
                );

                if ($updated) {
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
    /*
    public function addSite($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "site SET name = '" . $this->db->escape($data['name']) . "', `url_host` = '" . $this->db->escape($data['url_host']) . "', `ssl` = '" . $this->db->escape($data['ssl']) . "'");

        $site_id = $this->db->getLastId();

        // Layout Route
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "layout_route WHERE site_id = '0'");

        foreach ($query->rows as $layout_route) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_route['layout_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "', site_id = '" . (int)$site_id . "'");
        }

        $this->cache->delete('site');

        return $site_id;
    }
    */

    public function editSite($site_id, $data)
    {
        $this->db->set(
            DB_PREFIX . 'site',
            [
                'name'     => $data['name'],
                'url_host' => $data['url_host'],
            ],
            ['site_id' => (int)$site_id]
        );

        $this->cache->delete('site');
    }

    /*
    public function deleteSite($site_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "site WHERE site_id = '" . (int)$site_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE site_id = '" . (int)$site_id . "'");

        $this->cache->delete('site');
    }

    public function getSite($site_id)
    {
        $query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "site WHERE site_id = '" . (int)$site_id . "'");

        return $query->row;
    }
    */

    public function getSites()
    {
        $site_data = $this->cache->get('site');

        if (!$site_data) {
            $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "site` ORDER BY site_id ASC");
            $site_data = $query->rows;

            $this->cache->set('site', $site_data);
        }

        return $site_data;
    }

    public function getTotal()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "site`");

        return $query->row['total'];
    }

    /*
    public function getTotalSitesByLayoutId($layout_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'layout_id' AND `value` = '" . (int)$layout_id . "' AND site_id != '0'");

        return $query->row['total'];
    }

    public function getTotalSitesByLanguage($language)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'language' AND `value` = '" . $this->db->escape($language) . "' AND site_id != '0'");

        return $query->row['total'];
    }

    public function getTotalSitesByCurrency($currency)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'currency' AND `value` = '" . $this->db->escape($currency) . "' AND site_id != '0'");

        return $query->row['total'];
    }

    public function getTotalSitesByCountryId($country_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'country_id' AND `value` = '" . (int)$country_id . "' AND site_id != '0'");

        return $query->row['total'];
    }

    public function getTotalSitesByZoneId($zone_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'zone_id' AND `value` = '" . (int)$zone_id . "' AND site_id != '0'");

        return $query->row['total'];
    }

    public function getTotalSitesByCustomerGroupId($customer_group_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND site_id != '0'");

        return $query->row['total'];
    }

    public function getTotalSitesByInformationId($information_id)
    {
        $account_query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'account_id' AND `value` = '" . (int)$information_id . "' AND site_id != '0'");

        $checkout_query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'checkout_id' AND `value` = '" . (int)$information_id . "' AND site_id != '0'");

        return ($account_query->row['total'] + $checkout_query->row['total']);
    }

    public function getTotalSitesByOrderStatusId($order_status_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'order_status_id' AND `value` = '" . (int)$order_status_id . "' AND site_id != '0'");

        return $query->row['total'];
    }
    */
}
