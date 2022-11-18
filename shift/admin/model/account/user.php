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
        $filterMap  = $columnMap;
        $filterMap['fullname']   = 'CONCAT(u.firstname, " ", u.lastname, " ", u.username)';
        $filterMap['user_group'] = 'u.user_group_id';

        $dataTables = (new Helper\Datatables())->parse($params)->sqlQuery($filterMap)->pullData();

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "user` u
                LEFT JOIN `" . DB_PREFIX . "user_group` ug ON (u.user_group_id = ug.user_group_id)"
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


    public function getTotal()
    {
        return $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`")->row['total'];
    }

    public function deleteUsers(array $users)
    {
        $this->db->delete(DB_PREFIX . 'user', ['user_id' => $users]);

        $this->cache->delete('users');
    }

    /*
    public function addUser($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', password = '" . $this->db->escape($this->secure->passwordHash($data['password'])) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

        return $this->db->getLastId();
    }

    public function editUser($user_id, $data)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

        if ($data['password']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape($this->secure->passwordHash($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
        }
    }

    public function editPassword($user_id, $password)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape($this->secure->passwordHash($password)) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
    }

    public function editCode($email, $code)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function deleteUser($user_id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
    }

    public function getUser($user_id)
    {
        $query = $this->db->get("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

        return $query->row;
    }

    public function getUserByUsername($username)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getUserByEmail($email)
    {
        $query = $this->db->get("SELECT DISTINCT * FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getUserByCode($code)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = array())
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "user`";

        $sort_data = array(
            'username',
            'status',
            'date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY username";
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

    public function getTotalUsers()
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email)
    {
        $query = $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row['total'];
    }
    */
}
