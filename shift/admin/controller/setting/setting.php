<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

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
            $this->request->getArray('post', [])
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
        $this->load->config('setting/setting');
        $this->load->model('setting/setting');
        $this->load->language('setting/setting');

        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('setting.setting.form'),
            $this->request->getArray('post', [])
        );

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        Arr::unset($post, ['form', 'action', 'timezone']);

        $this->model_setting_setting->editSetting('system', 'setting', $post);

        // Clear all cache
        if ($post['development']) {
            $this->load->controller('tool/cache/purge');
        }

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->range(0, 10)->check($post['compression'])) {
            $errors['items']['compression'] = sprintf($this->language->get('error_value_between'), 0, 10);
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
