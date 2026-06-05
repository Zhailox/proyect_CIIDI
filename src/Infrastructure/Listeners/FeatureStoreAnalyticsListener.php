<?php

declare(strict_types=1);

namespace App\Infrastructure\Listeners;

use App\Core\EventListenerInterface;
use App\Core\EventInterface;

class FeatureStoreAnalyticsListener implements EventListenerInterface
{
    public function handle(EventInterface $event): void
    {
        // En un entorno de producción, los datos se emitirían a un broker (ej. Kafka, RabbitMQ)
        // Aquí simularemos el volcado a un archivo de registro (log) para que los pipelines de ML lo consuman.
        $payload = $event->getPayload();
        $eventName = $event->getName();
        
        $logEntry = json_encode([
            'event' => $eventName,
            'feature_data' => $payload,
            'ingested_at' => date('c')
        ]) . PHP_EOL;

        $logDir = __DIR__ . '/../../../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        file_put_contents($logDir . '/ml_feature_store.log', $logEntry, FILE_APPEND);
    }
}
