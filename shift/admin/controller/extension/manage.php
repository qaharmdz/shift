<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Manage extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extension/manage');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('page_title')],
        ]);

        $extensions = [];
        foreach (glob(PATH_EXTENSIONS . '*', GLOB_ONLYDIR) as $ext) {
            $extensions[basename($ext)] = [];

            foreach (glob($ext . DS . '*', GLOB_ONLYDIR | GLOB_NOESCAPE) as $node) {
                if (is_file($node . DS . 'meta.json')) {
                    $codename = basename($node);
                    $metaInfo = array_merge(
                        [
                            'name'        => ucwords($codename),
                            'version'     => '1.0.0-b',
                            'author'      => '',
                            'link'        => '',
                            'description' => '',
                        ],
                        json_decode(file_get_contents($node . DS . 'meta.json'), true)
                    );

                    $extensions[basename($ext)][] = [
                        'codename' => basename($node),
                        'meta'     => $metaInfo,
                        'path'     => $node,
                    ];
                }
            }
        }

        d($extensions);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/manage', $data));
    }
}
