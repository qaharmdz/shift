<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Mvc;

class Layout extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('tool/layout');
        $this->load->language('tool/layout');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('tool')],
            [$this->language->get('page_title'), $this->router->url('tool/layout')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('tool/layout_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('tool/layout');

        $params  = $this->request->get('post');
        $results = $this->model_tool_layout->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['layout_id'];
            $items[$i]['url_edit']    = $this->router->url('tool/layout/form', 'layout_id=' . $items[$i]['layout_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_tool_layout->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('tool/layout');
        $this->load->language('tool/layout');

        if (!$this->user->hasPermission('modify', 'tool/layout')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $post  = array_replace(['type' => '', 'item' => ''], $this->request->get('post'));
        $types = ['enabled', 'disabled', 'delete'];
        $items = explode(',', $post['item']);
        $data  = [
            'items'     => $items,
            'message'   => '',
            'updated'   => [],
        ];

        if (empty($items) || !in_array($post['type'], $types) || in_array(0, $items)) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data['updated'] = $this->model_tool_layout->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $layout_id = $this->request->getInt('query.layout_id', 0);
        $mode = !$layout_id ? 'add' : 'edit';

        $this->load->config('tool/layout');
        $this->load->model('tool/layout');
        $this->load->language('tool/layout');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('tool')],
            [$this->language->get('page_title'), $this->router->url('tool/layout')],
            [$this->language->get($mode), $this->router->url('tool/layout/form', 'layout_id=' . $layout_id)],
        ]);

        $data = [];

        $data['mode']      = $mode;
        $data['layout_id'] = $layout_id;
        $data['setting']   = array_replace_recursive(
            $this->config->getArray('tool.layout.form'),
            $this->model_tool_layout->getLayout($layout_id),
            $this->request->get('post', [])
        );

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('tool/layout_form', $data));
    }
}
