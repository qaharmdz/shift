<?php

declare(strict_types=1);

namespace Shift\Install\Model\Install;

use Shift\System\Mvc;

class Install extends Mvc\Model
{
    public function database($data)
    {
        $db = new DB(
            $data['db_driver'],
            htmlspecialchars_decode($data['db_hostname']),
            htmlspecialchars_decode($data['db_username']),
            htmlspecialchars_decode($data['db_password']),
            htmlspecialchars_decode($data['db_database']),
            (int)$data['db_port']
        );

        $file = DIR_APPLICATION . 'schema.sql';

        if (!file_exists($file)) {
            exit('Could not load sql file: ' . $file);
        }

        $lines = file($file);

        if ($lines) {
            $sql = '';

            foreach ($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql .= $line;

                    if (preg_match('/;\s*$/', $line)) {
                        $sql = str_replace('{{ DB_PREFIX }}', $data['db_prefix'], $sql);

                        // var_dump($sql);
                        $db->query($sql);

                        $sql = '';
                    }
                }
            }

            $db->query("SET CHARACTER SET utf8mb4");

            $db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");
            $db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', password = '" . $this->db->escape($this->secure->passwordHash($data['password'])) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($data['email']) . "', status = '1', date_added = NOW()");

            $db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
            $db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");
        }
    }
}
