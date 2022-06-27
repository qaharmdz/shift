<?php

declare(strict_types=1);

namespace Shift\Site\Model\Localisation;

use Shift\System\Core\Mvc;

class Language extends Mvc\Model
{
    public function getLanguage($language_id)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

        return $query->row;
    }

    public function getLanguages()
    {
        $language_data = $this->cache->get('language');

        if (!$language_data) {
            $language_data = array();

            $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1' ORDER BY sort_order, name");

            foreach ($query->rows as $result) {
                $language_data[$result['code']] = array(
                    'language_id' => $result['language_id'],
                    'name'        => $result['name'],
                    'code'        => $result['code'],
                    'locale'      => $result['locale'],
                    'image'       => $result['image'],
                    'directory'   => $result['directory'],
                    'sort_order'  => $result['sort_order'],
                    'status'      => $result['status']
                );
            }

            $this->cache->set('language', $language_data);
        }

        return $language_data;
    }
}
