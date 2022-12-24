<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Plugin extends Mvc\Model
{
    /**
     * DataTables records
     *
     * @param  array  $params
     */
    public function dtRecords(array $params): \stdClass
    {
        $columnMap = [
            'extension_id' => 'e.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'e.name',
            'status'       => 'ed.status',
        ];
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "extension` e
                LEFT JOIN `" . DB_PREFIX . "extension_data` ed ON (e.extension_id = ed.extension_id)"
            . " WHERE e.`type` = 'plugin' AND e.`install` = 1"
                . ($dtResult['query']['where'] ? " AND  " . $dtResult['query']['where'] : "")
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
                    ],
                    ['extension_id' => (int)$item]
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

    public function getTotal(): int
    {
        return (int)$this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "extension` WHERE `type` = 'plugin'")->row['total'];
    }
}
