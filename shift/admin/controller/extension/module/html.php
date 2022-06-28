<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Module;

use Shift\System\Core\Mvc;

class Html extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/html');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');

        if ($this->request->is('POST') && $this->validate()) {
            if (!$this->request->has('query.module_id')) {
                $this->model_extension_module->addModule('html', $this->request->get('post'));
            } else {
                $this->model_extension_module->editModule($this->request->get('query.module_id'), $this->request->get('post'));
            }

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=module'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_status'] = $this->language->get('entry_status');

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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=module')
        );

        if (!$this->request->has('query.module_id')) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->router->url('extension/module/html', 'token=' . $this->session->get('token'))
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->router->url('extension/module/html', 'token=' . $this->session->get('token') . '&module_id=' . $this->request->get('query.module_id'))
            );
        }

        if (!$this->request->has('query.module_id')) {
            $data['action'] = $this->router->url('extension/module/html', 'token=' . $this->session->get('token'));
        } else {
            $data['action'] = $this->router->url('extension/module/html', 'token=' . $this->session->get('token') . '&module_id=' . $this->request->get('query.module_id'));
        }

        $data['cancel'] = $this->router->url('extension/extension', 'token=' . $this->session->get('token') . '&type=module');

        if ($this->request->has('query.module_id') && !$this->request->is('POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get('query.module_id'));
        }

        if ($this->request->has('post.name')) {
            $data['name'] = $this->request->get('post.name');
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if ($this->request->has('post.module_description')) {
            $data['module_description'] = $this->request->get('post.module_description');
        } elseif (!empty($module_info)) {
            $data['module_description'] = $module_info['module_description'];
        } else {
            $data['module_description'] = array();
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if ($this->request->has('post.status')) {
            $data['status'] = $this->request->get('post.status');
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/html', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/html')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->get('post.name')) < 3) || (utf8_strlen($this->request->get('post.name')) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
}
