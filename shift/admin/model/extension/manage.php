<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Manage extends Mvc\Model
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
            'extension_id' => 'e.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'e.name',
            'version'      => 'e.version',
            'type'         => 'e.type',
            'status'       => 'e.status',
            'install'      => 'e.install',
        ];
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "extension` e"
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
                    DB_PREFIX . 'extension',
                    [
                        'status'  => $status,
                        'updated' => date('Y-m-d H:i:s'),
                    ],
                    ['extension_id' => (int)$item]
                );

                if ($this->db->affectedRows()) {
                    $_items[] = $item;
                }
            }
        }

        if ($type == 'install') {
            foreach ($items as $extension_id) {
                // $this->install($extension_id);
            }
        }

        if ($type == 'uninstall') {
            foreach ($items as $extension_id) {
                // $this->uninstall($extension_id);
            }
        }

        if ($type == 'delete') {
            // $this->deletes($items);
        }

        return $_items;
    }

    public function getTotal(): int
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "post` WHERE `taxonomy` = 'post'")->row['total'];
    }

    // Manage
    // ================================================

    public function getInstalled($type)
    {
        $data = [];
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY codename");

        foreach ($query->rows as $result) {
            $data[] = $result['codename'];
        }

        return $data;
    }

    /*
    public function install($type, $codename)
    {
        $extensions = $this->getInstalled($type);

        if (!in_array($codename, $extensions)) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = '" . $this->db->escape($type) . "', `codename` = '" . $this->db->escape($codename) . "'");
        }
    }

    public function uninstall($type, $codename)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = ?s AND `codename` = ?s", [$type, $codename]);
        // TODO: delete extension_meta
        // TODO: delete extension_module
        $this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = ?s AND `codename` = ?s", [$type, $codename]);
    }
    */
}
