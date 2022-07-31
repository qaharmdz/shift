<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Site extends Mvc\Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/site');

        $this->load->model('setting/setting');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/site');

        if ($this->request->is('POST') && $this->validateForm()) {
            $site_id = $this->model_setting_site->addSite($this->request->get('post'));

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('system', 'site', $this->request->get('post'), (int)$site_id);

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('setting/site', 'token=' . $this->session->get('token')));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/site');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_setting_site->editSite($this->request->getInt('query.site_id', 0), $this->request->get('post'));

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('system', 'site', $this->request->get('post'), $this->request->getInt('query.site_id', 0));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('setting/site', 'token=' . $this->session->get('token')));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/site');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            $this->load->model('setting/setting');

            foreach ($this->request->get('post.selected') as $site_id) {
                $this->model_setting_site->deleteSite($site_id);

                $this->model_setting_setting->deleteSetting('system', 'site', (int)$site_id);
            }

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('setting/site', 'token=' . $this->session->get('token')));
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
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('setting/site', 'token=' . $this->session->get('token'))
        );

        $data['add'] = $this->router->url('setting/site/add', 'token=' . $this->session->get('token'));
        $data['delete'] = $this->router->url('setting/site/delete', 'token=' . $this->session->get('token'));

        $data['sites'] = array();
        $site_total    = $this->model_setting_site->getTotalSites();
        $results       = $this->model_setting_site->getSites();

        foreach ($results as $result) {
            $data['sites'][] = array(
                'site_id' => $result['site_id'],
                'name'    => $result['name'],
                'url'     => $result['url_host'],
                'edit'    => $this->router->url('setting/site/edit', 'token=' . $this->session->get('token') . '&site_id=' . $result['site_id'])
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

        $this->response->setOutput($this->load->view('setting/site_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.site_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');

        $data['entry_logo'] = $this->language->get('entry_logo');
        $data['entry_icon'] = $this->language->get('entry_icon');

        $data['help_url'] = $this->language->get('help_url');
        $data['help_icon'] = $this->language->get('help_icon');

        $data['entry_language'] = $this->language->get('entry_language');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_maintenance'] = $this->language->get('entry_maintenance');
        $data['entry_theme'] = $this->language->get('entry_theme');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_site'] = $this->language->get('tab_site');
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
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('setting/site', 'token=' . $this->session->get('token'))
        );

        if (!$this->request->has('query.site_id')) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_add'),
                'href' => $this->router->url('setting/site/add', 'token=' . $this->session->get('token'))
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_edit') . ' #' . $this->request->get('query.site_id'),
                'href' => $this->router->url('setting/site/edit', 'token=' . $this->session->get('token') . '&site_id=' . $this->request->get('query.site_id'))
            );
        }

        $data['success'] = $this->session->pull('flash.success');

        if (!$this->request->has('query.site_id')) {
            $data['action'] = $this->router->url('setting/site/add', 'token=' . $this->session->get('token'));
        } else {
            $data['action'] = $this->router->url('setting/site/edit', 'token=' . $this->session->get('token') . '&site_id=' . $this->request->get('query.site_id'));
        }

        $data['cancel'] = $this->router->url('setting/site', 'token=' . $this->session->get('token'));

        // TODO: add array_replace_recursive() before POST save add/ edit
        $this->load->config('setting/site');

        $site_info = [];
        if ($this->request->has('query.site_id') && !$this->request->is('POST')) {
            $this->load->model('setting/setting');

            $site_info = $this->model_setting_setting->getSetting('system', 'site', $this->request->getInt('query.site_id', 0));
        }

        $data['setting'] = array_replace_recursive(
            $this->config->getArray('setting.site.form'),
            $site_info,
            $this->request->getArray('post')
        );

        $data['token'] = $this->session->get('token');

        $this->load->model('extension/extension');
        $extensions = $this->model_extension_extension->getInstalled('theme');

        $data['themes'] = array();
        foreach ($extensions as $code) {
            $this->load->language('extension/theme/' . $code);

            $data['themes'][] = array(
                'text'  => $this->language->get('heading_title'),
                'value' => $code
            );
        }

        $this->load->model('design/layout');
        $data['layouts'] = $this->model_design_layout->getLayouts();

        $this->load->model('extension/language');
        $data['languages'] = $this->model_extension_language->getLanguages();

        $data['placeholder'] = $no_image_thumb = $this->image->construct('image/no-image.png', 100, 100);

        $data['logo'] = $no_image_thumb;
        if ($this->request->has('post.logo') && is_file(DIR_MEDIA . $this->request->get('post.logo'))) {
            $data['logo'] = $this->image->construct($this->request->get('post.logo'), 100, 100);
        } elseif (Arr::has($site_info, 'logo') && is_file(DIR_MEDIA . Arr::get($site_info, 'logo'))) {
            $data['logo'] = $this->image->construct(Arr::get($site_info, 'logo'), 100, 100);
        }

        $data['icon'] = $no_image_thumb;
        if ($this->request->has('post.icon') && is_file(DIR_MEDIA . $this->request->get('post.icon'))) {
            $data['icon'] = $this->image->construct($this->request->get('post.icon'), 100, 100);
        } elseif (Arr::has($site_info, 'icon') && is_file(DIR_MEDIA . Arr::get($site_info, 'icon'))) {
            $data['icon'] = $this->image->construct(Arr::get($site_info, 'icon'), 100, 100);
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/site_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'setting/site')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->get('post.name')) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->get('post.url_host')) {
            $this->error['url'] = $this->language->get('error_url');
        }

        if (!$this->request->get('post.meta_title')) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        if ((utf8_strlen($this->request->get('post.email')) > 96) || !filter_var($this->request->get('post.email'), FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'setting/site')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->getArray('post.selected', []) as $site_id) {
            if (!$site_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }
        }

        return !$this->error;
    }

    public function theme()
    {
        $url_site = $this->config->get('env.url_site');

        $theme = basename($this->request->get('query.theme', ''));

        if (is_file(DIR_MEDIA . 'theme/' . $theme . '.png')) {
            $this->response->setOutput($url_site . 'image/theme/' . $theme . '.png');
        } else {
            $this->response->setOutput($url_site . 'image/no-image.png');
        }
    }
}
