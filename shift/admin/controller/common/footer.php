<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Mvc;

class Footer extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/footer');

        $data['text_footer'] = $this->language->get('text_footer');

        $data['text_version'] = '';
        if ($this->user->isLogged() && $this->request->get('query.token', time()) == $this->session->get('token')) {
            $data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
        }

        return $this->load->view('common/footer', $data);
    }
}
