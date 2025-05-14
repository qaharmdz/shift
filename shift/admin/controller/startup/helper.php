<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;

class Helper extends Mvc\Controller {
    public function index()
    {
        $class_body = [];
        foreach ($this->request->get('query', ['route' => $this->config->get('root.route_default')]) as $key => $value) {
            if (in_array($key, ['access_token'])) {
                continue;
            }

            $prefix = 'sfp-' . $key;
            if ($key == 'route') {
                $prefix = 'sfr';
            }

            $class_body[] = str_replace(['/', '\\', '_'], '-', $prefix . '-' . $value);
        }
        $class_body = array_unique(array_merge($class_body, $this->document->getNode('class_body', [])));
        $this->document->addNode('class_body', $class_body);

        $this->document->addNode('mainnav_position', 'header'); // sidebar-left
        $this->document->addNode('mainnav', $this->load->controller('helper/mainnav')); // sidebar-left
    }
}
