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
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "site` s"
            . ($dtResult['query']['where'] ? " WHERE " . $dtResult['query']['where'] : "")
            . " ORDER BY " . $dtResult['query']['order']
            . " LIMIT " . $dtResult['query']['limit'];

        return $this->db->get($query, $dtResult['query']['params']);
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

        $site_id = (int)$this->db->insertId();

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
            ['site_id' => $site_id]
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
        $sites = $this->cache->get('sites');

        if (!$sites) {
            $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "site` ORDER BY site_id ASC");
            $sites = $query->rows;

            $this->cache->set('sites', $sites);
        }

        return $sites;
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
}
