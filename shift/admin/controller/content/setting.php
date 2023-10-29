<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Content;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Setting extends Mvc\Controller
{
    public function index()
    {
        $this->load->config('content/setting');
        $this->load->model('setting/site');
        $this->load->model('setting/setting');
        $this->load->language('content/general');
        $this->load->language('content/setting');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content')],
            [$this->language->get('page_title'), $this->router->url('content/setting')],
        ]);

        $data = [];

        $data['site_id'] = $this->request->getInt('query.site_id', 0);
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('content.setting.form'),
            $this->model_setting_setting->getSetting('plugin', 'content', $data['site_id']),
        );

        $data['sites'] = [];
        foreach ($this->model_setting_site->getSites() as $key => $site) {
            $data['sites'][$key] = $site;
            $data['sites'][$key]['url_setting'] = $this->router->url('content/setting', 'site_id=' . $site['site_id']);
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/setting', $data));
    }

    public function save()
    {
        $this->load->config('content/setting');
        $this->load->model('setting/setting');
        $this->load->language('content/setting');

        if (!$this->user->hasPermission('modify', 'content/setting')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.site_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('content.setting.form'),
            $this->request->get('post', [])
        );
        $site_id = (int)$post['site_id'];

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        Arr::unset($post, ['site_id', 'form', 'action', 'timezone']);

        $this->model_setting_setting->editSetting('plugin', 'content', $post, $site_id);

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        return $errors;
    }
}
