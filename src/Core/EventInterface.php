<?php

declare(strict_types=1);

namespace App\Core;

interface EventInterface
{
    public function getName(): string;
    public function getPayload(): array;
}
