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

        $data['identifier'] = time();
        $data['inModal']    = $this->request->getBool('query.modal', false);

        if ($data['inModal']) {
            $this->response->setOutput($this->load->view('tool/mediamanager_panel', $data));
        } else {
            $data['layouts'] = $this->load->controller('block/position');
            $data['footer']  = $this->load->controller('block/footer');
            $data['header']  = $this->load->controller('block/header');

            $this->response->setOutput($this->load->view('tool/mediamanager', $data));
        }
    }

    public function getFolders()
    {
        if (!$this->request->is('ajax', 'post')) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $this->response->setOutputJson(
            $this->folderTree(
                PATH_MEDIA,
                $this->request->get('post.folder', ''),
                ['cache', 'flags']
            )
        );
    }

    private function folderTree(string $root, string $folder, array $excludes = []): array
    {
        $output = [];
        $folder = $this->cleanPath($folder);

        foreach (new \DirectoryIterator($root . $folder) as $item) {
            if ($item->isDot() || !$item->isDir()) {
                continue;
            }

            $filePath = $folder . $item->getFilename();
            if (in_array(strtolower($filePath), $excludes)) {
                continue;
            }

            // First node
            if (!$folder) {
                $output[] = [
                    'id'        => $item->getFilename() . '/',
                    'text'      => $item->getFilename(),
                    'state'     => ['opened' => true],
                    'children'  => $this->folderTree($root, $item->getFilename() . '/', $excludes),
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

    public function folderAction()
    {
        if (!$this->request->is('ajax', 'post')) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $data = [];
        $post = $this->request->getArray('post');
        $post['parent'] = $this->cleanPath($post['parent'] ?? '');
        $post['folder'] = $this->cleanPath($post['folder'] ?? '');

        if ($post['action'] == 'create_node') {
            $folder     = sanitizeChar($post['text']);
            $folder_new = $post['parent'] . $folder;

            // folder will be created in rename_node
            $data = [
                'id'    => $folder_new,
                'text'  => $folder
            ];
        }

        if ($post['action'] == 'rename_node' && sanitizeChar($post['parent'])) {
            $status     = false;
            $folder     = mb_strtolower(sanitizeChar($post['text']));
            $folder_old = $post['folder'];
            $folder_new = $post['parent'] . $folder . '/';

            if (!file_exists(PATH_MEDIA . $folder_old)) {
                $status = mkdir(PATH_MEDIA . $folder_new); // in chain with create_node
            } elseif ($folder_old != $folder_new) {
                $status = rename(PATH_MEDIA . $folder_old, PATH_MEDIA . $folder_new);
            }

            if ($status) {
                $data = [
                    'id'    => $folder_new,
                    'text'  => $folder
                ];
            }
        }

        if ($post['action'] == 'delete_node' && sanitizeChar($post['parent'])) {
            $this->deletePath(PATH_MEDIA . $post['folder']);
        }

        $this->response->setOutputJson($data);
    }

    private function cleanPath(string $folder): string
    {
        $folder = str_replace(['.', '#'], '', $folder); // remove unwanted char
        $folder = str_replace('\\', '/', $folder);      // standarize slash
        $folder = preg_replace('~//+~', '/', $folder);  // multi slash to one

        return $folder;
    }

    private function deletePath(string $folder)
    {
        $folder = $this->cleanPath($folder);

        if (empty($folder) || !file_exists($folder)) {
            return true;
        } elseif (is_file($folder) || is_link($folder)) {
            return @unlink($folder);
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $action = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            if (!@$action($fileinfo->getRealPath())) {
                return false;
            }
        }

        if (is_dir($folder)) {
            return rmdir($folder);
        }
    }

    public function getItems()
    {
        if (!$this->request->is('ajax', 'post')) {
            // return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $this->load->language('tool/mediamanager');

        $folder   = $this->cleanPath($this->request->get('post.folder', ''));
        $data     = [
            'files' => [],
            'inModal' => $this->request->getBool('post.inModal', false),
        ];

        foreach (new \DirectoryIterator(PATH_MEDIA . $folder) as $item) {
            $fileType = explode('/', mime_content_type(PATH_MEDIA . $folder . $item->getFilename()))[0];

            if (!$item->isDot() && $item->isFile() && $fileType == 'image') {
                $data['files'][] = [
                    'folder'        => $folder,
                    'filename'      => $filename = $item->getFilename(),
                    'basename'      => $item->getBasename('.' . $item->getExtension()),
                    'extension'     => strtolower($item->getExtension()),
                    'path'          => $imagePath = $folder . $item->getFilename(),
                    'thumbnail'     => $this->image->getThumbnail($imagePath, 200, 200),
                    'url'           => $this->config->get('env.url_media') . $imagePath,
                    'filesize'      => $this->load->controller('tool/log/bytesToHuman', (int)$item->getSize()),
                    'created'       => date($this->config->get('env.datetime_format'), $item->getCTime()),
                    'modified'      => date($this->config->get('env.datetime_format'), $item->getMTime()),
                ];
            }
        }

        $this->response->setOutput($this->load->view('tool/mediamanager_items', $data));
    }
}
