<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Dashboard;

use Shift\System\Core\Mvc;

class Online extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/dashboard/online');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if ($this->request->is('POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('dashboard_online', $this->request->get('post'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=dashboard'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

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
            'href' => $this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('extension/dashboard/online', 'token=' . $this->session->get('token'))
        );

        $data['action'] = $this->router->url('extension/dashboard/online', 'token=' . $this->session->get('token'));

        $data['cancel'] = $this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=dashboard');

        if ($this->request->has('post.dashboard_online_width')) {
            $data['dashboard_online_width'] = $this->request->get('post.dashboard_online_width');
        } else {
            $data['dashboard_online_width'] = $this->config->get('dashboard_online_width');
        }

        $data['columns'] = array();

        for ($i = 3; $i <= 12; $i++) {
            $data['columns'][] = $i;
        }

        if ($this->request->has('post.dashboard_online_status')) {
            $data['dashboard_online_status'] = $this->request->get('post.dashboard_online_status');
        } else {
            $data['dashboard_online_status'] = $this->config->get('dashboard_online_status');
        }

        if ($this->request->has('post.dashboard_online_sort_order')) {
            $data['dashboard_online_sort_order'] = $this->request->get('post.dashboard_online_sort_order');
        } else {
            $data['dashboard_online_sort_order'] = $this->config->get('dashboard_online_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/dashboard/online_form', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/analytics/google_analytics')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function dashboard()
    {
        $this->load->language('extension/dashboard/online');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->get('token');

        // Customers Online
        $data['total'] = 101;
        $data['online'] = $this->router->url('report/customer_online', 'token=' . $this->session->get('token'));

        return $this->load->view('extension/dashboard/online_info', $data);
    }
}
