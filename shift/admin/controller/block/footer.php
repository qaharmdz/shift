<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

use Shift\System\Mvc;

class Footer extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/footer', 'footer');

        return $this->load->view('block/footer');
    }
}
