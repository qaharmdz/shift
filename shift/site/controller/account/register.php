<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Account;

use Shift\System\Core\Mvc;

class Register extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        if ($this->user->isLogged()) {
            $this->response->redirect($this->url->link('account/account', '', true));
        }

        $this->load->language('account/register');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('asset/script/jquery/datetimepicker/moment.js');
        $this->document->addScript('asset/script/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('asset/script/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        if ($this->request->is('post') && $this->validate()) {
            $this->user->login($this->request->getString('post.email'), $this->request->getString('post.password'));

            $this->response->redirect($this->url->link('account/success'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_register'),
            'href' => $this->url->link('account/register', '', true)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', true));
        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_address'] = $this->language->get('text_your_address');
        $data['text_your_password'] = $this->language->get('text_your_password');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_upload'] = $this->language->get('button_upload');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['address_1'])) {
            $data['error_address_1'] = $this->error['address_1'];
        } else {
            $data['error_address_1'] = '';
        }

        if (isset($this->error['city'])) {
            $data['error_city'] = $this->error['city'];
        } else {
            $data['error_city'] = '';
        }

        if (isset($this->error['postcode'])) {
            $data['error_postcode'] = $this->error['postcode'];
        } else {
            $data['error_postcode'] = '';
        }

        if (isset($this->error['country'])) {
            $data['error_country'] = $this->error['country'];
        } else {
            $data['error_country'] = '';
        }

        if (isset($this->error['zone'])) {
            $data['error_zone'] = $this->error['zone'];
        } else {
            $data['error_zone'] = '';
        }

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = array();
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        $data['action'] = $this->url->link('account/register', '', true);

        $data['customer_group_id'] = $this->request->get('post.customer_group_id', $this->config->get('config_customer_group_id'));
        $data['firstname']         = $this->request->getString('post.firstname');
        $data['lastname']          = $this->request->getString('post.lastname');
        $data['email']             = $this->request->getString('post.email');
        $data['password']          = $this->request->getString('post.password');
        $data['confirm']           = $this->request->getString('post.confirm');

        // Captcha
        if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
            $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
        } else {
            $data['captcha'] = '';
        }

        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        $data['agree'] = $this->request->getInt('post.agree');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/register', $data));
    }

    private function validate()
    {
        if ((utf8_strlen(trim($this->request->get('post.firstname'))) < 1) || (utf8_strlen(trim($this->request->get('post.firstname'))) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->get('post.lastname'))) < 1) || (utf8_strlen(trim($this->request->get('post.lastname'))) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->get('post.email')) > 96) || !filter_var($this->request->get('post.email'), FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        // if ($this->model_account_customer->getTotalCustomersByEmail($this->request->get('post.email'))) {
        //     $this->error['warning'] = $this->language->get('error_exists');
        // }

        // Customer Group
        if ($this->request->has('post.customer_group_id') && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get('post.customer_group_id'), $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get('post.customer_group_id');
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
        $post_fields   = $this->request->getArray('post.custom_field');

        foreach ($custom_fields as $custom_field) {
            if ($custom_field['required'] && empty($post_fields[$custom_field['location']][$custom_field['custom_field_id']])) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            } elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($post_fields[$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        if ((utf8_strlen($this->request->get('post.password')) < 4) || (utf8_strlen($this->request->get('post.password')) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ($this->request->get('post.confirm') != $this->request->get('post.password')) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        // Captcha
        if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
            $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

            if ($captcha) {
                $this->error['captcha'] = $captcha;
            }
        }

        // Agree to terms
        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info && !$this->request->has('post.agree')) {
                $this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }

        return !$this->error;
    }

    public function customfield()
    {
        $json = array();

        $this->load->model('account/custom_field');

        // Customer Group
        if ($this->request->has('query.customer_group_id') && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get('query.customer_group_id'), $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->get('query.customer_group_id');
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            $json[] = array(
                'custom_field_id' => $custom_field['custom_field_id'],
                'required'        => $custom_field['required']
            );
        }

        $this->response->setOutputJson($json);
    }
}
