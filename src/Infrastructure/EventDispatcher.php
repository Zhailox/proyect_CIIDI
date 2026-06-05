<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Core\EventDispatcherInterface;
use App\Core\EventInterface;
use App\Core\EventListenerInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    public function subscribe(string $eventName, EventListenerInterface $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch(EventInterface $event): void
    {
        $eventName = $event->getName();
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $listener) {
            $listener->handle($event);
        }
    }
}
