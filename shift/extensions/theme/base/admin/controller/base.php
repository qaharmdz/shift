<?php

declare(strict_types=1);

namespace Shift\Extensions\Theme\Base\Admin\Controller;

use Shift\System\Mvc;

class Base extends Mvc\Controller {
    public function index()
    {
        $site_id = $this->request->getInt('query.site_id', 0);
        $extension_id = $this->request->getInt('query.extension_id', 0);

        $this->load->model('setting/site');
        $this->load->model('setting/setting');
        $this->load->model('extension/manage');
        $this->load->config('extensions/theme/base');
        $this->load->language('extensions/theme/base');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('extensions')],
            [$this->language->get('theme'), $this->router->url('extension/theme')],
            [
                $this->language->get('page_title'),
                $this->router->url('extensions/theme/base', 'extension_id=' . $extension_id . '&site_id=' . $site_id),
            ],
        ]);

        $data = [];

        $data['site_id'] = $site_id;
        $data['extension_id'] = $extension_id;
        $data['type'] = $this->request->get('query.type', 'setting');
        $data['url_setting'] = $this->router->url(
            'extensions/theme/base',
            'extension_id=' . $extension_id . '&site_id=' . $site_id . '&type=setting'
        );
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('extensions.theme.base.form'),
            $this->model_extension_manage->getById($extension_id),
            ['site' => $this->model_setting_setting->getSetting('theme', 'base', $site_id)]
        );

        $data['sites'] = [];
        foreach ($this->model_setting_site->getSites() as $key => $site) {
            $data['sites'][$key] = $site;
            $data['sites'][$key]['url_setting'] = $this->router->url(
                'extensions/theme/base',
                'extension_id=' . $extension_id . '&site_id=' . $site_id . '&type=site'
            );
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer'] = $this->load->controller('block/footer');
        $data['header'] = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('extensions/theme/base/base', $data));
    }

    public function install()
    {
        $this->log->write(__METHOD__);
    }

    public function uninstall()
    {
        $this->log->write(__METHOD__);
    }
}
