<?php

declare(strict_types=1);

namespace Shift\Site\Model\Extension;

use Shift\System\Mvc;

class Module extends Mvc\Model
{
    public function getModule($module_id)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");

        if ($query->row) {
            return json_decode($query->row['setting'], true);
        } else {
            return array();
        }
    }
}
