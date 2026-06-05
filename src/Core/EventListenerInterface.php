<?php

declare(strict_types=1);

namespace App\Core;

interface EventListenerInterface
{
    public function handle(EventInterface $event): void;
}
