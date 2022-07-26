<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;

class Home extends Mvc\Controller
{
    public function index()
    {
        $this->document->setTitle($this->config->get('system.site.meta_title'));
        $this->document->addMeta('name', 'description', $this->config->get('system.site.meta_description'));
        $this->document->addMeta('name', 'keywords', $this->config->get('system.site.meta_keyword'));

        if ($this->request->has('query.route')) {
            $this->document->addLink($this->config->get('env.url_app'), 'canonical');
        }

        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('common/home', $data));
    }
}
