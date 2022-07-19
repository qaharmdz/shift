<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Core\{Http, Mvc};

class Event extends Mvc\Controller
{
    public function index()
    {
        // Add events from the DB
        $this->load->model('extension/event');

        $results = $this->model_extension_event->getEvents();

        foreach ($results as $result) {
            $this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Http\Dispatch($result['action']));
        }
    }
}
