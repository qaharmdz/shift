<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Theme;

use Shift\System\Core\Mvc;

class Base extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->config('theme/base');
        $this->load->language('extension/theme/base');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if ($this->request->is('POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('theme', 'base', $this->request->get('post'), $this->request->getInt('query.site_id'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=theme'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_general'] = $this->language->get('text_general');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

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
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=theme')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('extension/theme/base', 'token=' . $this->session->get('token') . '&site_id=' . $this->request->get('query.site_id'))
        );

        $data['action'] = $this->router->url('extension/theme/base', 'token=' . $this->session->get('token') . '&site_id=' . $this->request->get('query.site_id'));
        $data['cancel'] = $this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=theme');

        $theme_info = [];
        if ($this->request->has('query.site_id') && !$this->request->is('POST')) {
            $theme_info = $this->model_setting_setting->getSetting('theme', 'base', $this->request->getInt('query.site_id'));
        }

        $data['setting'] = array_replace_recursive(
            $this->config->getArray('theme.base.form'),
            $theme_info,
            $this->request->getArray('post')
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/theme/base', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/theme/base')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install() {}

    public function uninstall() {}
}
