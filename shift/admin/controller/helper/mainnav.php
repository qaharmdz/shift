<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Helper;

use Shift\System\Mvc;

class MainNav extends Mvc\Controller {
    public function index()
    {
        $this->load->language('helper/mainnav', 'mainNav');
        $data = [];

        //=== Left panel
        $data['links'] = [];
        $data['links'][101] = $this->nav([
            'name' => $this->language->get('mainNav.my_account'),
            'url'  => $this->router->url('account/user/form', 'user_id=' . $this->user->get('user_id')),
        ]);
        $data['links'][102] = $this->nav([
            'name' => $this->language->get('mainNav.logout'),
            'url'  => $this->router->url('page/logout'),
        ]);
        $data['links'][201] = $this->nav([
            'name' => $this->language->get('mainNav.recent_visit'),
            'type' => 'header',
        ]);

        //=== Right panel
        $data['menus'] = [];
        $data['menus'][0] = $this->nav([
            'name' => $this->language->get('mainNav.dashboard'),
            'url'  => $this->router->url('page/dashboard'),
        ]);

        // Content
        $content = [];

        if ($this->user->hasPermission('access', 'content/post')) {
            $content[] = $this->nav([
                'name' => $this->language->get('mainNav.posts'),
                'url'  => $this->router->url('content/post'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/category')) {
            $content[] = $this->nav([
                'name' => $this->language->get('mainNav.categories'),
                'url'  => $this->router->url('content/category'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/tag')) {
            $content[] = $this->nav([
                'name' => $this->language->get('mainNav.tags'),
                'url'  => $this->router->url('content/tag'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/setting')) {
            $content[] = $this->nav([
                'type' => 'divider',
            ]);
            $content[] = $this->nav([
                'name' => $this->language->get('mainNav.settings'),
                'url'  => $this->router->url('content/setting'),
            ]);
        }

        if ($content) {
            $data['menus'][100] = $this->nav([
                'id'   => 'menu-content',
                'name' => $this->language->get('mainNav.contents'),
                'subs' => $content,
            ]);
        }

        // Extension
        $extension = [];

        if ($this->user->hasPermission('access', 'extension/manage')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('mainNav.manage'),
                'url'  => $this->router->url('extension/manage'),
            ]);
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/plugin')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('mainNav.plugins'),
                'url'  => $this->router->url('extension/plugin'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/module')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('mainNav.modules'),
                'url'  => $this->router->url('extension/module'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/theme')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('mainNav.themes'),
                'url'  => $this->router->url('extension/theme'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('mainNav.languages'),
                'url'  => $this->router->url('extension/language'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
            $extension[] = $this->nav([
                'name' => $this->language->get('mainNav.events'),
                'url'  => $this->router->url('extension/event'),
            ]);
        }

        if ($extension) {
            $data['menus'][200] = $this->nav([
                'id'   => 'menu-extension',
                'name' => $this->language->get('mainNav.extensions'),
                'subs' => $extension,
            ]);
        }

        // Tools
        $tool = [];

        if ($this->user->hasPermission('access', 'tool/layout')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('mainNav.module_layout'),
                'url'  => $this->router->url('tool/layout'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/mediamanager')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('mainNav.media_manager'),
                'url'  => $this->router->url('tool/mediamanager'),
            ]);
        }

        if ($tool) {
            $data['menus'][300] = $this->nav([
                'id'   => 'menu-tool',
                'name' => $this->language->get('mainNav.tools'),
                'subs' => $tool,
            ]);
        }

        // Users
        $user = [];

        if ($this->user->hasPermission('access', 'account/user')) {
            $user[] = $this->nav([
                'name' => $this->language->get('mainNav.users'),
                'url'  => $this->router->url('account/user'),
            ]);
        }
        if ($this->user->hasPermission('access', 'account/usergroup')) {
            $user[] = $this->nav([
                'name' => $this->language->get('mainNav.user_groups'),
                'url'  => $this->router->url('account/usergroup'),
            ]);
        }

        if ($user) {
            $data['menus'][400] = $this->nav([
                'id'   => 'menu-design',
                'name' => $this->language->get('mainNav.accounts'),
                'subs' => $user,
            ]);
        }

        // System
        $system = [];

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = $this->nav([
                'name' => $this->language->get('mainNav.settings'),
                'url'  => $this->router->url('setting/setting'),
            ]);
        }
        if ($this->user->hasPermission('access', 'setting/site')) {
            $system[] = $this->nav([
                'name' => $this->language->get('mainNav.sites'),
                'url'  => $this->router->url('setting/site'),
            ]);
        }

        if ($system) {
            $data['menus'][900] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('mainNav.system'),
                'subs' => $system,
            ]);
        }

        // Maintenance
        $maintenance = [];

        if ($this->user->hasPermission('access', 'tool/backupdb')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('mainNav.backup_restore'),
                'url'  => $this->router->url('tool/backupdb'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/cache')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('mainNav.cache'),
                'url'  => $this->router->url('tool/cache'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/log')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('mainNav.log'),
                'url'  => $this->router->url('tool/log'),
            ]);
        }

        if ($maintenance) {
            $data['menus'][1000] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('mainNav.maintenance'),
                'subs' => $maintenance,
            ]);
        }

        return $data;
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
