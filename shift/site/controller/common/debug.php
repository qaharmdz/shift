<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;
use Shift\System\Helper\Arr;

class Debug extends Mvc\Controller
{
    public function index()
    {
        d(
            $userDefinedConstants = get_defined_constants(true)['user'],
            $_SESSION === $this->session->all(),
            $this->session->all(),
            $this->config->all(),
            // $this->user->get(),
        );

        // d(
        //     $this->cache->getInstance()->getConfig(),
        //     $this->cache->getInstance()->getStats(),
        //     $this->cache->setup('DevNull'),
        //     $this->cache->getInstance()->getConfig(),
        //     $this->cache->getInstance()->getStats(),
        // );

        // $mail = $this->mail->getInstance();
        // d($this->mail, $mail);

        // $this->helperArr();

        // $this->load->model('tool/image');
        // d($this->model_tool_image->resize('no-image.png', 100, 100));

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
