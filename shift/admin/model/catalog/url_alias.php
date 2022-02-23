<?php

declare(strict_types=1);

class ModelCatalogUrlAlias extends Model
{
    public function getUrlAlias($keyword)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");

        return $query->row;
    }
}
