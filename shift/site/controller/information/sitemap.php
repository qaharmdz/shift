<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Information;

use Shift\System\Mvc;

class Sitemap extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('information/sitemap');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('information/sitemap')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_account'] = $this->language->get('text_account');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_information'] = $this->language->get('text_information');
        $data['text_contact'] = $this->language->get('text_contact');


        $data['account'] = $this->router->url('account/account');
        $data['edit'] = $this->router->url('account/edit');
        $data['password'] = $this->router->url('account/password');
        $data['contact'] = $this->router->url('information/contact');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            $data['informations'][] = array(
                'title' => $result['title'],
                'href'  => $this->router->url('information/information', 'information_id=' . $result['information_id'])
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('information/sitemap', $data));
    }
}
