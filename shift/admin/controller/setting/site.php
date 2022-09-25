<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Setting;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Site extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('page_title'));
        $this->document->loadAsset('datatables');

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('system')],
            [$this->language->get('page_title')],
            [$this->language->get('list'), $this->router->url('setting/site')],
        ]);

        $data = [];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/site_list', $data));
    }

    public function list()
    {
        if (!$this->request->has('post.draw')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $this->load->model('setting/site');
        $this->load->language('setting/site');

        $params  = $this->request->get('post');
        $results = $this->model_setting_site->dtRecords($params);

        $items = [];
        for ($i = 0; $i < $results->num_rows; $i++) {
            $items[$i] = $results->rows[$i];

            $items[$i]['DT_RowClass'] = 'dt-row-' . $items[$i]['site_id'];
            $items[$i]['url_edit']    = $this->router->url('setting/site/form', 'site_id=' . $items[$i]['site_id']);
        }

        $data = [
            'draw'            => (int)$params['draw'] ?? 1,
            'data'            => $items,
            'recordsFiltered' => $results->num_rows,
            'recordsTotal'    => $this->model_setting_site->getTotal(),
        ];

        $this->response->setOutputJson($data);
    }

    public function dtaction()
    {
        // TODO: DataTables quick action - delete
    }

    // Form
    // ================================================

    public function form()
    {
        $site_id = $this->request->has('query.site_id') ? $this->request->getInt('query.site_id', 0) : null;
        $mode    = is_null($site_id) ? 'add' : 'edit';

        $this->load->config('setting/site');
        $this->load->model('setting/setting');
        $this->load->model('setting/site');
        $this->load->language('setting/site');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('system')],
            [$this->language->get('page_title')],
            [$this->language->get('list'), $this->router->url('setting/site')],
            [$this->language->get($mode), $this->router->url('setting/site/form')],
        ]);

        $data = [];

        $data['setting'] = array_replace_recursive(
            $this->config->getArray('setting.site.form'),
            $this->model_setting_setting->getSetting('system', 'site', (int)$site_id),
            $this->request->get('post', [])
        );

        $this->load->model('extension/language');
        $data['languages'] = $this->model_extension_language->getLanguages();

        $this->load->model('design/layout');
        $data['layoutList'] = $this->model_design_layout->getLayouts();

        $this->load->model('extension/extension');
        $extensions = $this->model_extension_extension->getInstalled('theme');

        $data['themes'] = [];
        foreach ($extensions as $code) {
            $lang = $this->load->language('extension/theme/' . $code, '_temp');
            $data['themes'][] = [
                'text'  => $lang['heading_title'] ?? ucwords($code),
                'value' => $code,
            ];
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('setting/site_form', $data));
    }

    public function save()
    {
        $output = [];

        $this->response->setOutputJson($output);
    }
}
