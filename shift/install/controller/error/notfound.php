<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Common;

use Shift\System\Mvc;

class NotFound extends Mvc\Controller
{
    public function index()
    {
        /*
        $this->load->language('error/notfound');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_error'] = $this->language->get('text_error');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->router->url('common/home');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('error/not_found', $data), 404);
        */
    }
}
