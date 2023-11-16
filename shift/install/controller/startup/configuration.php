<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Startup;

use Shift\System\Mvc;

class Configuration extends Mvc\Controller
{
    public function index()
    {
        $this->language->load('en');
        $this->cache->setup('DevNull');
        $this->log->setConfig([
            'context' => [
                'uri'        => htmlspecialchars_decode($_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']),
                'referrer'   => htmlspecialchars_decode($_SERVER['HTTP_REFERER'] ?? ''),
                'method'     => $_SERVER['REQUEST_METHOD'],
                'ip_address' => $_SERVER['REMOTE_ADDR'],
            ],
        ]);
        $this->view->setConfig([
            'debug' => true,
        ]);

        // Not supporting URL alias, trigger 404 not found
        if ($this->request->has('query._route_')) {
            $this->request->set('query.route', $this->request->get('query._route_'));
        }

        // Route based body class
        $class_body = [];
        foreach ($this->request->get('query', ['route' => $this->config->get('root.route_default')]) as $key => $value) {
            $prefix = 'sfp-' . $key;
            if ($key == 'route') {
                $prefix = 'sfr';
            }

            $class_body[] = str_replace(['/', '\\', '_'], '-', $prefix . '-' . $value);
        }
        $class_body = array_unique(array_merge($class_body, $this->document->getNode('class_body', [])));
        $this->document->setNode('class_body', $class_body);

        $this->view->setGlobal('config', $this->config);
        $this->view->setGlobal('router', $this->router);
        $this->view->setGlobal('document', $this->document);
        $this->view->setGlobal('language', $this->language);
    }
}
