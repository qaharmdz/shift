<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Core\Mvc;

class Startup extends Mvc\Controller
{
    public function index()
    {
        //=== Settings
        $results = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "setting` WHERE site_id = '0' AND `group` = ? ORDER BY `site_id` ASC, `group` ASC, `code` ASC, `key` ASC",
            ['system']
        );

        $settings = [];
        foreach ($results->rows as $row) {
            $value = $row['encoded'] ? json_decode($row['value'], true) : $row['value'];

            if ($row['code']) {
                $settings[$row['group']][$row['code']][$row['key']] = $value;
            } else {
                $settings[$row['group']][$row['key']] = $value;
            }
        }

        $this->config->set($settings);
        $this->config->set('env.limit', $this->config->getInt('system.setting.admin_limit', 25));

        // Apply DB setting
        $this->log->setConfig([
            'display' => $this->config->getBool('system.setting.error_display', false)
        ]);

        //=== Language
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('system.setting.admin_language')) . "'");

        if ($query->num_rows) {
            $this->config->set('env.language_id', (int)$query->row['language_id']);
            $this->config->set('env.language_code', $query->row['code']);
        }

        //=== Language
        $language = new \Language($this->config->get('env.language_code'));
        $language->load($this->config->get('env.language_code'));
        $this->registry->set('language', $language);
    }
}
