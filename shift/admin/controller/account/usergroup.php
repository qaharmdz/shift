<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Account;

use Shift\System\Mvc;

class UserGroup extends Mvc\Controller {
    public function index()
    {
        $this->load->language('account/usergroup');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('accounts')],
            [$this->language->get('page_title'), $this->router->url('account/usergroup')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('account/usergroup_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('account/usergroup');

        $params = $this->request->get('post');
        $results = $this->model_account_usergroup->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['user_group_id'];
            $items[$i]['url_edit'] = $this->router->url('account/usergroup/form', 'user_group_id=' . $items[$i]['user_group_id']);
        }

        $data = [
            'draw'            => (int) $params['draw'] ?? 1,
            'data'            => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_account_usergroup->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('account/usergroup');
        $this->load->language('account/usergroup');

        if (!$this->user->hasPermission('modify', 'account/usergroup')) {
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

        if (empty($items) || !in_array($post['type'], $types) || in_array(1, $items)) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data['updated'] = $this->model_account_usergroup->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $user_group_id = $this->request->getInt('query.user_group_id', 0);
        $mode = !$user_group_id ? 'add_new' : 'edit';

        $this->load->model('account/usergroup');
        $this->load->language('account/usergroup');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('accounts')],
            [$this->language->get('page_title'), $this->router->url('account/usergroup')],
            [$this->language->get($mode), $this->router->url('account/usergroup/form', 'user_group_id=' . $user_group_id)],
        ]);

        $data = [];

        $data['mode'] = $mode;
        $data['user_group_id'] = $user_group_id;
        $data['permissions'] = $this->permissionList();
        $data['setting'] = $this->model_account_usergroup->getUserGroup($user_group_id);

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('account/usergroup_form', $data));
    }

    protected function permissionList()
    {
        $permissions = [];
        $ignore = array_merge(
            $this->config->getArray('root.app_component'),
            $this->config->getArray('root.app_startup'),
            [
                'page/login',
                'page/logout',
                'page/dashboard',
                'error/notfound',
                'error/permission',
                'block/header',
                'block/footer',
                'block/position',
                'block/sidebarleft',
            ]
        );

        $path = [PATH_APP . 'controller/*'];

        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                if (is_file($file)) {
                    $routes[] = strtolower(str_replace('.php', '', substr($file, strlen(PATH_APP . 'controller/'))));
                }
            }
        }

        sort($routes);

        foreach ($routes as $route) {
            if (!in_array($route, $ignore)) {
                $permissions[] = [
                    'text'  => $route,
                    'value' => $route,
                ];
            }
        }

        return $permissions;
    }

    public function save()
    {
        $this->load->config('account/usergroup');
        $this->load->model('account/usergroup');
        $this->load->language('account/usergroup');

        if (!$this->user->hasPermission('modify', 'account/usergroup')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.user_group_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('account.usergroup.form'),
            $this->request->get('post', [])
        );
        $user_group_id = (int) $post['user_group_id'];

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        if (!$user_group_id) {
            $data['new_id'] = $this->model_account_usergroup->addUserGroup($post);
        } else {
            $this->model_account_usergroup->editUserGroup($user_group_id, $post);
        }

        // Redirect
        if ($post['action'] === 'close') {
            $data['redirect'] = $this->router->url('account/usergroup');
        }
        if ($post['action'] === 'new') {
            $data['redirect'] = $this->router->url('account/usergroup/form');
        }
        if (isset($data['new_id']) && empty($data['redirect'])) {
            $data['redirect'] = $this->router->url('account/usergroup/form', 'user_group_id=' . $data['new_id']);
        }

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->stringNotEmpty()->check($post['name'])) {
            $errors['items']['name'] = $this->language->get('error_no_empty');
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
