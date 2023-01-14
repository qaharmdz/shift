<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Tool;

use Shift\System\Mvc;

class Backup extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('tool/backup');
        $this->load->language('tool/backup');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('tool')],
            [$this->language->get('maintenance')],
            [$this->language->get('page_title'), $this->router->url('tool/backup')],
        ]);

        $data = [];

        $data['alerts']   = $this->session->pull('flash.alert');


        $data['dbTables'] = [];
        foreach ($this->model_tool_backup->getTables() as $table) {
            $data['dbTables'][] = [
                'text'  => $table,
                'value' => $table,
            ];
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('tool/backup', $data));
    }

    public function export()
    {
        $this->load->language('tool/backup');

        if (!$this->user->hasPermission('modify', 'tool/backup')) {
            $this->session->push('flash.alert.warning', $this->language->get('error_permission'));
        }
        if (!$this->request->is('post')) {
            $this->session->push('flash.alert.warning', $this->language->get('error_request_method'));
        }
        if (!$this->request->has('post.export')) {
            $this->session->push('flash.alert.warning', $this->language->get('error_export'));
        }

        if (!$this->session->isEmpty('flash.alert')) {
            $this->response->redirect($this->router->url('tool/backup'));
        }

        $this->load->model('tool/backup');
        $exportContent = $this->model_tool_backup->export($this->request->getArray('post.export'));

        $this->response->download(
            $exportContent,
            'backup_db_' . $this->config->get('root.database.config.database') . '_' . date('Y-m-d_H-i-s', time()) . '.sql'
        );
    }

    public function import()
    {
        $this->load->language('tool/backup');

        if (!$this->user->hasPermission('modify', 'tool/backup')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }

        $filename = '';
        if ($this->request->has('files.import.tmp_name') && is_uploaded_file($this->request->get('files.import.tmp_name'))) {
            $filename = tempnam(PATH_TEMP . 'storage/', 'sf_');
            move_uploaded_file($this->request->get('files.import.tmp_name'), $filename);
        } elseif ($this->request->has('query.import')) {
            $filename = PATH_TEMP . 'storage/' . basename(html_entity_decode($this->request->get('query.import'), ENT_QUOTES, 'UTF-8'));
        }

        if (!is_file($filename)) {
            return $this->response->setOutputJson($this->language->get('error_file'), 412);
        }

        $position = $this->request->getInt('query.position', 0);
        $skipTruncate = [
            'TRUNCATE TABLE `{db_prefix}user`',
            'TRUNCATE TABLE `{db_prefix}user_group`',
            'TRUNCATE TABLE `{db_prefix}user_meta`'
        ];

        $i = 0;
        $handle = fopen($filename, 'r');
        fseek($handle, $position, SEEK_SET);

        while (!feof($handle) && ($i < 100)) {
            $position = ftell($handle);
            $line = trim((string)fgets($handle, 1000000));

            if ($line) {
                if (in_array($line, $skipTruncate)) {
                    fseek($handle, $position, SEEK_SET);
                    break;
                }

                if (str_starts_with($line, 'TRUNCATE TABLE') || str_starts_with($line, 'INSERT INTO')) {
                    $this->db->query(str_replace('{db_prefix}', DB_PREFIX, $line));
                }
            }

            $i++;
        }

        $size     = filesize($filename);
        $position = ftell($handle);

        $data  = [];
        $data['total'] = round(($position / $size) * 100);

        if ($position && !feof($handle)) {
            fclose($handle);
            $data['next'] = $this->router->url('tool/backup/import', 'import=' . $filename . '&position=' . $position);
        } else {
            fclose($handle);
            unlink($filename);
            $data['success'] = $this->language->get('success_import');

            $this->cache->clear();
        }

        $this->response->setOutputJson($data);
    }
}
