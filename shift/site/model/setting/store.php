<?php

declare(strict_types=1);

namespace Shift\Site\Model\Setting;

use Shift\System\Core\Mvc;

class Store extends Mvc\Model
{
    public function getStores($data = array())
    {
        $store_data = $this->cache->get('store');

        if (!$store_data) {
            $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

            $store_data = $query->rows;

            $this->cache->set('store', $store_data);
        }

        return $store_data;
    }
}
