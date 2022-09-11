<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Site extends Mvc\Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('setting/site');

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/site_list', $data));
    }
}
