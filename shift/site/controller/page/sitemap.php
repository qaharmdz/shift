<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Page;

use Shift\System\Mvc;

class Sitemap extends Mvc\Controller {
    public function index()
    {
        $this->load->language('page/sitemap');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('home'), $this->router->url('page/home')],
            [$this->language->get('page_title'), $this->router->url('page/sitemap')],
        ]);


        $data = [];
        $data['content'] = [];
        $data['sections'] = [
            'alpha' => [
                [$this->language->get('home'), $this->router->url('page/home')],
                [$this->language->get('contact'), $this->router->url('page/contact')],
            ],
            'omega' => [
                [$this->language->get('home'), $this->router->url('page/home')],
                [
                    $this->language->get('information'),
                    [
                        [$this->language->get('home'), $this->router->url('page/home')],
                        [$this->language->get('n/a'), '#'],
                        [$this->language->get('contact'), $this->router->url('page/contact')],
                    ],
                ],
            ],
        ];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/sitemap', $data));
    }

    public function xml()
    {
        // TODO: sitemap xml feed
    }
}
