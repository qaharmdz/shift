<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Language extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extension/language');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extensions')],
            [$this->language->get('page_title')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/language', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('extension/language');

        $params  = $this->request->get('post');
        $results = $this->model_extension_language->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['extension_id'];
            $items[$i]['url_edit']    = $this->router->url('extension/language/form', 'extension_id=' . $items[$i]['extension_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_extension_language->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $extension_id = $this->request->getInt('query.extension_id', 0);

        $this->load->model('extension/language');
        $this->load->language('extension/language');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('ckeditor');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extensions')],
            [$this->language->get('page_title'), $this->router->url('extension/language')],
            [$this->language->get('edit'), $this->router->url('extension/language/form', 'extension_id=' . $extension_id)],
        ]);

        $data = [];

        $data['extension_id'] = $extension_id;
        $data['languages']    = $this->model_extension_language->getLanguages();
        $data['setting']      = $this->model_extension_language->getLanguage($extension_id);

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/language_form', $data));
    }

    public function save()
    {
        $this->load->model('extension/language');

        if (!$this->user->hasPermission('modify', 'extension/language')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.extension_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            [
                'setting' => [
                    'locale' => '',
                    'flag' => '',
                ],
                'status' => 0,
            ],
            $this->request->get('post', [])
        );
        $extension_id = (int)$post['extension_id'];

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        $this->model_extension_language->edit($extension_id, $post);

        // Redirect
        if ($post['action'] === 'close') {
            $data['redirect'] = $this->router->url('extension/language');
        }

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->stringNotEmpty()->check($post['setting']['locale'])) {
            $errors['items']['setting[locale]'] = $this->language->get('error_no_empty');
        }

        if (!$this->assert->stringNotEmpty()->check($post['setting']['flag'])) {
            $errors['items']['setting[flag]'] = $this->language->get('error_no_empty');
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
