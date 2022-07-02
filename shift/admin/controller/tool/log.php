<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Core\Mvc;

class Log extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('tool/log');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['button_download'] = $this->language->get('button_download');
        $data['button_clear'] = $this->language->get('button_clear');

        $data['success'] = $this->session->pull('flash.success');
        $data['error_warning'] = $this->error['warning'] ?? $this->session->pull('flash.error');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('tool/log', 'token=' . $this->session->get('token'))
        );

        $data['download'] = $this->router->url('tool/log/download', 'token=' . $this->session->get('token'));
        $data['clear'] = $this->router->url('tool/log/clear', 'token=' . $this->session->get('token'));

        $data['log'] = '';

        $file = DIR_STORAGE . 'logs/' . $this->log->getConfig('logfile');

        if (file_exists($file)) {
            $size = filesize($file);

            if ($size >= 5242880) {
                $suffix = array(
                    'B',
                    'KB',
                    'MB',
                    'GB',
                    'TB',
                    'PB',
                    'EB',
                    'ZB',
                    'YB'
                );

                $i = 0;

                while (($size / 1024) > 1) {
                    $size = $size / 1024;
                    $i++;
                }

                $data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
            } else {
                $data['log'] = file_get_contents($file);
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/log', $data));
    }

    public function download()
    {
        $this->load->language('tool/log');

        $file = DIR_STORAGE . 'logs/' . $this->log->getConfig('logfile');

        if (file_exists($file) && filesize($file) > 0) {
            $this->response->download(
                $file,
                $this->config->get('system.setting.name') . '_' . date('Y-m-d_H-i-s', time()) . '_error.log'
            );
        } else {
            $this->session->set('flash.error', sprintf($this->language->get('error_warning'), basename($file), '0B'));

            $this->response->redirect($this->router->url('tool/log', 'token=' . $this->session->get('token')));
        }
    }

    public function clear()
    {
        $this->load->language('tool/log');

        if (!$this->user->hasPermission('modify', 'tool/log')) {
            $this->session->set('flash.error', $this->language->get('error_permission'));
        } else {
            $file = DIR_STORAGE . 'logs/' . $this->log->getConfig('logfile');

            $handle = fopen($file, 'w+');

            fclose($handle);

            $this->session->set('flash.success', $this->language->get('text_success'));
        }

        $this->response->redirect($this->router->url('tool/log', 'token=' . $this->session->get('token')));
    }
}
