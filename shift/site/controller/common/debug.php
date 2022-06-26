<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Core\Mvc;
use Shift\System\Helper\Arr;

class Debug extends Mvc\Controller
{
    public function index()
    {
        d(
            [
                'URL_APP'         => URL_APP,
                'URL_SITE'        => URL_SITE,
                'DIR_APPLICATION' => DIR_APPLICATION,
                'DIR_SITE'        => DIR_SITE,
                'DIR_LANGUAGE'    => DIR_LANGUAGE,
                'DIR_TEMPLATE'    => DIR_TEMPLATE,
                'DIR_SYSTEM'      => DIR_SYSTEM,
                'DIR_STORAGE'     => DIR_STORAGE,
                'DIR_CACHE'       => DIR_CACHE,
                'DIR_UPLOAD'      => DIR_UPLOAD,
                'DIR_IMAGE'       => DIR_IMAGE,
            ],
            $this->config->all()
        );

        $this->helperArr();

        $this->load->model('tool/image');
        d($this->model_tool_image->resize('no-image.png', 100, 100));

        $this->response->setOutput('<a href="http://localhost/mdzGit/shift/public/" target="_blank">Home</a>');
    }

    private function helperArr()
    {
        $array = [
            'foo' => 'bar',
            'boo' => 'baz',
            'lev1' => [
                'lev2a' => 'lev3a',
                'lev2b' => 'lev3b',
                'lev2c' => [
                    'lev3c' => 'lev4a',
                    'lev3d' => 'lev4b',
                    'lev3e' => 'lev4c',
                ],
            ],
        ];

        $array_temp = $array;

        Arr::set($array, 'lev1.lev2c.lev3f', 'cool');

        d(
            Arr::get($array, 'foo'),
            Arr::get($array, 'lol', 'not-found'),
            Arr::get($array, 'lev1.lev2c.lev3c'),
            Arr::get($array, 'lev1.lev2c.lev3f'),
            Arr::has($array, 'lev1.lev2b'),
            Arr::has($array, 'lev1.lev2z'),
            $array === $array_temp,
        );
    }
}
