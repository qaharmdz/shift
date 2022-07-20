<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Extension;

use Shift\System\Core\Mvc;

class Dashboard extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/extension/dashboard');

        $this->load->model('extension/extension');

        $this->getList();
    }

    public function install()
    {
        $this->load->language('extension/extension/dashboard');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->install('dashboard', 'dashboard_' . $this->request->get('query.extension'));

            $this->load->model('user/usergroup');

            $this->model_user_usergroup->addPermission($this->user->getGroupId(), 'access', 'extension/dashboard/' . $this->request->get('query.extension'));
            $this->model_user_usergroup->addPermission($this->user->getGroupId(), 'modify', 'extension/dashboard/' . $this->request->get('query.extension'));

            $this->load->controller('extension/dashboard/' . $this->request->get('query.extension') . '/install');

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    public function uninstall()
    {
        $this->load->language('extension/extension/dashboard');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('dashboard', 'dashboard_' . $this->request->get('query.extension'));

            // Call uninstall method if it exsits
            $this->load->controller('extension/dashboard/' . $this->request->get('query.extension') . '/uninstall');

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_width'] = $this->language->get('column_width');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_install'] = $this->language->get('button_install');
        $data['button_uninstall'] = $this->language->get('button_uninstall');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['success'] = $this->session->pull('flash.success');

        $extensions = $this->model_extension_extension->getInstalled('dashboard');

        foreach ($extensions as $key => $value) {
            if (!is_file(DIR_APPLICATION . 'controller/extension/dashboard/' . $value . '.php')) {
                $this->model_extension_extension->uninstall('dashboard', $value);

                unset($extensions[$key]);
            }
        }

        $data['extensions'] = array();

        // Compatibility code for old extension folders
        $files = glob(DIR_APPLICATION . 'controller/extension/dashboard/*.php', GLOB_BRACE);

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                // Compatibility code for old extension folders
                $this->load->language('extension/dashboard/' . $extension);

                $data['extensions'][] = array(
                    'name'       => $this->language->get('heading_title'),
                    'width'      => $this->config->get('dashboard_' . $extension . '_width'),
                    'status'     => $this->config->get('dashboard_' . $extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'sort_order' => $this->config->get('dashboard_' . $extension . '_sort_order'),
                    'install'    => $this->router->url('extension/extension/dashboard/install', 'token=' . $this->session->get('token') . '&extension=' . $extension),
                    'uninstall'  => $this->router->url('extension/extension/dashboard/uninstall', 'token=' . $this->session->get('token') . '&extension=' . $extension),
                    'installed'  => in_array($extension, $extensions),
                    'edit'       => $this->router->url('extension/dashboard/' . $extension, 'token=' . $this->session->get('token'))
                );
            }
        }

        $this->response->setOutput($this->load->view('extension/extension/dashboard', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/extension/dashboard')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
