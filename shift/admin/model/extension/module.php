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
            'module_id'    => 'ed.extension_data_id AS module_id',
            'extension_id' => 'ed.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'ed.name',
            'status'       => 'ed.status',
        ];
        $filterMap = $columnMap;

        $dtResult  = Helper\DataTables::parse($params, $filterMap);
        $filterMap['module_id'] = 'ed.extension_data_id';

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "extension_data` ed
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (ed.extension_id = e.extension_id)"
            . " WHERE e.`type` = 'module' AND e.`install` = 1"
                 . ($dtResult['query']['where'] ? " AND " . $dtResult['query']['where'] : "")
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
            $this->deleteModules($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function getModule(int $module_id): array
    {
        $result = $this->db->get(
            "SELECT *
            FROM `" . DB_PREFIX . "extension_data` ed
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (ed.extension_id = e.extension_id)
            WHERE  e.`type` = 'module' AND ed.`extension_data_id` = ?i",
            [$module_id]
        )->row;

        if (!empty($result['user_id'])) {
            $result['setting'] = json_decode($result['setting'], true);
        }

        return $result;
    }

    public function getTotal(): int
    {
        return (int)$this->db->get(
            "SELECT COUNT(*) AS total
            FROM `" . DB_PREFIX . "extension_data` ed
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (ed.extension_id = e.extension_id)
            WHERE e.`type` = 'module'"
        )->row['total'];
    }

    public function deleteModules(array $modules): void
    {
        $this->db->delete(DB_PREFIX . 'extension_data', ['extension_data_id' => $modules]);

        $this->cache->deleteByTags('modules');
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

    public function getModulesByCode($code)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

        return $query->rows;
    }
    */
}
