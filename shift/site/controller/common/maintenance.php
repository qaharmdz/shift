<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Core\Mvc;

class Maintenance extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/maintenance');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_maintenance'),
            'href' => $this->url->link('common/maintenance')
        );

        $data['message'] = $this->language->get('text_message');

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setHeader('Retry-After', 3600);
        $this->response->setOutput($this->load->view('common/maintenance', $data), 503);
    }
}
