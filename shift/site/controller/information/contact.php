<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Information;

use Shift\System\Mvc;

class Contact extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('information/contact');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->is('post') && $this->validate()) {
            $mail = $this->mail->getInstance();
            $mail->setFrom($this->config->get('system.site.email'), $this->config->get('system.site.name'));
            $mail->addAddress($this->config->get('system.site.email'), $this->config->get('system.site.name'));
            $mail->addReplyTo($this->request->get('post.email'), $this->request->get('post.name'));

            $mail->Subject = html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->get('post.name')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $mail->Body    = html_entity_decode($this->request->get('post.enquiry'), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $mail->isHTML(false);
            $mail->send();

            $this->response->redirect($this->router->url('information/contact/success'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('information/contact')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_location'] = $this->language->get('text_location');
        $data['text_site'] = $this->language->get('text_site');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_open'] = $this->language->get('text_open');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');

        $data['button_map'] = $this->language->get('button_map');

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['enquiry'])) {
            $data['error_enquiry'] = $this->error['enquiry'];
        } else {
            $data['error_enquiry'] = '';
        }

        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->router->url('information/contact');

        if ($this->config->get('system.setting.image')) {
            $data['image'] = $this->image->construct($this->config->get('system.setting.image'), (int)$this->config->get($this->config->get('system.site.theme') . '_image_location_width'), (int)$this->config->get($this->config->get('system.site.theme') . '_image_location_height'));
        } else {
            $data['image'] = false;
        }

        $data['site'] = $this->config->get('system.site.name');
        $data['address'] = '';
        $data['geocode'] = $this->config->get('system.setting.geocode');
        $data['geocode_hl'] = $this->config->get('system.site.language');
        $data['telephone'] = $this->config->get('system.setting.telephone');
        $data['fax'] = $this->config->get('system.setting.fax');
        $data['open'] = '';
        $data['comment'] = $this->config->get('system.setting.comment');

        $data['locations'] = array();

        $data['name']    = $this->request->getString('post.name', $this->user->get('fullname'));
        $data['email']   = $this->request->getString('post.email', $this->user->get('email'));
        $data['enquiry'] = $this->request->getString('post.enquiry');

        // Captcha
        $data['captcha'] = '';
        if ($this->config->get($this->config->get('system.setting.captcha') . '_status') && in_array('contact', (array)$this->config->get('system.setting.captcha_page'))) {
            $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('system.setting.captcha'), $this->error);
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('information/contact', $data));
    }

    protected function validate()
    {
        if ((utf8_strlen($this->request->get('post.name')) < 3) || (utf8_strlen($this->request->get('post.name')) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!filter_var($this->request->get('post.email'), FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->get('post.enquiry')) < 10) || (utf8_strlen($this->request->get('post.enquiry')) > 3000)) {
            $this->error['enquiry'] = $this->language->get('error_enquiry');
        }

        // Captcha
        if ($this->config->get($this->config->get('system.setting.captcha') . '_status') && in_array('contact', (array)$this->config->get('system.setting.captcha_page'))) {
            $captcha = $this->load->controller('extension/captcha/' . $this->config->get('system.setting.captcha') . '/validate');

            if ($captcha) {
                $this->error['captcha'] = $captcha;
            }
        }

        return !$this->error;
    }

    public function success()
    {
        $this->load->language('information/contact');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('information/contact')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_success');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->router->url('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('common/success', $data));
    }
}
