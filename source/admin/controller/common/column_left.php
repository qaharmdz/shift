<?php

declare(strict_types=1);

class ControllerCommonColumnLeft extends Controller
{
    public function index()
    {
        if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->load->language('common/column_left');

            $this->load->model('user/user');

            $this->load->model('tool/image');

            $user_info = $this->model_user_user->getUser($this->user->getId());

            if ($user_info) {
                $data['firstname'] = $user_info['firstname'];
                $data['lastname'] = $user_info['lastname'];

                $data['user_group'] = $user_info['user_group'];

                if (is_file(DIR_IMAGE . $user_info['image'])) {
                    $data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
                } else {
                    $data['image'] = '';
                }
            } else {
                $data['firstname'] = '';
                $data['lastname'] = '';
                $data['user_group'] = '';
                $data['image'] = '';
            }

            // Create a 3 level menu array
            // Level 2 can not have children

            // Menu
            $data['menus'][] = array(
                'id'       => 'menu-dashboard',
                'icon'     => 'fa-dashboard',
                'name'     => $this->language->get('text_dashboard'),
                'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
                'children' => array()
            );

            if ($this->user->hasPermission('access', 'catalog/information')) {
                $catalog[] = array(
                    'name'     => $this->language->get('text_information'),
                    'href'     => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($catalog) {
                $data['menus'][] = array(
                    'id'       => 'menu-catalog',
                    'icon'     => 'fa-tags',
                    'name'     => $this->language->get('text_catalog'),
                    'href'     => '',
                    'children' => $catalog
                );
            }


            // Extension
            $extension = array();

            if ($this->user->hasPermission('access', 'extension/installer')) {
                $extension[] = array(
                    'name'     => $this->language->get('text_installer'),
                    'href'     => $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'extension/extension')) {
                $extension[] = array(
                    'name'     => $this->language->get('text_extension'),
                    'href'     => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'extension/event')) {
                $extension[] = array(
                    'name'     => $this->language->get('text_event'),
                    'href'     => $this->url->link('extension/event', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($extension) {
                $data['menus'][] = array(
                    'id'       => 'menu-extension',
                    'icon'     => 'fa-puzzle-piece',
                    'name'     => $this->language->get('text_extension'),
                    'href'     => '',
                    'children' => $extension
                );
            }

            // Design
            $design = array();

            if ($this->user->hasPermission('access', 'design/layout')) {
                $design[] = array(
                    'name'     => $this->language->get('text_layout'),
                    'href'     => $this->url->link('design/layout', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }
            if ($this->user->hasPermission('access', 'design/banner')) {
                $design[] = array(
                    'name'     => $this->language->get('text_banner'),
                    'href'     => $this->url->link('design/banner', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($design) {
                $data['menus'][] = array(
                    'id'       => 'menu-design',
                    'icon'     => 'fa-television',
                    'name'     => $this->language->get('text_design'),
                    'href'     => '',
                    'children' => $design
                );
            }

            // System
            $system = array();

            if ($this->user->hasPermission('access', 'setting/setting')) {
                $system[] = array(
                    'name'     => $this->language->get('text_setting'),
                    'href'     => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            // Users
            $user = array();

            if ($this->user->hasPermission('access', 'user/user')) {
                $user[] = array(
                    'name'     => $this->language->get('text_users'),
                    'href'     => $this->url->link('user/user', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'user/user_permission')) {
                $user[] = array(
                    'name'     => $this->language->get('text_user_group'),
                    'href'     => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($user) {
                $system[] = array(
                    'name'     => $this->language->get('text_users'),
                    'href'     => '',
                    'children' => $user
                );
            }

            // Localisation
            $localisation = array();

            if ($this->user->hasPermission('access', 'localisation/language')) {
                $localisation[] = array(
                    'name'     => $this->language->get('text_language'),
                    'href'     => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($localisation) {
                $system[] = array(
                    'name'     => $this->language->get('text_localisation'),
                    'href'     => '',
                    'children' => $localisation
                );
            }

            // Tools
            $tool = array();

            if ($this->user->hasPermission('access', 'tool/upload')) {
                $tool[] = array(
                    'name'     => $this->language->get('text_upload'),
                    'href'     => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'tool/backup')) {
                $tool[] = array(
                    'name'     => $this->language->get('text_backup'),
                    'href'     => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'tool/log')) {
                $tool[] = array(
                    'name'     => $this->language->get('text_log'),
                    'href'     => $this->url->link('tool/log', 'token=' . $this->session->data['token'], true),
                    'children' => array()
                );
            }

            if ($tool) {
                $system[] = array(
                    'name'     => $this->language->get('text_tools'),
                    'href'     => '',
                    'children' => $tool
                );
            }

            if ($system) {
                $data['menus'][] = array(
                    'id'       => 'menu-system',
                    'icon'     => 'fa-cog',
                    'name'     => $this->language->get('text_system'),
                    'href'     => '',
                    'children' => $system
                );
            }

            return $this->load->view('common/column_left', $data);
        }
    }
}
