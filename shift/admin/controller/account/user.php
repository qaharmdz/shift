<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Account;

use Shift\System\Mvc;

class User extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('account/user');

        $this->document->setTitle($this->language->get('page_title'));
        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('account')],
            [$this->language->get('page_title')],
            [$this->language->get('list'), $this->router->url('account/user')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('account/user_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('account/user');
        $this->load->language('account/user');

        $params  = $this->request->get('post');
        $results = $this->model_account_user->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['user_id'];
            $items[$i]['url_edit']    = $this->router->url('account/user/form', 'user_id=' . $items[$i]['user_id']);
        }

        $data = [
            'draw'            => (int)$params['draw'] ?? 1,
            'data'            => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_account_user->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }
}
