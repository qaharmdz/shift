<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Mvc;

class ColumnLeft extends Mvc\Controller
{
    public function index()
    {
        if ($this->request->get('query.token', time()) == $this->session->get('token')) {
            $this->load->language('common/columnleft');

            $data['firstname']  = $this->user->get('firstname');
            $data['lastname']   = $this->user->get('lastname');
            $data['user_group'] = $this->user->get('usergroup');
            $data['image']      = '';

            // Create a 3 level menu array
            // Level 2 can not have children

            // Menu
            $data['menus'][] = array(
                'id'       => 'menu-dashboard',
                'icon'     => 'fa-dashboard',
                'name'     => $this->language->get('text_dashboard'),
                'href'     => $this->router->url('common/dashboard', 'token=' . $this->session->get('token')),
                'children' => array()
            );

            if ($this->user->hasPermission('access', 'catalog/information')) {
                $catalog[] = array(
                    'name'     => $this->language->get('text_information'),
                    'href'     => $this->router->url('catalog/information', 'token=' . $this->session->get('token')),
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
                    'href'     => $this->router->url('extension/installer', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'extension/extension')) {
                $extension[] = array(
                    'name'     => $this->language->get('text_extension'),
                    'href'     => $this->router->url('extension/extension', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'extension/event')) {
                $extension[] = array(
                    'name'     => $this->language->get('text_event'),
                    'href'     => $this->router->url('extension/event', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'extension/language')) {
                $extension[] = array(
                    'name'     => $this->language->get('text_language'),
                    'href'     => $this->router->url('extension/language', 'token=' . $this->session->get('token')),
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
                    'href'     => $this->router->url('design/layout', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }
            if ($this->user->hasPermission('access', 'design/banner')) {
                $design[] = array(
                    'name'     => $this->language->get('text_banner'),
                    'href'     => $this->router->url('design/banner', 'token=' . $this->session->get('token')),
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
                    'href'     => $this->router->url('setting/setting', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }

            $system[] = array(
                'name'     => $this->language->get('text_sites'),
                'href'     => $this->router->url('setting/site', 'token=' . $this->session->get('token')),
                'children' => array()
            );

            // Users
            $user = array();

            if ($this->user->hasPermission('access', 'user/user')) {
                $user[] = array(
                    'name'     => $this->language->get('text_users'),
                    'href'     => $this->router->url('user/user', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'user/userpermission')) {
                $user[] = array(
                    'name'     => $this->language->get('text_user_group'),
                    'href'     => $this->router->url('user/userpermission', 'token=' . $this->session->get('token')),
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

            // Tools
            $tool = array();

            if ($this->user->hasPermission('access', 'tool/backup')) {
                $tool[] = array(
                    'name'     => $this->language->get('text_backup'),
                    'href'     => $this->router->url('tool/backup', 'token=' . $this->session->get('token')),
                    'children' => array()
                );
            }

            if ($this->user->hasPermission('access', 'tool/log')) {
                $tool[] = array(
                    'name'     => $this->language->get('text_log'),
                    'href'     => $this->router->url('tool/log', 'token=' . $this->session->get('token')),
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
