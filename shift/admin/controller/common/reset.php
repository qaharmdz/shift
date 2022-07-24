<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Mvc;
use Shift\System\Core\Http;

class Reset extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        if ($this->user->isLogged() && $this->request->get('query.token', time()) == $this->session->get('token')) {
            $this->response->redirect($this->router->url('common/dashboard'));
        }

        if (!$this->config->get('system.setting.password')) {
            $this->response->redirect($this->router->url('common/login'));
        }

        $code = $this->request->get('query.code', '');

        $this->load->model('user/user');

        $user_info = $this->model_user_user->getUserByCode($code);

        if ($user_info) {
            $this->load->language('common/reset');

            $this->document->setTitle($this->language->get('heading_title'));

            if ($this->request->is('POST') && $this->validate()) {
                $this->model_user_user->editPassword($user_info['user_id'], $this->request->get('post.password'));

                $this->session->set('flash.success', $this->language->get('text_success'));

                $this->response->redirect($this->router->url('common/login'));
            }

            $data['heading_title'] = $this->language->get('heading_title');
            $data['text_password'] = $this->language->get('text_password');

            $data['entry_password'] = $this->language->get('entry_password');
            $data['entry_confirm'] = $this->language->get('entry_confirm');

            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');

            $data['breadcrumbs'] = array();
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->router->url('common/dashboard')
            );
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->router->url('common/reset')
            );

            $data['error_password'] = '';
            if (isset($this->error['password'])) {
                $data['error_password'] = $this->error['password'];
            }

            $data['error_confirm'] = '';
            if (isset($this->error['confirm'])) {
                $data['error_confirm'] = $this->error['confirm'];
            }

            $data['action'] = $this->router->url('common/reset', 'code=' . $code);
            $data['cancel'] = $this->router->url('common/login');

            $data['password'] = $this->request->get('post.password', '');
            $data['confirm']  = $this->request->get('post.confirm', '');

            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('common/reset', $data));
        } else {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSettingValue('config', 'config_password', '0');

            return new Http\Dispatch('common/login');
        }
    }

    protected function validate()
    {
        $password = $this->request->get('post.password', '');
        $confirm  = $this->request->get('post.confirm', '');

        if ((utf8_strlen($password) < 4) || (utf8_strlen($password) > 20)) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ($confirm != $password) {
            $this->error['confirm'] = $this->language->get('error_confirm');
        }

        return !$this->error;
    }
}
