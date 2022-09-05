<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Block;

use Shift\System\Mvc;

class Header extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/header', 'header');

        if (is_file(PATH_MEDIA . $this->config->get('system.site.icon'))) {
            $this->document->addLink('icon', $this->config->get('env.url_media') . $this->config->get('system.site.icon'));
        }

        $class_body = [];
        foreach ($this->request->get('query', ['route' => $this->config->get('root.route_default')]) as $key => $value) {
            if (in_array($key, ['token'])) {
                continue;
            }

            if (in_array($key, ['_route_', 'route'])) {
                $class_body[] = str_replace(['/', '\\', '_'], '-', $value);
            } else {
                $class_body[] = str_replace(['/', '\\', '_'], '-', $key . '-' . $value);
            }
        }
        $class_body = array_unique(array_merge($class_body, $this->document->getNode('class_body', [])));
        $this->document->setNode('class_body', $class_body);

        $data = [];

        $this->load->model('setting/site');
        $results       = $this->model_setting_site->getSites();
        $data['sites'] = [];

        foreach ($results as $result) {
            $data['sites'][] = [
                'name' => $result['name'],
                'url'  => $result['url_host'],
            ];
        }

        return $this->load->view('block/header', $data);
    }
}
