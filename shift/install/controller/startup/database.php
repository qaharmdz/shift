<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Startup;

use Shift\System\Mvc;

class Database extends Mvc\Controller
{
    public function index()
    {
        if (is_file(PATH_SHIFT . 'config.php') && filesize(PATH_SHIFT . 'config.php') > 0) {
            $lines = file(PATH_SHIFT . 'config.php');

            foreach ($lines as $line) {
                if (strpos(strtoupper($line), 'DB_') !== false) {
                    eval($line);
                }
            }

            if (defined('DB_PORT')) {
                $port = DB_PORT;
            } else {
                $port = ini_get('mysqli.default_port');
            }

            $this->registry->set('db', new DB(
                DB_DRIVER,
                DB_HOSTNAME,
                DB_USERNAME,
                DB_PASSWORD,
                DB_DATABASE,
                (int)$port
            ));
        }
    }
}
