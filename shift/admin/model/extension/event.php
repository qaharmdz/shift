<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Event extends Mvc\Model
{
    /**
     * DataTables records
     *
     * @param  array  $params
     */
    public function dtRecords(array $params): \stdClass
    {
        $columnMap = [
            'event_id'    => 'e.event_id',
            'codename'    => 'e.codename',
            'description' => 'e.description',
            'emitter'     => 'e.emitter',
            'listener'    => 'e.listener',
            'priority'    => 'e.priority',
            'status'      => 'e.status',
        ];
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "event` e"
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
                DB_PREFIX . 'event',
                [
                    'status'  => $status,
                ],
                ['event_id' => $items]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
            }
        }

        return $_items;
    }

    public function getTotal(): int
    {
        return (int)$this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "event`")->row['total'];
    }
}
