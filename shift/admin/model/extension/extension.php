<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;

class Extension extends Mvc\Model
{
    public function getInstalled($type)
    {
        $extension_data = array();

        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

        foreach ($query->rows as $result) {
            $extension_data[] = $result['code'];
        }

        return $extension_data;
    }

    public function install($type, $code)
    {
        $extensions = $this->getInstalled($type);

        if (!in_array($code, $extensions)) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
        }
    }

    public function uninstall($type, $code)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = ?s AND `code` = ?s", [$type, $code]);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = ?s AND `code` = ?s", [$type, $code]);
    }
}
