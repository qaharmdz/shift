<?php

declare(strict_types=1);

namespace Shift\System\Core;

use Shift\System\Http\Dispatch;

class Event
{
    protected $events = [];
    protected $propagation = true;

    /**
     * Add event listener
     *
     * @param  string    $eventName
     * @param  Dispatch  $listener
     * @param  integer   $priority
     */
    public function addListener(string $eventName, Dispatch $listener, int $priority = 0)
    {
        $route = $listener->getRoute();

        $this->events[$eventName][$route] = [
            'priority' => $priority,
            'route'    => $route,
            'dispatch' => $listener,
        ];
    }

    /**
     * Get event listener
     *
     * @param  string $eventName
     *
     * @return array
     */
    public function getListeners(string $eventName): array
    {
        if ($this->hasEmitter($eventName)) {
            return $this->events[$eventName];
        }

        return [];
    }

    /**
     * Remove event listener
     *
     * @param  string $eventName
     * @param  string $listenerRoute
     */
    public function removeListener(string $eventName, string $listenerRoute)
    {
        if (isset($this->events[$eventName][$listenerRoute])) {
            unset($this->events[$eventName][$listenerRoute]);
        }
    }

    /**
     * Check if a given listener exists in emitter.
     *
     * @param  string  $eventName
     *
     * @return boolean
     */
    public function hasListener(string $eventName, string $listenerRoute): bool
    {
        if ($this->hasEmitter($eventName)) {
            return array_key_exists($listenerRoute, $this->events[$eventName]);
        }

        return false;
    }

    /**
     * An event is triggered, execute all listeners.
     *
     * Recommended format for Event name is `route::anchor`, where "anchor" indicate the event placement.
     * The following example show different anchors for a controller "post/content".
     * - post/content::before     automatic
     * - post/content::content    manually added
     * - post/content::comments   manually added
     * - post/content::after      automatic
     *
     * @param  string $eventName
     * @param  array  $params
     */
    public function emit(string $eventName, array $params = [])
    {
        if (!$this->hasEmitter($eventName) || !$this->events[$eventName]) {
            return;
        }

        array_multisort(
            array_column($this->events[$eventName], 'priority'), SORT_DESC,
            array_column($this->events[$eventName], 'route'), SORT_ASC,
            $this->events[$eventName]
        );

        foreach ($this->events[$eventName] as $listener) {
            $listener['dispatch']->execute($params);

            // Stop propagation
            if (!$this->propagation) {
                break;
            }
        }

        // Normalize
        $this->propagation = true;
    }

    /**
     * Check if a given emitter exists.
     *
     * @param  string  $eventName
     *
     * @return boolean
     */
    public function hasEmitter(string $eventName): bool
    {
        return array_key_exists($eventName, $this->events);
    }

    /**
     * Remove all attached listener on emitter.
     *
     * @param  string $eventName
     */
    public function removeEmitter(string $eventName): void
    {
        if ($this->hasEmitter($eventName)) {
            unset($this->events[$eventName]);
        }
    }

    /**
     * Stop next event from being executed.
     */
    public function stopPropagation(): void
    {
        $this->propagation = false;
    }
}
