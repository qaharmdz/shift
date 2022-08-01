<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Account;

use Shift\System\Mvc;

class Account extends Mvc\Controller
{
    public function index()
    {
        if (!$this->user->isLogged()) {
            $this->session->set('flash.redirect', $this->router->url('account/account'));

            $this->response->redirect($this->router->url('account/login'));
        }

        $this->load->language('account/account');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->router->url('account/account')
        );

        $data['success'] = $this->session->pull('flash.success');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_my_account'] = $this->language->get('text_my_account');
        $data['text_my_orders'] = $this->language->get('text_my_orders');
        $data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_password'] = $this->language->get('text_password');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_credit_card'] = $this->language->get('text_credit_card');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_download'] = $this->language->get('text_download');
        $data['text_reward'] = $this->language->get('text_reward');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_transaction'] = $this->language->get('text_transaction');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_recurring'] = $this->language->get('text_recurring');

        $data['edit'] = $this->router->url('account/edit');
        $data['password'] = $this->router->url('account/password');
        $data['address'] = $this->router->url('account/address');

        $data['credit_cards'] = array();

        $files = glob(PATH_APP . 'controller/extension/credit_card/*.php');

        foreach ($files as $file) {
            $code = basename($file, '.php');

            if ($this->config->get($code . '_status') && $this->config->get($code . '_card')) {
                $this->load->language('extension/credit_card/' . $code);

                $data['credit_cards'][] = array(
                    'name' => $this->language->get('heading_title'),
                    'href' => $this->router->url('extension/credit_card/' . $code)
                );
            }
        }

        $data['wishlist'] = $this->router->url('account/wishlist');
        $data['order'] = $this->router->url('account/order');
        $data['download'] = $this->router->url('account/download');

        if ($this->config->get('reward_status')) {
            $data['reward'] = $this->router->url('account/reward');
        } else {
            $data['reward'] = '';
        }

        $data['return'] = $this->router->url('account/return');
        $data['transaction'] = $this->router->url('account/transaction');
        $data['newsletter'] = $this->router->url('account/newsletter');
        $data['recurring'] = $this->router->url('account/recurring');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/account', $data));
    }
}
