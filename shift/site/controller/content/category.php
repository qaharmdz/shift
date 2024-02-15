<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Content;

use Shift\System\Mvc;
use Shift\System\Helper\Str;

class Category extends Mvc\Controller {
    public function index()
    {
        if (!$this->request->has('query.category_id')) {
            $this->home();
        } else {
            $this->page($this->request->getInt('query.category_id'));
        }
    }

    public function home()
    {
        $this->load->model('content/post');
        $this->load->model('content/category');
        $this->load->language('content/general');

        $this->document->setTitle($this->language->get('content'));
        $this->document->addMeta('name', 'description', $this->config->get('system.site.meta_description.' . $this->config->get('env.language_id', 0)));
        $this->document->addMeta('name', 'keywords', $this->config->get('system.site.meta_keyword.' . $this->config->get('env.language_id', 0)));

        $this->document->addLink($this->router->url('content/category'), 'canonical');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('home'), $this->router->url('page/home')],
            [$this->language->get('content'), $this->router->url('content/category')],
        ]);

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
                'term_id' => (int) $category['term_id'],
            ]);

            foreach ($posts as $kPost => $post) {
                $data['categories'][$key]['posts'][$kPost] = $post;
                $data['categories'][$key]['posts'][$kPost]['url'] = $this->router->url('content/post', 'category_id=' . $post['term_id'] . '&post_id=' . $post['post_id']);

                if ($post['excerpt']) {
                    $data['categories'][$key]['posts'][$kPost]['excerpt'] = Str::htmlDecode($post['excerpt']);
                } else {
                    $data['categories'][$key]['posts'][$kPost]['excerpt'] = '<p>' . Str::truncate(Str::htmlDecode($post['content'])) . '</p>';
                }
            }
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/home', $data));
    }

    protected function page($category_id)
    {
        $this->load->model('content/post');
        $this->load->model('content/category');
        $this->load->language('content/general');

        $category = $this->model_content_category->getCategory($category_id);

        $this->document->setTitle($category['meta_title'] ?: $category['title']);
        $this->document->addMeta('name', 'description', $category['meta_description']);
        $this->document->addMeta('name', 'keywords', $category['meta_keyword']);

        $this->document->addLink($this->router->url('content/category', 'category_id=' . $category_id), 'canonical');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('home'), $this->router->url('page/home')],
            [$this->language->get('content'), $this->router->url('content/category')],
            [$category['title'], $this->router->url('content/category', 'category_id=' . $category_id)],
        ]);

        $data = [];
        $data['category'] = $category;
        $data['category']['content'] = Str::htmlDecode($category['content']);

        $posts = $this->model_content_post->getPosts([
            'term_id' => (int) $category['term_id'],
        ]);

        $data['posts'] = [];
        foreach ($posts as $key => $post) {
            $data['posts'][$key] = $post;
            $data['posts'][$key]['url'] = $this->router->url('content/post', 'category_id=' . $post['term_id'] . '&post_id=' . $post['post_id']);

            if ($post['excerpt']) {
                $data['posts'][$key]['excerpt'] = Str::htmlDecode($post['excerpt']);
            } else {
                // TODO: excerpt limit
                $data['posts'][$key]['excerpt'] = '<p>' . Str::truncate(Str::htmlDecode($post['content'])) . '</p>';
            }
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/category', $data));
    }
}
