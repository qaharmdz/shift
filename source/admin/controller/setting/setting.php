<?php

declare(strict_types=1);

class ControllerSettingSetting extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('config', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_review'] = $this->language->get('text_review');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_captcha'] = $this->language->get('text_captcha');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_shipping'] = $this->language->get('text_shipping');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_mail'] = $this->language->get('text_mail');
        $data['text_smtp'] = $this->language->get('text_smtp');
        $data['text_mail_alert'] = $this->language->get('text_mail_alert');
        $data['text_mail_account'] = $this->language->get('text_mail_account');
        $data['text_mail_affiliate'] = $this->language->get('text_mail_affiliate');
        $data['text_mail_order']  = $this->language->get('text_mail_order');
        $data['text_mail_review'] = $this->language->get('text_mail_review');
        $data['text_general'] = $this->language->get('text_general');
        $data['text_security'] = $this->language->get('text_security');
        $data['text_upload'] = $this->language->get('text_upload');
        $data['text_error'] = $this->language->get('text_error');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_owner'] = $this->language->get('entry_owner');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_geocode'] = $this->language->get('entry_geocode');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_open'] = $this->language->get('entry_open');
        $data['entry_comment'] = $this->language->get('entry_comment');
        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_theme'] = $this->language->get('entry_theme');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_language'] = $this->language->get('entry_language');
        $data['entry_admin_language'] = $this->language->get('entry_admin_language');
        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['entry_currency_auto'] = $this->language->get('entry_currency_auto');
        $data['entry_length_class'] = $this->language->get('entry_length_class');
        $data['entry_weight_class'] = $this->language->get('entry_weight_class');
        $data['entry_limit_admin'] = $this->language->get('entry_limit_admin');
        $data['entry_product_count'] = $this->language->get('entry_product_count');
        $data['entry_review'] = $this->language->get('entry_review');
        $data['entry_review_guest'] = $this->language->get('entry_review_guest');
        $data['entry_voucher_min'] = $this->language->get('entry_voucher_min');
        $data['entry_voucher_max'] = $this->language->get('entry_voucher_max');
        $data['entry_tax'] = $this->language->get('entry_tax');
        $data['entry_tax_default'] = $this->language->get('entry_tax_default');
        $data['entry_tax_customer'] = $this->language->get('entry_tax_customer');
        $data['entry_customer_online'] = $this->language->get('entry_customer_online');
        $data['entry_customer_activity'] = $this->language->get('entry_customer_activity');
        $data['entry_customer_search'] = $this->language->get('entry_customer_search');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
        $data['entry_customer_price'] = $this->language->get('entry_customer_price');
        $data['entry_login_attempts'] = $this->language->get('entry_login_attempts');
        $data['entry_account'] = $this->language->get('entry_account');
        $data['entry_invoice_prefix'] = $this->language->get('entry_invoice_prefix');
        $data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
        $data['entry_checkout_guest'] = $this->language->get('entry_checkout_guest');
        $data['entry_checkout'] = $this->language->get('entry_checkout');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_processing_status'] = $this->language->get('entry_processing_status');
        $data['entry_complete_status'] = $this->language->get('entry_complete_status');
        $data['entry_fraud_status'] = $this->language->get('entry_fraud_status');
        $data['entry_api'] = $this->language->get('entry_api');
        $data['entry_stock_display'] = $this->language->get('entry_stock_display');
        $data['entry_stock_warning'] = $this->language->get('entry_stock_warning');
        $data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
        $data['entry_affiliate_approval'] = $this->language->get('entry_affiliate_approval');
        $data['entry_affiliate_auto'] = $this->language->get('entry_affiliate_auto');
        $data['entry_affiliate_commission'] = $this->language->get('entry_affiliate_commission');
        $data['entry_affiliate'] = $this->language->get('entry_affiliate');
        $data['entry_return'] = $this->language->get('entry_return');
        $data['entry_return_status'] = $this->language->get('entry_return_status');
        $data['entry_captcha'] = $this->language->get('entry_captcha');
        $data['entry_captcha_page'] = $this->language->get('entry_captcha_page');
        $data['entry_logo'] = $this->language->get('entry_logo');
        $data['entry_icon'] = $this->language->get('entry_icon');
        $data['entry_ftp_hostname'] = $this->language->get('entry_ftp_hostname');
        $data['entry_ftp_port'] = $this->language->get('entry_ftp_port');
        $data['entry_ftp_username'] = $this->language->get('entry_ftp_username');
        $data['entry_ftp_password'] = $this->language->get('entry_ftp_password');
        $data['entry_ftp_root'] = $this->language->get('entry_ftp_root');
        $data['entry_ftp_status'] = $this->language->get('entry_ftp_status');
        $data['entry_mail_protocol'] = $this->language->get('entry_mail_protocol');
        $data['entry_mail_parameter'] = $this->language->get('entry_mail_parameter');
        $data['entry_mail_smtp_hostname'] = $this->language->get('entry_mail_smtp_hostname');
        $data['entry_mail_smtp_username'] = $this->language->get('entry_mail_smtp_username');
        $data['entry_mail_smtp_password'] = $this->language->get('entry_mail_smtp_password');
        $data['entry_mail_smtp_port'] = $this->language->get('entry_mail_smtp_port');
        $data['entry_mail_smtp_timeout'] = $this->language->get('entry_mail_smtp_timeout');
        $data['entry_mail_alert'] = $this->language->get('entry_mail_alert');
        $data['entry_mail_alert_email'] = $this->language->get('entry_mail_alert_email');
        $data['entry_alert_email'] = $this->language->get('entry_alert_email');
        $data['entry_secure'] = $this->language->get('entry_secure');
        $data['entry_shared'] = $this->language->get('entry_shared');
        $data['entry_robots'] = $this->language->get('entry_robots');
        $data['entry_file_max_size'] = $this->language->get('entry_file_max_size');
        $data['entry_file_ext_allowed'] = $this->language->get('entry_file_ext_allowed');
        $data['entry_file_mime_allowed'] = $this->language->get('entry_file_mime_allowed');
        $data['entry_maintenance'] = $this->language->get('entry_maintenance');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_encryption'] = $this->language->get('entry_encryption');
        $data['entry_seo_url'] = $this->language->get('entry_seo_url');
        $data['entry_compression'] = $this->language->get('entry_compression');
        $data['entry_error_display'] = $this->language->get('entry_error_display');
        $data['entry_error_log'] = $this->language->get('entry_error_log');
        $data['entry_error_filename'] = $this->language->get('entry_error_filename');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['help_geocode'] = $this->language->get('help_geocode');
        $data['help_open'] = $this->language->get('help_open');
        $data['help_comment'] = $this->language->get('help_comment');
        $data['help_location'] = $this->language->get('help_location');
        $data['help_currency'] = $this->language->get('help_currency');
        $data['help_currency_auto'] = $this->language->get('help_currency_auto');
        $data['help_limit_admin'] = $this->language->get('help_limit_admin');
        $data['help_product_count'] = $this->language->get('help_product_count');
        $data['help_review'] = $this->language->get('help_review');
        $data['help_review_guest'] = $this->language->get('help_review_guest');
        $data['help_voucher_min'] = $this->language->get('help_voucher_min');
        $data['help_voucher_max'] = $this->language->get('help_voucher_max');
        $data['help_tax_default'] = $this->language->get('help_tax_default');
        $data['help_tax_customer'] = $this->language->get('help_tax_customer');
        $data['help_customer_online'] = $this->language->get('help_customer_online');
        $data['help_customer_activity'] = $this->language->get('help_customer_activity');
        $data['help_customer_group'] = $this->language->get('help_customer_group');
        $data['help_customer_group_display'] = $this->language->get('help_customer_group_display');
        $data['help_customer_price'] = $this->language->get('help_customer_price');
        $data['help_login_attempts'] = $this->language->get('help_login_attempts');
        $data['help_account'] = $this->language->get('help_account');
        $data['help_cart_weight'] = $this->language->get('help_cart_weight');
        $data['help_checkout_guest'] = $this->language->get('help_checkout_guest');
        $data['help_checkout'] = $this->language->get('help_checkout');
        $data['help_invoice_prefix'] = $this->language->get('help_invoice_prefix');
        $data['help_order_status'] = $this->language->get('help_order_status');
        $data['help_processing_status'] = $this->language->get('help_processing_status');
        $data['help_complete_status'] = $this->language->get('help_complete_status');
        $data['help_fraud_status'] = $this->language->get('help_fraud_status');
        $data['help_api'] = $this->language->get('help_api');
        $data['help_stock_display'] = $this->language->get('help_stock_display');
        $data['help_stock_warning'] = $this->language->get('help_stock_warning');
        $data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
        $data['help_affiliate_approval'] = $this->language->get('help_affiliate_approval');
        $data['help_affiliate_auto'] = $this->language->get('help_affiliate_auto');
        $data['help_affiliate_commission'] = $this->language->get('help_affiliate_commission');
        $data['help_affiliate'] = $this->language->get('help_affiliate');
        $data['help_commission'] = $this->language->get('help_commission');
        $data['help_return'] = $this->language->get('help_return');
        $data['help_return_status'] = $this->language->get('help_return_status');
        $data['help_captcha'] = $this->language->get('help_captcha');
        $data['help_icon'] = $this->language->get('help_icon');
        $data['help_ftp_root'] = $this->language->get('help_ftp_root');
        $data['help_mail_protocol'] = $this->language->get('help_mail_protocol');
        $data['help_mail_parameter'] = $this->language->get('help_mail_parameter');
        $data['help_mail_smtp_hostname'] = $this->language->get('help_mail_smtp_hostname');
        $data['help_mail_smtp_password'] = $this->language->get('help_mail_smtp_password');
        $data['help_mail_alert'] = $this->language->get('help_mail_alert');
        $data['help_mail_alert_email'] = $this->language->get('help_mail_alert_email');
        $data['help_secure'] = $this->language->get('help_secure');
        $data['help_shared'] = $this->language->get('help_shared');
        $data['help_robots'] = $this->language->get('help_robots');
        $data['help_seo_url'] = $this->language->get('help_seo_url');
        $data['help_file_max_size'] = $this->language->get('help_file_max_size');
        $data['help_file_ext_allowed'] = $this->language->get('help_file_ext_allowed');
        $data['help_file_mime_allowed'] = $this->language->get('help_file_mime_allowed');
        $data['help_maintenance'] = $this->language->get('help_maintenance');
        $data['help_password'] = $this->language->get('help_password');
        $data['help_encryption'] = $this->language->get('help_encryption');
        $data['help_compression'] = $this->language->get('help_compression');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_store'] = $this->language->get('tab_store');
        $data['tab_local'] = $this->language->get('tab_local');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_ftp'] = $this->language->get('tab_ftp');
        $data['tab_mail'] = $this->language->get('tab_mail');
        $data['tab_server'] = $this->language->get('tab_server');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['owner'])) {
            $data['error_owner'] = $this->error['owner'];
        } else {
            $data['error_owner'] = '';
        }

        if (isset($this->error['address'])) {
            $data['error_address'] = $this->error['address'];
        } else {
            $data['error_address'] = '';
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

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = '';
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

        if (isset($this->error['customer_group_display'])) {
            $data['error_customer_group_display'] = $this->error['customer_group_display'];
        } else {
            $data['error_customer_group_display'] = '';
        }

        if (isset($this->error['login_attempts'])) {
            $data['error_login_attempts'] = $this->error['login_attempts'];
        } else {
            $data['error_login_attempts'] = '';
        }

        if (isset($this->error['voucher_min'])) {
            $data['error_voucher_min'] = $this->error['voucher_min'];
        } else {
            $data['error_voucher_min'] = '';
        }

        if (isset($this->error['voucher_max'])) {
            $data['error_voucher_max'] = $this->error['voucher_max'];
        } else {
            $data['error_voucher_max'] = '';
        }

        if (isset($this->error['processing_status'])) {
            $data['error_processing_status'] = $this->error['processing_status'];
        } else {
            $data['error_processing_status'] = '';
        }

        if (isset($this->error['complete_status'])) {
            $data['error_complete_status'] = $this->error['complete_status'];
        } else {
            $data['error_complete_status'] = '';
        }

        if (isset($this->error['ftp_hostname'])) {
            $data['error_ftp_hostname'] = $this->error['ftp_hostname'];
        } else {
            $data['error_ftp_hostname'] = '';
        }

        if (isset($this->error['ftp_port'])) {
            $data['error_ftp_port'] = $this->error['ftp_port'];
        } else {
            $data['error_ftp_port'] = '';
        }

        if (isset($this->error['ftp_username'])) {
            $data['error_ftp_username'] = $this->error['ftp_username'];
        } else {
            $data['error_ftp_username'] = '';
        }

        if (isset($this->error['ftp_password'])) {
            $data['error_ftp_password'] = $this->error['ftp_password'];
        } else {
            $data['error_ftp_password'] = '';
        }

        if (isset($this->error['error_filename'])) {
            $data['error_error_filename'] = $this->error['error_filename'];
        } else {
            $data['error_error_filename'] = '';
        }

        if (isset($this->error['limit_admin'])) {
            $data['error_limit_admin'] = $this->error['limit_admin'];
        } else {
            $data['error_limit_admin'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_stores'),
            'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true)
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['config_meta_title'])) {
            $data['config_meta_title'] = $this->request->post['config_meta_title'];
        } else {
            $data['config_meta_title'] = $this->config->get('config_meta_title');
        }

        if (isset($this->request->post['config_meta_description'])) {
            $data['config_meta_description'] = $this->request->post['config_meta_description'];
        } else {
            $data['config_meta_description'] = $this->config->get('config_meta_description');
        }

        if (isset($this->request->post['config_meta_keyword'])) {
            $data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
        } else {
            $data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
        }

        if (isset($this->request->post['config_theme'])) {
            $data['config_theme'] = $this->request->post['config_theme'];
        } else {
            $data['config_theme'] = $this->config->get('config_theme');
        }

        if ($this->request->server['HTTPS']) {
            $data['store_url'] = HTTPS_CATALOG;
        } else {
            $data['store_url'] = HTTP_CATALOG;
        }

        $data['themes'] = array();

        $this->load->model('extension/extension');

        $extensions = $this->model_extension_extension->getInstalled('theme');

        foreach ($extensions as $code) {
            $this->load->language('extension/theme/' . $code);

            $data['themes'][] = array(
                'text'  => $this->language->get('heading_title'),
                'value' => $code
            );
        }

        if (isset($this->request->post['config_layout_id'])) {
            $data['config_layout_id'] = $this->request->post['config_layout_id'];
        } else {
            $data['config_layout_id'] = $this->config->get('config_layout_id');
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        if (isset($this->request->post['config_name'])) {
            $data['config_name'] = $this->request->post['config_name'];
        } else {
            $data['config_name'] = $this->config->get('config_name');
        }

        if (isset($this->request->post['config_owner'])) {
            $data['config_owner'] = $this->request->post['config_owner'];
        } else {
            $data['config_owner'] = $this->config->get('config_owner');
        }

        if (isset($this->request->post['config_address'])) {
            $data['config_address'] = $this->request->post['config_address'];
        } else {
            $data['config_address'] = $this->config->get('config_address');
        }

        if (isset($this->request->post['config_email'])) {
            $data['config_email'] = $this->request->post['config_email'];
        } else {
            $data['config_email'] = $this->config->get('config_email');
        }

        if (isset($this->request->post['config_telephone'])) {
            $data['config_telephone'] = $this->request->post['config_telephone'];
        } else {
            $data['config_telephone'] = $this->config->get('config_telephone');
        }

        if (isset($this->request->post['config_fax'])) {
            $data['config_fax'] = $this->request->post['config_fax'];
        } else {
            $data['config_fax'] = $this->config->get('config_fax');
        }

        if (isset($this->request->post['config_image'])) {
            $data['config_image'] = $this->request->post['config_image'];
        } else {
            $data['config_image'] = $this->config->get('config_image');
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
        } elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
            $data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['config_country_id'])) {
            $data['config_country_id'] = $this->request->post['config_country_id'];
        } else {
            $data['config_country_id'] = $this->config->get('config_country_id');
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['config_language'])) {
            $data['config_language'] = $this->request->post['config_language'];
        } else {
            $data['config_language'] = $this->config->get('config_language');
        }

        if (isset($this->request->post['config_admin_language'])) {
            $data['config_admin_language'] = $this->request->post['config_admin_language'];
        } else {
            $data['config_admin_language'] = $this->config->get('config_admin_language');
        }

        if (isset($this->request->post['config_limit_admin'])) {
            $data['config_limit_admin'] = $this->request->post['config_limit_admin'];
        } else {
            $data['config_limit_admin'] = $this->config->get('config_limit_admin');
        }

        $this->load->model('catalog/information');
        $data['informations'] = $this->model_catalog_information->getInformations();

        if (isset($this->request->post['config_logo'])) {
            $data['config_logo'] = $this->request->post['config_logo'];
        } else {
            $data['config_logo'] = $this->config->get('config_logo');
        }

        if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
            $data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
        } elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
        } else {
            $data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_icon'])) {
            $data['config_icon'] = $this->request->post['config_icon'];
        } else {
            $data['config_icon'] = $this->config->get('config_icon');
        }

        if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
            $data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
        } elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
        } else {
            $data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        if (isset($this->request->post['config_mail_protocol'])) {
            $data['config_mail_protocol'] = $this->request->post['config_mail_protocol'];
        } else {
            $data['config_mail_protocol'] = $this->config->get('config_mail_protocol');
        }

        if (isset($this->request->post['config_mail_parameter'])) {
            $data['config_mail_parameter'] = $this->request->post['config_mail_parameter'];
        } else {
            $data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
        }

        if (isset($this->request->post['config_mail_smtp_hostname'])) {
            $data['config_mail_smtp_hostname'] = $this->request->post['config_mail_smtp_hostname'];
        } else {
            $data['config_mail_smtp_hostname'] = $this->config->get('config_mail_smtp_hostname');
        }

        if (isset($this->request->post['config_mail_smtp_username'])) {
            $data['config_mail_smtp_username'] = $this->request->post['config_mail_smtp_username'];
        } else {
            $data['config_mail_smtp_username'] = $this->config->get('config_mail_smtp_username');
        }

        if (isset($this->request->post['config_mail_smtp_password'])) {
            $data['config_mail_smtp_password'] = $this->request->post['config_mail_smtp_password'];
        } else {
            $data['config_mail_smtp_password'] = $this->config->get('config_mail_smtp_password');
        }

        if (isset($this->request->post['config_mail_smtp_port'])) {
            $data['config_mail_smtp_port'] = $this->request->post['config_mail_smtp_port'];
        } elseif ($this->config->has('config_mail_smtp_port')) {
            $data['config_mail_smtp_port'] = $this->config->get('config_mail_smtp_port');
        } else {
            $data['config_mail_smtp_port'] = 25;
        }

        if (isset($this->request->post['config_mail_smtp_timeout'])) {
            $data['config_mail_smtp_timeout'] = $this->request->post['config_mail_smtp_timeout'];
        } elseif ($this->config->has('config_mail_smtp_timeout')) {
            $data['config_mail_smtp_timeout'] = $this->config->get('config_mail_smtp_timeout');
        } else {
            $data['config_mail_smtp_timeout'] = 5;
        }

        if (isset($this->request->post['config_mail_alert'])) {
            $data['config_mail_alert'] = $this->request->post['config_mail_alert'];
        } elseif ($this->config->has('config_mail_alert')) {
            $data['config_mail_alert'] = $this->config->get('config_mail_alert');
        } else {
            $data['config_mail_alert'] = array();
        }

        $data['mail_alerts'] = array();
        $data['mail_alerts'][] = array(
            'text'  => $this->language->get('text_mail_account'),
            'value' => 'account'
        );

        if (isset($this->request->post['config_alert_email'])) {
            $data['config_alert_email'] = $this->request->post['config_alert_email'];
        } else {
            $data['config_alert_email'] = $this->config->get('config_alert_email');
        }

        if (isset($this->request->post['config_secure'])) {
            $data['config_secure'] = $this->request->post['config_secure'];
        } else {
            $data['config_secure'] = $this->config->get('config_secure');
        }

        if (isset($this->request->post['config_shared'])) {
            $data['config_shared'] = $this->request->post['config_shared'];
        } else {
            $data['config_shared'] = $this->config->get('config_shared');
        }

        if (isset($this->request->post['config_robots'])) {
            $data['config_robots'] = $this->request->post['config_robots'];
        } else {
            $data['config_robots'] = $this->config->get('config_robots');
        }

        if (isset($this->request->post['config_seo_url'])) {
            $data['config_seo_url'] = $this->request->post['config_seo_url'];
        } else {
            $data['config_seo_url'] = $this->config->get('config_seo_url');
        }

        if (isset($this->request->post['config_file_max_size'])) {
            $data['config_file_max_size'] = $this->request->post['config_file_max_size'];
        } elseif ($this->config->get('config_file_max_size')) {
            $data['config_file_max_size'] = $this->config->get('config_file_max_size');
        } else {
            $data['config_file_max_size'] = 300000;
        }

        if (isset($this->request->post['config_file_ext_allowed'])) {
            $data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
        } else {
            $data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
        }

        if (isset($this->request->post['config_file_mime_allowed'])) {
            $data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
        } else {
            $data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
        }

        if (isset($this->request->post['config_maintenance'])) {
            $data['config_maintenance'] = $this->request->post['config_maintenance'];
        } else {
            $data['config_maintenance'] = $this->config->get('config_maintenance');
        }

        if (isset($this->request->post['config_password'])) {
            $data['config_password'] = $this->request->post['config_password'];
        } else {
            $data['config_password'] = $this->config->get('config_password');
        }

        if (isset($this->request->post['config_encryption'])) {
            $data['config_encryption'] = $this->request->post['config_encryption'];
        } else {
            $data['config_encryption'] = $this->config->get('config_encryption');
        }

        if (isset($this->request->post['config_compression'])) {
            $data['config_compression'] = $this->request->post['config_compression'];
        } else {
            $data['config_compression'] = $this->config->get('config_compression');
        }

        if (isset($this->request->post['config_error_display'])) {
            $data['config_error_display'] = $this->request->post['config_error_display'];
        } else {
            $data['config_error_display'] = $this->config->get('config_error_display');
        }

        if (isset($this->request->post['config_error_log'])) {
            $data['config_error_log'] = $this->request->post['config_error_log'];
        } else {
            $data['config_error_log'] = $this->config->get('config_error_log');
        }

        if (isset($this->request->post['config_error_filename'])) {
            $data['config_error_filename'] = $this->request->post['config_error_filename'];
        } else {
            $data['config_error_filename'] = $this->config->get('config_error_filename');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('setting/setting', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['config_meta_title']) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        if (!$this->request->post['config_name']) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }

        if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }

        if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
            $this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
        }

        if (!$this->request->post['config_limit_admin']) {
            $this->error['limit_admin'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['config_error_filename']) {
            $this->error['error_filename'] = $this->language->get('error_error_filename');
        } else {
            if (preg_match('/\.\.[\/\\\]?/', $this->request->post['config_error_filename'])) {
                $this->error['error_filename'] = $this->language->get('error_malformed_filename');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    public function theme()
    {
        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        // This is only here for compatibility with old themes.
        if ($this->request->get['theme'] == 'theme_default') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = basename($this->request->get['theme']);
        }

        if (is_file(DIR_CATALOG . 'view/theme/' . $theme . '/image/' . $theme . '.png')) {
            $this->response->setOutput($server . 'catalog/view/theme/' . $theme . '/image/' . $theme . '.png');
        } else {
            $this->response->setOutput($server . 'image/no_image.png');
        }
    }
}
