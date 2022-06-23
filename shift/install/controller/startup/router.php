<?php

declare(strict_types=1);

class ControllerStartupRouter extends Controller
{
    public function index()
    {
        if ($this->request->has('query.route') && $this->request->get('query.route') != 'action/route') {
            return new Action($this->request->get('query.route'));
        } else {
            return new Action($this->config->get('root.action_default'));
        }
    }
}
