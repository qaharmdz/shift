<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Error;

use Shift\System\Mvc;

class NotFound extends Mvc\Controller
{
    public function index($params = [])
    {
        $this->load->language('error/notfound');

        if ($this->request->is('ajax')) {
            return $this->response->setOutputJson([
                'title'   => $params['title'] ?? $this->language->get('page_title'),
                'message' => $params['message'] ?? '',
            ], 404);
        }

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('class_body', ['error-404']);
        $this->document->addNode('breadcrumbs', [
            [$this->language->get('page_title'), $this->router->url($this->request->get('query.route'))],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('error/notfound', $data), 404);
    }
}
