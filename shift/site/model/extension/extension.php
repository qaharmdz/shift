<?php

declare(strict_types=1);

namespace Shift\Site\Model\Extension;

use Shift\System\Mvc;

class Extension extends Mvc\Model {
    function getExtensions($type)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

        return $query->rows;
    }
}
