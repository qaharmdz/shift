<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;

class Maintenance extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/maintenance');

        $this->document->setTitle($this->language->get('page_title'));

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('common/footer');
        $data['header']  = $this->load->controller('common/header');

        $this->response->setHeader('Retry-After', 3600);
        $this->response->setOutput($this->load->view('page/maintenance', $data), 503);
    }
}
