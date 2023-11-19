<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Page;

use Shift\System\Mvc;

class Login extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('page/login');

        $this->document->setTitle($this->language->get('page_title'));

        if ($this->request->is('post') && $this->validate($this->request->getArray('post'))) {
            $route = $this->session->pull('flash.auth.after_login', $this->config->get('root.route_default'));

            $this->session->delete('flash.auth');
            $this->response->redirect($this->router->url($route));
        }

        // Hide require login alert if redirected from default route.
        if ($this->request->is('get') && $this->session->get('flash.auth.after_login') == $this->config->get('root.route_default')) {
            $this->session->pull('flash.auth.require_login');
        }

        if ($this->session->pull('flash.auth.require_login', false)) {
            $this->session->push('flash.alert.warning', $this->language->get('error_require_login'));
        }
        if ($this->session->pull('flash.auth.unauthorize', false)) {
            $this->session->push('flash.alert.warning', $this->language->get('error_unauthorize'));
        }
        if ($this->session->pull('flash.auth.inactive', false)) {
            $this->session->push('flash.alert.warning', $this->language->get('error_inactive'));
        }
        if ($this->session->pull('flash.auth.invalid_token', false)) {
            $this->session->push('flash.alert.warning', $this->language->get('error_token'));
        }

        $data = [];

        $data['alerts']      = $this->session->pull('flash.alert');
        $data['email']       = $this->request->get('post.email');
        $data['password']    = $this->request->get('post.password');

        $this->response->setOutput($this->load->view('page/login', $data));
    }

    protected function validate(array $post): bool
    {
        $errors = [];

        if (!isset($post['email']) || !isset($post['password']) || !$this->user->login($post['email'], $post['password'])) {
            $errors['warning'][] = $this->language->get('error_account');
        }

        $this->session->mergeRecursive('flash.alert', $errors);

        return !$errors;
    }
}
