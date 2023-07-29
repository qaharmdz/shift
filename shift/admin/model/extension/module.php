<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Module extends Mvc\Model
{
    // List
    // ================================================

    /**
     * DataTables records
     *
     * @param  array  $params
     */
    public function dtRecords(array $params)
    {
        $columnMap = [
            'module_id'    => 'em.extension_module_id AS module_id',
            'extension_id' => 'em.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'em.name',
            'status'       => 'em.status',
        ];
        $filterMap = $columnMap;
        $filterMap['module_id'] = 'em.extension_module_id';

        $dtResult  = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "extension_module` em
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (em.extension_id = e.extension_id)"
            . " WHERE e.`type` = 'module' AND e.`status` = 1 AND e.`install` = 1"
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
                DB_PREFIX . 'extension_module',
                [
                    'status'  => $status,
                    'updated' => date('Y-m-d H:i:s'),
                ],
                ['extension_module_id' => $items]
            );

            if ($this->db->affectedRows()) {
                $_items[] = $items;
            }
        }

        if ($type == 'delete') {
            $this->deleteModules($items);
        }

        return $_items;
    }

    public function getTotal(): int
    {
        return (int)$this->db->get(
            "SELECT COUNT(*) AS total
            FROM `" . DB_PREFIX . "extension_module` em
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (em.extension_id = e.extension_id)
            WHERE e.`type` = 'module' AND e.`status` = 1 AND e.`install` = 1"
        )->row['total'];
    }

    // Form CRUD
    // ================================================

    public function addModule(array $data)
    {
        $this->db->add(
            DB_PREFIX . 'extension_module',
            [
                'extension_id' => (int)$data['extension_id'],
                'name'         => $data['name'],
                'setting'      => json_encode($data['setting'] ?? []),
                'status'       => (int)$data['status'],
                'created'      => date('Y-m-d H:i:s'),
                'updated'      => date('Y-m-d H:i:s'),
            ]
        );

        $module_id = (int)$this->db->insertId();

        if (!empty($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                $this->db->add(
                    DB_PREFIX . 'extension_meta',
                    [
                        'extension_id' => (int)$data['extension_id'],
                        'extension_module_id' => $module_id,
                        'key'     => $key,
                        'value'   => (is_array($value) ? json_encode($value) : $value),
                        'encoded' => (is_array($value) ? 1 : 0),
                    ]
                );
            }
        }

        return $module_id;
    }

    public function editModule(int $module_id, array $data)
    {
        $updated = $this->db->set(
            DB_PREFIX . 'extension_module',
            [
                'extension_id' => (int)$data['extension_id'],
                'name'         => $data['name'],
                'setting'      => json_encode($data['setting'] ?? []),
                'status'       => (int)$data['status'],
                'updated'      => date('Y-m-d H:i:s'),
            ],
            ['extension_module_id' => $module_id]
        );

        if ($updated->affected_rows != -1) {
            $this->db->delete(DB_PREFIX . 'extension_meta', [
                'extension_id' => (int)$data['extension_id'],
                'extension_module_id' => $module_id,
            ]);

            if (!empty($data['meta'])) {
                foreach ($data['meta'] as $key => $value) {
                    $this->db->add(
                        DB_PREFIX . 'extension_meta',
                        [
                            'extension_id' => (int)$data['extension_id'],
                            'extension_module_id' => $module_id,
                            'key'     => $key,
                            'value'   => (is_array($value) ? json_encode($value) : $value),
                            'encoded' => (is_array($value) ? 1 : 0),
                        ]
                    );
                }
            }
        }

        return $updated->affected_rows;
    }

    public function getModule(int $module_id): array
    {
        $result = $this->db->get(
            "SELECT em.*, e.codename
            FROM `" . DB_PREFIX . "extension_module` em
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (em.extension_id = e.extension_id)
            WHERE  e.`type` = 'module' AND em.`extension_module_id` = ?i",
            [$module_id]
        )->row;

        if ($result) {
            $result['setting'] = json_decode($result['setting'], true);
        }

        return $result;
    }

    public function deleteModules(array $modules): void
    {
        $this->db->delete(DB_PREFIX . 'extension_module', ['extension_module_id' => $modules]);

        $this->cache->deleteByTags('modules');
    }

    public function getExtModules()
    {
        return $this->db->get(
            "SELECT *
            FROM `" . DB_PREFIX . "extension` e
            WHERE e.`type` = 'module' AND e.`status` = 1 AND e.`install` = 1
            ORDER BY e.`name` ASC"
        )->rows;
    }

    /**
     * Get modules
     *
     * @param  array  $filters
     * @param  string $rkey    Return key
     * @return array
     */
    public function getModules(array $filters = ['em.status = ?i' => 1], string $rkey = 'extension_module_id'): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('modules.' . $argsHash, []);

        if (!$data) {
            $modules = $this->db->get(
                "SELECT em.*, e.`codename`
                FROM `" . DB_PREFIX . "extension_module` em
                    LEFT JOIN `" . DB_PREFIX . "extension` e ON (em.extension_id = e.extension_id)
                WHERE " . implode(' AND ', array_keys($filters)) . "
                    AND e.`status` = 1 AND e.`install` = 1
                ORDER BY em.extension_id ASC, em.name ASC",
                array_values($filters)
            )->rows;

            foreach ($modules as $result) {
                $data[$result[$rkey]] = $result;
            }

            $this->cache->set('modules.' . $argsHash, $data, tags: ['modules']);
        }

        return $data;
    }
}
