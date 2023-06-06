<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Codex\Admin\Controller;

use Shift\System\Mvc;

class Codex extends Mvc\Controller
{
    public function index()
    {
        $module_id = $this->request->getInt('query.module_id', 0);
        $mode = !$module_id ? 'add' : 'edit';

        $this->load->language('extensions/module/codex');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('codemirror');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('module'), $this->router->url('extension/module')],
            [$this->language->get('page_title'), $this->router->url('extensions/module/codex')],
        ]);

        $data = [];

        $data['mode']        = $mode;
        $data['module_id'] = $module_id;

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extensions/module/codex/form', $data));
    }

    public function save()
    {
        $data = [];

        $this->response->setOutputJson($data);
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
