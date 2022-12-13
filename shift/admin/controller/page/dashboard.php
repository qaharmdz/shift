<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Page;

use Shift\System\Mvc;

class Dashboard extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('page/dashboard');

        $this->document->setTitle($this->language->get('page_title'));

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/dashboard', $data));
    }
}
