<?php

declare(strict_types=1);

namespace Shift\Extensions\Plugin\Architect\Admin\Controller;

use Shift\System\Mvc;

class Architect extends Mvc\Controller
{
    public function index()
    {
        d(__METHOD__);

        $this->load->language('extensions/plugin/architect');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('plugin')],
            [$this->language->get('page_title')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extensions/plugin/architect/architect_list', $data));
    }

    public function list()
    {
        d(__METHOD__);

        $this->load->model('extensions/plugin/architect');

        d($this->model_extensions_plugin_architect->dtRecords([]));

        $this->index();
    }
}
