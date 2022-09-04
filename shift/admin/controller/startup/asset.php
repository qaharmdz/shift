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
    }
}
