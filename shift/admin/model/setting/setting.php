<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Setting;

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

    public function editSetting(string $group, string $code, array $data, int $site_id = 0)
    {
        $this->deleteSetting($group, $code, $site_id);

        $params = [];
        foreach ($data as $key => $value) {
            $params[] = [$site_id, $group, $code, $key, (is_array($value) ? json_encode($value) : $value), (is_array($value) ? 1 : 0)];
        }

        $this->db->transaction(
            "INSERT INTO `" . DB_PREFIX . "setting` (`site_id`, `group`, `code`, `key`, `value`, `encoded`) VALUES (?i, ?s, ?s, ?s, ?s, ?i)",
            $params
        );
    }

    public function deleteSetting(string $group, string $code = null, int $site_id = 0)
    {
        $sqlQuery = "DELETE FROM `" . DB_PREFIX . "setting` WHERE site_id = ?s AND `group` = ?s";
        $sqlParam = [$site_id, $group];

        if ($code !== null) {
            $sqlQuery .= " AND `code`= ?";
            $sqlParam[] = $code;
        }

        return $this->db->query($sqlQuery, $sqlParam);
    }

    public function getSettingValue(string $group, string $code, string $key, int $site_id = 0)
    {
        $result = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "setting` WHERE site_id = ?i AND `group` = ?s AND `code` = ?s AND `key` = ?s",
            [$site_id, $group, $code, $key]
        )->row;

        if ($result) {
            return $result['encoded'] ? json_decode($result['value'], true) : $result['value'];
        }

        return null;
    }

    public function editSettingValue(string $group, string $code, string $key, string|array $value = '', $site_id = 0)
    {
        $this->db->query(
            "UPDATE `" . DB_PREFIX . "setting` SET `value` = ?s, encoded = ?i WHERE site_id = ?i AND `group` = ?s AND `code` = ?s AND `key` = ?s",
            [(is_array($value) ? json_encode($value) : $value), (is_array($value) ? 1 : 0), $site_id, $group, $code, $key]
        );
    }
}
