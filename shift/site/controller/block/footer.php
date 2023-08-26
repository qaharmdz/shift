<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Block;

use Shift\System\Mvc;

class Footer extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('catalog/information');
        $this->load->language('block/footer');

        $data = [];

        $data['informations'] = array();
        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = array(
                    'title' => $result['title'],
                    'href'  => $this->router->url('information/information', 'information_id=' . $result['information_id']),
                );
            }
        }

        return $this->load->view('block/footer', $data);
    }
}
