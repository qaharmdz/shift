<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Module extends Mvc\Model
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
            'module_id'    => 'm.module_id',
            'extension_id' => 'm.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'm.name',
            'status'       => 'm.status',
            'publish'      => 'm.publish',
            'unpublish'    => 'm.unpublish',
        ];
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "module` m
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (m.extension_id = e.extension_id)"
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

            foreach ($items as $item) {
                $this->db->set(
                    DB_PREFIX . 'module',
                    [
                        'status'  => $status,
                        'updated' => date('Y-m-d H:i:s'),
                    ],
                    ['module_id' => (int)$item]
                );

                if ($this->db->affectedRows()) {
                    $_items[] = $item;
                }
            }
        }

        if ($type == 'delete') {
            // $this->deleteUsers($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "module`")->row['total'];
    }

    /*
    public function addModule($code, $data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "'");
    }

    public function editModule($module_id, $data)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
    }

    public function deleteModule($module_id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '%." . (int)$module_id . "'");
    }

    public function getModule($module_id)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

        if ($query->row) {
            return json_decode($query->row['setting'], true);
        } else {
            return array();
        }
    }

    public function getModules()
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "module` ORDER BY `code`");

        return $query->rows;
    }

    public function getModulesByCode($code)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

        return $query->rows;
    }

    public function deleteModulesByCode($code)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '" . $this->db->escape($code) . "' OR `code` LIKE '" . $this->db->escape($code . '.%') . "'");
    }
    */
}
