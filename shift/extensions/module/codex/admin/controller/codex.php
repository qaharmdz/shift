<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Codex\Admin\Controller;

use Shift\System\Mvc;

class Codex extends Mvc\Controller
{
    public function index()
    {
        $module_id = $this->request->getInt('query.module_id', 0);
        $mode = !$module_id ? 'add_new' : 'edit';

        $this->load->model('account/usergroup');
        $this->load->model('extension/manage');
        $this->load->model('extension/module');
        $this->load->config('extensions/module/codex');
        $this->load->language('extensions/module/codex');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('codemirror');
        $this->document->loadAsset('flatpickr');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extensions')],
            [$this->language->get('modules'), $this->router->url('extension/module')],
            [$this->language->get('page_title'), $this->router->url('extensions/module/codex')],
        ]);

        $data = [];

        $data['mode']      = $mode;
        $data['module_id'] = $module_id;
        $data['module']    = array_replace_recursive(
            $this->config->getArray('extensions.module.codex.form'),
            $this->model_extension_module->getModule($module_id),
        );
        $data['module']['setting']['editor'] = htmlspecialchars_decode(
            $data['module']['setting']['editor'],
            ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401
        );

        $extension = $this->model_extension_manage->getExtension('codex');
        $data['extension_id'] = $extension['extension_id'];
        $data['user_groups']  = $this->model_account_usergroup->getUserGroups();

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extensions/module/codex/form', $data));
    }

    public function save()
    {
        $this->load->model('extension/module');
        $this->load->config('extensions/module/codex');
        $this->load->language('extensions/module/codex');

        if (!$this->user->hasPermission('modify', 'extension/module/codex')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.extension_module_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('extensions.module.codex.form'),
            $this->request->getArray('post')
        );
        $module_id = (int)$post['extension_module_id'];

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        if (!$module_id) {
            $data['new_id'] = $this->model_extension_module->addModule($post);
        } else {
            $this->model_extension_module->editModule($module_id, $post);
        }

        // Redirect
        if ($post['action'] === 'close') {
            $data['redirect'] = $this->router->url('extension/module');
        }
        if ($post['action'] === 'new') {
            $data['redirect'] = $this->router->url('extensions/module/codex');
        }
        if (isset($data['new_id']) && empty($data['redirect'])) {
            $data['redirect'] = $this->router->url('extensions/module/codex', 'module_id=' . $data['new_id']);
        }

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->lengthBetween(2, 100)->check($post['name'])) {
            $errors['items']['name'] = sprintf($this->language->get('error_length_between'), 2, 100);
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }

    public function install()
    {
        $this->log->write(__METHOD__);
    }

    public function uninstall()
    {
        $this->log->write(__METHOD__);
    }
}
