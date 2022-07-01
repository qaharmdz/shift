<?php

declare(strict_types=1);

namespace Cart;

class User
{
    private $user_id;
    private $username;
    private $permission = array();

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        if ($user_id = $this->session->getInt('user_id')) {
            $user_query = $this->db->get("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = ?i AND status = ?i", [$user_id, 1]);

            if ($user_query->num_rows) {
                $this->user_id = $user_query->row['user_id'];
                $this->username = $user_query->row['username'];
                $this->user_group_id = $user_query->row['user_group_id'];

                $this->db->query(
                    "UPDATE " . DB_PREFIX . "user SET ip = ?s WHERE user_id = ?i",
                    [$this->request->getIp(), $user_id]
                );

                $user_group_query = $this->db->get("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = ?i", [$user_query->row['user_group_id']]);

                $permissions = json_decode($user_group_query->row['permission'], true);

                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }
            } else {
                $this->logout();
            }
        }
    }

    public function login($username, $password)
    {
        $user_query = $this->db->get("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape(htmlspecialchars($password, ENT_QUOTES)) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

        if ($user_query->num_rows) {
            $this->session->set('user_id', $user_query->row['user_id']);

            $this->user_id = $user_query->row['user_id'];
            $this->username = $user_query->row['username'];
            $this->user_group_id = $user_query->row['user_group_id'];

            $user_group_query = $this->db->get("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = ?i", [$user_query->row['user_group_id']]);

            $permissions = json_decode($user_group_query->row['permission'], true);

            if (is_array($permissions)) {
                foreach ($permissions as $key => $value) {
                    $this->permission[$key] = $value;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $this->session->delete('user_id');

        $this->user_id = '';
        $this->username = '';
    }

    public function hasPermission($key, $value)
    {
        if (isset($this->permission[$key])) {
            return in_array($value, $this->permission[$key]);
        } else {
            return false;
        }
    }

    public function isLogged()
    {
        return $this->user_id;
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getFirstName()
    {
        return $this->username;
    }

    public function getLastName()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->username;
    }

    public function getGroupId()
    {
        return $this->user_group_id;
    }
}
