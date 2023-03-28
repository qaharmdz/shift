<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Mvc;

class MediaManager extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('tool/mediamanager');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('jstree');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('tool')],
            [$this->language->get('page_title'), $this->router->url('tool/mediamanager')],
        ]);

        $data = [];

        $data['is_ajax'] = $this->request->is('ajax') || $this->request->get('query.modal');

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('tool/mediamanager', $data));
    }

    public function tree()
    {
        if (!$this->request->is('ajax', 'post')) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $this->response->setOutputJson(
            $this->treeFolder(
                PATH_MEDIA,
                $this->request->get('post.folder', ''),
                ['cache', 'flags', 'image/test', 'image/demo/test2-']
            )
        );
    }

    private function treeFolder(string $root, string $folder, array $xcludes = []): array
    {
        $output = [];
        $folder = $this->cleanPath($folder);

        foreach (new \DirectoryIterator($root . $folder) as $item) {
            if ($item->isDot() || !$item->isDir()) {
                continue;
            }

            $filePath = $folder . $item->getFilename();
            if (in_array(strtolower($filePath), $xcludes)) {
                continue;
            }

            // First node
            if (!$folder) {
                $output[] = [
                    'id'        => $item->getFilename() . '/',
                    'text'      => $item->getFilename(),
                    'state'     => ['opened' => true],
                    'children'  => $this->treeFolder($root, $item->getFilename() . '/', $xcludes),
                ];
            } elseif ($folder) {
                $output[] = [
                    'id'        => $filePath . '/',
                    'text'      => $item->getFilename(),
                    'state'     => ['opened' => false],
                    'children'  => (bool)count(glob($root . $filePath . '/*', GLOB_ONLYDIR)),
                ];
            }
        }

        return $output;
    }

    private function cleanPath(string $folder): string
    {
        $folder = str_replace(['.', '#'], '', $folder); // remove unwanted char
        $folder = str_replace('\\', '/', $folder);      // standarize slash
        $folder = preg_replace('~//+~', '/', $folder);  // multi slash to one

        return $folder;
    }

    public function getItems()
    {
        $data = [];

        $this->response->setOutput($this->load->view('tool/mediamanager_items', $data));
    }
}
