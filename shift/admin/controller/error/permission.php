<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Error;

use Shift\System\Mvc;

class Permission extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('error/permission');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title']   = $this->language->get('heading_title');
        $data['text_permission'] = $this->language->get('text_permission');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url($this->request->get('query.route'))
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('error/permission', $data));
    }
}
