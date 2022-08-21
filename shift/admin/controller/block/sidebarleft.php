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
        $data['menus'][0] = $this->nav([
            'id'   => 'menu-dashboard',
            'name' => $this->language->get('sidebarleft.dashboard'),
            'url'  => $this->router->url('common/dashboard', 'token=' . $this->session->get('token')),
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
            $data['menus'][100] = $this->nav([
                'id'   => 'menu-content',
                'name' => $this->language->get('sidebarleft.content'),
                'subs' => $content
            ]);
        }

        // Extension
        $extension = [];

        if ($this->user->hasPermission('access', 'extension/installer')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.installer'),
                'url'  => $this->router->url('extension/installer', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/extension')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.extension'),
                'url'  => $this->router->url('extension/extension', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.event'),
                'url'  => $this->router->url('extension/event', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.language'),
                'url'  => $this->router->url('extension/language', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($extension) {
            $data['menus'][200] = $this->nav([
                'id'   => 'menu-extension',
                'name' => $this->language->get('sidebarleft.extension'),
                'subs' => $extension
            ]);
        }

        // Design
        $design = [];

        if ($this->user->hasPermission('access', 'design/layout')) {
            $design[] = $this->nav([
                'name' => $this->language->get('sidebarleft.layout'),
                'url'  => $this->router->url('design/layout', 'token=' . $this->session->get('token')),
            ]);
        }
        if ($this->user->hasPermission('access', 'design/banner')) {
            $design[] = $this->nav([
                'name' => $this->language->get('sidebarleft.banner'),
                'url'  => $this->router->url('design/banner', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($design) {
            $data['menus'][300] = $this->nav([
                'id'       => 'menu-design',
                'name'     => $this->language->get('sidebarleft.design'),
                'subs' => $design
            ]);
        }

        // System
        $system = [];

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.setting'),
                'url'  => $this->router->url('setting/setting', 'token=' . $this->session->get('token')),
            ]);
        }

        $system[] = $this->nav([
            'name' => $this->language->get('sidebarleft.sites'),
            'url'  => $this->router->url('setting/site', 'token=' . $this->session->get('token')),
        ]);

        // Users
        $user = [];

        if ($this->user->hasPermission('access', 'user/user')) {
            $user[] = $this->nav([
                'name' => $this->language->get('sidebarleft.users'),
                'url'  => $this->router->url('user/user', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($this->user->hasPermission('access', 'user/userpermission')) {
            $user[] = $this->nav([
                'name' => $this->language->get('sidebarleft.user_group'),
                'url'  => $this->router->url('user/userpermission', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($user) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.users'),
                'url'  => '',
                'subs' => $user
            ]);
        }

        // Tools
        $tool = [];

        if ($this->user->hasPermission('access', 'tool/backup')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('sidebarleft.backup'),
                'url'  => $this->router->url('tool/backup', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($this->user->hasPermission('access', 'tool/log')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('sidebarleft.log'),
                'url'  => $this->router->url('tool/log', 'token=' . $this->session->get('token')),
            ]);
        }

        if ($tool) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.tools'),
                'subs' => $tool
            ]);
        }

        if ($system) {
            $data['menus'][1000] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('sidebarleft.system'),
                'subs' => $system
            ]);
        }

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
