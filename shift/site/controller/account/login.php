<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Account;

use Shift\System\Mvc;

class Login extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->model('account/customer');

        if ($this->user->isLogged()) {
            $this->response->redirect($this->router->url('account/account'));
        }

        $this->load->language('account/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->is('post') && $this->validate()) {
            // Add to activity log
            if ($this->config->get('system.setting.customer_activity')) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->user->getId(),
                    'name'        => $this->user->get('fullname'),
                );

                $this->model_account_activity->addActivity('login', $activity_data);
            }

            $this->response->redirect($this->router->url('account/account'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('page/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->router->url('account/account')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_login'),
            'href' => $this->router->url('account/login')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');

        if (!$data['error_warning'] = $this->session->pull('flash.error') && isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }

        $data['action'] = $this->router->url('account/login');
        $data['register'] = $this->router->url('account/register');
        $data['forgotten'] = $this->router->url('account/forgotten');

        $data['redirect'] = $this->session->pull('flash.redirect');
        $data['success'] = $this->session->pull('flash.success');

        $data['email']    = $this->request->getString('post.email');
        $data['password'] = $this->request->getString('post.password');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('account/login', $data));
    }

    protected function validate()
    {
        $email    = $this->request->getString('post.email');
        $password = $this->request->getString('post.password');

        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($email);

        if ($login_info && ($login_info['total'] >= $this->config->get('system.setting.login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);

        if ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->user->login($email, $password)) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($email);
            } else {
                $this->model_account_customer->deleteLoginAttempts($email);
            }
        }

        return !$this->error;
    }
}
