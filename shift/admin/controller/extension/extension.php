<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Extension extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/extension');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('extension/extension', 'token=' . $this->session->get('token'))
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list']    = $this->language->get('text_list');
        $data['text_type']    = $this->language->get('text_type');
        $data['text_filter']  = $this->language->get('text_filter');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['token']        = $this->session->get('token');
        $data['type']         = $this->request->get('query.type', '');

        $data['categories'] = array();

        $files = glob(PATH_APP . 'controller/extension/extension/*.php', GLOB_BRACE);

        foreach ($files as $file) {
            $extension = basename($file, '.php');

            $this->load->language('extension/extension/' . $extension);

            if ($this->user->hasPermission('access', 'extension/extension/' . $extension)) {
                $files = glob(PATH_APP . 'controller/{extension/' . $extension . ',' . $extension . '}/*.php', GLOB_BRACE);

                $data['categories'][] = array(
                    'code' => $extension,
                    'text' => $this->language->get('heading_title') . ' (' . count($files) . ')',
                    'href' => $this->router->url('extension/extension/' . $extension, 'token=' . $this->session->get('token'))
                );
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/extension', $data));
    }
}
