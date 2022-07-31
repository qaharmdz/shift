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

        // $mail = $this->mail->getInstance();
        // d($this->mail, $mail);

        // $this->testHelperArr();
        // $this->testCache();
        $this->testImage();

        $this->response->setOutput('<a href="http://localhost/mdzGit/shift/public/" target="_blank">Home</a>');
    }

    private function testImage()
    {
        $html = '';

        // $this->image->clearCache();

        $no_image  = $this->image->fromFile('image/no-image.png')->resize(150, 150)->toCache()->getUrl();
        // $thumbnail = $this->image->fromFile('image/demo/banners/MacBookAir.jpg')->thumbnail(300, 300, 'center')->toCache('apple-macbook-air')->getUrl();
        $thumbnail = $this->image->construct('image/demo/banners/MacBookAir.jpg', 300, 300, 'apple-macbook-air');
        $bestfit   = $this->image->fromFile('image/demo/banners/MacBookAir.jpg')->bestFit(305, 305)->toCache()->getUrl();
        $resize    = $this->image->fromFile('image/demo/banners/MacBookAir.jpg')->resize(310, 310)->toCache()->getUrl();

        $html .= '<div style="margin:10px;padding:10px;background:#eee;"><img src="' . $no_image . '" /></div>';
        $html .= '<div style="margin:10px;padding:10px;background:#eee;">Thumbnail <img src="' . $thumbnail . '" /></div>';
        $html .= '<div style="margin:10px;padding:10px;background:#eee;">BestFit <img src="' . $bestfit . '" /></div>';
        $html .= '<div style="margin:10px;padding:10px;background:#eee;">Resize <img src="' . $resize . '" /></div>';

        echo $html;
    }

    private function testCache()
    {
        d(
            $this->cache->getInstance()->getConfig(),
            $this->cache->getInstance()->getStats(),
            $this->cache->setup('DevNull'),
            $this->cache->getInstance()->getConfig(),
            $this->cache->getInstance()->getStats(),
        );
    }

    private function testHelperArr()
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
