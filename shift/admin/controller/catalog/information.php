<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Catalog;

use Shift\System\Core\Mvc;

class Information extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('catalog/information');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/information');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('catalog/information');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/information');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_catalog_information->addInformation($this->request->getArray('post'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect(
                $this->router->url(
                    'catalog/information',
                    'token=' . $this->session->get('token') . $this->urlQueryList(['sort', 'order', 'page'])
                )
            );
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('catalog/information');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/information');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_catalog_information->editInformation($this->request->getInt('query.information_id'), $this->request->get('post'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect(
                $this->router->url(
                    'catalog/information',
                    'token=' . $this->session->get('token') . $this->urlQueryList(['sort', 'order', 'page'])
                )
            );
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/information');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/information');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            foreach ($this->request->get('post.selected') as $information_id) {
                $this->model_catalog_information->deleteInformation((int)$information_id);
            }

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect(
                $this->router->url(
                    'catalog/information',
                    'token=' . $this->session->get('token') . $this->urlQueryList(['sort', 'order', 'page'])
                )
            );
        }

        $this->getList();
    }

    protected function getList()
    {
        $sort  = $this->request->get('query.sort', 'id.title');
        $order = $this->request->get('query.order', 'ASC');
        $page  = $this->request->get('query.page', 1);

        $url = $this->urlQueryList(['sort', 'order', 'page']);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('catalog/information', 'token=' . $this->session->get('token') . $url)
        );

        $data['add'] = $this->router->url('catalog/information/add', 'token=' . $this->session->get('token') . $url);
        $data['delete'] = $this->router->url('catalog/information/delete', 'token=' . $this->session->get('token') . $url);

        $data['informations'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('env.limit'),
            'limit' => $this->config->get('env.limit')
        );

        $information_total = $this->model_catalog_information->getTotalInformations();

        $results = $this->model_catalog_information->getInformations($filter_data);

        foreach ($results as $result) {
            $data['informations'][] = array(
                'information_id' => $result['information_id'],
                'title'          => $result['title'],
                'sort_order'     => $result['sort_order'],
                'edit'           => $this->router->url('catalog/information/edit', 'token=' . $this->session->get('token') . '&information_id=' . $result['information_id'] . $url)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_title'] = $this->language->get('column_title');
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
        $data['selected'] = $this->request->getArray('post.selected', []);

        $url = $this->urlQueryList(['page']) . '&order=' . ($order == 'ASC' ? 'DESC' : 'ASC');

        $data['sort_title'] = $this->router->url('catalog/information', 'token=' . $this->session->get('token') . '&sort=id.title' . $url);
        $data['sort_sort_order'] = $this->router->url('catalog/information', 'token=' . $this->session->get('token') . '&sort=i.sort_order' . $url);

        $url = $this->urlQueryList(['sort', 'order']);

        $pagination = new \Pagination();
        $pagination->total = $information_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('env.limit');
        $pagination->url = $this->router->url('catalog/information', 'token=' . $this->session->get('token') . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($information_total) ? (($page - 1) * $this->config->get('env.limit')) + 1 : 0, ((($page - 1) * $this->config->get('env.limit')) > ($information_total - $this->config->get('env.limit'))) ? $information_total : ((($page - 1) * $this->config->get('env.limit')) + $this->config->get('env.limit')), $information_total, ceil($information_total / $this->config->get('env.limit')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/information_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.information_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_bottom'] = $this->language->get('entry_bottom');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_layout'] = $this->language->get('entry_layout');

        $data['help_keyword'] = $this->language->get('help_keyword');
        $data['help_bottom'] = $this->language->get('help_bottom');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_design'] = $this->language->get('tab_design');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = array();
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = $this->urlQueryList(['sort', 'order', 'page']);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('catalog/information', 'token=' . $this->session->get('token') . $url)
        );

        if (!$this->request->has('query.information_id')) {
            $data['action'] = $this->router->url('catalog/information/add', 'token=' . $this->session->get('token') . $url);
        } else {
            $data['action'] = $this->router->url('catalog/information/edit', 'token=' . $this->session->get('token') . '&information_id=' . $this->request->get('query.information_id', 0) . $url);
        }

        $data['cancel'] = $this->router->url('catalog/information', 'token=' . $this->session->get('token') . $url);

        if ($this->request->has('query.information_id') && !$this->request->is('POST')) {
            $information_info = $this->model_catalog_information->getInformation($this->request->getInt('query.information_id'));
        }

        $data['token'] = $this->session->get('token');

        $this->load->model('extension/language');

        $data['languages'] = $this->model_extension_language->getLanguages();

        $data['information_description'] = $this->request->getArray('post.information_description', []);
        if ($this->request->has('query.information_id')) {
            $data['information_description'] = $this->model_catalog_information->getInformationDescriptions($this->request->getInt('query.information_id'));
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $data['information_store'] = $this->request->getArray('post.information_store', [0]);
        if ($this->request->has('query.information_id')) {
            $data['information_store'] = $this->model_catalog_information->getInformationStores($this->request->getInt('query.information_id'));
        }

        $data['keyword'] = '';
        if ($this->request->has('post.keyword')) {
            $data['keyword'] = $this->request->getString('post.keyword');
        } elseif (!empty($information_info)) {
            $data['keyword'] = $information_info['keyword'];
        }

        $data['bottom'] = 0;
        if ($this->request->has('post.bottom')) {
            $data['bottom'] = $this->request->getInt('post.bottom');
        } elseif (!empty($information_info)) {
            $data['bottom'] = (int)$information_info['bottom'];
        }

        $data['status'] = true;
        if ($this->request->has('post.status')) {
            $data['status'] = $this->request->getBool('post.status');
        } elseif (!empty($information_info)) {
            $data['status'] = (bool)$information_info['status'];
        }

        $data['sort_order'] = '';
        if ($this->request->has('post.sort_order')) {
            $data['sort_order'] = $this->request->getString('post.sort_order');
        } elseif (!empty($information_info)) {
            $data['sort_order'] = $information_info['sort_order'];
        }

        $data['information_layout'] = [];
        if ($this->request->has('post.information_layout')) {
            $data['information_layout'] = $this->request->getArray('post.information_layout');
        } elseif ($this->request->has('query.information_id')) {
            $data['information_layout'] = $this->model_catalog_information->getInformationLayouts($this->request->getInt('query.information_id'));
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/information_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/information')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->getArray('post.information_description') as $language_id => $value) {
            if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }

            if (utf8_strlen($value['description']) < 3) {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if (utf8_strlen($this->request->getString('post.keyword')) > 0) {
            $this->load->model('catalog/urlalias');

            $url_alias_info = $this->model_catalog_urlalias->getUrlAlias($this->request->getString('post.keyword'));

            if ($url_alias_info && $url_alias_info['param'] != 'information_id' && $url_alias_info['value'] != $this->request->get('query.information_id', 'x')) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }

            if ($url_alias_info && !$this->request->has('query.information_id')) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/information')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/store');

        foreach ($this->request->get('post.selected', []) as $information_id) {
            if ($this->config->get('system.setting.account_id') == $information_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }

            if ($this->config->get('system.setting.checkout_id') == $information_id) {
                $this->error['warning'] = $this->language->get('error_checkout');
            }

            if ($this->config->get('system.setting.affiliate_id') == $information_id) {
                $this->error['warning'] = $this->language->get('error_affiliate');
            }

            if ($this->config->get('system.setting.return_id') == $information_id) {
                $this->error['warning'] = $this->language->get('error_return');
            }

            $store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);

            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }
        }

        return !$this->error;
    }

    protected function urlQueryList(array $params = array())
    {
        $url = '';

        if (in_array('sort', $params) && $this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if (in_array('order', $params) && $this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        if (in_array('page', $params) && $this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        return $url;
    }
}
