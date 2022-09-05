<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Error;

use Shift\System\Mvc;

class Permission extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('error/permission');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('class_body', ['error-403']);
        $this->document->addNode('breadcrumbs', [
            [$this->language->get('page_title'), $this->router->url($this->request->get('query.route'))],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('error/permission', $data));
    }
}
