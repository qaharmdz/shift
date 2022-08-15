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

        $this->response->setHeader('Retry-After', 3600);
        $this->response->setOutput($this->load->view('page/maintenance', $data), 503);
    }
}
