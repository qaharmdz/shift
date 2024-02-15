<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Core;
use Shift\System\Mvc;

class Install extends Mvc\Controller {
    private $db;

    public function index()
    {
        if (!$this->session->get('install.config.database')) {
            $this->response->redirect($this->router->url(''));
        }

        $config = $this->session->get('install.config');

        try {
            $this->dbConnect();
            $this->dbInstall();
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $this->db->set(
            $config['prefix'] . 'site',
            [
                'name'     => $config['sitename'],
                'url_host' => $config['url_host'],
            ],
            ['site_id' => 0]
        );

        $this->db->add(
            $config['prefix'] . 'user',
            [
                'user_group_id' => 1,
                'email'         => $config['email'],
                'password'      => $this->secure->passwordHash($config['user_password']),
                'username'      => 'admin',
                'firstname'     => 'Super',
                'lastname'      => 'Admin',
                'status'        => 1,
                'created'       => date('Y-m-d H:i:s'),
                'updated'       => date('Y-m-d H:i:s'),
            ]
        );

        $this->response->redirect($this->router->url('page/complete'));
    }

    private function dbConnect()
    {
        $dbConfig = array_merge(
            $this->config->get('root.database.config'),
            [
                'host'     => $this->session->get('install.config.host', 'localhost'),
                'username' => $this->session->get('install.config.username', 'root'),
                'password' => $this->session->get('install.config.password', ''),
                'database' => $this->session->get('install.config.database', ''),
                'port'     => $this->session->getInt('install.config.port', 3306),
            ]
        );

        $db = new Core\Database(...$dbConfig);
        $db->raw('
            SET time_zone="+00:00",
                session group_concat_max_len = ' . $this->config->getInt('root.database.group_concat_max_len') . ',
                SESSION sql_mode="' . implode(',', $this->config->getArray('root.database.modes')) . '";
        ');

        $this->db = $db;
    }

    // TODO: Update the schema.sql with actual initial installation
    private function dbInstall()
    {
        $sql = '';
        $queries = file(PATH_APP . 'schema.sql', FILE_IGNORE_NEW_LINES);

        if (!$queries) {
            throw new \Exception('Unable to open the installation database schema!');
        }

        foreach ($queries as $query) {
            if ($query === '') {
                $sql .= "\n";
                continue;
            }

            if (str_starts_with($query, '-- ') || str_starts_with($query, '/*!')) {
                continue;
            }

            $sql .= $query . "\n";
        }

        $sql = str_replace('{DB_PREFIX}', $this->session->get('install.config.prefix', 'xyz_'), $sql);

        $this->db->multiQuery($sql);
    }
}
