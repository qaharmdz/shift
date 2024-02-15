<?php

declare(strict_types=1);

namespace Shift\Site\Model\Extension;

use Shift\System\Mvc;

class Module extends Mvc\Model {
    public function getModule(int $module_id): array
    {
        $result = $this->db->get(
            "SELECT *
            FROM `" . DB_PREFIX . "extension_module` em
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (em.extension_id = e.extension_id)
            WHERE  e.`type` = 'module' AND em.`extension_module_id` = ?i",
            [$module_id]
        )->row;

        if (!empty($result['user_id'])) {
            $result['setting'] = json_decode($result['setting'], true);
        }

        return $result;
    }
}
