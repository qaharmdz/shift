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

        $this->load->model('account/usergroup');
        $data['user_groups'] = $this->model_account_usergroup->getUserGroups();

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

    public function dtaction()
    {
        $this->load->model('account/user');
        $this->load->language('account/user');

        if (!$this->user->hasPermission('modify', 'account/user')) {
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

        $data['updated'] = $this->model_account_user->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $user_id = $this->request->getInt('query.user_id', 0);
        $mode = !$user_id ? 'add' : 'edit';

        $this->load->config('account/user');
        $this->load->model('account/user');
        $this->load->language('account/user');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('account')],
            [$this->language->get('page_title')],
            [$this->language->get('list'), $this->router->url('account/user')],
            [$this->language->get($mode), $this->router->url('account/user/form', 'user_id=' . $user_id)],
        ]);

        $data = [];

        $data['mode']    = $mode;
        $data['user_id'] = $user_id;
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('account.user.form'),
            $this->model_account_user->getUser($user_id),
            $this->request->get('post', [])
        );

        $this->load->model('account/usergroup');
        $data['user_groups'] = $this->model_account_usergroup->getUserGroups();

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');


        $this->response->setOutput($this->load->view('account/user_form', $data));
    }

    public function save()
    {
        $this->load->config('account/user');
        $this->load->model('account/user');
        $this->load->language('account/user');

        if (!$this->user->hasPermission('modify', 'account/usergroup')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.user_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }
    }
}
