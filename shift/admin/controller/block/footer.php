<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

use Shift\System\Mvc;

class Footer extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/footer', 'blockFooter');

        return $this->load->view('block/footer');
    }
}
