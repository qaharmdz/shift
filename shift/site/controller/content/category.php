<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Content;

use Shift\System\Mvc;

class Category extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('content/post');
        $this->load->model('content/category');
        $this->load->language('content/general');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('home'), $this->router->url('page/home')],
            [$this->language->get('content'), $this->router->url('content/category')],
        ]);

        if (!$this->request->has('category_id')) {
            $this->home();
        } else {
            $this->page($this->request->getInt('category_id'));
        }
    }

    protected function home()
    {
        $this->document->addMeta('name', 'description', $this->config->get('system.site.meta_description.' . $this->config->get('env.language_id', 0)));
        $this->document->addMeta('name', 'keywords', $this->config->get('system.site.meta_keyword.' . $this->config->get('env.language_id', 0)));

        $this->document->addLink($this->router->url('content/category'), 'canonical');

        $data = [];
        $data['page_title'] = $this->language->get('content');

        $parent_id = 0; // TODO: admin content setting for category landing page "home"
        $categories = $this->model_content_category->getCategories([
            'parent_id' => $parent_id,
        ]);

        $data['categories'] = [];
        foreach ($categories as $key => $category) {
            $data['categories'][$key] = $category;
            $data['categories'][$key]['url'] = $this->router->url('content/category', 'category_id=' . $category['term_id']);

            $posts = $this->model_content_post->getPosts([
                'category_id' => (int)$category['term_id'],
                'limit' => 9,
            ]);

            foreach ($posts as $kPost => $post) {
                $data['categories'][$key]['posts'][$kPost] = $post;
                $data['categories'][$key]['posts'][$kPost]['excerpt'] = html_entity_decode($post['excerpt'], ENT_QUOTES, 'UTF-8');
                $data['categories'][$key]['posts'][$kPost]['url'] = $this->router->url('content/post', 'post_id=' . $post['post_id']);
            }
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/home', $data));
    }

    protected function page($category_id)
    {
        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content'), $this->router->url('content/category')],
        ]);

        $this->response->setOutput($this->load->view('content/category', $data));
    }

    private function test()
    {
        d(__METHOD__);

        $this->load->model('content/post');
        $this->load->model('content/category');
        $this->load->model('content/tag');

        d(
            $this->model_content_post->getPost($post_id = 2),
            $this->model_content_post->getPosts(),
            $this->model_content_post->getPosts(['category_id' => 17]),
            // $this->model_content_category->getCategory(16),
        );
    }
}
