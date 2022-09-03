<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;

class Setting extends Mvc\Controller
{
    public function index()
    {
        $this->load->config('setting/setting');
        $this->load->model('setting/setting');
        $this->load->language('setting/setting');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('system')],
            [$this->language->get('page_title'), $this->router->url('setting/setting')],
        ]);

        $data = [];

        $data['setting'] = array_replace_recursive(
            $this->config->getArray('setting.setting.form'),
            $this->model_setting_setting->getSetting('system', 'setting'),
            $this->request->get('post', [])
        );

        $this->load->model('extension/language');
        $data['languages'] = $this->model_extension_language->getLanguages();

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/setting', $data));
    }

    public function save()
    {
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $this->load->model('setting/setting');
        $this->load->language('setting/setting');

        $output = [];
        $post   = $this->request->getArray('post');

        unset($post['form']);
        unset($post['action']);

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        $this->model_setting_setting->editSetting('system', 'setting', $post);

        $this->response->setOutputJson($output);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->range(0, 10)->check($post['compression'])) {
            $errors['items']['compression'] = sprintf($this->language->get('error_value_between'), 0, 10);
        }

        if (!$this->assert->greaterThanEq(20)->check($post['admin_limit'])) {
            $errors['items']['admin_limit'] = sprintf($this->language->get('error_value_minimum'), 20);
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
