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

        if ($type == 'delete') {
            $this->deleteSites($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================
    public function addSite(array $data)
    {
        $this->db->add(
            DB_PREFIX . 'site',
            [
                'name'     => $data['name'],
                'url_host' => $data['url_host'],
            ]
        );

        $site_id = $this->db->insertId();

        // Layout Route
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE site_id = 0");

        foreach ($query->rows as $layout_route) {
            $this->db->add(
                DB_PREFIX . 'layout_route',
                [
                    'layout_id' => $layout_route['layout_id'],
                    'route'     => $layout_route['route'],
                    'site_id'   => $site_id,
                ]
            );
        }

        $this->cache->delete('sites');

        return $site_id;
    }

    public function editSite(int $site_id, array $data)
    {
        $this->db->set(
            DB_PREFIX . 'site',
            [
                'name'     => $data['name'],
                'url_host' => $data['url_host'],
            ],
            ['site_id' => (int)$site_id]
        );

        $this->cache->delete('sites');
    }

    public function getSite(int $site_id)
    {
        return $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "site` WHERE site_id = ?i",
            [$site_id]
        )->row;
    }

    public function getSites()
    {
        $site_data = $this->cache->get('sites');

        if (!$site_data) {
            $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "site` ORDER BY site_id ASC");
            $site_data = $query->rows;

            $this->cache->set('sites', $site_data);
        }

        return $site_data;
    }

    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "site`")->row['total'];
    }

    public function deleteSites(array $site_ids)
    {
        if (!in_array(0, $site_ids)) {
            $this->db->delete(DB_PREFIX . 'site', ['site_id' => $site_ids]);
            $this->db->delete(DB_PREFIX . 'layout_route', ['site_id' => $site_ids]);
            $this->db->delete(DB_PREFIX . 'setting', ['site_id' => $site_ids]);
        }

        $this->cache->delete('sites');
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
