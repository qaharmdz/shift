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
                "SELECT lr.* FROM `" . DB_PREFIX . "layout_route` lr
                    INNER JOIN `" . DB_PREFIX . "site` s
                WHERE layout_id = ?i AND lr.site_id = s.site_id
                ORDER BY lr.site_id ASC, lr.priority DESC, lr.route ASC",
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
        return $this->db->get("SELECT * FROM " . DB_PREFIX . "layout ORDER BY name ASC")->rows;
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
}
