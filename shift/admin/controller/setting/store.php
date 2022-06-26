<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Core\Mvc;
use Shift\System\Helper\Arr;

class Store extends Mvc\Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        $this->load->model('setting/setting');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        if ($this->request->is('POST') && $this->validateForm()) {
            $store_id = $this->model_setting_store->addStore($this->request->get('post'));

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('config', $this->request->get('post'), $store_id);

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->get('token'), true));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_setting_store->editStore($this->request->get('query.store_id', 0), $this->request->get('post'));

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('config', $this->request->get('post'), $this->request->get('query.store_id', 0));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->get('token') . '&store_id=' . $this->request->get('query.store_id', 0), true));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/store');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/store');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            $this->load->model('setting/setting');

            foreach ($this->request->get('post.selected') as $store_id) {
                $this->model_setting_store->deleteStore($store_id);

                $this->model_setting_setting->deleteSetting('config', $store_id);
            }

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->get('token'), true));
        }

        $this->getList();
    }

    protected function getList()
    {
        $url = '';

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->get('token'), true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/store', 'token=' . $this->session->get('token'), true)
        );

        $data['add'] = $this->url->link('setting/store/add', 'token=' . $this->session->get('token'), true);
        $data['delete'] = $this->url->link('setting/store/delete', 'token=' . $this->session->get('token'), true);

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
            'url'      => URL_SITE,
            'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->get('token'), true)
        );

        $store_total = $this->model_setting_store->getTotalStores();

        $results = $this->model_setting_store->getStores();

        foreach ($results as $result) {
            $data['stores'][] = array(
                'store_id' => $result['store_id'],
                'name'     => $result['name'],
                'url'      => $result['url'],
                'edit'     => $this->url->link('setting/store/edit', 'token=' . $this->session->get('token') . '&store_id=' . $result['store_id'], true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_url'] = $this->language->get('column_url');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        $data['error_warning'] = Arr::get($this->error, 'warning', '');
        $data['success']  = $this->session->pull('flash.success');
        $data['selected'] = $this->request->get('post.selected', []);

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/store_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.store_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_payment'] = $this->language->get('text_payment');

        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_ssl'] = $this->language->get('entry_ssl');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_theme'] = $this->language->get('entry_theme');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_owner'] = $this->language->get('entry_owner');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_open'] = $this->language->get('entry_open');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_language'] = $this->language->get('entry_language');
        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['entry_tax'] = $this->language->get('entry_tax');
        $data['entry_tax_default'] = $this->language->get('entry_tax_default');
        $data['entry_tax_customer'] = $this->language->get('entry_tax_customer');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
        $data['entry_customer_price'] = $this->language->get('entry_customer_price');
        $data['entry_account'] = $this->language->get('entry_account');
        $data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
        $data['entry_checkout_guest'] = $this->language->get('entry_checkout_guest');
        $data['entry_checkout'] = $this->language->get('entry_checkout');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_stock_display'] = $this->language->get('entry_stock_display');
        $data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
        $data['entry_ajax_cart'] = $this->language->get('entry_ajax_cart');
        $data['entry_logo'] = $this->language->get('entry_logo');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_secure'] = $this->language->get('entry_secure');

        $data['help_url'] = $this->language->get('help_url');
        $data['help_ssl'] = $this->language->get('help_ssl');
        $data['help_open'] = $this->language->get('help_open');
        $data['help_comment'] = $this->language->get('help_comment');
        $data['help_location'] = $this->language->get('help_location');
        $data['help_currency'] = $this->language->get('help_currency');
        $data['help_tax_default'] = $this->language->get('help_tax_default');
        $data['help_tax_customer'] = $this->language->get('help_tax_customer');
        $data['help_customer_group'] = $this->language->get('help_customer_group');
        $data['help_customer_group_display'] = $this->language->get('help_customer_group_display');
        $data['help_customer_price'] = $this->language->get('help_customer_price');
        $data['help_account'] = $this->language->get('help_account');
        $data['help_checkout_guest'] = $this->language->get('help_checkout_guest');
        $data['help_checkout'] = $this->language->get('help_checkout');
        $data['help_order_status'] = $this->language->get('help_order_status');
        $data['help_stock_display'] = $this->language->get('help_stock_display');
        $data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
        $data['help_icon'] = $this->language->get('help_icon');
        $data['help_secure'] = $this->language->get('help_secure');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_store'] = $this->language->get('tab_store');
        $data['tab_local'] = $this->language->get('tab_local');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_server'] = $this->language->get('tab_server');

        $data['error_warning']    = Arr::get($this->error, 'warning', '');
        $data['error_url']        = Arr::get($this->error, 'url', '');
        $data['error_meta_title'] = Arr::get($this->error, 'meta_title', '');
        $data['error_name']       = Arr::get($this->error, 'name', '');
        $data['error_owner']      = Arr::get($this->error, 'owner', '');
        $data['error_address']    = Arr::get($this->error, 'address', '');
        $data['error_email']      = Arr::get($this->error, 'email', '');
        $data['error_telephone']  = Arr::get($this->error, 'telephone', '');
        $data['error_customer_group_display'] = Arr::get($this->error, 'customer_group_display', '');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->get('token'), true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/store', 'token=' . $this->session->get('token'), true)
        );

        if (!$this->request->has('query.store_id')) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/store/add', 'token=' . $this->session->get('token'), true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_settings'),
                'href' => $this->url->link('setting/store/edit', 'token=' . $this->session->get('token') . '&store_id=' . $this->request->get('query.store_id'), true)
            );
        }

        $data['success'] = $this->session->pull('flash.success');

        if (!$this->request->has('query.store_id')) {
            $data['action'] = $this->url->link('setting/store/add', 'token=' . $this->session->get('token'), true);
        } else {
            $data['action'] = $this->url->link('setting/store/edit', 'token=' . $this->session->get('token') . '&store_id=' . $this->request->get('query.store_id'), true);
        }

        $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->get('token'), true);

        $store_info = [];
        if ($this->request->has('query.store_id') && !$this->request->is('POST')) {
            $this->load->model('setting/setting');

            $store_info = $this->model_setting_setting->getSetting('config', $this->request->get('query.store_id'));
        }

        if (!$store_info) {
            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->get('token'), true));
        }

        $data['token'] = $this->session->get('token');

        $data['config_url']  = $this->request->get('post.config_url', Arr::get($store_info, 'config_url', ''));
        $data['config_ssl']  = $this->request->get('post.config_ssl', Arr::get($store_info, 'config_ssl', ''));

        $data['config_meta_title']       = $this->request->get('post.config_meta_title', Arr::get($store_info, 'config_meta_title', ''));
        $data['config_meta_description'] = $this->request->get('post.config_meta_description', Arr::get($store_info, 'config_meta_description', ''));
        $data['config_meta_keyword']     = $this->request->get('post.config_meta_keyword', Arr::get($store_info, 'config_meta_keyword', ''));

        $data['config_theme']  = $this->request->get('post.config_theme', Arr::get($store_info, 'config_theme', ''));

        $data['themes'] = array();

        $this->load->model('extension/extension');

        $extensions = $this->model_extension_extension->getInstalled('theme');

        foreach ($extensions as $code) {
            $this->load->language('extension/theme/' . $code);

            $data['themes'][] = array(
                'text'  => $this->language->get('heading_title'),
                'value' => $code
            );
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();
        $data['config_layout_id'] = $this->request->get('post.config_layout_id', Arr::get($store_info, 'config_layout_id', ''));
        $data['config_name']      = $this->request->get('post.config_name', Arr::get($store_info, 'config_name', ''));
        $data['config_owner']     = $this->request->get('post.config_owner', Arr::get($store_info, 'config_owner', ''));
        $data['config_address']   = $this->request->get('post.config_address', Arr::get($store_info, 'config_address', ''));
        $data['config_email']     = $this->request->get('post.config_email', Arr::get($store_info, 'config_email', ''));
        $data['config_telephone'] = $this->request->get('post.config_telephone', Arr::get($store_info, 'config_telephone', ''));
        $data['config_fax']       = $this->request->get('post.config_fax', Arr::get($store_info, 'config_fax', ''));
        $data['config_image']     = $this->request->get('post.config_image', Arr::get($store_info, 'config_image', ''));

        $this->load->model('tool/image');

        $data['thumb']       = $no_image_thumb = $this->model_tool_image->resize('no-image.png', 100, 100);
        $data['placeholder'] = $no_image_thumb;

        if ($this->request->has('post.config_image') && is_file(DIR_IMAGE . $this->request->get('post.config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->get('post.config_image'), 100, 100);
        } elseif (Arr::has($store_info, 'config_image') && is_file(DIR_IMAGE . Arr::get($store_info, 'config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize(Arr::get($store_info, 'config_image'), 100, 100);
        }

        $data['config_language'] = $this->request->get('post.config_logoconfig_language', Arr::get($store_info, 'config_language', $this->config->get('config_language')));
        $data['config_logo']     = $this->request->get('post.config_logo', Arr::get($store_info, 'config_logo', ''));
        $data['config_icon']     = $this->request->get('post.config_icon', Arr::get($store_info, 'config_icon', ''));

        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('catalog/information');
        $data['informations'] = $this->model_catalog_information->getInformations();

        $data['logo'] = $no_image_thumb;
        if ($this->request->has('post.config_logo') && is_file(DIR_IMAGE . $this->request->get('post.config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->request->get('post.config_logo'), 100, 100);
        } elseif (Arr::has($store_info, 'config_logo') && is_file(DIR_IMAGE . Arr::get($store_info, 'config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize(Arr::get($store_info, 'config_logo'), 100, 100);
        }

        $data['icon'] = $no_image_thumb;
        if ($this->request->has('post.config_icon') && is_file(DIR_IMAGE . $this->request->get('post.config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize($this->request->get('post.config_icon'), 100, 100);
        } elseif (Arr::has($store_info, 'config_icon') && is_file(DIR_IMAGE . Arr::get($store_info, 'config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize(Arr::get($store_info, 'config_icon'), 100, 100);
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/store_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->get('post.config_url')) {
            $this->error['url'] = $this->language->get('error_url');
        }

        if (!$this->request->get('post.config_meta_title')) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        if (!$this->request->get('post.config_name')) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->get('post.config_owner')) < 3) || (utf8_strlen($this->request->get('post.config_owner')) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }

        if ((utf8_strlen($this->request->get('post.config_address')) < 3) || (utf8_strlen($this->request->get('post.config_address')) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if ((utf8_strlen($this->request->get('post.config_email')) > 96) || !filter_var($this->request->get('post.config_email'), FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->get('post.config_telephone')) < 3) || (utf8_strlen($this->request->get('post.config_telephone')) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (
            !empty($this->request->get('post.config_customer_group_display'))
            && !in_array($this->request->get('post.config_customer_group_id'), $this->request->getArray('post.config_customer_group_display'))
        ) {
            $this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'setting/store')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('sale/order');

        foreach ($this->request->get('post.selected', []) as $store_id) {
            if (!$store_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);

            if ($store_total) {
                $this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
            }
        }

        return !$this->error;
    }
}
