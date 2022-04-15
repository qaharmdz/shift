<?php

declare(strict_types=1);

class ControllerStartupRouter extends Controller
{
    public function index()
    {
        $route = $this->request->getString(
            'query.route',
            $this->config->get('root.action_default')
        );

        if (str_starts_with($route, 'startup/')) {
            $route = $this->config->get('root.action_error');
        }

        $data = array();

        // Sanitize the call
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

        // Trigger the pre events
        $result = $this->event->trigger('controller/' . $route . '/before', array(&$route, &$data));

        if (!is_null($result)) {
            return $result;
        }

        $action = new Action($route);

        // Any output needs to be another Action object.
        $output = $action->execute($this->registry, $data);

        // Trigger the post events
        $result = $this->event->trigger('controller/' . $route . '/after', array(&$route, &$output));

        if (!is_null($result)) {
            return $result;
        }

        return $output;
    }
}
