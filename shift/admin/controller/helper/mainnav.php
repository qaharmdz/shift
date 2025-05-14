<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Helper;

use Shift\System\Mvc;

class MainNav extends Mvc\Controller {
    public function index()
    {
        $this->load->language('helper/mainnav', 'helperMainnav');
        $data = [];

        //=== Left panel
        $data['links'] = [];
        $data['links'][101] = $this->nav([
            'name' => $this->language->get('helperMainnav.my_account'),
            'url'  => $this->router->url('account/user/form', 'user_id=' . $this->user->get('user_id')),
        ]);
        $data['links'][102] = $this->nav([
            'name' => $this->language->get('helperMainnav.logout'),
            'url'  => $this->router->url('page/logout'),
        ]);
        $data['links'][201] = $this->nav([
            'name' => $this->language->get('helperMainnav.recent_visit'),
            'type' => 'header',
        ]);

        //=== Right panel
        $data['menus'] = [];
        $data['menus'][0] = $this->nav([
            'name' => $this->language->get('helperMainnav.dashboard'),
            'url'  => $this->router->url('page/dashboard'),
        ]);

        // Content
        $content = [];

        if ($this->user->hasPermission('access', 'content/post')) {
            $content[] = $this->nav([
                'name' => $this->language->get('helperMainnav.posts'),
                'url'  => $this->router->url('content/post'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/category')) {
            $content[] = $this->nav([
                'name' => $this->language->get('helperMainnav.categories'),
                'url'  => $this->router->url('content/category'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/tag')) {
            $content[] = $this->nav([
                'name' => $this->language->get('helperMainnav.tags'),
                'url'  => $this->router->url('content/tag'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/setting')) {
            $content[] = $this->nav([
                'type' => 'divider',
            ]);
            $content[] = $this->nav([
                'name' => $this->language->get('helperMainnav.settings'),
                'url'  => $this->router->url('content/setting'),
            ]);
        }

        if ($content) {
            $data['menus'][100] = $this->nav([
                'id'   => 'menu-content',
                'name' => $this->language->get('helperMainnav.contents'),
                'subs' => $content,
            ]);
        }

        // Extension
        $extension = [];

        if ($this->user->hasPermission('access', 'extension/manage')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('helperMainnav.manage'),
                'url'  => $this->router->url('extension/manage'),
            ]);
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/plugin')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('helperMainnav.plugins'),
                'url'  => $this->router->url('extension/plugin'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/module')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('helperMainnav.modules'),
                'url'  => $this->router->url('extension/module'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/theme')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('helperMainnav.themes'),
                'url'  => $this->router->url('extension/theme'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('helperMainnav.languages'),
                'url'  => $this->router->url('extension/language'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
            $extension[] = $this->nav([
                'name' => $this->language->get('helperMainnav.events'),
                'url'  => $this->router->url('extension/event'),
            ]);
        }

        if ($extension) {
            $data['menus'][200] = $this->nav([
                'id'   => 'menu-extension',
                'name' => $this->language->get('helperMainnav.extensions'),
                'subs' => $extension,
            ]);
        }

        // Tools
        $tool = [];

        if ($this->user->hasPermission('access', 'tool/layout')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('helperMainnav.module_layout'),
                'url'  => $this->router->url('tool/layout'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/mediamanager')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('helperMainnav.media_manager'),
                'url'  => $this->router->url('tool/mediamanager'),
            ]);
        }

        if ($tool) {
            $data['menus'][300] = $this->nav([
                'id'   => 'menu-tool',
                'name' => $this->language->get('helperMainnav.tools'),
                'subs' => $tool,
            ]);
        }

        // Users
        $user = [];

        if ($this->user->hasPermission('access', 'account/user')) {
            $user[] = $this->nav([
                'name' => $this->language->get('helperMainnav.users'),
                'url'  => $this->router->url('account/user'),
            ]);
        }
        if ($this->user->hasPermission('access', 'account/usergroup')) {
            $user[] = $this->nav([
                'name' => $this->language->get('helperMainnav.user_groups'),
                'url'  => $this->router->url('account/usergroup'),
            ]);
        }

        if ($user) {
            $data['menus'][400] = $this->nav([
                'id'   => 'menu-design',
                'name' => $this->language->get('helperMainnav.accounts'),
                'subs' => $user,
            ]);
        }

        // System
        $system = [];

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = $this->nav([
                'name' => $this->language->get('helperMainnav.settings'),
                'url'  => $this->router->url('setting/setting'),
            ]);
        }
        if ($this->user->hasPermission('access', 'setting/site')) {
            $system[] = $this->nav([
                'name' => $this->language->get('helperMainnav.sites'),
                'url'  => $this->router->url('setting/site'),
            ]);
        }

        if ($system) {
            $data['menus'][900] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('helperMainnav.system'),
                'subs' => $system,
            ]);
        }

        // Maintenance
        $maintenance = [];

        if ($this->user->hasPermission('access', 'tool/backupdb')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('helperMainnav.backup_restore'),
                'url'  => $this->router->url('tool/backupdb'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/cache')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('helperMainnav.cache'),
                'url'  => $this->router->url('tool/cache'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/log')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('helperMainnav.log'),
                'url'  => $this->router->url('tool/log'),
            ]);
        }

        if ($maintenance) {
            $data['menus'][1000] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('helperMainnav.maintenance'),
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
