<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\User;

use Shift\System\Core\Mvc;

class UserPermission extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/usergroup');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/usergroup');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_user_usergroup->addUserGroup($this->request->get('post'));

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

            $this->response->redirect($this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/usergroup');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_user_usergroup->editUserGroup($this->request->get('query.user_group_id'), $this->request->get('post'));

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

            $this->response->redirect($this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('user/user_group');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/usergroup');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            foreach ($this->request->get('post.selected') as $user_group_id) {
                $this->model_user_usergroup->deleteUserGroup($user_group_id);
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

            $this->response->redirect($this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url));
        }

        $this->getList();
    }

    protected function getList()
    {
        if ($this->request->has('query.sort')) {
            $sort = $this->request->get('query.sort');
        } else {
            $sort = 'name';
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
            'href' => $this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url)
        );

        $data['add'] = $this->router->url('user/userpermission/add', 'token=' . $this->session->get('token') . $url);
        $data['delete'] = $this->router->url('user/userpermission/delete', 'token=' . $this->session->get('token') . $url);

        $data['user_groups'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('env.limit'),
            'limit' => $this->config->get('env.limit')
        );

        $user_group_total = $this->model_user_usergroup->getTotalUserGroups();

        $results = $this->model_user_usergroup->getUserGroups($filter_data);

        foreach ($results as $result) {
            $data['user_groups'][] = array(
                'user_group_id' => $result['user_group_id'],
                'name'          => $result['name'],
                'edit'          => $this->router->url('user/userpermission/edit', 'token=' . $this->session->get('token') . '&user_group_id=' . $result['user_group_id'] . $url)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
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

        $data['sort_name'] = $this->router->url('user/userpermission', 'token=' . $this->session->get('token') . '&sort=name' . $url);

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        $pagination = new \Pagination();
        $pagination->total = $user_group_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('env.limit');
        $pagination->url = $this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($user_group_total) ? (($page - 1) * $this->config->get('env.limit')) + 1 : 0, ((($page - 1) * $this->config->get('env.limit')) > ($user_group_total - $this->config->get('env.limit'))) ? $user_group_total : ((($page - 1) * $this->config->get('env.limit')) + $this->config->get('env.limit')), $user_group_total, ceil($user_group_total / $this->config->get('env.limit')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_group_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.user_group_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_access'] = $this->language->get('entry_access');
        $data['entry_modify'] = $this->language->get('entry_modify');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
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
            'href' => $this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url)
        );

        if (!$this->request->has('query.user_group_id')) {
            $data['action'] = $this->router->url('user/userpermission/add', 'token=' . $this->session->get('token') . $url);
        } else {
            $data['action'] = $this->router->url('user/userpermission/edit', 'token=' . $this->session->get('token') . '&user_group_id=' . $this->request->get('query.user_group_id') . $url);
        }

        $data['cancel'] = $this->router->url('user/userpermission', 'token=' . $this->session->get('token') . $url);

        if ($this->request->has('query.user_group_id') && !$this->request->is('POST')) {
            $user_group_info = $this->model_user_usergroup->getUserGroup($this->request->get('query.user_group_id'));
        }

        if ($this->request->has('post.name')) {
            $data['name'] = $this->request->get('post.name');
        } elseif (!empty($user_group_info)) {
            $data['name'] = $user_group_info['name'];
        } else {
            $data['name'] = '';
        }

        $ignore = array(
            'common/dashboard',
            'common/startup',
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'common/footer',
            'common/header',
            'error/not_found',
            'error/permission'
        );

        $data['permissions'] = array();

        $files = array();

        // Make path into an array
        $path = array(DIR_APPLICATION . 'controller/*');

        // While the path array is still populated keep looping through
        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                // If directory add to path array
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                // Add the file to the files to be deleted array
                if (is_file($file)) {
                    $files[] = $file;
                }
            }
        }

        // Sort the file array
        sort($files);

        foreach ($files as $file) {
            $controller = substr($file, strlen(DIR_APPLICATION . 'controller/'));

            $permission = substr($controller, 0, strrpos($controller, '.'));

            if (!in_array($permission, $ignore)) {
                $data['permissions'][] = $permission;
            }
        }

        if ($this->request->has('post.permission.access')) {
            $data['access'] = $this->request->get('post.permission.access');
        } elseif (isset($user_group_info['permission']['access'])) {
            $data['access'] = $user_group_info['permission']['access'];
        } else {
            $data['access'] = array();
        }

        if ($this->request->has('post.permission.modify')) {
            $data['modify'] = $this->request->get('post.permission.modify');
        } elseif (isset($user_group_info['permission']['modify'])) {
            $data['modify'] = $user_group_info['permission']['modify'];
        } else {
            $data['modify'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('user/user_group_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'user/userpermission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->get('post.name')) < 3) || (utf8_strlen($this->request->get('post.name')) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'user/userpermission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('user/user');

        foreach ($this->request->get('post.selected') as $user_group_id) {
            $user_total = $this->model_user_user->getTotalUsersByGroupId($user_group_id);

            if ($user_total) {
                $this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
            }
        }

        return !$this->error;
    }
}
