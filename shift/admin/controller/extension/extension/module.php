<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Extension;

use Shift\System\Core\Mvc;

class Module extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/extension/module');

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        $this->getList();
    }

    public function install()
    {
        $this->load->language('extension/extension/module');

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        if ($this->validate()) {
            $this->model_extension_extension->install('module', $this->request->get('query.extension'));

            $this->load->model('user/usergroup');

            $this->model_user_usergroup->addPermission($this->user->getGroupId(), 'access', 'extension/module/' . $this->request->get('query.extension'));
            $this->model_user_usergroup->addPermission($this->user->getGroupId(), 'modify', 'extension/module/' . $this->request->get('query.extension'));

            // Call install method if it exsits
            $this->load->controller('extension/module/' . $this->request->get('query.extension') . '/install');

            $this->session->set('flash.success', $this->language->get('text_success'));
        } else {
            $this->session->set('flash.error', $this->error['warning']);
        }

        $this->getList();
    }

    public function uninstall()
    {
        $this->load->language('extension/extension/module');

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('module', $this->request->get('query.extension'));

            $this->model_extension_module->deleteModulesByCode($this->request->get('query.extension'));

            // Call uninstall method if it exsits
            $this->load->controller('extension/module/' . $this->request->get('query.extension') . '/uninstall');

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    public function add()
    {
        $this->load->language('extension/extension/module');

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        if ($this->validate()) {
            $this->load->language('module' . '/' . $this->request->get('query.extension'));

            $this->model_extension_module->addModule($this->request->get('query.extension'), $this->language->get('heading_title'));

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    public function delete()
    {
        $this->load->language('extension/extension/module');

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        if ($this->request->has('query.module_id') && $this->validate()) {
            $this->model_extension_module->deleteModule($this->request->get('query.module_id'));

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_layout'] = sprintf($this->language->get('text_layout'), $this->router->url('design/layout', 'token=' . $this->session->get('token')));
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_install'] = $this->language->get('button_install');
        $data['button_uninstall'] = $this->language->get('button_uninstall');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['success'] = $this->session->pull('flash.success');

        $extensions = $this->model_extension_extension->getInstalled('module');

        foreach ($extensions as $key => $value) {
            if (!is_file(DIR_APPLICATION . 'controller/extension/module/' . $value . '.php') && !is_file(DIR_APPLICATION . 'controller/module/' . $value . '.php')) {
                $this->model_extension_extension->uninstall('module', $value);

                unset($extensions[$key]);

                $this->model_extension_module->deleteModulesByCode($value);
            }
        }

        $data['extensions'] = array();

        // Compatibility code for old extension folders
        $files = glob(DIR_APPLICATION . 'controller/{extension/module,module}/*.php', GLOB_BRACE);

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('extension/module/' . $extension);

                $module_data = array();

                $modules = $this->model_extension_module->getModulesByCode($extension);

                foreach ($modules as $module) {
                    $module_data[] = array(
                        'module_id' => $module['module_id'],
                        'name'      => $module['name'],
                        'edit'      => $this->router->url('extension/module/' . $extension, 'token=' . $this->session->get('token') . '&module_id=' . $module['module_id']),
                        'delete'    => $this->router->url('extension/extension/module/delete', 'token=' . $this->session->get('token') . '&module_id=' . $module['module_id'])
                    );
                }

                $data['extensions'][] = array(
                    'name'      => $this->language->get('heading_title'),
                    'module'    => $module_data,
                    'install'   => $this->router->url('extension/extension/module/install', 'token=' . $this->session->get('token') . '&extension=' . $extension),
                    'uninstall' => $this->router->url('extension/extension/module/uninstall', 'token=' . $this->session->get('token') . '&extension=' . $extension),
                    'installed' => in_array($extension, $extensions),
                    'edit'      => $this->router->url('extension/module/' . $extension, 'token=' . $this->session->get('token'))
                );
            }
        }

        $sort_order = array();

        foreach ($data['extensions'] as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $data['extensions']);

        $this->response->setOutput($this->load->view('extension/extension/module', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
