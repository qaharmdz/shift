<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Page;

use Shift\System\Mvc;

class Maintenance extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('page/maintenance');

        $this->document->setTitle($this->language->get('page_title'));

        $data = [];
        $data['content'] = $this->language->get('content'); // TODO: Custom rich-text maintenance message

        $this->response->setHeader('Retry-After', 3600);
        $this->response->setOutput($this->load->view('page/maintenance', $data), 503);
    }
}
