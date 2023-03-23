<?php

declare(strict_types=1);

namespace Shift\Site\Model\Setting;

use Shift\System\Mvc;

class Site extends Mvc\Model
{
    /**
     * Get sites
     *
     * @param  array  $filters
     * @param  string $rkey    Return key
     * @return array
     */
    public function getSites(array $filters = ['2 = ?i' => 2], string $rkey = 'site_id'): array
    {
        $argsHash = $this->cache->getHash(func_get_args());
        $data     = $this->cache->get('sites.' . $argsHash, []);

        if (!$data) {
            $sites = $this->db->get(
                "SELECT s.* FROM `" . DB_PREFIX . "site` s
                WHERE " . implode(' AND ', array_keys($filters)) . "
                ORDER BY s.site_id ASC",
                array_values($filters)
            )->rows;

            foreach ($sites as &$result) {
                $data[$result[$rkey]] = $result;
            }

            $this->cache->set('sites.' . $argsHash, $data, tags: ['sites']);
        }

        return $data;
    }
}
