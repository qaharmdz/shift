<?php

declare(strict_types=1);

namespace Shift\Site\Model\Extension;

use Shift\System\Mvc;

class Language extends Mvc\Model
{
    public function getLanguage(int $language_id): array
    {
        return $this->db->get("SELECT * FROM `" . DB_PREFIX . "language` WHERE language_id = ?i", [$language_id])->row;
    }

    /**
     * Get languages
     *
     * @param  array  $filters
     * @param  string $rkey    Return key
     * @return array
     */
    public function getLanguages(array $filters = ['l.status = ?i' => 1], string $rkey = 'language_id'): array
    {
        $data = $this->cache->get('languages.' . $this->cache->getHash(func_get_args()));

        if (!$data) {
            $data = [];

            $languages = $this->db->get(
                "SELECT l.* FROM `" . DB_PREFIX . "language` l
                WHERE " . implode(' AND ', array_keys($filters)) . "
                ORDER BY l.sort_order ASC, l.name ASC",
                array_values($filters)
            )->rows;

            foreach ($languages as $result) {
                $data[$result[$rkey]] = array(
                    'language_id' => $result['language_id'],
                    'name'        => $result['name'],
                    'code'        => $result['code'],
                    'locale'      => $result['locale'],
                    'flag'        => $result['flag'],
                    'sort_order'  => $result['sort_order'],
                    'status'      => $result['status']
                );
            }

            $this->cache->set('languages.' . $this->cache->getHash(func_get_args()), $data, tags: ['languages']);
        }

        return $data;
    }

    public function getTotalLanguages(): int
    {
        return (int)$this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "language`")->row['total'];
    }
}
