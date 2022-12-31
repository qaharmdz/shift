<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Log extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('tool/log');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('tool')],
            [$this->language->get('maintenance')],
            [$this->language->get('page_title'), $this->router->url('tool/log')],
        ]);

        $data = [];
        $data['alerts'] = $this->session->pull('flash.alert');

        // Logs listing
        $data['logFiles'] = [];
        $logFiles = glob(PATH_TEMP . 'logs/*.{log,txt}', GLOB_BRACE);

        $i = 0;
        $part = 1;
        foreach ($logFiles as $file) {
            $_filename = basename($file);
            $_fileinfo = $this->fileInfo($_filename);

            $data['logFiles'][$_filename] = $_fileinfo;
            $data['logFiles'][$_filename]['url_view'] = $this->router->url('tool/log', 'file=' . $_filename);

            $i++;
            if ($i % 12 == 0) {
                $part++;
            }
        }

        // Logs dropdown-grid
        $partition = min($part, 3);
        $data['partition'] = [
            'column' => $partition,
            'width'  => $partition * 250,
            'files'  => Arr::partition($data['logFiles'], $partition),
        ];

        // Current log file
        $file     = $this->request->get('query.file', $this->log->getConfig('logfile', 'log-' . date('Y-m') . '.log'));
        $fileinfo = $data['logFiles'][$file] ?? $this->fileinfo($file);

        $data['fileinfo'] = $fileinfo;
        $data['log'] = '';

        if ($fileinfo['exist']) {
            if ($fileinfo['state'] == 2) {
                $data['log'] = sprintf($this->language->get('error_filesize'), $file, $fileinfo['size']);
            } else {
                $data['log'] = file_get_contents($fileinfo['filepath'], true, null);
            }
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('tool/log', $data));
    }

    public function action()
    {
        $this->load->language('tool/log');

        $redirect = $this->router->url('tool/log');

        if (!$this->user->hasPermission('modify', 'tool/log')) {
            $this->session->push('flash.alert.warning', $this->language->get('error_permission'));
        }
        if (!$this->request->is('post')) {
            $this->session->push('flash.alert.warning', $this->language->get('error_request_method'));
        }

        if ($this->session->isEmpty('flash.alert')) {
            $submit   = $this->request->get('query.submit', 'download');
            $filename = $this->request->get('post.file', 'error-' . date('Y-m') . '.log');
            $filepath = PATH_TEMP . 'logs/' . $filename;

            if (file_exists($filepath)) {
                if ($submit == 'download') {
                    $this->response->download($filepath, $this->config->get('system.site.name') . '__' . date('Y-m-d_H-i-s', time()) . '__' . $filename);
                }

                if ($submit == 'clear') {
                    $this->log->clear($filename);

                    $this->session->push('flash.alert.success', sprintf($this->language->get('success_clear'), $filename));
                    $redirect = $this->router->url('tool/log', 'file=' . $filename);
                }

                if ($submit == 'delete') {
                    unlink($filepath);

                    $this->session->push('flash.alert.success', sprintf($this->language->get('success_delete'), $filename));
                    $redirect = $this->router->url('tool/log');
                }
            }
        }

        $this->response->redirect($redirect);
    }

    protected function fileinfo($file)
    {
        $filepath = PATH_TEMP . 'logs/' . $file;
        $data     = [
            'exist'    => false,
            'file'     => $file,
            'filepath' => $filepath,
            'bytes'    => 0,
            'size'     => '0 B',
            'state'    => 0,
            'label'    => 'success',
        ];

        if (is_file($filepath)) {
            $data['exist'] = true;

            $size = $bytes = filesize($filepath);
            $suffix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            $i = 0;
            while (($size / 1024) > 1) {
                $size = $size / 1024;
                $i++;
            }

            $data['bytes'] = $bytes;
            $data['size']  = round($size, 2) . ' ' . $suffix[$i];

            /**
             * State:
             * - x < 15 MB = 0
             * - x > 15 MB = 1
             * - x > 50 MB = 2
             */
            if ($bytes > 52428800) {  // 50 MB
                $data['state'] = 2;
                $data['label'] = 'danger';
            } elseif ($bytes > 15728640) { // 15 MB
                $data['state'] = 1;
                $data['label'] = 'warning';
            }
        }

        return $data;
    }
}
