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
            'url'  => $this->router->url('page/dashboard'),
        ]);

        // Content
        $content = [];

        if ($this->user->hasPermission('access', 'catalog/information')) {
            $content[] = $this->nav([
                'name' => $this->language->get('sidebarleft.information'),
                'url'  => $this->router->url('catalog/information'),
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
                'url'  => $this->router->url('extension/installer'),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/extension')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.extension'),
                'url'  => $this->router->url('extension/extension'),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.event'),
                'url'  => $this->router->url('extension/event'),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('sidebarleft.language'),
                'url'  => $this->router->url('extension/language'),
            ]);
        }

        if ($extension) {
            $data['menus'][200] = $this->nav([
                'id'   => 'menu-extension',
                'name' => $this->language->get('sidebarleft.extension'),
                'subs' => $extension
            ]);
        }

        // Tools
        $tool = [];

        if ($this->user->hasPermission('access', 'tool/layout')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('sidebarleft.layout'),
                'url'  => $this->router->url('tool/layout'),
            ]);
        }
        if ($this->user->hasPermission('access', 'design/banner')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('sidebarleft.banner'),
                'url'  => $this->router->url('design/banner'),
            ]);
        }

        if ($tool) {
            $data['menus'][300] = $this->nav([
                'id'   => 'menu-tool',
                'name' => $this->language->get('sidebarleft.tool'),
                'subs' => $tool
            ]);
        }

        // Users
        $user = [];

        if ($this->user->hasPermission('access', 'account/user')) {
            $user[] = $this->nav([
                'name' => $this->language->get('sidebarleft.user'),
                'url'  => $this->router->url('account/user'),
            ]);
        }

        if ($this->user->hasPermission('access', 'account/usergroup')) {
            $user[] = $this->nav([
                'name' => $this->language->get('sidebarleft.user_group'),
                'url'  => $this->router->url('account/usergroup'),
            ]);
        }

        if ($user) {
            $data['menus'][400] = $this->nav([
                'id'   => 'menu-design',
                'name' => $this->language->get('sidebarleft.account'),
                'subs' => $user
            ]);
        }

        // System
        $system = [];

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.setting'),
                'url'  => $this->router->url('setting/setting'),
            ]);
        }

        if ($this->user->hasPermission('access', 'setting/site')) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.sites'),
                'url'  => $this->router->url('setting/site'),
            ]);
        }

        if ($this->user->hasPermissions('access', ['tool/backup', 'tool/log'])) {
            $system[] = $this->nav([
                'type' => 'header',
                'name' => $this->language->get('sidebarleft.maintenance'),
            ]);
        }

        if ($this->user->hasPermission('access', 'tool/backup')) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.backup_restore'),
                'url'  => $this->router->url('tool/backup'),
            ]);
        }

        if ($this->user->hasPermission('access', 'tool/log')) {
            $system[] = $this->nav([
                'name' => $this->language->get('sidebarleft.log'),
                'url'  => $this->router->url('tool/log'),
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
