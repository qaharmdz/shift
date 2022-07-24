<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Common;

use Shift\System\Mvc;

class ColumnLeft extends Mvc\Controller
{
    public function index()
    {
        $this->language->load('common/column_left');

        // Step
        $data['text_license'] = $this->language->get('text_license');
        $data['text_installation'] = $this->language->get('text_installation');
        $data['text_configuration'] = $this->language->get('text_configuration');
        $data['text_upgrade'] = $this->language->get('text_upgrade');
        $data['text_finished'] = $this->language->get('text_finished');
        $data['text_language'] = $this->language->get('text_language');

        if ($this->request->has('query.route')) {
            $data['route'] = $this->request->get('query.route');
        } else {
            $data['route'] = 'install/step_1';
        }

        if (!$this->request->has('query.route')) {
            $data['redirect'] = $this->router->url('install/step_1');
        } else {
            $url_data = $this->request->get;

            $route = $url_data['route'];

            unset($url_data['route']);

            $url = '';

            if ($url_data) {
                $url = '&' . urldecode(http_build_query($url_data, '', '&'));
            }

            $data['redirect'] = $this->router->url($route, $url, $this->request->get('server.HTTPS'));
        }

        return $this->load->view('common/column_left', $data);
    }

    public function language()
    {
        if ($this->request->has('post.code') && is_dir(DIR_LANGUAGE . basename($this->request->get('post.code')))) {
            $this->session->set('language', $this->request->get('post.code'));
        }

        if ($this->request->has('post.redirect')) {
            $this->response->redirect($this->request->get('post.redirect'));
        } else {
            $this->response->redirect($this->router->url('install/step_1'));
        }
    }
}
