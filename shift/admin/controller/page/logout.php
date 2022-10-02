<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Page;

use Shift\System\Mvc;

class Logout extends Mvc\Controller
{
    public function index()
    {
        $this->user->logout();

        $this->response->redirect($this->router->url('page/login'));
    }
}
