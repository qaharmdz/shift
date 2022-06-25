<?php

declare(strict_types=1);

class ControllerCommonHeader extends Controller
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

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $this->document->addLink($data['base'] . 'image/' . $this->config->get('config_icon'), 'icon');
        }

        $data['logo'] = '';
        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $data['base'] . 'image/' . $this->config->get('config_logo');
        }

        $this->load->language('common/header');

        $data['text_home'] = $this->language->get('text_home');

        $data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->user->getFirstName(), $this->url->link('account/logout', '', true));

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

        $data['home'] = $this->url->link('common/home');
        $data['wishlist'] = $this->url->link('account/wishlist', '', true);
        $data['logged'] = $this->user->isLogged();
        $data['account'] = $this->url->link('account/account', '', true);
        $data['register'] = $this->url->link('account/register', '', true);
        $data['login'] = $this->url->link('account/login', '', true);
        $data['order'] = $this->url->link('account/order', '', true);
        $data['transaction'] = $this->url->link('account/transaction', '', true);
        $data['download'] = $this->url->link('account/download', '', true);
        $data['logout'] = $this->url->link('account/logout', '', true);
        $data['shopping_cart'] = $this->url->link('checkout/cart');
        $data['checkout'] = $this->url->link('checkout/checkout', '', true);
        $data['contact'] = $this->url->link('information/contact');
        $data['telephone'] = $this->config->get('config_telephone');

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


        return $this->load->view('common/header', $data);
    }
}
