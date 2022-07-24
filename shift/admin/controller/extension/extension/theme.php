<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension\Extension;

use Shift\System\Mvc;

class Theme extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/extension/theme');

        $this->load->model('extension/extension');

        $this->getList();
    }

    public function install()
    {
        $this->load->language('extension/extension/feed');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->install('theme', $this->request->get('query.extension'));

            $this->load->model('user/usergroup');

            $this->model_user_usergroup->addPermission($this->user->getGroupId(), 'access', 'extension/theme/' . $this->request->get('query.extension'));
            $this->model_user_usergroup->addPermission($this->user->getGroupId(), 'modify', 'extension/theme/' . $this->request->get('query.extension'));

            $this->load->controller('extension/theme/' . $this->request->get('query.extension') . '/install');

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    public function uninstall()
    {
        $this->load->language('extension/extension/theme');

        $this->load->model('extension/extension');

        if ($this->validate()) {
            $this->model_extension_extension->uninstall('theme', $this->request->get('query.extension'));

            // Call uninstall method if it exsits
            $this->load->controller('extension/theme/' . $this->request->get('query.extension') . '/uninstall');

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_status'] = $this->language->get('column_status');
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

        // Validate installed themes
        $extensions = $this->model_extension_extension->getInstalled('theme');

        foreach ($extensions as $key => $value) {
            if (!is_file(DIR_APPLICATION . 'controller/extension/theme/' . $value . '.php') && !is_file(DIR_APPLICATION . 'controller/theme/' . $value . '.php')) {
                $this->model_extension_extension->uninstall('theme', $value);
                unset($extensions[$key]);
            }
        }

        $this->load->model('setting/site');
        $this->load->model('setting/setting');

        $sites = $this->model_setting_site->getSites();

        $data['extensions'] = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/theme/*.php', GLOB_BRACE);

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('extension/theme/' . $extension);

                $site_data = array();
                foreach ($sites as $site) {
                    $site_data[] = array(
                        'name'   => $site['name'],
                        'edit'   => $this->router->url('extension/theme/' . $extension, 'token=' . $this->session->get('token') . '&site_id=' . $site['site_id']),
                        'status' => $this->model_setting_setting->getSettingValue('theme', $extension, 'status', $site['site_id']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
                    );
                }

                $data['extensions'][] = array(
                    'name'      => $this->language->get('heading_title'),
                    'install'   => $this->router->url('extension/extension/theme/install', 'token=' . $this->session->get('token') . '&extension=' . $extension),
                    'uninstall' => $this->router->url('extension/extension/theme/uninstall', 'token=' . $this->session->get('token') . '&extension=' . $extension),
                    'installed' => in_array($extension, $extensions),
                    'site'      => $site_data
                );
            }
        }

        $this->response->setOutput($this->load->view('extension/extension/theme', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/extension/theme')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
