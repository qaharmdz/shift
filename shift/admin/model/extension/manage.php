<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Extension;

use Shift\System\Mvc;
use Shift\System\Helper;

class Manage extends Mvc\Model {
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
            'extension_id' => 'e.extension_id',
            'codename'     => 'e.codename',
            'name'         => 'e.name',
            'version'      => 'e.version',
            'author'       => 'e.author',
            'url'          => 'e.url',
            'type'         => 'e.type',
            'status'       => 'e.status',
            'install'      => 'e.install',
        ];
        $filterMap = $columnMap;
        $dtResult = Helper\DataTables::parse($params, $filterMap);

        $query = "SELECT " . implode(', ', $columnMap)
            . " FROM `" . DB_PREFIX . "extension` e"
            . ($dtResult['query']['where'] ? " WHERE " . $dtResult['query']['where'] : "")
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
                $_items = $items;
            }
        }

        if ($type == 'install') {
            foreach ($items as $extension_id) {
                $this->install((int) $extension_id);
            }
        }

        if ($type == 'uninstall') {
            foreach ($items as $extension_id) {
                $this->uninstall((int) $extension_id);
            }
        }

        if ($type == 'delete') {
            foreach ($items as $extension_id) {
                $this->delete((int) $extension_id);
            }
        }

        $this->cache->deleteByTags('extensions');

        return $_items;
    }

    public function getTotal(): int
    {
        return (int) $this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "extension`")->row['total'];
    }

    // Manage
    // ================================================

    public function editSetting(int $extension_id, array $data)
    {
        $this->db->set(
            DB_PREFIX . 'extension',
            [
                'setting' => json_encode($data['setting']),
                'status'  => (int) $data['status'],
                'updated' => date('Y-m-d H:i:s'),
            ],
            [
                'extension_id' => $extension_id,
            ]
        );
    }

    public function getExtension(string $type, string $codename): array
    {
        $result = $this->db->get("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = ?s AND `codename` = ?s", [$type, $codename])->row;

        if (!empty($result['setting'])) {
            $result['setting'] = json_decode($result['setting'], true);
        }

        return $result;
    }

    public function getById(int $extension_id): array
    {
        $language = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "extension` WHERE extension_id = ?i",
            [$extension_id]
        )->row;

        if ($language) {
            $language['setting'] = json_decode($language['setting'], true);
        }

        return $language;
    }

    public function getExtensions(array $filters = ['1 = ?i' => 1], string $rkey = 'extension_id'): array
    {
        $data = [];

        $results = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "extension`
            WHERE " . implode(' AND ', array_keys($filters)) . "
            ORDER BY `codename` ASC",
            array_values($filters)
        )->rows;

        foreach ($results as $result) {
            $data[$result[$rkey]] = $result;
            $data[$result[$rkey]]['setting'] = json_decode($result['setting'], true);
        }

        return $data;
    }

    public function install(int $extension_id): void
    {
        $extension = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "extension` WHERE `extension_id` = ?i AND `install` = ?i",
            [$extension_id, 0]
        )->row;

        if ($extension) {
            $extPath = 'extensions/' . $extension['type'] . '/' . $extension['codename'];
            $metaInfo = json_decode(
                file_get_contents(PATH_EXTENSIONS . $extension['type'] . DS . $extension['codename'] . DS . 'meta.json'),
                true
            );

            if ($metaInfo) {
                $this->load->model('account/usergroup');
                $this->model_account_usergroup->addPermission($this->user->get('user_group_id'), 'access', $extPath);
                $this->model_account_usergroup->addPermission($this->user->get('user_group_id'), 'modify', $extPath);

                $this->load->controller($extPath . '/install');

                $this->db->set(
                    DB_PREFIX . 'extension',
                    [
                        'name'        => $metaInfo['name'],
                        'version'     => $metaInfo['version'],
                        'description' => $metaInfo['description'],
                        'author'      => $metaInfo['author'],
                        'url'         => $metaInfo['url'],
                        'setting'     => '[]',
                        'status'      => 1,
                        'install'     => 1,
                        'updated'     => date('Y-m-d H:i:s'),
                    ],
                    ['extension_id' => $extension['extension_id']]
                );

                $this->cache->deleteByTags('extensions');
            }
        }
    }

    public function uninstall(int $extension_id)
    {
        $extension = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "extension` WHERE `extension_id` = ?i AND `install` = ?i",
            [$extension_id, 1]
        )->row;

        if ($extension) {
            $extPath = 'extensions/' . $extension['type'] . '/' . $extension['codename'];

            $this->load->model('account/usergroup');
            $this->model_account_usergroup->removePermission('access', $extPath);
            $this->model_account_usergroup->removePermission('modify', $extPath);

            $this->load->controller($extPath . '/uninstall');

            $this->db->set(
                DB_PREFIX . 'extension',
                [
                    'setting' => '[]',
                    'status'  => 0,
                    'install' => 0,
                    'updated' => date('Y-m-d H:i:s'),
                ],
                ['extension_id' => $extension['extension_id']]
            );

            $this->db->delete(DB_PREFIX . 'extension_meta', ['extension_id' => $extension['extension_id']]);
            $this->db->delete(DB_PREFIX . 'extension_module', ['extension_id' => $extension['extension_id']]);
            $this->db->delete(DB_PREFIX . 'setting', ['group' => $extension['type'], 'code' => $extension['codename']]);
            $this->cache->deleteByTags('extensions');
        }
    }

    public function delete(int $extension_id)
    {
        $extension = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "extension` WHERE `extension_id` = ?i AND `install` = ?i",
            [$extension_id, 0]
        )->row;

        $path = PATH_EXTENSIONS . $extension['type'] . DS . $extension['codename'] . DS;

        if (is_dir($path)) {
            $dirIterator = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
            $nodes = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($nodes as $node) {
                $node->isDir() ? rmdir($node->getRealPath()) : unlink($node->getRealPath());
            }

            rmdir($path);
        }

        $this->db->delete(DB_PREFIX . 'extension', ['type' => $extension['type'], 'codename' => $extension['codename']]);
        $this->cache->deleteByTags('extensions');
    }
}
