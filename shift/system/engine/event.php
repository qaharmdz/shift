<?php

declare(strict_types=1);

class Event
{
    protected $registry;
    protected $data = array();

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function register($trigger, $action)
    {
        $this->data[$trigger][] = $action;
    }

    public function trigger($event, array $args = array())
    {
        foreach ($this->data as $trigger => $actions) {
            if (preg_match('/^' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($trigger, '/')) . '/', $event)) {
                foreach ($actions as $action) {
                    $result = $action->execute($args);

                    if (!is_null($result) && !($result instanceof Exception)) {
                        return $result;
                    }
                }
            }
        }
    }

    public function unregister($trigger, $route = '')
    {
        if ($route) {
            foreach ($this->data[$trigger] as $key => $action) {
                if ($action->getId() == $route) {
                    unset($this->data[$trigger][$key]);
                }
            }
        } else {
            unset($this->data[$trigger]);
        }
    }

    public function removeAction($trigger, $route)
    {
        foreach ($this->data[$trigger] as $key => $action) {
            if ($action->getId() == $route) {
                unset($this->data[$trigger][$key]);
            }
        }
    }
}
