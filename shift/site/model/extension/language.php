<?php

declare(strict_types=1);

namespace Shift\Site\Model\Extension;

use Shift\System\Mvc;

class Language extends Mvc\Model
{
    public function getLanguage(int $extension_id): array
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
        $data     = $this->cache->get('languages.' . $argsHash, []);

        if (!$data) {
            $filters = array_merge([
                'type = ?s' => 'language',
                'install = ?i' => 1,
            ], $filters);

            $languages  = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "extension`
                WHERE " . implode(' AND ', array_keys($filters)) . "
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

    public function getTotalLanguages(): int
    {
        return (int)$this->db->get(
            "SELECT COUNT(*) AS total
            FROM `" . DB_PREFIX . "extension`
            WHERE `type` = 'language'
                AND `install` = 1
                AND `status` = 1"
        )->row['total'];
    }
}
