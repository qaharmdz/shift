<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Error;

use Shift\System\Mvc;

class NotFound extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('error/notfound');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_not_found'] = $this->language->get('text_not_found');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('error/not_found', 'token=' . $this->session->get('token'))
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('error/not_found', $data));
    }
}
