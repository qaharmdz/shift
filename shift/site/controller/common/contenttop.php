<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Core\Mvc;

class ContentTop extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('design/layout');

        $layout_id = 0;
        $route     = $this->request->getString('query.route', 'common/home');

        if ($route == 'information/information' && $this->request->has('query.information_id')) {
            $this->load->model('catalog/information');
            $layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get('query.information_id'));
        }

        if (!$layout_id) {
            $layout_id = $this->model_design_layout->getLayout($route);
        }

        if (!$layout_id) {
            $layout_id = $this->config->get('config_layout_id');
        }

        $this->load->model('extension/module');

        $data['modules'] = array();

        $modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top');

        foreach ($modules as $module) {
            $part = explode('.', $module['code']);

            if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
                $module_data = $this->load->controller('extension/module/' . $part[0]);

                if ($module_data) {
                    $data['modules'][] = $module_data;
                }
            }

            if (isset($part[1])) {
                $setting_info = $this->model_extension_module->getModule($part[1]);

                if ($setting_info && $setting_info['status']) {
                    $output = $this->load->controller('extension/module/' . $part[0], $setting_info);

                    if ($output) {
                        $data['modules'][] = $output;
                    }
                }
            }
        }

        return $this->load->view('common/content_top', $data);
    }
}
