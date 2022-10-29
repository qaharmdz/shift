<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Account;

use Shift\System\Mvc;
use Shift\System\Helper;

class UserGroup extends Mvc\Model
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
            'user_group_id' => 'ug.user_group_id',
            'name'          => 'ug.name',
            'backend'       => 'ug.backend',
            'status'        => 'ug.status',
        ];
        $filterMap  = $columnMap;
        $dataTables = (new Helper\Datatables())->parse($params)->sqlQuery($filterMap)->pullData();

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "user_group` ug"
            . ($dataTables['sql']['query']['where'] ? " WHERE " . $dataTables['sql']['query']['where'] : "")
            . " ORDER BY " . $dataTables['sql']['query']['order']
            . " LIMIT " . $dataTables['sql']['query']['limit'];

        return $this->db->get($query, $dataTables['sql']['params']);
    }

    public function dtAction(string $type, array $items): array
    {
        $_items = [];

        if (in_array($type, ['enabled', 'disabled'])) {
            $status = $type == 'enabled' ? 1 : 0;

            foreach ($items as $item) {
                $this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `status` = ?i, updated = NOW() WHERE `user_group_id` = ?i", [$status, (int)$item]);

                if ($this->db->affectedRows()) {
                    $_items[] = $item;
                }
            }
        }

        if ($type == 'delete') {
            $this->deleteUserGroups($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_group`")->row['total'];
    }

    public function deleteUserGroups(array $userGroups)
    {
        $this->db->delete(DB_PREFIX . 'user_group', ['user_group_id' => $userGroups]);

        $this->cache->delete('user_groups');
    }

    /*
    public function addUserGroup($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "'");

        return $this->db->getLastId();
    }

    public function editUserGroup($user_group_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
    }

    public function deleteUserGroup($user_group_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
    }

    public function getUserGroup($user_group_id)
    {
        $query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        $user_group = array(
            'name'       => $query->row['name'],
            'permission' => json_decode($query->row['permission'], true)
        );

        return $user_group;
    }

    public function getUserGroups($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "user_group";

        $sql .= " ORDER BY name";

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

    public function getTotalUserGroups()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

        return $query->row['total'];
    }

    public function addPermission($user_group_id, $type, $route)
    {
        $user_group_query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        if ($user_group_query->num_rows) {
            $data = json_decode($user_group_query->row['permission'], true);

            $data[$type][] = $route;

            $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
        }
    }

    public function removePermission($user_group_id, $type, $route)
    {
        $user_group_query = $this->db->get("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

        if ($user_group_query->num_rows) {
            $data = json_decode($user_group_query->row['permission'], true);

            $data[$type] = array_diff($data[$type], array($route));

            $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
        }
    }
    */
}
