<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Module;

use Shift\System\Mvc;

class Site extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/site');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if ($this->request->is('POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('site', $this->request->get('post'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('extension/extension' . '&type=module'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_admin'] = $this->language->get('entry_admin');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->router->url('extension/extension' . '&type=module')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('extension/module/site')
        );

        $data['action'] = $this->router->url('extension/module/site');

        $data['cancel'] = $this->router->url('extension/extension' . '&type=module');

        if ($this->request->has('post.site_admin')) {
            $data['site_admin'] = $this->request->get('post.site_admin');
        } else {
            $data['site_admin'] = $this->config->get('site_admin');
        }

        if ($this->request->has('post.site_status')) {
            $data['site_status'] = $this->request->get('post.site_status');
        } else {
            $data['site_status'] = $this->config->get('site_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/site', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/site')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
