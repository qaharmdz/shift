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
        $filterMap = $columnMap;
        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "user_group` ug"
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
                'status'     => (int)$data['status'],
                'created'    => date('Y-m-d H:i:s'),
                'updated'    => date('Y-m-d H:i:s'),
            ]
        );

        $this->cache->delete('usergroups');

        return (int)$this->db->insertId();
    }

    public function editUserGroup(int $user_group_id, array $data)
    {
        $this->db->set(
            DB_PREFIX . 'user_group',
            [
                'name'       => $data['name'],
                'backend'    => $data['backend'],
                'permission' => json_encode($data['permission']),
                'status'     => (int)$data['status'],
                'updated'    => date('Y-m-d H:i:s'),
            ],
            ['user_group_id' => $user_group_id]
        );

        $this->cache->delete('usergroups');
    }

    public function getUserGroup(int $user_group_id)
    {
        $this->load->config('account/usergroup');

        $default = $this->config->getArray('account.usergroup.form');

        $data = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "user_group` WHERE user_group_id = ?i",
            [$user_group_id]
        )->row;

        if (!empty($data['permission'])) {
            $data['permission'] = json_decode($data['permission'], true);
        }

        return array_replace_recursive($default, $data);
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
        if (!in_array(1, $items)) {
            $this->db->delete(DB_PREFIX . 'user_group', ['user_group_id' => $userGroups]);
        }

        $this->cache->delete('usergroups');
    }
}
