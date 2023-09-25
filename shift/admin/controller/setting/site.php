<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Site extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('system')],
            [$this->language->get('page_title'), $this->router->url('setting/site')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/site_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('setting/site');

        $params  = $this->request->get('post');
        $results = $this->model_setting_site->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['site_id'];
            $items[$i]['url_edit']    = $this->router->url('setting/site/form', 'site_id=' . $items[$i]['site_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_setting_site->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('setting/site');
        $this->load->language('setting/site');

        if (!$this->user->hasPermission('modify', 'setting/site')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $post  = array_replace(['type' => '', 'item' => ''], $this->request->get('post'));
        $types = ['delete'];
        $items = explode(',', $post['item']);
        $data  = [
            'items'     => $items,
            'message'   => '',
            'updated'   => [],
        ];

        if (empty($items) || !in_array($post['type'], $types) || in_array(0, $items)) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data['updated'] = $this->model_setting_site->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $site_id = $this->request->getInt('query.site_id', -1);
        $mode    = $site_id == -1 ? 'add_new' : 'edit';

        $this->load->config('setting/site');
        $this->load->model('setting/setting');
        $this->load->model('setting/site');
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('system')],
            [$this->language->get('page_title'), $this->router->url('setting/site')],
            [$this->language->get($mode), $this->router->url('setting/site/form')],
        ]);

        $data = [];

        $data['site_id'] = $site_id;
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('setting.site.form'),
            $this->model_setting_setting->getSetting('system', 'site', $site_id),
            $this->model_setting_site->getSite($site_id)
        );

        $this->load->model('extension/language');
        $data['languages'] = $this->model_extension_language->getLanguages();

        $this->load->model('extension/manage');
        $data['themes'] = $this->model_extension_manage->getExtensions([
            'type = ?s' => 'theme',
            'status = ?i' => 1,
            'install = ?i' => 1,
        ]);

        $this->load->model('tool/layout');
        $data['layoutList'] = $this->model_tool_layout->getLayouts();

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/site_form', $data));
    }

    public function save()
    {
        $this->load->config('setting/site');
        $this->load->model('setting/setting');
        $this->load->model('setting/site');
        $this->load->language('setting/site');

        if (!$this->user->hasPermission('modify', 'setting/site')) {
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
            $this->config->getArray('setting.site.form'),
            $this->request->get('post', [])
        );
        $site_id = (int)$post['site_id'];
        $action  = $post['action'];

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        $post['url_host'] = rtrim($post['url_host'], '/') . '/';

        if (-1 == $site_id) {
            $data['new_id'] = $site_id = $this->model_setting_site->addSite($post);
        } else {
            $this->model_setting_site->editSite($site_id, $post);
        }

        Arr::unset($post, ['site_id', 'name', 'url_host', 'form', 'action', 'timezone']);
        $this->model_setting_setting->editSetting('system', 'site', $post, $site_id);

        // Redirect
        if ($action === 'close') {
            $data['redirect'] = $this->router->url('setting/site');
        }
        if ($action === 'new') {
            $data['redirect'] = $this->router->url('setting/site/form');
        }
        if (isset($data['new_id']) && empty($data['redirect'])) {
            $data['redirect'] = $this->router->url('setting/site/form', 'site_id=' . $data['new_id']);
        }

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->stringNotEmpty()->check($post['name'])) {
            $errors['items']['name'] = $this->language->get('error_no_empty');
        }

        if (false === \filter_var($post['url_host'], \FILTER_VALIDATE_URL)) {
            $errors['items']['url_host'] = $this->language->get('error_url_host');
        }

        if (!$this->assert->email()->check($post['email'])) {
            $errors['items']['email'] = $this->language->get('error_email');
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
