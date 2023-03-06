<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Mvc;

class Cache extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('tool/cache');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('tool')],
            [$this->language->get('maintenance')],
            [$this->language->get('page_title'), $this->router->url('tool/cache')],
        ]);

        $data = [];

        $data['cache_general'] = [
            'driver'      => $this->cache->instance()->getDriverName(),
            'default_ttl' => ($this->cache->instance()->getConfig()->getDefaultTtl() / 60) . ' minutes',
            'size'        => $this->load->controller('tool/log/bytesToHuman', $this->cache->instance()->getStats()->getSize()),
            'info'        => $this->cache->instance()->getStats()->getInfo(),
        ];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('tool/cache', $data));
    }

    public function clear()
    {
        $this->load->language('tool/cache');

        if (!$this->user->hasPermission('modify', 'tool/cache')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $data = [
            'updates' => [],
        ];

        switch ($this->request->get('post.clear', '')) {
            case 'all':
                $this->purge();

                $data['updates'] = [
                    '.cache_general_size' => $this->load->controller('tool/log/bytesToHuman', $this->cache->instance()->getStats()->getSize()),
                    '.cache_general_info' => $this->cache->instance()->getStats()->getInfo(),
                ];
                break;

            case 'general':
                $this->cache->clear();

                $data['updates'] = [
                    '.cache_general_size' => $this->load->controller('tool/log/bytesToHuman', $this->cache->instance()->getStats()->getSize()),
                    '.cache_general_info' => $this->cache->instance()->getStats()->getInfo(),
                ];
                break;

            case 'template':
                $this->view->clearCache();
                break;

            case 'image':
                $this->image->clearCache();
                break;
        }

        $this->response->setOutputJson($data);
    }

    public function purge()
    {
        $this->cache->clear();
        $this->view->clearCache();
        $this->image->clearCache();
    }
}
