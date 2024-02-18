<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Http;
use Shift\System\Exception;

class App extends Mvc\Controller {
    public function index()
    {
        $route = $this->request->get('query.route');
        $params = [];
        $output = null;

        $this->event->emit($eventName = 'shift/app::before', [$eventName, &$params, &$output]);
        $this->event->emit($eventName = 'controller/' . $route . '::before', [$eventName, &$params, &$output]);

        if (is_null($output)) {
            $dispatch = new Http\Dispatch($route);
            $dispatch->execute($params);

            $output = $this->response->getOutput();
        }

        if (is_null($output)) {
            throw new Exception\NotFoundHttpException(sprintf('App route "%s" do not have a response output.', $route));
        }

        $this->event->emit($eventName = 'controller/' . $route . '::after', [$eventName, &$params, &$output]);
        $this->event->emit($eventName = 'shift/app::after', [$eventName, &$params, &$output]);
    }
}
