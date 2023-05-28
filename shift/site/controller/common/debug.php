<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Common;

use Shift\System\Mvc;
use Shift\System\Http;
use Shift\System\Helper\Arr;

class Debug extends Mvc\Controller
{
    public function index()
    {
        echo '<a href="http://localhost/mdzGit/shift/public/" target="_blank">Home</a>';

        d(
            $documentRoot = $_SERVER['DOCUMENT_ROOT'],
            $userDefinedConstants = get_defined_constants(true)['user'],
            $_SESSION === $this->session->all(),
            $this->session->all(),
            $this->config->all(),
            $this->config->get('env.languages'),
            // '----------------------------',
            $this->user->get(),
            $this->language->all(),
            // '----------------------------',
            // $this->view->getConfig(),
            // $this->view->getTemplatePath(),
        );
        d(
            $this->user::class,
            // $this->load->view('Hello {{ name }}', ['name' => 'Shift', 'twigTemplateFromString' => true]),
            // '----------------------------',
            // date('Y-m-d H:i:s'),
            // (int)'foo',
            // (int)'foo bar',
            // (array)'foo',
            // (array)['foo'],
        );

        // $this->dbAllTableSearch('architect');

        /*
        $path = PATH_TEMP . 'twig/';
        $path = PATH_MEDIA . 'cache/';
        // $path = PATH_EXTENSIONS . 'plugin/architect/';

        $iterators = $this->testIterator($path);
        d($path);
        d($iterators);
         */

        // d($this->db->get($this->session->get('dataTables_query'), $this->session->get('dataTables.sql.params')));

        // $mail = $this->mail->getInstance();
        // d($this->mail, $mail);

        // $this->testExtensions();
        // $this->testImage();
        // $this->testCache();
        // $this->testHelperArr();

        // $pagination = new \Shift\System\Library\Pagination();
        // $pagination->page  = 6;
        // $pagination->total = 300;
        // $pagination->limit = 36;
        // $pagination->url = $this->router->url('catalog/information', '&page={page}');
        // d($pagination);
        // echo $pagination->render();
        // echo '<pre><code>' . $this->router->url('catalog/information', '&page={page}') . '</code></pre>';

        // $this->log->write(['foo' => 'bar', 'baz' => [1, 2, 3]]);

        $this->response->setOutput('o_O');
    }

    private function testExtensions()
    {

        $dispatch = new Http\Dispatch('extensions/plugin/architect');

        d(
            $dispatch->getData(),
        );

        try {
            $this->load->controller('its/not/exist');
        } catch (\Exception $e) {
            d($e->getMessage());
        }
    }

    private function testImage()
    {
        $html = '';

        // $this->image->clearCache();

        $no_image  = $this->image->fromFile('image/no-image.png')->resize(150, 150)->toCache()->getUrl();
        // $thumbnail = $this->image->fromFile('image/demo/banners/MacBookAir.jpg')->thumbnail(250, 250)->toCache('apple-macbook-air')->getUrl();
        $thumbnail = $this->image->getThumbnail('image/demo/banners/MacBookAir.jpg', 250, 250, 'apple-macbook-air');
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
            $this->cache->instance()->getConfig(),
            $this->cache->instance()->getStats(),
            // $this->cache->setup('DevNull'),
            // $this->cache->instance()->getConfig(),
            // $this->cache->instance()->getStats(),
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

    private function dbAllTableSearch(string $keyword)
    {
        $tables = $this->db->get(
            "SELECT table_name AS `name` FROM information_schema.TABLES  WHERE table_schema = ?s ORDER BY `name` ASC",
            [$this->config->get('root.database.config.database')]
        )->rows;

        // d($tables);

        foreach ($tables as $table) {
            $columns = $this->db->get(
                "SELECT column_name AS `name` FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?s",
                [$table['name']]
            )->rows;

            // d($columns);

            $colSearch = [];
            foreach ($columns as $column) {
                $colSearch[] = "`" . $column['name'] . "` LIKE '%" . $keyword . "%'";
            }

            // d($colSearch);

            if ($colSearch) {
                $results = $this->db->get("SELECT * FROM `" . $table['name'] . "` WHERE " . implode(' OR ', $colSearch))->rows;

                if ($results) {
                    d($foundTableAt = $table['name'], $results);
                }
            }
        }
    }

    private function testIterator(string $path)
    {
        $dirIterator = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
        $nodes       = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);
        $lists       = [];

        foreach ($nodes as $node) {
            $lists[] = $node->getRealPath();
            // $node->isDir() ? rmdir($node->getRealPath()) : unlink($node->getRealPath());
        }

        return $lists;
    }
}
