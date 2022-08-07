<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;

class Header extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/header');

        if (is_file(PATH_MEDIA . $this->config->get('system.site.icon'))) {
            $this->document->addLink('icon', $this->config->get('env.url_media') . $this->config->get('system.site.icon'), type:'image/x-icon');
        }

        $data = [];

        $data['logo'] = '';
        if (is_file(PATH_MEDIA . $this->config->get('system.site.logo'))) {
            $data['logo'] = $this->config->get('env.url_media') . $this->config->get('system.site.logo');
        }

        $data['logged']   = $this->user->isLogged();
        $data['language'] = $this->load->controller('common/language');
        $data['search']   = $this->load->controller('common/search');

        $data['navigations'] = [];
        $data['navigations'][] = [
            'name'     => $this->language->get('text_home'),
            'href'     => $this->router->url('common/home')
        ];
        $data['navigations'][] = [
            'name'     => $this->language->get('text_about', 'About'),
            'href'     => $this->router->url('information/information', 'information_id=4')
        ];
        $dropdownItems = [];
        for ($i = 0; $i < 12; $i++) {
            $dropdownItems[] = [
                'name'  => 'Item ' . $i,
                'href'  => $this->router->url('common/home'),
            ];
        }
        $data['navigations'][] = [
            'name'     => 'Dropdown',
            'column'   => 2,
            'children' => $dropdownItems,
            'href'     => $this->router->url('information/information', 'information_id=6')
        ];
        $data['navigations'][] = [
            'name'     => $this->language->get('text_contact'),
            'href'     => $this->router->url('information/contact')
        ];

        return $this->load->view('block/header', $data);
    }
}
