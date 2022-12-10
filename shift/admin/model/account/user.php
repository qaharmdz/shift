<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Account;

use Shift\System\Mvc;
use Shift\System\Helper;

class User extends Mvc\Model
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
            'user_id'       => 'u.user_id',
            'user_group_id' => 'u.user_group_id',
            'user_group'    => 'ug.name AS user_group',
            'username'      => 'u.username',
            'email'         => 'u.email',
            'password'      => 'u.password',
            'firstname'     => 'u.firstname',
            'lastname'      => 'u.lastname',
            'fullname'      => 'CONCAT(u.firstname, " ", u.lastname) AS fullname',
            'status'        => 'u.status',
            'created'       => 'u.created',
            'updated'       => 'u.updated',
            'last_login'    => 'u.last_login',
        ];
        $filterMap = $columnMap;
        $filterMap['fullname']   = 'CONCAT_WS(" ", u.firstname, u.lastname, u.username)';
        $filterMap['user_group'] = 'u.user_group_id';

        $dtResult = Helper\DataTables::parse($params, $filterMap, ['last_login']);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "user` u
                LEFT JOIN `" . DB_PREFIX . "user_group` ug ON (u.user_group_id = ug.user_group_id)"
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
                    DB_PREFIX . 'user',
                    [
                        'status'  => $status,
                        'updated' => date('Y-m-d H:i:s'),
                    ],
                    ['user_id' => (int)$item]
                );

                if ($this->db->affectedRows()) {
                    $_items[] = $item;
                }
            }
        }

        if ($type == 'delete') {
            $this->deleteUsers($items);
        }

        return $_items;
    }

    // Form CRUD
    // ================================================

    public function addUser(array $data)
    {
        $this->db->add(
            DB_PREFIX . 'user',
            [
                'user_group_id' => $data['user_group_id'],
                'email'         => $data['email'],
                'password'      => $this->secure->passwordHash($data['password']),
                'username'      => $data['username'],
                'firstname'     => $data['firstname'],
                'lastname'      => $data['lastname'],
                'status'        => (int)$data['status'],
                'created'       => date('Y-m-d H:i:s'),
                'updated'       => date('Y-m-d H:i:s'),
            ]
        );

        $user_id = $this->db->insertId();

        // User meta
        $params = [];
        foreach ($data['meta'] as $key => $value) {
            $params[] = [$user_id, $key, (is_array($value) ? json_encode($value) : $value), (is_array($value) ? 1 : 0)];
        }

        $this->db->transaction(
            "INSERT INTO `" . DB_PREFIX . "user_meta` (`user_id`, `key`, `value`, `encoded`) VALUES (?i, ?s, ?s, ?i)",
            $params
        );

        return $user_id;
    }

    public function editUser(int $user_id, array $data)
    {
        $updates = [
            'user_group_id' => $data['user_group_id'],
            'email'         => $data['email'],
            'username'      => $data['username'],
            'firstname'     => $data['firstname'],
            'lastname'      => $data['lastname'],
            'status'        => (int)$data['status'],
            'updated'       => date('Y-m-d H:i:s'),
        ];

        if ($data['password']) {
            $data['password'] = $this->secure->passwordHash($data['password']);
        }

        $this->db->set(DB_PREFIX . 'user', $updates, ['user_id' => $user_id]);

        // User meta
        $this->db->delete(DB_PREFIX . 'user_meta', ['user_id' => $user_id]);

        $params = [];
        foreach ($data['meta'] as $key => $value) {
            $params[] = [$user_id, $key, (is_array($value) ? json_encode($value) : $value), (is_array($value) ? 1 : 0)];
        }

        $this->db->transaction(
            "INSERT INTO `" . DB_PREFIX . "user_meta` (`user_id`, `key`, `value`, `encoded`) VALUES (?i, ?s, ?s, ?i)",
            $params
        );

        $this->cache->delete('user.' . $user_id);
    }

    public function getUser(int $user_id)
    {
        $result = $this->db->get(
            "SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group
            FROM `" . DB_PREFIX . "user` u
            WHERE u.user_id = ?i",
            [$user_id]
        )->row;

        if (!empty($result['user_id'])) {
            $result['meta'] = [];
            $metas = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_meta` um WHERE um.user_id = ?i", [$user_id]);

            foreach ($metas as $meta) {
                $result['meta'][$meta['key']] = $meta['encoded'] ? json_encode($meta['value'], true) : $meta['value'];
            }
        }

        return $result;
    }

    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`")->row['total'];
    }

    public function deleteUsers(array $users)
    {
        $this->db->delete(DB_PREFIX . 'user', ['user_id' => $users]);
        $this->db->delete(DB_PREFIX . 'user_meta', ['user_id' => $users]);

        $this->cache->delete('users');
    }
}
