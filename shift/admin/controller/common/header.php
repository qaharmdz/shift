<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Core\Mvc;

class Header extends Mvc\Controller
{
    public function index()
    {
        $data['base']        = $this->config->get('env.url_app');
        $data['title']       = $this->document->getTitle();

        $data['description'] = $this->document->getDescription();
        $data['keywords']    = $this->document->getKeywords();
        $data['links']       = $this->document->getLinks();
        $data['styles']      = $this->document->getStyles();
        $data['scripts']     = $this->document->getScripts();
        $data['lang']        = $this->language->get('code');
        $data['direction']   = $this->language->get('direction');

        $this->load->language('common/header');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_order'] = $this->language->get('text_order');
        $data['text_processing_status'] = $this->language->get('text_processing_status');
        $data['text_complete_status'] = $this->language->get('text_complete_status');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_online'] = $this->language->get('text_online');
        $data['text_approval'] = $this->language->get('text_approval');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_review'] = $this->language->get('text_review');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_site'] = $this->language->get('text_site');
        $data['text_front'] = $this->language->get('text_front');
        $data['text_help'] = $this->language->get('text_help');
        $data['text_homepage'] = $this->language->get('text_homepage');
        $data['text_documentation'] = $this->language->get('text_documentation');
        $data['text_support'] = $this->language->get('text_support');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
        $data['text_logout'] = $this->language->get('text_logout');

        if ($this->request->get('query.token', time()) != $this->session->get('token')) {
            $data['logged'] = false;
            $data['home']   = $this->router->url('common/dashboard');
        } else {
            $data['logged'] = true;
            $data['home']   = $this->router->url('common/dashboard', 'token=' . $this->session->get('token'));
            $data['logout'] = $this->router->url('common/logout', 'token=' . $this->session->get('token'));

            // Online Sites
            $data['sites'] = array();

            $data['sites'][] = array(
                'name' => $this->config->get('system.setting.name'),
                'href' => URL_SITE
            );

            $this->load->model('setting/site');

            $results = $this->model_setting_site->getSites();

            foreach ($results as $result) {
                $data['sites'][] = array(
                    'name' => $result['name'],
                    'href' => $result['url']
                );
            }
        }

        return $this->load->view('common/header', $data);
    }
}
