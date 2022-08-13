<?php

declare(strict_types=1);

namespace Shift\Site\Controller\common;

use Shift\System\Mvc;

class Search extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/search');

        $data['text_search'] = $this->language->get('text_search');
        $data['search'] = $this->request->getString('query.search', '');

        return $this->load->view('common/search', $data);
    }
}
