<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Core\EventInterface;

class CurriculumUpdatedEvent implements EventInterface
{
    private array $payload;

    public function __construct(int $userId, array $processedData)
    {
        $this->payload = [
            'user_id' => $userId,
            'data' => $processedData,
            'timestamp' => time()
        ];
    }

    public function getName(): string
    {
        return 'curriculum.updated';
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
