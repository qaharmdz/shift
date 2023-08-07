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

            $this->db->set(
                DB_PREFIX . 'user_group',
                [
                    'status'  => $status,
                    'updated' => date('Y-m-d H:i:s'),
                ],
                ['user_group_id' => $items]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
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

        $this->cache->deleteByTags('usergroups');

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

        $this->cache->deleteByTags('usergroups');
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

    /**
     * Get user groups
     *
     * @param  array  $filters
     * @param  string $rkey    Return key
     * @return array
     */
    public function getUserGroups(array $filters = ['1 = ?i' => 1], string $rkey = 'user_group_id'): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('usergroups.' . $argsHash, []);

        if (!$data) {
            $userGroups = $this->db->get(
                "SELECT ug.* FROM `" . DB_PREFIX . "user_group` ug
                WHERE " . implode(' AND ', array_keys($filters)) . "
                ORDER BY ug.name ASC",
                array_values($filters)
            )->rows;

            foreach ($userGroups as $result) {
                $data[$result[$rkey]] = $result;
            }

            $this->cache->set('usergroups.' . $argsHash, $data, tags: ['usergroups']);
        }

        return $data;
    }

    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_group`")->row['total'];
    }

    public function deleteUserGroups(array $userGroups)
    {
        if (!in_array(1, $userGroups)) {
            $this->db->delete(DB_PREFIX . 'user_group', ['user_group_id' => $userGroups]);
        }

        $this->cache->deleteByTags('usergroups');
    }

    public function addPermission(int $user_group_id, string $access, string $path)
    {
        $userGroup = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = ?i",
            [$user_group_id]
        )->row;

        if ($userGroup) {
            $permissions = json_decode($userGroup['permission'], true);

            if (!in_array($path, $permissions[$access])) {
                $permissions[$access][] = $path;

                $this->db->set(
                    DB_PREFIX . 'user_group',
                    ['permission' => json_encode($permissions)],
                    ['user_group_id' => $user_group_id]
                );
            }
        }
    }

    public function removePermission(string $access, string $path)
    {
        $userGroups = $this->db->get("SELECT * FROM `" . DB_PREFIX . "user_group`")->rows;

        foreach ($userGroups as $userGroup) {
            $permissions = json_decode($userGroup['permission'], true);

            if (in_array($path, $permissions[$access])) {
                $permissions[$access] = array_diff($permissions[$access], [$path]);

                $this->db->set(
                    DB_PREFIX . 'user_group',
                    ['permission' => json_encode($permissions)],
                    ['user_group_id' => $userGroup['user_group_id']]
                );
            }
        }
    }
}
