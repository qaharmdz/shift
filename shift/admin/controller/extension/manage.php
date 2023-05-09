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

        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extension')],
            [$this->language->get('page_title'), $this->router->url('extension/manage')],
        ]);

        $data = [];

        $newExtensions = $this->checkExtensions();
        // TODO: notification new extensions

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extension/manage', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('extension/manage');

        $params  = $this->request->get('post');
        $results = $this->model_extension_manage->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['extension_id'];
        }

        $data = [
            'draw' => (int)$params['draw'] ?? 1,
            'data' => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_extension_manage->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        // TODO: install, uninstall, delete extension
        $data  = [
            'items'     => $items,
            'message'   => '',
            'updated'   => [],
        ];

        $this->response->setOutputJson($data);
    }

    /**
     * Check if there is new extensions and add to the DB `sf_extension`
     *
     * @return array
     */
    private function checkExtensions(): array
    {
        $data = ['total' => 0];

        $extensions = $this->db->get("SELECT * FROM `" . DB_PREFIX . "extension`")->rows;

        foreach (glob(PATH_EXTENSIONS . '*', GLOB_ONLYDIR) as $ext) {
            $extType = basename($ext);
            $data[$extType] = [];

            foreach (glob($ext . DS . '*', GLOB_ONLYDIR | GLOB_NOESCAPE) as $node) {
                if (is_file($node . DS . 'meta.json')) {
                    $codename = basename($node);
                    $metas    = json_decode(file_get_contents($node . DS . 'meta.json'), true);

                    if ($codename === $metas['codename']) {
                        $isExtInDb = array_filter($extensions, function ($v) use ($extType, $codename) {
                            return ($v['type'] === $extType && $v['codename'] === $codename);
                        });

                        // Add new extensions to database
                        if (empty($isExtInDb)) {
                            $extData = [
                                'codename'    => $metas['codename'],
                                'type'        => $extType,
                                'name'        => $metas['name'],
                                'version'     => $metas['version'],
                                'description' => $metas['description'],
                                'author'      => $metas['author'],
                                'url'         => $metas['url'],
                            ];

                            $this->db->add(DB_PREFIX . 'extension', $extData);

                            $data[$extType][] = $extData;

                            $data['total']++;
                        }
                    }
                }
            }
        }

        return $data;
    }
}
