<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Extension\Module;

use Shift\System\Mvc;

class Information extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extension/module/information');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_sitemap'] = $this->language->get('text_sitemap');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            $data['informations'][] = array(
                'title' => $result['title'],
                'href'  => $this->router->url('information/information', 'information_id=' . $result['information_id'])
            );
        }

        $data['contact'] = $this->router->url('information/contact');
        $data['sitemap'] = $this->router->url('information/sitemap');

        return $this->load->view('extension/module/information', $data);
    }
}
