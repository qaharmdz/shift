<?php

declare(strict_types=1);

class ControllerCommonLogout extends Controller
{
    public function index()
    {
        $this->user->logout();

        unset($this->session->get('token'));

        $this->response->redirect($this->url->link('common/login', '', true));
    }
}
