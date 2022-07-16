<?php

declare(strict_types=1);

namespace Shift\Site\Model\Setting;

use Shift\System\Core\Mvc;

class Site extends Mvc\Model
{
    public function getSites($data = array())
    {
        $site_data = $this->cache->get('site');

        if (!$site_data) {
            $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "site ORDER BY url_host");

            $site_data = $query->rows;

            $this->cache->set('site', $site_data);
        }

        return $site_data;
    }
}
