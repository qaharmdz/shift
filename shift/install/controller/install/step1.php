<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Install;

use Shift\System\Mvc;

class Step1 extends Mvc\Controller
{
    public function index()
    {
        $this->language->load('install/step_1');

        if ($this->request->is('POST')) {
            $this->response->redirect($this->router->url('install/step_2'));
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_step_1'] = $this->language->get('text_step_1');
        $data['text_terms'] = $this->language->get('text_terms');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['action'] = $this->router->url('install/step_1');

        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');
        $data['column_left'] = $this->load->controller('common/column_left');

        $this->response->setOutput($this->load->view('install/step_1', $data));
    }
}
