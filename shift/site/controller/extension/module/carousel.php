<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Extension\Module;

use Shift\System\Mvc;

class Carousel extends Mvc\Controller
{
    public function index($setting)
    {
        static $module = 0;

        $this->load->model('design/banner');

        $this->document->addStyle('asset/script/jquery/owl-carousel/owl.carousel.css');
        $this->document->addScript('asset/script/jquery/owl-carousel/owl.carousel.min.js');

        $data['banners'] = array();

        $results = $this->model_design_banner->getBanner($setting['banner_id']);

        foreach ($results as $result) {
            if (is_file(PATH_MEDIA . $result['image'])) {
                $data['banners'][] = array(
                    'title' => $result['title'],
                    'link'  => $result['link'],
                    'image' => $this->image->construct($result['image'], (int)$setting['width'], (int)$setting['height'])
                );
            }
        }

        $data['module'] = $module++;

        return $this->load->view('extension/module/carousel', $data);
    }
}
