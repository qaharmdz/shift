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

    // Form CRUD
    // ================================================

    /*
    public function addEvent($code, $trigger, $action, $status = 1)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($code) . "', `trigger` = '" . $this->db->escape($trigger) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `date_added` = now()");

        return $this->db->getLastId();
    }

    public function deleteEvent($code)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "'");
    }

    public function getEvent($code, $trigger, $action)
    {
        $event = $this->db->get("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "' AND `trigger` = '" . $this->db->escape($trigger) . "' AND `action` = '" . $this->db->escape($action) . "'");

        return $event->rows;
    }

    public function enableEvent($event_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "event SET `status` = '1' WHERE event_id = '" . (int)$event_id . "'");
    }

    public function disableEvent($event_id)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "event SET `status` = '0' WHERE event_id = '" . (int)$event_id . "'");
    }

    public function uninstall($type, $code)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = ?s AND `code` = ?s", [$type, $code]);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = ?S AND `code` = ?s", [$type, $code]);
    }

    public function getEvents($data = array())
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "event`";

        $sort_data = array(
            'group',
            'trigger',
            'action',
            'status',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY `" . $data['sort'] . "`";
        } else {
            $sql .= " ORDER BY `group`";
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

    public function getTotalEvents()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "event");

        return $query->row['total'];
    }
    */
}
