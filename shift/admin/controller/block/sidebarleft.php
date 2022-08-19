<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

use Shift\System\Mvc;

class SidebarLeft extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/sidebarleft', 'sidebarleft');

        // Home
        $data['menus'][] = $this->nav([
            'id'   => 'menu-dashboard',
            'name' => $this->language->get('sidebarleft.dashboard'),
            'url'  => $this->router->url('common/dashboard'),
        ]);

        // Content
        $content = [];

        if ($this->user->hasPermission('access', 'catalog/information')) {
            $content[] = $this->nav([
                'name' => $this->language->get('sidebarleft.information'),
                'url'  => $this->router->url('catalog/information', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($content) {
            $data['menus'][] = $this->nav([
                'id'   => 'menu-catalog',
                'name' => $this->language->get('sidebarleft.content'),
                'subs' => $content
            ]);
        }

        /*
        // Extension
        $extension = array();

        if ($this->user->hasPermission('access', 'extension/installer')) {
            $extension[] = array(
                'name'     => $this->language->get('text_installer'),
                'url'     => $this->router->url('extension/installer', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/extension')) {
            $extension[] = array(
                'name'     => $this->language->get('text_extension'),
                'url'     => $this->router->url('extension/extension', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = array(
                'name'     => $this->language->get('text_event'),
                'url'     => $this->router->url('extension/event', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = array(
                'name'     => $this->language->get('text_language'),
                'url'     => $this->router->url('extension/language', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($extension) {
            $data['menus'][] = array(
                'id'       => 'menu-extension',
                'name'     => $this->language->get('text_extension'),
                'url'     => '',
                'children' => $extension
            );
        }

        // Design
        $design = array();

        if ($this->user->hasPermission('access', 'design/layout')) {
            $design[] = array(
                'name'     => $this->language->get('text_layout'),
                'url'     => $this->router->url('design/layout', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'design/banner')) {
            $design[] = array(
                'name'     => $this->language->get('text_banner'),
                'url'     => $this->router->url('design/banner', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($design) {
            $data['menus'][] = array(
                'id'       => 'menu-design',
                'name'     => $this->language->get('text_design'),
                'url'     => '',
                'children' => $design
            );
        }

        // System
        $system = array();

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = array(
                'name'     => $this->language->get('text_setting'),
                'url'     => $this->router->url('setting/setting', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        $system[] = array(
            'name'     => $this->language->get('text_sites'),
            'url'     => $this->router->url('setting/site', 'token=' . $this->session->get('token')),
            'children' => array()
        );

        // Users
        $user = array();

        if ($this->user->hasPermission('access', 'user/user')) {
            $user[] = array(
                'name'     => $this->language->get('text_users'),
                'url'     => $this->router->url('user/user', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'user/userpermission')) {
            $user[] = array(
                'name'     => $this->language->get('text_user_group'),
                'url'     => $this->router->url('user/userpermission', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($user) {
            $system[] = array(
                'name'     => $this->language->get('text_users'),
                'url'     => '',
                'children' => $user
            );
        }

        // Tools
        $tool = array();

        if ($this->user->hasPermission('access', 'tool/backup')) {
            $tool[] = array(
                'name'     => $this->language->get('text_backup'),
                'url'     => $this->router->url('tool/backup', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'tool/log')) {
            $tool[] = array(
                'name'     => $this->language->get('text_log'),
                'url'     => $this->router->url('tool/log', 'token=' . $this->session->get('token')),
                'children' => array()
            );
        }

        if ($tool) {
            $system[] = array(
                'name'     => $this->language->get('text_tools'),
                'url'     => '',
                'children' => $tool
            );
        }

        if ($system) {
            $data['menus'][] = array(
                'id'       => 'menu-system',
                'name'     => $this->language->get('text_system'),
                'url'     => '',
                'children' => $system
            );
        }
        */

        return $this->load->view('block/sidebarleft', $data);
    }

    /**
     * Standarize navigation item
     *
     * @param  array  $params
     * @return array
     */
    protected function nav(array $params): array
    {
        return [
            'id'   => $params['id'] ?? '',
            'type' => $params['type'] ?? 'link', // Option: header, divider, link
            'icon' => $params['icon'] ?? '',
            'name' => $params['name'] ?? '',
            'url'  => $params['url'] ?? '',
            'subs' => $params['subs'] ?? [],
        ];
    }
}
