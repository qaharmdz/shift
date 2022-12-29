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

    public function getLanguages(string $key = 'language_id'): array
    {
        $data = $this->cache->get('languages');

        if (!$data) {
            $data = [];

            $languages = $this->db->get("SELECT * FROM `" . DB_PREFIX . "language` ORDER BY sort_order ASC, name ASC")->rows;

            foreach ($languages as $result) {
                $data[$result[$key]] = array(
                    'language_id' => $result['language_id'],
                    'name'        => $result['name'],
                    'code'        => $result['code'],
                    'locale'      => $result['locale'],
                    'flag'        => $result['flag'],
                    'sort_order'  => $result['sort_order'],
                    'status'      => $result['status']
                );
            }

            $this->cache->set('languages', $data);
        }

        return $data;
    }

    public function getTotalLanguages(): int
    {
        return (int)$this->db->get("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "language`")->row['total'];
    }
}
