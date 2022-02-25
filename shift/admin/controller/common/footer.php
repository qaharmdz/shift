<?php

declare(strict_types=1);

class ControllerCommonFooter extends Controller
{
    public function index()
    {
        $this->load->language('common/footer');

        $data['text_footer'] = $this->language->get('text_footer');

        $data['text_version'] = '';
        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->get('token'))) {
            $data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
        }

        return $this->load->view('common/footer', $data);
    }
}
