<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Adminnav\Admin\Controller;

use Shift\System\Mvc;

class Navigation extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extensions/module/adminnav/navigation', 'nav');

        // Home
        $data['menus'][0] = $this->nav([
            'id'   => 'menu-dashboard',
            'name' => $this->language->get('nav.dashboard'),
            'url'  => $this->router->url('page/dashboard'),
        ]);

        // Content
        $content = [];

        if ($this->user->hasPermission('access', 'content/post')) {
            $content[] = $this->nav([
                'name' => $this->language->get('nav.post'),
                'url'  => $this->router->url('content/post'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/category')) {
            $content[] = $this->nav([
                'name' => $this->language->get('nav.category'),
                'url'  => $this->router->url('content/category'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/tag')) {
            $content[] = $this->nav([
                'name' => $this->language->get('nav.tag'),
                'url'  => $this->router->url('content/tag'),
            ]);
        }

        if ($content) {
            $data['menus'][100] = $this->nav([
                'id'   => 'menu-content',
                'name' => $this->language->get('nav.content'),
                'subs' => $content
            ]);
        }

        // Extension
        $extension = [];

        if ($this->user->hasPermission('access', 'extension/installer')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.installer'),
                'url'  => $this->router->url('extension/installer'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/manage')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.manage'),
                'url'  => $this->router->url('extension/manage'),
            ]);
        }
        if (
            $this->user->hasPermission('access', 'extension/installer')
            || $this->user->hasPermission('access', 'extension/manage')
        ) {
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/plugin')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.plugin'),
                'url'  => $this->router->url('extension/plugin'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/module')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.module'),
                'url'  => $this->router->url('extension/module'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/theme')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.theme'),
                'url'  => $this->router->url('extension/theme'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.language'),
                'url'  => $this->router->url('extension/language'),
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
            $extension[] = $this->nav([
                'name' => $this->language->get('nav.event'),
                'url'  => $this->router->url('extension/event'),
            ]);
        }

        if ($extension) {
            $data['menus'][200] = $this->nav([
                'id'   => 'menu-extension',
                'name' => $this->language->get('nav.extension'),
                'subs' => $extension
            ]);
        }

        // Tools
        $tool = [];

        if ($this->user->hasPermission('access', 'tool/layout')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('nav.layout'),
                'url'  => $this->router->url('tool/layout'),
            ]);
        }

        if ($tool) {
            $data['menus'][300] = $this->nav([
                'id'   => 'menu-tool',
                'name' => $this->language->get('nav.tool'),
                'subs' => $tool
            ]);
        }

        // Users
        $user = [];

        if ($this->user->hasPermission('access', 'account/user')) {
            $user[] = $this->nav([
                'name' => $this->language->get('nav.user'),
                'url'  => $this->router->url('account/user'),
            ]);
        }

        if ($this->user->hasPermission('access', 'account/usergroup')) {
            $user[] = $this->nav([
                'name' => $this->language->get('nav.user_group'),
                'url'  => $this->router->url('account/usergroup'),
            ]);
        }

        if ($user) {
            $data['menus'][400] = $this->nav([
                'id'   => 'menu-design',
                'name' => $this->language->get('nav.account'),
                'subs' => $user
            ]);
        }

        // System
        $system = [];

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = $this->nav([
                'name' => $this->language->get('nav.setting'),
                'url'  => $this->router->url('setting/setting'),
            ]);
        }

        if ($this->user->hasPermission('access', 'setting/site')) {
            $system[] = $this->nav([
                'name' => $this->language->get('nav.sites'),
                'url'  => $this->router->url('setting/site'),
            ]);
        }

        if ($this->user->hasPermissions('access', ['tool/backup', 'tool/log'])) {
            $system[] = $this->nav([
                'type' => 'header',
                'name' => $this->language->get('nav.maintenance'),
            ]);
        }

        if ($this->user->hasPermission('access', 'tool/backup')) {
            $system[] = $this->nav([
                'name' => $this->language->get('nav.backup_restore'),
                'url'  => $this->router->url('tool/backup'),
            ]);
        }

        if ($this->user->hasPermission('access', 'tool/log')) {
            $system[] = $this->nav([
                'name' => $this->language->get('nav.log'),
                'url'  => $this->router->url('tool/log'),
            ]);
        }

        if ($system) {
            $data['menus'][1000] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('nav.system'),
                'subs' => $system
            ]);
        }

        return $this->load->view('extensions/module/adminnav/navigation', $data);
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
            'id'   => $params['id']   ?? '',
            'type' => $params['type'] ?? 'link', // Option: header, divider, link
            'icon' => $params['icon'] ?? '',
            'name' => $params['name'] ?? '',
            'url'  => $params['url']  ?? '',
            'subs' => $params['subs'] ?? [],
        ];
    }
}
