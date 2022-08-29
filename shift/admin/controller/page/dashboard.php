<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Page;

use Shift\System\Mvc;

class Dashboard extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('page/dashboard');

        $this->document->setTitle($this->language->get('page_title'));

        // Check install directory exists
        $data['error_install'] = '';
        if (is_dir(dirname(PATH_APP) . '/install')) {
            $data['error_install'] = $this->language->get('error_install');
        }

        // Dashboard Extensions
        $this->load->model('extension/extension');

        $extensions = $this->model_extension_extension->getInstalled('dashboard');
        $dashboards = [];

        foreach ($extensions as $code) {
            if ($this->config->get('dashboard_' . $code . '_status') && $this->user->hasPermission('access', 'extension/dashboard/' . $code)) {
                $output = $this->load->controller('extension/dashboard/' . $code . '/dashboard');

                if ($output) {
                    $dashboards[] = array(
                        'code'       => $code,
                        'width'      => $this->config->get('dashboard_' . $code . '_width'),
                        'sort_order' => $this->config->get('dashboard_' . $code . '_sort_order'),
                        'output'     => $output
                    );
                }
            }
        }

        $sort_order = array();

        foreach ($dashboards as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $dashboards);

        // Split the array so the columns width is not more than 12 on each row.
        $width = 0;
        $column = array();
        $data['rows'] = array();

        foreach ($dashboards as $dashboard) {
            $column[] = $dashboard;

            $width = ($width + $dashboard['width']);

            if ($width >= 12) {
                $data['rows'][] = $column;

                $width = 0;
                $column = array();
            }
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/dashboard', $data));
    }
}
