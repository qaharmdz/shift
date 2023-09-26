<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Content;

use Shift\System\Mvc;

class Category extends Mvc\Controller
{
    public function index()
    {
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

        $this->document->addLink($this->config->get('env.url_app'), 'canonical');

        $this->test();

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/home', $data));
    }

    protected function page($category_id)
    {
        //
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
            $this->model_content_post->getPosts(['p.term_id = ?i' => 17]),
        );
    }
}
