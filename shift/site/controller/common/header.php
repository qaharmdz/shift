<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;

class Header extends Mvc\Controller
{
    public function index()
    {
        $data['base']        = $this->config->get('env.url_app');
        $data['title']       = $this->document->getTitle();

        $metas = array_merge([
            ['value' => 'description', 'content' => ''],
            ['value' => 'keywords', 'content' => ''],
        ], $this->document->getMetas());
        foreach ($metas as $meta) {
            $data[$meta['value']] = $meta['content'];
        }

        if (is_file(DIR_MEDIA . $this->config->get('system.site.icon'))) {
            $this->document->addLink('icon', $this->config->get('env.url_media') . $this->config->get('system.site.icon'), type:'image/x-icon');
        }

        $data['links']       = $this->document->getLinks();
        $data['styles']      = $this->document->getStyles();
        $data['scripts']     = $this->document->getScripts();
        $data['lang']        = $this->language->get('code');
        $data['direction']   = $this->language->get('direction');

        $data['name'] = $this->config->get('system.site.name');

        $data['logo'] = '';
        if (is_file(DIR_MEDIA . $this->config->get('system.site.logo'))) {
            $data['logo'] = $this->config->get('env.url_media') . $this->config->get('system.site.logo');
        }

        $this->load->language('common/header');

        $data['text_home'] = $this->language->get('text_home');

        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->router->url('account/account'), $this->user->get('firstname'), $this->router->url('account/logout'));

        $data['text_account'] = $this->language->get('text_account');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_transaction'] = $this->language->get('text_transaction');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_all'] = $this->language->get('text_all');

        $data['home'] = $this->router->url('common/home');
        $data['wishlist'] = $this->router->url('account/wishlist');
        $data['logged'] = $this->user->isLogged();
        $data['account'] = $this->router->url('account/account');
        $data['register'] = $this->router->url('account/register');
        $data['login'] = $this->router->url('account/login');
        $data['order'] = $this->router->url('account/order');
        $data['transaction'] = $this->router->url('account/transaction');
        $data['download'] = $this->router->url('account/download');
        $data['logout'] = $this->router->url('account/logout');
        $data['shopping_cart'] = $this->router->url('checkout/cart');
        $data['checkout'] = $this->router->url('checkout/checkout');
        $data['contact'] = $this->router->url('information/contact');
        $data['telephone'] = $this->config->get('system.setting.telephone');

        // Menu
        $data['categories'] = array();
        $data['categories'] = array();

        $data['language'] = $this->load->controller('common/language');
        $data['search'] = $this->load->controller('common/search');

        // For page specific css
        $data['class'] = 'common-home';
        if ($this->request->has('query.route')) {
            $class = '';

            if ($this->request->has('query.information_id')) {
                $class = '-' . $this->request->get('query.information_id');
            }

            $data['class'] = str_replace('/', '-', $this->request->get('query.route') . $class);
        }


        return $this->load->view('block/header', $data);
    }
}
