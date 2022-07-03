<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\User;

use Shift\System\Core\Mvc;

class User extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_user_user->addUser($this->request->get('post'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $url = '';

            if ($this->request->has('query.sort')) {
                $url .= '&sort=' . $this->request->get('query.sort');
            }

            if ($this->request->has('query.order')) {
                $url .= '&order=' . $this->request->get('query.order');
            }

            if ($this->request->has('query.page')) {
                $url .= '&page=' . $this->request->get('query.page');
            }

            $this->response->redirect($this->router->url('user/user', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_user_user->editUser($this->request->get('query.user_id'), $this->request->get('post'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $url = '';

            if ($this->request->has('query.sort')) {
                $url .= '&sort=' . $this->request->get('query.sort');
            }

            if ($this->request->has('query.order')) {
                $url .= '&order=' . $this->request->get('query.order');
            }

            if ($this->request->has('query.page')) {
                $url .= '&page=' . $this->request->get('query.page');
            }

            $this->response->redirect($this->router->url('user/user', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('user/user');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            foreach ($this->request->get('post.selected') as $user_id) {
                $this->model_user_user->deleteUser($user_id);
            }

            $this->session->set('flash.success', $this->language->get('text_success'));

            $url = '';

            if ($this->request->has('query.sort')) {
                $url .= '&sort=' . $this->request->get('query.sort');
            }

            if ($this->request->has('query.order')) {
                $url .= '&order=' . $this->request->get('query.order');
            }

            if ($this->request->has('query.page')) {
                $url .= '&page=' . $this->request->get('query.page');
            }

            $this->response->redirect($this->router->url('user/user', 'token=' . $this->session->get('token') . $url));
        }

        $this->getList();
    }

    protected function getList()
    {
        if ($this->request->has('query.sort')) {
            $sort = $this->request->get('query.sort');
        } else {
            $sort = 'username';
        }

        if ($this->request->has('query.order')) {
            $order = $this->request->get('query.order');
        } else {
            $order = 'ASC';
        }

        if ($this->request->has('query.page')) {
            $page = $this->request->get('query.page');
        } else {
            $page = 1;
        }

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('user/user', 'token=' . $this->session->get('token') . $url)
        );

        $data['add'] = $this->router->url('user/user/add', 'token=' . $this->session->get('token') . $url);
        $data['delete'] = $this->router->url('user/user/delete', 'token=' . $this->session->get('token') . $url);

        $data['users'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('env.limit'),
            'limit' => $this->config->get('env.limit')
        );

        $user_total = $this->model_user_user->getTotalUsers();

        $results = $this->model_user_user->getUsers($filter_data);

        foreach ($results as $result) {
            $data['users'][] = array(
                'user_id'    => $result['user_id'],
                'username'   => $result['username'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'edit'       => $this->router->url('user/user/edit', 'token=' . $this->session->get('token') . '&user_id=' . $result['user_id'] . $url)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_username'] = $this->language->get('column_username');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['success'] = $this->session->pull('flash.success');

        if ($this->request->has('post.selected')) {
            $data['selected'] = (array)$this->request->get('post.selected');
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['sort_username'] = $this->router->url('user/user', 'token=' . $this->session->get('token') . '&sort=username' . $url);
        $data['sort_status'] = $this->router->url('user/user', 'token=' . $this->session->get('token') . '&sort=status' . $url);
        $data['sort_date_added'] = $this->router->url('user/user', 'token=' . $this->session->get('token') . '&sort=date_added' . $url);

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        $pagination = new \Pagination();
        $pagination->total = $user_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('env.limit');
        $pagination->url = $this->router->url('user/user', 'token=' . $this->session->get('token') . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('env.limit')) + 1 : 0, ((($page - 1) * $this->config->get('env.limit')) > ($user_total - $this->config->get('env.limit'))) ? $user_total : ((($page - 1) * $this->config->get('env.limit')) + $this->config->get('env.limit')), $user_total, ceil($user_total / $this->config->get('env.limit')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.user_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_user_group'] = $this->language->get('entry_user_group');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('user/user', 'token=' . $this->session->get('token') . $url)
        );

        if (!$this->request->has('query.user_id')) {
            $data['action'] = $this->router->url('user/user/add', 'token=' . $this->session->get('token') . $url);
        } else {
            $data['action'] = $this->router->url('user/user/edit', 'token=' . $this->session->get('token') . '&user_id=' . $this->request->get('query.user_id') . $url);
        }

        $data['cancel'] = $this->router->url('user/user', 'token=' . $this->session->get('token') . $url);

        if ($this->request->has('query.user_id') && !$this->request->is('POST')) {
            $user_info = $this->model_user_user->getUser($this->request->get('query.user_id'));
        }

        if ($this->request->has('post.username')) {
            $data['username'] = $this->request->get('post.username');
        } elseif (!empty($user_info)) {
            $data['username'] = $user_info['username'];
        } else {
            $data['username'] = '';
        }

        if ($this->request->has('post.user_group_id')) {
            $data['user_group_id'] = $this->request->get('post.user_group_id');
        } elseif (!empty($user_info)) {
            $data['user_group_id'] = $user_info['user_group_id'];
        } else {
            $data['user_group_id'] = '';
        }

        $this->load->model('user/usergroup');

        $data['user_groups'] = $this->model_user_usergroup->getUserGroups();

        if ($this->request->has('post.password')) {
            $data['password'] = $this->request->get('post.password');
        } else {
            $data['password'] = '';
        }

        if ($this->request->has('post.confirm')) {
            $data['confirm'] = $this->request->get('post.confirm');
        } else {
            $data['confirm'] = '';
        }

        if ($this->request->has('post.firstname')) {
            $data['firstname'] = $this->request->get('post.firstname');
        } elseif (!empty($user_info)) {
            $data['firstname'] = $user_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if ($this->request->has('post.lastname')) {
            $data['lastname'] = $this->request->get('post.lastname');
        } elseif (!empty($user_info)) {
            $data['lastname'] = $user_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if ($this->request->has('post.email')) {
            $data['email'] = $this->request->get('post.email');
        } elseif (!empty($user_info)) {
            $data['email'] = $user_info['email'];
        } else {
            $data['email'] = '';
        }

        if ($this->request->has('post.image')) {
            $data['image'] = $this->request->get('post.image');
        } elseif (!empty($user_info)) {
            $data['image'] = $user_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if ($this->request->has('post.image') && is_file(DIR_IMAGE . $this->request->get('post.image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->get('post.image'), 100, 100);
        } elseif (!empty($user_info) && $user_info['image'] && is_file(DIR_IMAGE . $user_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($user_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no-image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no-image.png', 100, 100);

        if ($this->request->has('post.status')) {
            $data['status'] = $this->request->get('post.status');
        } elseif (!empty($user_info)) {
            $data['status'] = $user_info['status'];
        } else {
            $data['status'] = 0;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'user/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->get('post.username')) < 3) || (utf8_strlen($this->request->get('post.username')) > 20)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        $user_info = $this->model_user_user->getUserByUsername($this->request->get('post.username'));

        if (!$this->request->has('query.user_id')) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_exists_username');
            }
        } else {
            if ($user_info && ($this->request->get('query.user_id') != $user_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists_username');
            }
        }

        if ((utf8_strlen(trim($this->request->get('post.firstname'))) < 1) || (utf8_strlen(trim($this->request->get('post.firstname'))) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->get('post.lastname'))) < 1) || (utf8_strlen(trim($this->request->get('post.lastname'))) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->get('post.email')) > 96) || !filter_var($this->request->get('post.email'), FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        $user_info = $this->model_user_user->getUserByEmail($this->request->get('post.email'));

        if (!$this->request->has('query.user_id')) {
            if ($user_info) {
                $this->error['warning'] = $this->language->get('error_exists_email');
            }
        } else {
            if ($user_info && ($this->request->get('query.user_id') != $user_info['user_id'])) {
                $this->error['warning'] = $this->language->get('error_exists_email');
            }
        }

        if ($this->request->get('post.password') || (!$this->request->has('query.user_id'))) {
            if ((utf8_strlen($this->request->get('post.password')) < 4) || (utf8_strlen($this->request->get('post.password')) > 20)) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if ($this->request->get('post.password') != $this->request->get('post.confirm')) {
                $this->error['confirm'] = $this->language->get('error_confirm');
            }
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'user/user')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->get('post.selected') as $user_id) {
            if ($this->user->getId() == $user_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }
}
