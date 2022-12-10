<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Module extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extension/module');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('page_title')],
            [$this->language->get('list'), $this->router->url('extension/module')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/module_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('extension/module');

        $params  = $this->request->get('post');
        $results = $this->model_extension_module->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['module_id'];
            $items[$i]['url_edit']    = $this->router->url('extension/module/form', 'module_id=' . $items[$i]['module_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_extension_module->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }
}
