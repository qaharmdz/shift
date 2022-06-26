<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Core\Mvc;

class Backup extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('tool/backup');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/backup');

        if ($this->request->is('POST') && $this->user->hasPermission('modify', 'tool/backup')) {
            $content = false;
            if (is_uploaded_file($this->request->get('files.import.tmp_name'))) {
                $content = file_get_contents($this->request->get('files.import.tmp_name'));
            }

            if ($content) {
                $this->model_tool_backup->restore($content);

                $this->session->set('flash.success', $this->language->get('text_success'));

                $this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->get('token'), true));
            } else {
                $this->error['warning'] = $this->language->get('error_empty');
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');

        $data['entry_export'] = $this->language->get('entry_export');
        $data['entry_import'] = $this->language->get('entry_import');

        $data['button_export'] = $this->language->get('button_export');
        $data['button_import'] = $this->language->get('button_import');

        $data['success'] = $this->session->pull('flash.success');
        $data['error_warning'] = $this->error['warning'] ?? $this->session->pull('flash.error');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->get('token'), true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/backup', 'token=' . $this->session->get('token'), true)
        );

        $data['restore'] = $this->url->link('tool/backup', 'token=' . $this->session->get('token'), true);

        $data['backup'] = $this->url->link('tool/backup/backup', 'token=' . $this->session->get('token'), true);

        $data['tables'] = $this->model_tool_backup->getTables();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/backup', $data));
    }

    public function backup()
    {
        $this->load->language('tool/backup');

        if (!$this->request->has('post.backup')) {
            $this->session->set('flash.error', $this->language->get('error_export'));

            $this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->get('token'), true));
        } elseif ($this->user->hasPermission('modify', 'tool/backup')) {
            $this->load->model('tool/backup');

            $this->response->download(
                $this->model_tool_backup->backup($this->request->get('post.backup')),
                $this->config->get('root.database.config.database') . '_' . date('Y-m-d_H-i-s', time()) . '_backup.sql',
            );
        } else {
            $this->session->set('flash.error', $this->language->get('error_permission'));

            $this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->get('token'), true));
        }
    }
}
