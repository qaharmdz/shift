<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

use Shift\System\Mvc;

class Header extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/header', 'header');

        if (is_file(PATH_MEDIA . $this->config->get('system.site.icon'))) {
            $this->document->addLink('icon', $this->config->get('env.url_media') . $this->config->get('system.site.icon'));
        }

        $class_body = [];
        foreach ($this->request->get('query', ['route' => $this->config->get('root.route_default')]) as $key => $value) {
            if (in_array($key, ['access_token'])) {
                continue;
            }

            if (in_array($key, ['_route_', 'route'])) {
                $class_body[] = str_replace(['/', '\\', '_'], '-', $value);
            } else {
                $class_body[] = str_replace(['/', '\\', '_'], '-', $key . '-' . $value);
            }
        }
        $class_body = array_unique(array_merge($class_body, $this->document->getNode('class_body', [])));
        $this->document->setNode('class_body', $class_body);

        $data = [];
        $data['navigation'] = $this->navigations();

        $this->load->model('setting/site');
        $results = $this->model_setting_site->getSites();
        $data['sites'] = [];
        foreach ($results as $result) {
            $data['sites'][] = [
                'name' => $result['name'],
                'url'  => $result['url_host'],
            ];
        }

        return $this->load->view('block/header', $data);
    }

    protected function navigations()
    {
        $data = [];

        //=== Left panel
        $data['links'][0] = $this->nav([
            'name' => $this->language->get('header.my_account'),
            'url'  => $this->router->url('account/user/form', 'user_id=' . $this->user->get('user_id')),
        ]);
        $data['links'][1] = $this->nav([
            'name' => $this->language->get('header.logout'),
            'url'  => $this->router->url('page/logout'),
        ]);

        //=== Right panel
        $data['menus'][0] = $this->nav([
            'name' => $this->language->get('header.dashboard'),
            'url'  => $this->router->url('page/dashboard'),
        ]);

        // Content
        $content = [];

        if ($this->user->hasPermission('access', 'content/post')) {
            $content[] = $this->nav([
                'name' => $this->language->get('header.post'),
                'url'  => $this->router->url('content/post'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/category')) {
            $content[] = $this->nav([
                'name' => $this->language->get('header.category'),
                'url'  => $this->router->url('content/category'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/tag')) {
            $content[] = $this->nav([
                'name' => $this->language->get('header.tag'),
                'url'  => $this->router->url('content/tag'),
            ]);
        }
        if ($this->user->hasPermission('access', 'content/setting')) {
            $content[] = $this->nav([
                'type' => 'divider',
            ]);
            $content[] = $this->nav([
                'name' => $this->language->get('header.setting'),
                'url'  => $this->router->url('content/setting'),
            ]);
        }

        if ($content) {
            $data['menus'][100] = $this->nav([
                'id'   => 'menu-content',
                'name' => $this->language->get('header.content'),
                'subs' => $content,
            ]);
        }

        // Extension
        $extension = [];

        if ($this->user->hasPermission('access', 'extension/manage')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('header.manage'),
                'url'  => $this->router->url('extension/manage'),
            ]);
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
        }

        if ($this->user->hasPermission('access', 'extension/plugin')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('header.plugin'),
                'url'  => $this->router->url('extension/plugin'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/module')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('header.module'),
                'url'  => $this->router->url('extension/module'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/theme')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('header.theme'),
                'url'  => $this->router->url('extension/theme'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/language')) {
            $extension[] = $this->nav([
                'name' => $this->language->get('header.language'),
                'url'  => $this->router->url('extension/language'),
            ]);
        }
        if ($this->user->hasPermission('access', 'extension/event')) {
            $extension[] = $this->nav([
                'type' => 'divider',
            ]);
            $extension[] = $this->nav([
                'name' => $this->language->get('header.event'),
                'url'  => $this->router->url('extension/event'),
            ]);
        }

        if ($extension) {
            $data['menus'][200] = $this->nav([
                'id'   => 'menu-extension',
                'name' => $this->language->get('header.extension'),
                'subs' => $extension,
            ]);
        }

        // Tools
        $tool = [];

        if ($this->user->hasPermission('access', 'tool/layout')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('header.module_layout'),
                'url'  => $this->router->url('tool/layout'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/mediamanager')) {
            $tool[] = $this->nav([
                'name' => $this->language->get('header.media_manager'),
                'url'  => $this->router->url('tool/mediamanager'),
            ]);
        }

        if ($tool) {
            $data['menus'][300] = $this->nav([
                'id'   => 'menu-tool',
                'name' => $this->language->get('header.tool'),
                'subs' => $tool,
            ]);
        }

        // Users
        $user = [];

        if ($this->user->hasPermission('access', 'account/user')) {
            $user[] = $this->nav([
                'name' => $this->language->get('header.user'),
                'url'  => $this->router->url('account/user'),
            ]);
        }
        if ($this->user->hasPermission('access', 'account/usergroup')) {
            $user[] = $this->nav([
                'name' => $this->language->get('header.user_group'),
                'url'  => $this->router->url('account/usergroup'),
            ]);
        }

        if ($user) {
            $data['menus'][400] = $this->nav([
                'id'   => 'menu-design',
                'name' => $this->language->get('header.account'),
                'subs' => $user,
            ]);
        }

        // System
        $system = [];

        if ($this->user->hasPermission('access', 'setting/setting')) {
            $system[] = $this->nav([
                'name' => $this->language->get('header.setting'),
                'url'  => $this->router->url('setting/setting'),
            ]);
        }
        if ($this->user->hasPermission('access', 'setting/site')) {
            $system[] = $this->nav([
                'name' => $this->language->get('header.sites'),
                'url'  => $this->router->url('setting/site'),
            ]);
        }

        if ($system) {
            $data['menus'][900] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('header.system'),
                'subs' => $system,
            ]);
        }

        // Maintenance
        $maintenance = [];

        if ($this->user->hasPermission('access', 'tool/backupdb')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('header.backup_restore'),
                'url'  => $this->router->url('tool/backupdb'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/cache')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('header.cache'),
                'url'  => $this->router->url('tool/cache'),
            ]);
        }
        if ($this->user->hasPermission('access', 'tool/log')) {
            $maintenance[] = $this->nav([
                'name' => $this->language->get('header.log'),
                'url'  => $this->router->url('tool/log'),
            ]);
        }

        if ($maintenance) {
            $data['menus'][1000] = $this->nav([
                'id'   => 'menu-system',
                'name' => $this->language->get('header.maintenance'),
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
            'id'   => $params['id']   ?? '',
            'type' => $params['type'] ?? 'link', // Option: header, divider, link
            'icon' => $params['icon'] ?? '',
            'name' => $params['name'] ?? '',
            'url'  => $params['url']  ?? '',
            'subs' => $params['subs'] ?? [],
        ];
    }
}
