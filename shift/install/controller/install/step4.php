<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Install;

use Shift\System\Core\Mvc;

class Step4 extends Mvc\Controller
{
    public function index()
    {
        $this->language->load('install/step_4');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_step_4'] = $this->language->get('text_step_4');
        $data['text_catalog'] = $this->language->get('text_catalog');
        $data['text_admin'] = $this->language->get('text_admin');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_extension'] = $this->language->get('text_extension');
        $data['text_mail'] = $this->language->get('text_mail');
        $data['text_mail_description'] = $this->language->get('text_mail_description');
        $data['text_openbay'] = $this->language->get('text_openbay');
        $data['text_maxmind'] = $this->language->get('text_maxmind');
        $data['text_facebook'] = $this->language->get('text_facebook');
        $data['text_facebook_description'] = $this->language->get('text_facebook_description');
        $data['text_facebook_visit'] = $this->language->get('text_facebook_visit');
        $data['text_forum'] = $this->language->get('text_forum');
        $data['text_forum_description'] = $this->language->get('text_forum_description');
        $data['text_forum_visit'] = $this->language->get('text_forum_visit');
        $data['text_commercial'] = $this->language->get('text_commercial');
        $data['text_commercial_description'] = $this->language->get('text_commercial_description');
        $data['text_commercial_visit'] = $this->language->get('text_commercial_visit');
        $data['text_view'] = $this->language->get('text_view');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_downloads'] = $this->language->get('text_downloads');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_view'] = $this->language->get('text_view');

        $data['button_mail'] = $this->language->get('button_mail');
        $data['button_setup'] = $this->language->get('button_setup');

        $data['error_warning'] = $this->language->get('error_warning');
        $data['success'] = $this->session->pull('flash.success');

        $data['maxmind'] = $this->router->url('3rd_party/maxmind');
        $data['openbay'] = $this->router->url('3rd_party/openbay');
        $data['extension'] = $this->router->url('3rd_party/extension');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('install/step_4', $data));
    }
}
