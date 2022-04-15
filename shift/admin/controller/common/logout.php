<?php

declare(strict_types=1);

class ControllerCommonLogout extends Controller
{
    public function index()
    {
        $this->user->logout();
        $this->session->delete('token');

        $this->response->redirect($this->url->link('common/login', '', true));
    }
}
