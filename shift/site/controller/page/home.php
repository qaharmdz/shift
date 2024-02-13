<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Page;

use Shift\System\Mvc;

class Home extends Mvc\Controller {
    public function index()
    {
        $this->document->addMeta('name', 'description', $this->config->get('system.site.meta_description.' . $this->config->get('env.language_id', 0)));
        $this->document->addMeta('name', 'keywords', $this->config->get('system.site.meta_keyword.' . $this->config->get('env.language_id', 0)));

        $this->document->addLink($this->router->url('page/home'), 'canonical');

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/home', $data));
    }
}
