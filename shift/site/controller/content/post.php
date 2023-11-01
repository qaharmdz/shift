<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Content;

use Shift\System\Mvc;
use Shift\System\Helper\Str;

class Post extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('content/post');
        $this->load->language('content/general');

        $category_id = $this->request->getInt('query.category_id');
        $post_id = $this->request->getInt('query.post_id');
        $post = $this->model_content_post->getPost($post_id);

        $this->document->setTitle($post['meta_title'] ?: $post['title']);
        $this->document->addMeta('name', 'description', $post['meta_description']);
        $this->document->addMeta('name', 'keywords', $post['meta_keyword']);

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('home'), $this->router->url('page/home')],
            [$this->language->get('content'), $this->router->url('content/category')],
        ]);

        if ($category_id) {
            if (isset($post['term']['categories'][$category_id])) {
                $this->document->addNode('breadcrumbs', [
                    [$post['term']['categories'][$category_id]['title'], $this->router->url('content/category', 'category_id=' . $category_id)],
                ]);
            }
        }

        $this->document->addNode('breadcrumbs', [
            [$post['title'], $this->router->url('content/post', 'category_id=' . $category_id . '&post_id=' . $post_id)],
        ]);

        $data = [];
        $data['post'] = $post;
        $data['post']['content'] = Str::htmlDecode($post['content']);

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/post', $data));
    }
}
