<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Information;

use Shift\System\Mvc;

class Information extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('catalog/information');
        $this->load->language('information/information');

        $information_id = $this->request->getInt('query.information_id', 0);
        $information_info = $this->model_catalog_information->getInformation($information_id);

        if (!$information_info) {
            return $this->load->controller('error/notfound');
        }

        $this->document->setTitle($information_info['meta_title']);
        $this->document->addMeta('name', 'description', $information_info['meta_description']);
        $this->document->addMeta('name', 'keywords', $information_info['meta_keyword']);

        $this->document->addLink($this->router->url('information/information', 'information_id=' .  $information_id), 'canonical');
        $this->document->addNode('breadcrumbs', [
            [$this->language->get('text_home'), $this->router->url('common/home')],
            [$information_info['title'], $this->router->url('information/information', 'information_id=' .  $information_id)],
        ]);

        $data = [];

        $data['page_title']     = html_entity_decode($information_info['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $data['content']        = html_entity_decode($information_info['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('common/footer');
        $data['header']  = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('page/information', $data));
    }

    public function agree()
    {
        $this->load->model('catalog/information');


        $information_id   = $this->request->getInt('query.information_id', 0);
        $information_info = $this->model_catalog_information->getInformation($information_id);

        $output = '';
        if ($information_info) {
            $output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
        }

        $this->response->setOutput($output);
    }
}
