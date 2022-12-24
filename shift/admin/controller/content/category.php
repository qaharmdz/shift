<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Content;

use Shift\System\Mvc;

class Category extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('content/category');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('page_title'), $this->router->url('content/category')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/category_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('content/category');

        $params  = $this->request->get('post');
        $results = $this->model_content_category->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['post_id'];
            $items[$i]['url_edit']    = $this->router->url('content/category', 'post_id=' . $items[$i]['post_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_content_category->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('content/category');
        $this->load->language('content/category');

        if (!$this->user->hasPermission('modify', 'content/category')) {
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

        $data['updated'] = $this->model_content_category->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }
}
