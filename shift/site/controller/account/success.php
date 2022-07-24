<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Account;

use Shift\System\Mvc;

class Success extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('account/success');

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

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_success'),
            'href' => $this->router->url('account/success')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('system.setting.customer_group_id'));

        if ($customer_group_info && !$customer_group_info['approval']) {
            $data['text_message'] = sprintf($this->language->get('text_message'), $this->router->url('information/contact'));
        } else {
            $data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('system.site.name'), $this->router->url('information/contact'));
        }

        $data['button_continue'] = $this->language->get('button_continue');

        if ($this->cart->hasProducts()) {
            $data['continue'] = $this->router->url('checkout/cart');
        } else {
            $data['continue'] = $this->router->url('account/account');
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('common/success', $data));
    }
}
