<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Mvc;

class Asset extends Mvc\Controller
{
    public function index()
    {
        $this->regAssets();

        $this->document->loadAsset('form');
    }

    protected function regAssets()
    {
        $this->document->addAsset('form', [
            'script' => [
                $this->config->get('env.url_app') . 'asset/script/form/jquery.form.min.js',
            ]
        ]);

        $this->document->addAsset('datatables', [
            'style'  => [
                $this->config->get('env.url_app') . 'asset/style/shift.datatables.css',
                $this->config->get('env.url_app') . 'asset/script/flatpickr/flatpickr.min.css',
            ],
            'script' => [
                $this->config->get('env.url_app') . 'asset/script/datatables/datatables.min.js',
                $this->config->get('env.url_app') . 'asset/script/shift.datatables.js',
                // Extra
                $this->config->get('env.url_app') . 'asset/script/typewatch/typewatch.min.js',
                $this->config->get('env.url_app') . 'asset/script/flatpickr/flatpickr.min.js',
            ]
        ]);

        $this->document->addAsset('flatpickr', [
            'style'  => [
                $this->config->get('env.url_app') . 'asset/script/flatpickr/flatpickr.min.css',
            ],
            'script' => [
                $this->config->get('env.url_app') . 'asset/script/flatpickr/flatpickr.min.js',
            ]
        ]);

        $this->document->addAsset('editor', [
            'script' => [
                $this->config->get('env.url_app') . 'asset/script/ckeditor/build/ckeditor.js',
                $this->config->get('env.url_app') . 'asset/script/shift.ckeditor.js',
            ]
        ]);

        $this->document->addAsset('select2', [
            'style'  => [
                $this->config->get('env.url_app') . 'asset/script/select2/select2.min.css',
            ],
            'script' => [
                $this->config->get('env.url_app') . 'asset/script/select2/select2.min.js',
            ]
        ]);

        $this->document->addAsset('jstree', [
            'style'     => [
                $this->config->get('env.url_app') . 'asset/script/jstree/themes/style.min.css'
            ],
            'script'    => [
                $this->config->get('env.url_app') . 'asset/script/jstree/jstree.min.js'
            ]
        ]);
    }
}
