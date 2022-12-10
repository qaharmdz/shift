<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;

class Extension extends Mvc\Model
{
    public function getInstalled($type)
    {
        $extension_data = array();

        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY codename");

        foreach ($query->rows as $result) {
            $extension_data[] = $result['codename'];
        }

        return $extension_data;
    }

    public function install($type, $codename)
    {
        $extensions = $this->getInstalled($type);

        if (!in_array($codename, $extensions)) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = '" . $this->db->escape($type) . "', `codename` = '" . $this->db->escape($codename) . "'");
        }
    }

    public function uninstall($type, $codename)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = ?s AND `codename` = ?s", [$type, $codename]);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = ?s AND `codename` = ?s", [$type, $codename]);
    }
}
