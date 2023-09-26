<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Block;

use Shift\System\Mvc;

class Header extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/header', 'blockHeader');

        if (is_file(PATH_MEDIA . $this->config->get('system.site.icon'))) {
            $this->document->addLink('icon', $this->config->get('env.url_media') . $this->config->get('system.site.icon'));
        }

        $class_body = [];
        foreach ($this->request->get('query', ['route' => $this->config->get('root.route_default')]) as $key => $value) {
            if (in_array($key, ['_route_', 'route'])) {
                $class_body[] = str_replace(['/', '\\', '_'], '-', $value);
            } else {
                $class_body[] = str_replace(['/', '\\', '_'], '-', $key . '-' . $value);
            }
        }
        $class_body = array_unique(array_merge($class_body, $this->document->getNode('class_body', [])));
        $this->document->setNode('class_body', $class_body);

        $data = [];

        $data['logo'] = '';
        if (is_file(PATH_MEDIA . $this->config->get('system.site.logo'))) {
            $data['logo'] = $this->config->get('env.url_media') . $this->config->get('system.site.logo');
        }

        $data['logged']   = $this->user->isLogged();
        $data['language'] = $this->load->controller('block/language');
        $data['search']   = $this->load->controller('block/search');

        $data['navigations'] = [];
        $data['navigations'][] = [
            'name'     => $this->language->get('blockHeader.home'),
            'href'     => $this->router->url('page/home'),
        ];
        $data['navigations'][] = [
            'name'     => $this->language->get('blockHeader.about', 'About'),
            'href'     => $this->router->url('information/information', 'information_id=4'),
        ];
        $data['navigations'][] = [
            'name'     => $this->language->get('blockHeader.content', 'Content'),
            'href'     => $this->router->url('content/category'),
        ];

        $dropdownItems = [];
        for ($i = 0; $i < 12; $i++) {
            $dropdownItems[] = [
                'name'  => 'Item ' . $i,
                'href'  => $this->router->url('page/home'),
            ];
        }
        $data['navigations'][] = [
            'name'     => 'Dropdown',
            'column'   => 2,
            'children' => $dropdownItems,
            'href'     => $this->router->url('information/information', 'information_id=6')
        ];

        $data['navigations'][] = [
            'name'     => $this->language->get('blockHeader.contact'),
            'href'     => $this->router->url('page/contact')
        ];

        return $this->load->view('block/header', $data);
    }
}
