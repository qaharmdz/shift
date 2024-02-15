<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Mvc;

class License extends Mvc\Controller {
    public function index()
    {
        $this->document->setTitle($this->language->get('license_agreement'));

        $data = [];

        $this->response->setOutput($this->load->view('page/license', $data));
    }
}
