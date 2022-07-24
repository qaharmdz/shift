<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Mvc;

class Logout extends Mvc\Controller
{
    public function index()
    {
        $this->user->logout();
        $this->session->delete('token');

        $this->response->redirect($this->router->url('common/login'));
    }
}
