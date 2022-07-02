<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Core\Mvc;

class Language extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/language');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('extension/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/language');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_extension_language->addLanguage($this->request->get('post'));

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

            $this->response->redirect($this->router->url('extension/language', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('extension/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/language');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_extension_language->editLanguage($this->request->get('query.language_id'), $this->request->get('post'));

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

            $this->response->redirect($this->router->url('extension/language', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('extension/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/language');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            foreach ($this->request->get('post.selected') as $language_id) {
                $this->model_extension_language->deleteLanguage($language_id);
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

            $this->response->redirect($this->router->url('extension/language', 'token=' . $this->session->get('token') . $url));
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
            'href' => $this->router->url('extension/language', 'token=' . $this->session->get('token') . $url)
        );

        $data['add'] = $this->router->url('extension/language/add', 'token=' . $this->session->get('token') . $url);
        $data['delete'] = $this->router->url('extension/language/delete', 'token=' . $this->session->get('token') . $url);

        $data['languages'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('system.setting.limit_admin'),
            'limit' => $this->config->get('system.setting.limit_admin')
        );

        $language_total = $this->model_extension_language->getTotalLanguages();

        $results = $this->model_extension_language->getLanguages($filter_data);

        foreach ($results as $result) {
            $data['languages'][] = array(
                'language_id' => $result['language_id'],
                'name'        => $result['name'] . (($result['code'] == $this->config->get('system.setting.language')) ? $this->language->get('text_default') : null),
                'code'        => $result['code'],
                'sort_order'  => $result['sort_order'],
                'edit'        => $this->router->url('extension/language/edit', 'token=' . $this->session->get('token') . '&language_id=' . $result['language_id'] . $url)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_code'] = $this->language->get('column_code');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
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

        $data['sort_name'] = $this->router->url('extension/language', 'token=' . $this->session->get('token') . '&sort=name' . $url);
        $data['sort_code'] = $this->router->url('extension/language', 'token=' . $this->session->get('token') . '&sort=code' . $url);
        $data['sort_sort_order'] = $this->router->url('extension/language', 'token=' . $this->session->get('token') . '&sort=sort_order' . $url);

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        $pagination = new \Pagination();
        $pagination->total = $language_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('system.setting.limit_admin');
        $pagination->url = $this->router->url('extension/language', 'token=' . $this->session->get('token') . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($language_total) ? (($page - 1) * $this->config->get('system.setting.limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('system.setting.limit_admin')) > ($language_total - $this->config->get('system.setting.limit_admin'))) ? $language_total : ((($page - 1) * $this->config->get('system.setting.limit_admin')) + $this->config->get('system.setting.limit_admin')), $language_total, ceil($language_total / $this->config->get('system.setting.limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/language_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.language_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_locale'] = $this->language->get('entry_locale');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['help_locale'] = $this->language->get('help_locale');
        $data['help_status'] = $this->language->get('help_status');

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

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

        if (isset($this->error['locale'])) {
            $data['error_locale'] = $this->error['locale'];
        } else {
            $data['error_locale'] = '';
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
            'href' => $this->router->url('extension/language', 'token=' . $this->session->get('token') . $url)
        );

        if (!$this->request->has('query.language_id')) {
            $data['action'] = $this->router->url('extension/language/add', 'token=' . $this->session->get('token') . $url);
        } else {
            $data['action'] = $this->router->url('extension/language/edit', 'token=' . $this->session->get('token') . '&language_id=' . $this->request->get('query.language_id') . $url);
        }

        $data['cancel'] = $this->router->url('extension/language', 'token=' . $this->session->get('token') . $url);

        if ($this->request->has('query.language_id') && !$this->request->is('POST')) {
            $language_info = $this->model_extension_language->getLanguage($this->request->get('query.language_id'));
        }

        if ($this->request->has('post.name')) {
            $data['name'] = $this->request->get('post.name');
        } elseif (!empty($language_info)) {
            $data['name'] = $language_info['name'];
        } else {
            $data['name'] = '';
        }

        if ($this->request->has('post.code')) {
            $data['code'] = $this->request->get('post.code');
        } elseif (!empty($language_info)) {
            $data['code'] = $language_info['code'];
        } else {
            $data['code'] = '';
        }

        $data['languages'] = array();

        $folders = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            $data['languages'][] = basename($folder);
        }

        if ($this->request->has('post.locale')) {
            $data['locale'] = $this->request->get('post.locale');
        } elseif (!empty($language_info)) {
            $data['locale'] = $language_info['locale'];
        } else {
            $data['locale'] = '';
        }

        if ($this->request->has('post.sort_order')) {
            $data['sort_order'] = $this->request->get('post.sort_order');
        } elseif (!empty($language_info)) {
            $data['sort_order'] = $language_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        if ($this->request->has('post.status')) {
            $data['status'] = $this->request->get('post.status');
        } elseif (!empty($language_info)) {
            $data['status'] = $language_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/language_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'extension/language')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->get('post.name')) < 3) || (utf8_strlen($this->request->get('post.name')) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (utf8_strlen($this->request->get('post.code')) < 2) {
            $this->error['code'] = $this->language->get('error_code');
        }

        if (!$this->request->get('post.locale')) {
            $this->error['locale'] = $this->language->get('error_locale');
        }

        $language_info = $this->model_extension_language->getLanguageByCode($this->request->get('post.code'));

        if (!$this->request->has('query.language_id')) {
            if ($language_info) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        } else {
            if ($language_info && ($this->request->get('query.language_id') != $language_info['language_id'])) {
                $this->error['warning'] = $this->language->get('error_exists');
            }
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'extension/language')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/store');

        foreach ($this->request->get('post.selected') as $language_id) {
            $language_info = $this->model_extension_language->getLanguage($language_id);

            if ($language_info) {
                if ($this->config->get('system.setting.language') == $language_info['code']) {
                    $this->error['warning'] = $this->language->get('error_default');
                }

                if ($this->config->get('system.setting.admin_language') == $language_info['code']) {
                    $this->error['warning'] = $this->language->get('error_admin');
                }

                $store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);

                if ($store_total) {
                    $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
                }
            }
        }

        return !$this->error;
    }
}
