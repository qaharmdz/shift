<?php

declare(strict_types=1);

namespace Shift\Site\Model\Design;

use Shift\System\Mvc;

class Layout extends Mvc\Model
{
    public function getLayout($route)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db->escape($route) . "' LIKE route AND site_id = '" . (int)$this->config->get('env.site_id') . "' ORDER BY route DESC LIMIT 1");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getLayoutModules($layout_id, $position)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "' AND position = '" . $this->db->escape($position) . "' ORDER BY sort_order");

        return $query->rows;
    }
}
