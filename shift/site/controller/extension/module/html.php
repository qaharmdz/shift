<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Extension\Module;

use Shift\System\Core\Mvc;

class Html extends Mvc\Controller
{
    public function index($setting)
    {
        if (isset($setting['module_description'][$this->config->get('env.language_id')])) {
            $data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('env.language_id')]['title'], ENT_QUOTES, 'UTF-8');
            $data['html'] = html_entity_decode($setting['module_description'][$this->config->get('env.language_id')]['description'], ENT_QUOTES, 'UTF-8');

            return $this->load->view('extension/module/html', $data);
        }
    }
}
