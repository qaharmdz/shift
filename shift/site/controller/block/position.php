<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Block;

use Shift\System\Mvc;

class Position extends Mvc\Controller
{
    /**
     * Get all layout modules
     *
     * @return array
     */
    public function index(): array
    {
        $data = [];
        $positions = $this->getLayoutPositions();
        $layout_id = $this->getLayoutId($this->request->get('query.route'));

        foreach ($positions as $position) {
            $modules = $this->getLayoutModules($layout_id, $position);
            $data[$position] = '';

            if ($modules) {
                foreach ($modules as $module) {
                    $data[$position] .= '<div class="module-wrapper module-' . $module['codename'] . ' module-id-' . $module['extension_module_id'] . '">';
                    $data[$position] .= $this->load->controller('extensions/module/' . $module['codename'], $module);
                    $data[$position] .= '</div>';
                }
            }
        }

        return $data;
    }

    public function getLayoutPositions(): array
    {
        return ['alpha', 'topbar', 'top', 'content_top', 'sidebar_left', 'sidebar_right', 'content_bottom', 'bottom', 'footer', 'omega'];
    }

    public function getLayoutId(string $route): int
    {
        // parse_str($allURLQuery, $urlParams)

        // TODO: match route by positions, header might be route all, while the rest match route pattern

        return (int)$this->db->get(
            "SELECT layout_id FROM `" . DB_PREFIX . "layout_route` WHERE ?s LIKE `route` ORDER BY `priority` DESC LIMIT 1",
            [$route],
        )->row['layout_id'] ?? 0;


        /*
        SELECT *
        FROM `sf_layout_route`
        WHERE
            'account/logout' LIKE REPLACE(`route`, '*', '%')
            AND NOT EXISTS (
                SELECT layout_route_id
                FROM `sf_layout_route`
                WHERE 'account/logout' LIKE REPLACE(`route`, '*', '%')
                AND `exclude` = 1
            )
         */
    }

    public function getLayoutModules(int $layout_id, string $position)
    {
        return $this->db->get(
            "SELECT e.codename, em.* FROM `" . DB_PREFIX . "layout_module` lm
                LEFT JOIN `" . DB_PREFIX . "extension_module` em ON (em.extension_module_id = lm.extension_module_id)
                LEFT JOIN `" . DB_PREFIX . "extension` e ON (e.extension_id = em.extension_id)
            WHERE lm.layout_id = ?i
                AND lm.position = ?s
                AND e.install = 1
                AND e.status = 1
                AND em.status = 1
            ORDER BY sort_order ASC",
            [$layout_id, $position],
        )->rows;
    }
}
