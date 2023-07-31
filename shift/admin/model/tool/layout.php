<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Tool;

use Shift\System\Mvc;
use Shift\System\Helper;

class Layout extends Mvc\Model
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
            'layout_id' => 'l.layout_id',
            'name'      => 'l.name',
            'status'    => 'l.status',
        ];
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "layout` l"
            . ($dtResult['query']['where'] ? " WHERE " . $dtResult['query']['where'] : "")
            . " ORDER BY " . $dtResult['query']['order']
            . " LIMIT " . $dtResult['query']['limit'];

        return $this->db->get($query, $dtResult['query']['params']);
    }

    public function dtAction(string $type, array $items): array
    {
        $_items = [];

        if (in_array($type, ['enabled', 'disabled'])) {
            $status = $type == 'enabled' ? 1 : 0;

            $this->db->set(
                DB_PREFIX . 'layout',
                [
                    'status'  => $status,
                ],
                ['layout_id' => $items]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
            }
        }

        if ($type == 'delete') {
            $this->deleteLayouts($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function addLayout(array $data): int
    {
        $this->db->add(
            DB_PREFIX . 'layout',
            [
                'name'        => $data['name'],
                'placements'  => $data['placements'],
                'custom_code' => $data['custom_code'],
                'status'      => (int)$data['status'],
            ],
        );

        $layout_id = (int)$this->db->insertId();

        $this->insertData($layout_id, $data);

        return $layout_id;
    }

    public function editLayout(int $layout_id, array $data)
    {
        $updated = $this->db->set(
            DB_PREFIX . 'layout',
            [
                'name'        => $data['name'],
                'placements'  => $data['placements'],
                'custom_code' => $data['custom_code'],
                'status'      => (int)$data['status'],
            ],
            ['layout_id' => $layout_id]
        );

        if ($updated->affected_rows != -1) {
            $this->db->delete(DB_PREFIX . 'layout_route', ['layout_id' => $layout_id]);
            // $this->db->delete(DB_PREFIX . 'layout_module', ['layout_id' => $layout_id]);

            $this->insertData($layout_id, $data);

            return $updated->affected_rows;
        }
    }

    protected function insertData(int $layout_id, array $data)
    {
        foreach ($data['routes'] as $route) {
            $this->db->add(
                DB_PREFIX . 'layout_route',
                [
                    'layout_id'  => $layout_id,
                    'site_id'    => (int)$route['site_id'],
                    'route'      => $route['route'],
                    'url_params' => $route['url_params'],
                    'exclude'    => (int)($route['exclude'] ?? 0),
                    'priority'   => (int)$route['priority'],
                ]
            );
        }
    }

    public function getLayout(int $layout_id): array
    {
        $this->load->config('tool/layout');

        $default = $this->config->getArray('tool.layout.form');

        $data = $this->db->get(
            "SELECT l.* FROM `" . DB_PREFIX . "layout` l WHERE l.layout_id = ?i",
            [$layout_id]
        )->row;

        if (!empty($data['layout_id'])) {
            $data['placements'] = json_decode((string)$data['placements'], true);
            if (!$data['placements']) {
                unset($data['placements']);
            }

            // Routes
            $data['routes'] = [];
            $routes = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "layout_route`
                WHERE layout_id = ?i
                ORDER BY site_id ASC, priority DESC, route ASC",
                [$layout_id]
            )->rows;
            foreach ($routes as $route) {
                $data['routes'][] = $route;
            }

            // Modules
            $data['modules'] = [];
            $modules = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "layout_module`
                WHERE layout_id = ?i ORDER BY position ASC, sort_order ASC",
                [$layout_id]
            )->rows;
            foreach ($modules as $module) {
                $data['modules'][] = $module;
            }
        }

        return array_replace_recursive($default, $data);
    }

    public function getLayouts($data = array()): array
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "layout";

        $sort_data = array('name');

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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
    }

    public function getTotal(): int
    {
        return (int)$this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "layout`")->row['total'];
    }

    public function deleteLayouts(array $layout_ids): void
    {
        $this->db->delete(DB_PREFIX . 'layout', ['layout_id' => $layout_ids]);
        $this->db->delete(DB_PREFIX . 'layout_module', ['layout_id' => $layout_ids]);
        $this->db->delete(DB_PREFIX . 'layout_route', ['layout_id' => $layout_ids]);

        $this->cache->deleteByTags('layouts');
    }

    /*
    public function addLayout($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = '" . $this->db->escape($data['name']) . "'");

        $layout_id = $this->db->getLastId();

        if (isset($data['layout_route'])) {
            foreach ($data['layout_route'] as $layout_route) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', site_id = '" . (int)$layout_route['site_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "'");
            }
        }

        if (isset($data['layout_module'])) {
            foreach ($data['layout_module'] as $layout_module) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$layout_id . "', code = '" . $this->db->escape($layout_module['code']) . "', position = '" . $this->db->escape($layout_module['position']) . "', sort_order = '" . (int)$layout_module['sort_order'] . "'");
            }
        }

        return $layout_id;
    }

    public function editLayout($layout_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "layout SET name = '" . $this->db->escape($data['name']) . "' WHERE layout_id = '" . (int)$layout_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");

        if (isset($data['layout_route'])) {
            foreach ($data['layout_route'] as $layout_route) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', site_id = '" . (int)$layout_route['site_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "'");

        if (isset($data['layout_module'])) {
            foreach ($data['layout_module'] as $layout_module) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$layout_id . "', code = '" . $this->db->escape($layout_module['code']) . "', position = '" . $this->db->escape($layout_module['position']) . "', sort_order = '" . (int)$layout_module['sort_order'] . "'");
            }
        }
    }

    public function getLayout($layout_id)
    {
        $query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row;
    }

    public function getLayoutRoutes($layout_id)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->rows;
    }

    public function getLayoutModules($layout_id)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "' ORDER BY position ASC, sort_order ASC");

        return $query->rows;
    }

    public function getTotalLayouts()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "layout");

        return $query->row['total'];
    }
    */
}
