<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Mvc;

class Forgotten extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        if ($this->user->isLogged() && $this->request->get('query.token', time()) == $this->session->get('token')) {
            $this->response->redirect($this->router->url('page/dashboard'));
        }

        if (!$this->config->get('system.setting.password')) {
            $this->response->redirect($this->router->url('common/login'));
        }

        $this->load->language('common/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('user/user');

        if ($this->request->is('POST') && $this->validate()) {
            $this->load->language('mail/forgotten');

            $code  = $this->secure->token('hash', 48);
            $email = $this->request->getString('post.email');

            $this->model_user_user->editCode($email, $code);

            $subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('system.site.name'), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            $message  = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('system.site.name'), ENT_QUOTES | ENT_HTML5, 'UTF-8')) . "\n\n";
            $message .= $this->language->get('text_change') . "\n\n";
            $message .= $this->router->url('common/reset', 'code=' . $code) . "\n\n";
            $message .= sprintf($this->language->get('text_ip'), $this->request->getIp()) . "\n\n";

            $mail = $this->mail->getInstance();
            $mail->setFrom($this->config->get('system.site.email'), $this->config->get('system.site.name'));
            $mail->addAddress($email);

            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->isHTML(false);
            $mail->send();

            $this->session->set('flash.success', $this->language->get('text_success'));

            $this->response->redirect($this->router->url('common/login'));
        }

        $data['heading_title']   = $this->language->get('heading_title');

        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_email']      = $this->language->get('text_email');
        $data['entry_email']     = $this->language->get('entry_email');
        $data['button_reset']    = $this->language->get('button_reset');
        $data['button_cancel']   = $this->language->get('button_cancel');

        $data['error_warning'] = '';
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('common/forgotten', 'token=' . '')
        );

        $data['action'] = $this->router->url('common/forgotten');
        $data['cancel'] = $this->router->url('common/login');
        $data['email']  = $this->request->get('post.email', '');

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/forgotten', $data));
    }

    protected function validate()
    {
        if ($this->request->isEmpty('post.email') || !$this->model_user_user->getTotalUsersByEmail($this->request->get('post.email'))) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        return !$this->error;
    }
}
