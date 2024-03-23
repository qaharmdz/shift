<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Manage extends Mvc\Controller {
    public function index()
    {
        $this->load->language('extension/manage');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extensions')],
            [$this->language->get('page_title'), $this->router->url('extension/manage')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/manage', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('extension/manage');

        $params = $this->request->get('post');
        $results = $this->model_extension_manage->dtRecords($params);
        $extMetas = $this->syncExtensions();

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['meta_version'] = $extMetas[$items[$i]['type'] . '_' . $items[$i]['codename']]['version'] ?? '';
            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['extension_id'];
        }

        $data = [
            'draw'            => (int) $params['draw'] ?? 1,
            'data'            => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_extension_manage->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        $this->load->model('extension/manage');
        $this->load->language('extension/manage');

        if (!$this->user->hasPermission('modify', 'extension/manage')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $post = array_replace(['type' => '', 'item' => ''], $this->request->get('post'));
        $types = ['enabled', 'disabled', 'install', 'uninstall', 'delete'];
        $items = explode(',', $post['item']);
        $data = [
            'items'   => $items,
            'message' => '',
            'updated' => [],
        ];

        if (empty ($items) || !in_array($post['type'], $types)) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data['updated'] = $this->model_extension_manage->dtAction($post['type'], $items);
        $data['message'] = $post['message'] ?? $this->language->get('success_' . $post['type']);

        $this->response->setOutputJson($data);
    }

    /**
     * Check if there is new extensions and add to the DB `sf_extension`
     *
     * @return array
     */
    protected function syncExtensions(): array
    {
        $data = [];

        if (!$this->user->hasPermission('modify', 'extension/manage')) {
            return $data;
        }

        $extDbLists = $this->db->get("SELECT * FROM `" . DB_PREFIX . "extension`")->rows;

        foreach (glob(PATH_EXTENSIONS . '*', GLOB_ONLYDIR) as $ext) {
            $extType = basename($ext);

            foreach (glob($ext . DS . '*', GLOB_ONLYDIR | GLOB_NOESCAPE) as $node) {
                if (is_file($node . DS . 'meta.json')) {
                    $metaInfo = [];
                    $codename = basename($node);

                    try {
                        $metaInfo = json_decode(file_get_contents($node . DS . 'meta.json'), true, flags: \JSON_THROW_ON_ERROR);
                    } catch (\JsonException $e) {
                        throw new \JsonException(
                            sprintf('%s. Path: %s', $e->getMessage(), $node . DS . 'meta.json'),
                            $e->getCode()
                        );
                    }

                    if ($metaInfo && $metaInfo['codename'] === $codename) {
                        $data[$metaInfo['type'] . '_' . $metaInfo['codename']] = $metaInfo;

                        $isExtInDb = array_filter($extDbLists, function ($v) use ($codename, $metaInfo) {
                            return ($v['type'] === $metaInfo['type'] && $v['codename'] === $codename);
                        });

                        // Add new extensions to database
                        if (empty ($isExtInDb)) {
                            $extData = [
                                'codename'    => $metaInfo['codename'],
                                'type'        => $metaInfo['type'],
                                'name'        => $metaInfo['name'],
                                'version'     => $metaInfo['version'],
                                'description' => $metaInfo['description'] ?? '',
                                'author'      => $metaInfo['author'],
                                'url'         => $metaInfo['url'] ?? '',
                                'setting'     => $metaInfo['setting'] ?? [],
                                'status'      => 0,
                                'install'     => 0,
                                'created'     => date('Y-m-d H:i:s'),
                                'updated'     => date('Y-m-d H:i:s'),
                            ];

                            $this->db->add(DB_PREFIX . 'extension', $extData);

                            // Optional extensions meta
                            if (!empty ($metaInfo['meta'])) {
                                $extension_id = (int) $this->db->insertId();

                                $params = [];
                                foreach ($metaInfo['meta'] as $key => $value) {
                                    $params[] = [$extension_id, $key, (is_array($value) ? json_encode($value) : $value), (is_array($value) ? 1 : 0)];
                                }

                                $this->db->transaction(
                                    "INSERT INTO `" . DB_PREFIX . "extension_meta` (`extension_id`, `key`, `value`, `encoded`) VALUES (?i, ?s, ?s, ?i)",
                                    $params
                                );
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function info()
    {
        $this->load->model('extension/manage');
        $this->load->language('extension/manage');

        $data = $this->model_extension_manage->getExtension(
            $this->request->getString('query.ext'),
            $this->request->getString('query.codename')
        );

        $extMetaPath = PATH_EXTENSIONS . $data['type'] . DS . $data['codename'] . DS . 'meta.json';

        $data['_meta'] = [];
        $data['hasUpdate'] = false;

        if (is_file($extMetaPath)) {
            $data['_meta'] = json_decode(
                file_get_contents(PATH_EXTENSIONS . $data['type'] . DS . $data['codename'] . DS . 'meta.json'),
                true
            );
            $data['hasUpdate'] = $data['version'] !== $data['_meta']['version'];
        }

        $this->response->setOutput($this->load->view('extension/manage_ext_info', $data));
    }

    public function upload()
    {
        $this->load->language('extension/manage');

        $data = [
            'success' => 0,
            'message' => '',
        ];

        if (!$this->user->hasPermission('modify', 'extension/manage')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }

        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }

        $fileUpload = $this->request->getArray('files.file');

        if (isset ($fileUpload['name'])) {
            if (substr($fileUpload['name'], -10) != '.shift.zip') {
                $data['message'] = $this->language->get('error_package_type');
            } else {
                $package = PATH_TEMP . 'storage' . DS . $fileUpload['name'];

                move_uploaded_file($fileUpload['tmp_name'], $package);

                if (!is_file($package)) {
                    $data['message'] = $this->language->get('error_package_move') . $package;
                    unlink($fileUpload['tmp_name']);
                } else {
                    $extZip = new \ZipArchive();
                    $extZipOpenStatus = $extZip->open($package);

                    if ($extZipOpenStatus !== true) {
                        $data['message'] = $this->language->get('error_package_open');
                    } else {
                        $extMeta = json_decode($extZip->getFromName('meta.json'), true);

                        if (empty ($extMeta['type']) || empty ($extMeta['codename']) || empty ($extMeta['name'])) {
                            $data['message'] = $this->language->get('error_package_meta');
                        } else {
                            $extPath = PATH_EXTENSIONS . $extMeta['type'] . DS;

                            if (!is_writable($extPath)) {
                                $data['message'] = $this->language->get('error_path_write') . $extPath;
                            } else {
                                $isExtracted = $extZip->extractTo($extPath . $extMeta['codename'] . DS);

                                if (!$isExtracted) {
                                    $data['message'] = $this->language->get('error_package_extract') . $extPath . $extMeta['codename'] . DS;
                                } else {
                                    $data['success'] = 1;
                                    $data['message'] = $this->language->get('success_upload');
                                }
                            }
                        }
                    }

                    $extZip->close();
                }

                unlink($package);
            }
        }

        $this->response->setOutputJson($data);
    }

    private function parseSize($size): float
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);

        if ($unit) {
            $size = $size * pow(1024, stripos('bkmgtpezy', $unit[0]));
        }

        return round($size);
    }
}
