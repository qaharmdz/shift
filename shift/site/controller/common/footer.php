<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Core\Mvc;

class Footer extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/footer');

        $data['scripts'] = $this->document->getScripts('footer');

        $data['text_information'] = $this->language->get('text_information');
        $data['text_service'] = $this->language->get('text_service');
        $data['text_extra'] = $this->language->get('text_extra');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_sitemap'] = $this->language->get('text_sitemap');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_special'] = $this->language->get('text_special');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_newsletter'] = $this->language->get('text_newsletter');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = array(
                    'title' => $result['title'],
                    'href'  => $this->router->url('information/information', 'information_id=' . $result['information_id'])
                );
            }
        }

        $data['contact'] = $this->router->url('information/contact');
        $data['return'] = $this->router->url('account/return/add');
        $data['sitemap'] = $this->router->url('information/sitemap');
        $data['manufacturer'] = $this->router->url('product/manufacturer');
        $data['voucher'] = $this->router->url('account/voucher');
        $data['affiliate'] = $this->router->url('affiliate/account');
        $data['special'] = $this->router->url('product/special');
        $data['account'] = $this->router->url('account/account');
        $data['order'] = $this->router->url('account/order');
        $data['wishlist'] = $this->router->url('account/wishlist');
        $data['newsletter'] = $this->router->url('account/newsletter');

        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('system.site.name'), date('Y', time()));
        $data['config'] = json_encode($this->config->all());

        return $this->load->view('common/footer', $data);
    }
}
