<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Core;
use Shift\System\Mvc;

class Install extends Mvc\Controller
{
    private $db;

    public function index()
    {
        if (!$this->session->get('install.config.database')) {
            $this->response->redirect($this->router->url(''));
        }

        $this->document->setTitle($this->language->get('installation'));

        try {
            $this->dbConnect();
            $this->dbInstall();
        } catch (\Exception $e) {
            d($e);
        }

        $data = [];

        // d($data);
        // d($this->router->url(''));
        d($this->config->all());
        d($this->session->all());

        $this->response->setOutput($this->load->view('page/install', $data));
    }

    private function dbConnect()
    {
        $dbConfig  = array_merge(
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

        $sql = str_replace('{DB_PREFIX}', $this->session->get('install.config.prefix', 'sf_'), $sql);

        $this->db->multiQuery($sql);
    }
}
