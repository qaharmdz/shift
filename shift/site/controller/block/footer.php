<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Block;

use Shift\System\Mvc;

class Footer extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/footer');

        $data = [];

        return $this->load->view('block/footer', $data);
    }
}
