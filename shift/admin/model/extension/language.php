<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Language extends Mvc\Model {
    /**
     * DataTables records
     *
     * @param  array  $params
     */
    public function dtRecords(array $params): \stdClass
    {
        $columnMap = [
            'extension_id' => 'e.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'e.name',
            'status'       => 'e.status',
        ];
        $filterMap = $columnMap;
        $dtResult = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "extension` e"
            . " WHERE e.`type` = 'language' AND e.`install` = 1"
            . ($dtResult['query']['where'] ? " AND " . $dtResult['query']['where'] : "")
            . " ORDER BY " . $dtResult['query']['order']
            . " LIMIT " . $dtResult['query']['limit'];

        return $this->db->get($query, $dtResult['query']['params']);
    }

    public function dtAction(string $type, array $items): array
    {
        $_items = [];

        if (in_array($type, ['enabled', 'disabled'])) {
            $status = $type == 'enabled' ? 1 : 0;

            $this->db->set(
                DB_PREFIX . 'extension',
                [
                    'status'  => $status,
                    'updated' => date('Y-m-d H:i:s'),
                ],
                ['extension_id' => $items]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
            }
        }

        return $_items;
    }

    public function getTotal(): int
    {
        return (int) $this->db->get(
            "SELECT COUNT(*) AS total
            FROM `" . DB_PREFIX . "extension`
            WHERE `type` = 'language' AND `status` = 1 AND `install` = 1"
        )->row['total'];
    }

    // Form CRUD
    // ================================================

    /**
     * Get languages
     *
     * @param  array  $filters
     * @param  string $rkey    Return key
     * @return array
     */
    public function getLanguages(array $filters = ['status = ?i' => 1], string $rkey = 'extension_id'): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data = $this->cache->get('languages.' . $argsHash, []);

        if (!$data) {
            $filters = array_merge([
                'install = ?i' => 1,
            ], $filters);

            $languages = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "extension`
                WHERE `type` = 'language' AND " . implode(' AND ', array_keys($filters)) . "
                ORDER BY `name` ASC",
                array_values($filters)
            )->rows;

            foreach ($languages as $result) {
                $data[$result[$rkey]] = $result;
                $data[$result[$rkey]]['setting'] = json_decode($result['setting'], true);
            }

            $this->cache->set('languages.' . $argsHash, $data, tags: ['extensions', 'languages']);
        }

        return $data;
    }
}
