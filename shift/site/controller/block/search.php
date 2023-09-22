<?php

declare(strict_types=1);

namespace Shift\Site\Controller\block;

use Shift\System\Mvc;

class Search extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('block/search');

        $data = [];
        $data['search'] = $this->request->getString('query.search', '');

        return $this->load->view('block/search', $data);
    }
}
