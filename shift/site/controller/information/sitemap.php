<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Information;

use Shift\System\Mvc;

class Sitemap extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('catalog/information');
        $this->load->language('information/sitemap');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('text_home'), $this->router->url('common/home')],
            [$this->language->get('page_title'), $this->router->url('information/sitemap')],
        ]);


        $data = [];

        $data['informations'] = [];
        foreach ($this->model_catalog_information->getInformations() as $result) {
            $data['informations'][] = [
                'title' => $result['title'],
                'href'  => $this->router->url('information/information', 'information_id=' . $result['information_id'])
            ];
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('common/footer');
        $data['header']  = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('page/sitemap', $data));
    }
}
