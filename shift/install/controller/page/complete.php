<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Mvc;

class Complete extends Mvc\Controller {
    public function index()
    {
        $this->document->setTitle($this->language->get('complete'));

        $data = [
            'url_site'      => $url_site = $this->session->get('install.config.url_host'),
            'url_admin'     => $url_site ? $url_site . 'admin/' : '',
            'user_email'    => $this->session->get('install.config.email'),
            'user_password' => str_repeat('*', strlen($this->session->getString('install.config.password'))),
        ];

        $this->session->delete('install');

        $this->response->setOutput($this->load->view('page/complete', $data));
    }
}
