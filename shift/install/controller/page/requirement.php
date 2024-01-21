<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Mvc;

class Requirement extends Mvc\Controller
{
    public function index()
    {
        $this->document->setTitle($this->language->get('requirements'));

        $data = [];
        $data['php'] = [
            'version' => $vPHP = phpversion(),
            'valid' => version_compare($vPHP, '8.2.0', '>='),
        ];
        $data['mysql'] = [
            'version' => $vMySQL = $this->getMySQLVersion(),
            'valid' => version_compare($vMySQL, '8.0.20', '>='),
        ];
        $data['php_exts'] = [
            'MySQLi'   => extension_loaded('mysqli'),
            'OpenSSL'  => extension_loaded('openssl'),
            'MBString' => extension_loaded('mbstring'),
            'JSON'     => extension_loaded('json'),
            'GD'       => extension_loaded('gd'),
        ];
        $data['validRequirement'] = ($data['php']['valid'] && $data['mysql']['valid'] && !in_array(false, $data['php_exts']));

        $this->response->setOutput($this->load->view('page/requirement', $data));
    }

    private function getMySQLVersion()
    {
        $v = '0';

        if (function_exists('shell_exec')) {
            try {
                $output = shell_exec('mysql -V');
                preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
                $v = $version[0];
            } catch (\Error $e) {
                //...
            }
        }

        return $v;
    }
}
