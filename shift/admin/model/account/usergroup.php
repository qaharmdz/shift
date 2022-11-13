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
                $this->db->set(
                    DB_PREFIX . 'user_group',
                    [
                        'status'  => $status,
                        'updated' => date('Y-m-d H:i:s'),
                    ],
                    ['user_group_id' => (int)$item]
                );

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

    public function addUserGroup(array $data)
    {
        $this->db->add(
            DB_PREFIX . 'user_group',
            [
                'name'       => $data['name'],
                'backend'    => $data['backend'],
                'permission' => json_encode($data['permission']),
                'status'     => $data['status'],
                'created'    => date('Y-m-d H:i:s'),
                'updated'    => date('Y-m-d H:i:s'),
            ]
        );

        $this->cache->delete('usergroups');

        return $this->db->insertId();
    }

    public function editUserGroup(int $user_group_id, array $data)
    {
        $this->db->set(
            DB_PREFIX . 'user_group',
            [
                'name'       => $data['name'],
                'backend'    => $data['backend'],
                'permission' => json_encode($data['permission']),
                'status'     => $user_group_id ? $data['status'] : 1,
                'updated'    => date('Y-m-d H:i:s'),
            ],
            ['user_group_id' => (int)$user_group_id]
        );

        $this->cache->delete('usergroups');
    }

    public function getUserGroup(int $user_group_id)
    {
        $result = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "user_group` WHERE user_group_id = ?i",
            [$user_group_id]
        )->row;

        if (!empty($result['permission'])) {
            $result['permission'] = json_decode($result['permission'], true);
        }

        return $result;
    }

    public function getUserGroups()
    {
        $userGroups = $this->cache->get('usergroups');

        if (!$userGroups) {
            $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "user_group` ORDER BY name ASC");
            $userGroups = $query->rows;

            $this->cache->set('usergroups', $userGroups);
        }

        return $userGroups;
    }

    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_group`")->row['total'];
    }

    public function deleteUserGroups(array $userGroups)
    {
        $this->db->delete(DB_PREFIX . 'user_group', ['user_group_id' => $userGroups]);

        $this->cache->delete('usergroups');
    }
}
