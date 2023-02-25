<?php

declare(strict_types=1);

namespace Shift\Site\Model\Setting;

use Shift\System\Mvc;

class Setting extends Mvc\Model
{
    public function getSetting(string $group, string $code = null, int $site_id = 0)
    {
        $data = [];

        $sqlQuery = "SELECT * FROM `" . DB_PREFIX . "setting` WHERE site_id = ?i AND `group` = ?s";
        $sqlParam = [$site_id, $group];

        if ($code !== null) {
            $sqlQuery .= " AND `code`= ?s";
            $sqlParam[] = $code;
        }

        $results = $this->db->get($sqlQuery, $sqlParam);

        foreach ($results->rows as $result) {
            $value = $result['encoded'] ? json_decode($result['value'], true) : $result['value'];

            if ($code === null && $result['code']) {
                $data[$result['code']][$result['key']] = $value;
            } else {
                $data[$result['key']] = $value;
            }
        }

        return $data;
    }

    public function getSettingValue(string $group, string $code, string $key, int $site_id = 0)
    {
        $result = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "setting` WHERE site_id = ?i AND `group` = ?s AND `code` = ?s AND `key` = ?s",
            [$site_id, $group, $code, $key]
        )->row;

        return $result['encoded'] ? json_decode($result['value'], true) : $result['value'];
    }
}
