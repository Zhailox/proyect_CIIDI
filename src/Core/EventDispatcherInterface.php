<?php

declare(strict_types=1);

namespace App\Core;

interface EventDispatcherInterface
{
    public function subscribe(string $eventName, EventListenerInterface $listener): void;
    public function dispatch(EventInterface $event): void;
}
