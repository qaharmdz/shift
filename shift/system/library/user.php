<?php

declare(strict_types=1);

namespace Shift\System\Library;

use Shift\System\Core;

class User
{
    protected $db;
    protected $session;
    protected $secure;
    protected $bags;

    public function __construct($registry)
    {
        $this->db      = $registry->get('db');
        $this->session = $registry->get('session');
        $this->secure  = $registry->get('secure');
        $this->bags    = new Core\Bags();

        if ($email = $this->session->getString('user_email')) {
            $user = $this->dbGetUserByMail($email);

            if ($user['user_id']) {
                unset($user['password']);
                $this->bags->add($user);
            } else {
                $this->logout();
            }
        }
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->dbGetUserByMail($email);

        if (!empty($user['user_id']) && $this->secure->passwordVerify($password, $user['password'])) {
            unset($user['password']);
            $this->bags->add($user);

            if ($this->secure->isPasswordNeedRehash($this->bags->getString('password'))) {
                $this->dbUpdatePassword($email, $password);
            }
            $this->dbUpdateLastLogin($email);

            $this->session->regenerateId();
            $this->session->set('user_email', $this->bags->get('email'));
            $this->session->set('user_activity', time());
            $this->session->set('access_token', $this->secure->token('hash', rand(24, 32)));

            return true;
        }

        return false;
    }

    public function logout()
    {
        $this->bags->clear();

        $this->session->delete('user_email');
        $this->session->delete('user_activity');
        $this->session->delete('access_token');
        $this->session->regenerateId(true);
    }

    public function setPassword(string $email, string $password)
    {
        $this->dbUpdatePassword($email, $password);
    }

    public function get(string $key = null, $default = null)
    {
        return $this->bags->get($key, $default);
    }

    public function isLogged(): bool
    {
        return $this->bags->getBool('user_id', false);
    }

    public function isSuperAdmin(): bool
    {
        return $this->bags->getInt('user_group_id', -1) === 0;
    }

    public function hasPermission(string $type, string $route): bool
    {
        if (!$valid = $this->isSuperAdmin()) {
            $valid = in_array($route, $this->bags->getArray('permission.' . $type));
        }

        return $valid;
    }

    public function hasPermissions(string $type, array $routes, bool $strict = true): bool
    {
        if (!$valid = $this->isSuperAdmin()) {
            $diffs = array_diff($routes, $this->bags->getArray('permission.' . $type));
            $valid = $strict ? count($diffs) == 0 : count($diffs) != count($routes);
        }

        return $valid;
    }

    protected function dbGetUserByMail(string $email)
    {
        $user = $this->db->get(
            "SELECT u.*, ug.name AS usergroup, ug.backend, ug.permission
            FROM `" . DB_PREFIX . "user` u
                LEFT JOIN `" . DB_PREFIX . "user_group` ug ON (u.user_group_id = ug.user_group_id)
            WHERE u.email = ?s AND u.status = ?i AND ug.status = ?i",
            [$email, 1, 1],
        )->row;

        if ($user) {
            $user['fullname']   = $user['firstname'] . ' ' . $user['lastname'];
            $user['permission'] = json_decode($user['permission'], true);

            $userMeta = [];
            $results  = $this->db->get('SELECT * FROM ' . DB_PREFIX . 'user_meta WHERE user_id = ?i', [$user['user_id']]);

            foreach ($results->rows as $result) {
                $userMeta[$result['key']] = $result['encoded'] ? json_decode($result['value'], true) : $result['value'];
            }

            $user['meta'] = $userMeta;
        }

        return $user;
    }

    protected function dbUpdatePassword(string $email, string $password)
    {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "user` SET `password` = ?s WHERE `email` = ?s",
            [$this->secure->passwordHash($password), $email],
        );
    }

    protected function dbUpdateLastLogin(string $email)
    {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "user` SET `last_login` = NOW() WHERE`email` = ?s",
            [$email],
        );
    }
}
