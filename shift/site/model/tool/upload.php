<?php

declare(strict_types=1);

namespace Shift\Site\Model\Tool;

use Shift\System\Mvc;

class Upload extends Mvc\Model
{
    public function addUpload($name, $filename)
    {
        $code = sha1(uniqid(mt_rand(), true));

        $this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

        return $code;
    }

    public function getUploadByCode($code)
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }
}
