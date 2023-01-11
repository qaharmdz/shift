<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Content;

use Shift\System\Mvc;

class Post extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('content/general');
        $this->load->language('content/post');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content')],
            [$this->language->get('page_title'), $this->router->url('content/post')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/post_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('content/post');

        $params  = $this->request->get('post');
        $results = $this->model_content_post->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['post_id'];
            $items[$i]['url_edit']    = $this->router->url('content/post/form', 'post_id=' . $items[$i]['post_id']);
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_content_post->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('content/post');
        $this->load->language('content/post');

        if (!$this->user->hasPermission('modify', 'content/post')) {
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

        $data['updated'] = $this->model_content_post->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    // Form
    // ================================================

    public function form()
    {
        $post_id = $this->request->getInt('query.post_id', 0);
        $mode = !$post_id ? 'add' : 'edit';

        $this->load->model('setting/site');
        $this->load->model('content/post');
        $this->load->model('extension/language');
        $this->load->language('content/general');
        $this->load->language('content/post');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content')],
            [$this->language->get('page_title'), $this->router->url('content/post')],
            [$this->language->get($mode), $this->router->url('content/post/form', 'post_id=' . $post_id)],
        ]);

        $data = [];

        $data['mode']      = $mode;
        $data['post_id']   = $post_id;
        $data['sites']     = $this->model_setting_site->getSites();
        $data['languages'] = $this->model_extension_language->getLanguages();
        $data['setting']   = $this->model_content_post->getPost($post_id);

        $data['categories'] = [
            ['item_id' => 0, 'title_tree' => $this->language->get('-none-')],
            // ...Tool\Taxonomy::buildTree($this->model_content_category->getCategories(), $post_id)
        ];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/post_form', $data));
    }

    public function save()
    {
        $this->load->config('content/post');
        $this->load->model('content/post');
        $this->load->language('content/post');

        if (!$this->user->hasPermission('modify', 'content/post')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.post_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('content.post.form'),
            $this->request->get('post', [])
        );
        $post_id = (int)$post['post_id'];

        unset($post['content'][0]);

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        if (!$post_id) {
            $data['new_id'] = $this->model_content_post->addPost($post);
        } else {
            $this->model_content_post->editPost($post_id, $post);
        }

        // Redirect
        if ($post['action'] === 'close') {
            $data['redirect'] = $this->router->url('content/post');
        }
        if ($post['action'] === 'new') {
            $data['redirect'] = $this->router->url('content/post/form');
        }
        if (isset($data['new_id']) && empty($data['redirect'])) {
            $data['redirect'] = $this->router->url('content/post/form', 'post_id=' . $data['new_id']);
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
                        !$post['post_id']
                        || (
                            $alias['route'] == 'content/post'
                            && $alias['param'] == 'post_id'
                            && $alias['value'] != $post['post_id']
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
