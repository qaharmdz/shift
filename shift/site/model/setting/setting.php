<?php

declare(strict_types=1);

namespace Shift\Site\Model\Setting;

use Shift\System\Core\Mvc;

class Setting extends Mvc\Model
{
    public function getSetting($code, $store_id = 0)
    {
        $data = array();

        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

        foreach ($query->rows as $result) {
            if (!$result['encoded']) {
                $data[$result['key']] = $result['value'];
            } else {
                $data[$result['key']] = json_decode($result['value'], true);
            }
        }

        return $data;
    }
}
