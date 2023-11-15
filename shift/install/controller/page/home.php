<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Mvc;

class Home extends Mvc\Controller
{
    public function index()
    {
        $this->document->setTitle('Welcome');

        $data = [];

        // $data['footer']  = $this->load->controller('block/footer');
        // $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/home', $data));
    }
}
