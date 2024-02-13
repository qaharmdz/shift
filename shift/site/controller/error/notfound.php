<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Error;

use Shift\System\Mvc;

class NotFound extends Mvc\Controller {
    public function index()
    {
        $this->load->language('error/notfound');

        $this->document->setTitle($this->language->get('page_title'));
        $this->document->addNode('class_body', ['error-404']);

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('error/notfound', $data), 404);
    }
}
