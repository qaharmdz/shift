<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;

class Home extends Mvc\Controller
{
    public function index()
    {
        $this->document->addMeta('name', 'description', $this->config->get('system.site.meta_description'));
        $this->document->addMeta('name', 'keywords', $this->config->get('system.site.meta_keyword'));

        $this->document->addLink($this->config->get('env.url_app'), 'canonical');

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('common/footer');
        $data['header']  = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('page/home', $data));
    }
}
