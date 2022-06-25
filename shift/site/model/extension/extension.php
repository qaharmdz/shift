<?php

declare(strict_types=1);

class ModelExtensionExtension extends Model
{
    function getExtensions($type)
    {
        $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");

        return $query->rows;
    }
}
