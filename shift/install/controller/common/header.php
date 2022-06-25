<?php

declare(strict_types=1);

class ControllerCommonHeader extends Controller
{
    public function index()
    {
        $this->language->load('common/header');

        $data['title'] = $this->document->getTitle();
        $data['description'] = $this->document->getDescription();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();

        $data['base'] = URL_APP;

        return $this->load->view('common/header', $data);
    }
}
