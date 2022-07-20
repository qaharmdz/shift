<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Extension\Module;

use Shift\System\Core\Mvc;

class Site extends Mvc\Controller
{
    public function index()
    {
        $status = true;

        if ($this->config->get('site_admin')) {
            $this->user = new Cart\User($this->registry);

            $status = $this->user->isLogged();
        }

        if ($status) {
            $this->load->language('extension/module/site');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_site'] = $this->language->get('text_site');

            $data['site_id'] = $this->config->get('env.site_id');

            $this->load->model('setting/site');

            $results = $this->model_setting_site->getSites();

            $data['sites'] = array();
            foreach ($results as $result) {
                $data['sites'][] = array(
                    'site_id' => $result['site_id'],
                    'name'     => $result['name'],
                    'url'      => $result['url'] . 'index.php?route=common/home&session_id=' . $this->session->getId()
                );
            }

            return $this->load->view('extension/module/site', $data);
        }
    }
}
