<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Account;

use Shift\System\Mvc;

class Password extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        if (!$this->user->isLogged()) {
            $this->session->set('flash.redirect', $this->router->url('account/password'));

            $this->response->redirect($this->router->url('account/login'));
        }

        $this->load->language('account/password');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->is('post') && $this->validate()) {
            $this->load->model('account/customer');

            $this->model_account_customer->editPassword($this->user->get('email'), $this->request->get('post.password'));

            $this->session->set('flash.success', $this->language->get('text_success'));

            // Add to activity log
            if ($this->config->get('system.setting.customer_activity')) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->user->getId(),
                    'name'        => $this->user->get('fullname'),
                );

                $this->model_account_activity->addActivity('password', $activity_data);
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
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('account/password')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_password'] = $this->language->get('text_password');

        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm'] = $this->language->get('entry_confirm');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');

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

        $data['action'] = $this->router->url('account/password');

        $data['password'] = $this->request->getString('post.password');
        $data['confirm']  = $this->request->getString('post.confirm');

        $data['back'] = $this->router->url('account/account');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('account/password', $data));
    }

    protected function validate() {
        if ((utf8_strlen($this->request->get('post.password')) < 4) || (utf8_strlen($this->request->get('post.password')) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ($this->request->get('post.confirm') != $this->request->get('post.password')) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        return !$this->error;
    }
}
