<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Account;

use Shift\System\Core\Mvc;

class Forgotten extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        if ($this->user->isLogged()) {
            $this->response->redirect($this->router->url('account/account'));
        }

        $this->load->language('account/forgotten');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/customer');

        if ($this->request->is('post') && $this->validate()) {
            $this->load->language('mail/forgotten');

            $code = token(40);

            $this->model_account_customer->editCode($this->request->get('post.email'), $code);

            $subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('system.setting.name'), ENT_QUOTES, 'UTF-8'));

            $message  = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('system.setting.name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
            $message .= $this->language->get('text_change') . "\n\n";
            $message .= $this->router->url('account/reset', 'code=' . $code) . "\n\n";
            $message .= sprintf($this->language->get('text_ip'), $this->request->get('server.REMOTE_ADDR')) . "\n\n";

            $mail = new Mail();
            $mail->protocol = $this->config->get('system.setting.mail_protocol');
            $mail->parameter = $this->config->get('system.setting.mail_parameter');
            $mail->smtp_hostname = $this->config->get('system.setting.mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('system.setting.mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('system.setting.mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('system.setting.mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('system.setting.mail_smtp_timeout');

            $mail->setTo($this->request->get('post.email'));
            $mail->setFrom($this->config->get('system.setting.email'));
            $mail->setSender(html_entity_decode($this->config->get('system.setting.name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            $this->session->set('flash.success', $this->language->get('text_success'));

            // Add to activity log
            if ($this->config->get('system.setting.customer_activity')) {
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->get('post.email'));

                if ($customer_info) {
                    $this->load->model('account/activity');

                    $activity_data = array(
                        'customer_id' => $customer_info['customer_id'],
                        'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
                    );

                    $this->model_account_activity->addActivity('forgotten', $activity_data);
                }
            }

            $this->response->redirect($this->router->url('account/login'));
        }

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
            'text' => $this->language->get('text_forgotten'),
            'href' => $this->router->url('account/forgotten')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_email'] = $this->language->get('text_email');

        $data['entry_email'] = $this->language->get('entry_email');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->router->url('account/forgotten');
        $data['back'] = $this->router->url('account/login');
        $data['email'] = $this->request->getString('post.email');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/forgotten', $data));
    }

    protected function validate()
    {
        if (!$this->request->has('post.email')) {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->get('post.email'))) {
            $this->error['warning'] = $this->language->get('error_email');
        }

        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->get('post.email'));

        if ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        return !$this->error;
    }
}
