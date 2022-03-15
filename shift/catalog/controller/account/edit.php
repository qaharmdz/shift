<?php

declare(strict_types=1);

class ControllerAccountEdit extends Controller
{
    private $error = array();

    public function index()
    {
        if (!$this->user->isLogged()) {
            $this->session->set('flash.redirect', $this->url->link('account/edit', '', true));

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/edit');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('asset/script/jquery/datetimepicker/moment.js');
        $this->document->addScript('asset/script/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('asset/script/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        if ($this->request->is('post') && $this->validate()) {
            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->url->link('account/account', '', true));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit'),
            'href'      => $this->url->link('account/edit', '', true)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
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

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = array();
        }

        $data['action'] = $this->url->link('account/edit', '', true);

        $data['firstname'] = $this->request->getString('post.firstname');
        if (isset($customer_info['firstname'])) {
            $data['firstname'] = $customer_info['firstname'];
        }

        $data['lastname'] = $this->request->getString('post.lastname');
        if (isset($customer_info['lastname'])) {
            $data['lastname'] = $customer_info['lastname'];
        }

        $data['email'] = $this->request->getString('post.email');
        if (isset($customer_info['email'])) {
            $data['email'] = $customer_info['email'];
        }

        $data['back'] = $this->url->link('account/account', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/edit', $data));
    }

    protected function validate()
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

        if (($this->user->getEmail() != $this->request->get('post.email')) && $this->model_account_customer->getTotalCustomersByEmail($this->request->get('post.email'))) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
        $post_fields   = $this->request->getArray('post.custom_field');

        foreach ($custom_fields as $custom_field) {
            if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($post_fields[$custom_field['custom_field_id']])) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            } elseif (($custom_field['location'] == 'account') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($post_fields[$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        return !$this->error;
    }
}
