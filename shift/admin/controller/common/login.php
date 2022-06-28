<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Core\Mvc;

class Login extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('common/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->user->isLogged() && $this->request->get('query.token', time()) == $this->session->get('token')) {
            $this->response->redirect($this->router->url('common/dashboard', 'token=' . $this->session->get('token')));
        }

        if ($this->request->is('POST') && $this->validate()) {
            $this->session->set('token', token(32));

            $this->response->redirect($this->router->url('common/dashboard', 'token=' . $this->session->get('token')));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_login'] = $this->language->get('text_login');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_login'] = $this->language->get('button_login');

        if (!$this->request->isEmpty('query.token') && $this->request->get('query.token') != $this->session->get('token')) {
            $this->error['warning'] = $this->language->get('error_token');
        }

        $data['action']        = $this->router->url('common/login');
        $data['success']       = $this->session->pull('flash.success');
        $data['error_warning'] = $this->error['warning'] ?? '';

        $data['username']      = $this->request->getString('post.username');
        $data['password']      = $this->request->getString('post.password');

        $data['redirect']      = '';
        if ($route = $this->request->get('query.route')) {
            $this->request->delete('query.route');
            $this->request->delete('query.token');

            $url = '';
            if ($this->request->get('query')) {
                $url .= http_build_query($this->request->get('query'));
            }

            $data['redirect'] = $this->router->url($route, $url);
        }

        $data['forgotten'] = '';
        if ($this->config->get('config_password')) {
            $data['forgotten'] = $this->router->url('common/forgotten');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/login', $data));
    }

    protected function validate()
    {
        if (
            !$this->request->has('post.username')
            || !$this->request->has('post.password')
            || !$this->user->login(
                $this->request->get('post.username'),
                html_entity_decode($this->request->get('post.password'), ENT_QUOTES, 'UTF-8')
            )
        ) {
            $this->error['warning'] = $this->language->get('error_login');
        }

        return !$this->error;
    }
}
