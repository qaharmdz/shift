<?php

declare(strict_types=1);

use Shift\System\Helper\Arr;

class ControllerCommonDebug extends Controller
{
    public function index()
    {
        d(
            $this->config->all()
        );

        $this->helperArr();

        $this->load->model('tool/image');
        d($this->model_tool_image->resize('no-image.png', 100, 100));

        /*
        $this->request->post->all();
        $this->request->post->get();
        $this->request->post->set();

        $this->request->query->all();
        $this->request->query->get();
        $this->request->query->set();

        $data['config_image'] = $this->request->get('post.config_image', $this->config->get('config_image', ''));
        $data['config_image'] = $this->request->post->get('config_image', $this->config->get('config_image', ''));
         */

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
