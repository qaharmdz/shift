<?php

declare(strict_types=1);

class ControllerCommonSearch extends Controller
{
    public function index()
    {
        $this->load->language('common/search');

        $data['text_search'] = $this->language->get('text_search');

        $data['search'] = $this->request->getString('query.search', '');

        return $this->load->view('common/search', $data);
    }
}
