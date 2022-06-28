<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Extension\Module;

use Shift\System\Core\Mvc;

class Account extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extension/module/account');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_register'] = $this->language->get('text_register');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_transaction'] = $this->language->get('text_transaction');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_recurring'] = $this->language->get('text_recurring');

        $data['logged'] = $this->user->isLogged();
        $data['register'] = $this->router->url('account/register');
        $data['login'] = $this->router->url('account/login');
        $data['logout'] = $this->router->url('account/logout');
        $data['forgotten'] = $this->router->url('account/forgotten');
        $data['account'] = $this->router->url('account/account');
        $data['edit'] = $this->router->url('account/edit');
        $data['password'] = $this->router->url('account/password');
        $data['address'] = $this->router->url('account/address');
        $data['wishlist'] = $this->router->url('account/wishlist');
        $data['order'] = $this->router->url('account/order');
        $data['download'] = $this->router->url('account/download');
        $data['reward'] = $this->router->url('account/reward');
        $data['return'] = $this->router->url('account/return');
        $data['transaction'] = $this->router->url('account/transaction');
        $data['newsletter'] = $this->router->url('account/newsletter');
        $data['recurring'] = $this->router->url('account/recurring');

        return $this->load->view('extension/module/account', $data);
    }
}
