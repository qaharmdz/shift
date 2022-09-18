<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Site extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('page_title'));
        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('system')],
            [$this->language->get('page_title')],
            [$this->language->get('list'), $this->router->url('setting/site')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/site_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('setting/site');
        $this->load->language('setting/site');

        $params  = $this->request->get('post');
        $results = $this->model_setting_site->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['site_id'];
            $items[$i]['url_edit']    = $this->router->url('setting/site/form', 'site_id=' . $items[$i]['site_id']);
        }

        $data = [
            'draw'            => (int)$params['draw'] ?? 1,
            'data'            => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_setting_site->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }
}
