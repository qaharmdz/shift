<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Module extends Mvc\Controller {
    public function index()
    {
        $this->load->language('extension/module');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extensions')],
            [$this->language->get('page_title'), $this->router->url('extension/module')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/module', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('extension/module');

        $params = $this->request->get('post');
        $results = $this->model_extension_module->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['module_id'];
            $items[$i]['url_edit'] = $this->router->url('extensions/module/' . $items[$i]['codename'], 'module_id=' . $items[$i]['module_id']);
        }

        $data = [
            'draw'            => (int) $params['draw'] ?? 1,
            'data'            => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_extension_module->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function trove()
    {
        $this->load->model('extension/module');
        $this->load->language('extension/module');

        $data = [];

        $data['modules'] = $this->model_extension_module->getExtModules();
        foreach ($data['modules'] as $key => $module) {
            $data['modules'][$key]['url_add'] = $this->router->url('extensions/module/' . $module['codename']);
        }

        $this->response->setOutput($this->load->view('extension/module_trove', $data));
    }

    public function dtaction()
    {
        $this->load->model('extension/module');
        $this->load->language('extension/module');

        if (!$this->user->hasPermission('modify', 'extension/module')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $post = array_replace(['type' => '', 'item' => ''], $this->request->get('post'));
        $types = ['enabled', 'disabled', 'delete'];
        $items = explode(',', $post['item']);
        $data = [
            'items'   => $items,
            'message' => '',
            'updated' => [],
        ];

        if (empty($items) || !in_array($post['type'], $types)) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data['updated'] = $this->model_extension_module->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }
}
