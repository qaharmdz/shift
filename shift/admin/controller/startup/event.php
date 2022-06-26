<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Startup;

use Shift\System\Core\Mvc;

class Event extends Mvc\Controller
{
    public function index()
    {
        // Add events from the DB
        $this->load->model('extension/event');

        $results = $this->model_extension_event->getEvents();

        foreach ($results as $result) {
            if ((substr($result['trigger'], 0, 6) == 'admin/') && $result['status']) {
                $this->event->register(substr($result['trigger'], 6), new Action($result['action']));
            }
        }
    }
}
