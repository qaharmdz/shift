<?php

declare(strict_types=1);

namespace Shift\Extensions\Theme\Base\Admin\Controller;

use Shift\System\Mvc;

class Base extends Mvc\Controller
{
    public function index()
    {
        d(__METHOD__);

        $this->load->language('extensions/theme/base');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('plugin'), $this->router->url('extension/theme')],
            [$this->language->get('page_title'), $this->router->url('extensions/theme/base')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extensions/theme/base/base', $data));
    }

    public function install()
    {
        $this->log->write(__METHOD__);
    }

    public function uninstall()
    {
        $this->log->write(__METHOD__);
    }
}
