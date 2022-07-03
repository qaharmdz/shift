<?php

declare(strict_types=1);

namespace Shift\Site\Model\Design;

use Shift\System\Core\Mvc;

class Banner extends Mvc\Model
{
    public function getBanner($banner_id)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "banner b LEFT JOIN " . DB_PREFIX . "banner_image bi ON (b.banner_id = bi.banner_id) WHERE b.banner_id = '" . (int)$banner_id . "' AND b.status = '1' AND bi.language_id = '" . (int)$this->config->get('env.language_id') . "' ORDER BY bi.sort_order ASC");
        return $query->rows;
    }
}
