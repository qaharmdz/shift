<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Content;

use Shift\System\Mvc;

class Tag extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('content/general');
        $this->load->language('content/tag');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content')],
            [$this->language->get('page_title'), $this->router->url('content/tag')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/tag_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('content/tag');

        $params  = $this->request->get('post');
        $results = $this->model_content_tag->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['tag_id'];
            $items[$i]['url_edit']    = $this->router->url('content/tag/form', 'tag_id=' . $items[$i]['tag_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_content_tag->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('content/tag');
        $this->load->language('content/tag');

        if (!$this->user->hasPermission('modify', 'content/tag')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $post  = array_replace(['type' => '', 'item' => ''], $this->request->get('post'));
        $types = ['enabled', 'disabled', 'delete'];
        $items = explode(',', $post['item']);
        $data  = [
            'items'     => $items,
            'message'   => '',
            'updated'   => [],
        ];

        if (empty($items) || !in_array($post['type'], $types)) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data['updated'] = $this->model_content_tag->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $tag_id = $this->request->getInt('query.tag_id', 0);
        $mode = !$tag_id ? 'add' : 'edit';

        $this->load->model('content/tag');
        $this->load->model('extension/language');
        $this->load->language('content/general');
        $this->load->language('content/tag');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('ckeditor');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content')],
            [$this->language->get('page_title'), $this->router->url('content/tag')],
            [$this->language->get($mode), $this->router->url('content/tag/form', 'tag_id=' . $tag_id)],
        ]);

        $data = [];

        $data['mode']      = $mode;
        $data['tag_id']    = $tag_id;
        $data['languages'] = $this->model_extension_language->getLanguages();
        $data['setting']   = $this->model_content_tag->getTag($tag_id);

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/tag_form', $data));
    }

    public function save()
    {
        $this->load->config('content/tag');
        $this->load->model('content/tag');
        $this->load->language('content/tag');

        if (!$this->user->hasPermission('modify', 'content/tag')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.tag_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('content.category.form'),
            $this->request->get('post', [])
        );
        $tag_id = (int)$post['tag_id'];

        unset($post['content'][0]);

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        if (!$tag_id) {
            $data['new_id'] = $this->model_content_tag->addTag($post);
        } else {
            $this->model_content_tag->editTag($tag_id, $post);
        }

        // Redirect
        if ($post['action'] === 'close') {
            $data['redirect'] = $this->router->url('content/tag');
        }
        if ($post['action'] === 'new') {
            $data['redirect'] = $this->router->url('content/tag/form');
        }
        if (isset($data['new_id']) && empty($data['redirect'])) {
            $data['redirect'] = $this->router->url('content/tag/form', 'tag_id=' . $data['new_id']);
        }

        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        foreach ($post['content'] as $language_id => $content) {
            if (!$this->assert->lengthBetween(2, 200)->check($content['title'])) {
                $errors['items']['content[' . $language_id . '][title]'] = sprintf($this->language->get('error_length_between'), 2, 200);
            }
        }

        foreach ($post['alias'] as $language_id => &$alias) {
            if (!$alias = str_replace(' ', '-', trim($alias))) {
                continue;
            }

            if (count(array_keys($post['alias'], $alias)) > 1) {
                $errors['items']['alias[' . $language_id . ']'] = $this->language->get('error_alias_unique');
            }

            if (empty($errors['items']['alias[' . $language_id . ']'])) {
                $aliases = $this->db->get(
                    "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` != ?i AND `alias` = ?s",
                    [$language_id, $alias]
                )->rows;

                foreach ($aliases as $alias) {
                    if (
                        !$post['tag_id']
                        || (
                            $alias['route'] == 'content/tag'
                            && $alias['param'] == 'tag_id'
                            && $alias['value'] != $post['tag_id']
                        )
                    ) {
                        $errors['items']['alias[' . $language_id . ']'] = $this->language->get('error_alias_exist');
                        break;
                    }
                }
            }
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
