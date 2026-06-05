<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\RoleMiddleware;
use App\Infrastructure\Database;
use App\Infrastructure\ManualFormProcessingStrategy;
use App\Infrastructure\EventDispatcher;
use App\Domain\Events\CurriculumUpdatedEvent;
use App\Infrastructure\Listeners\FeatureStoreAnalyticsListener;

class CurriculumController
{
    public function create()
    {
        // Estudiantes y Docentes pueden ingresar su currículo
        RoleMiddleware::check(['student', 'teacher', 'superadmin']);
        
        require __DIR__ . '/../../../templates/curriculum/create.php';
    }

    public function store()
    {
        RoleMiddleware::check(['student', 'teacher', 'superadmin']);

        $rawData = [
            'summary' => $_POST['summary'] ?? '',
            'education' => $_POST['education'] ?? '',
            'skills' => $_POST['skills'] ?? ''
        ];
        
        // 1. Patrón Estrategia para procesar el currículo
        $strategy = new ManualFormProcessingStrategy();
        $processedData = $strategy->process($rawData);
        
        // 2. Simulación de guardado en BD usando PDO
        try {
            $db = Database::getConnection();
            if ($db) {
                // user_id = 2 (Simulación de estudiante logueado)
                $stmt = $db->prepare("INSERT INTO curriculum_profiles (user_id, summary, education, skills) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE summary=?, education=?, skills=?");
                $skillsJson = json_encode($processedData['extracted_skills']);
                $stmt->execute([
                    2, $processedData['summary'], $processedData['education'], $skillsJson,
                    $processedData['summary'], $processedData['education'], $skillsJson
                ]);
            }
        } catch (\Exception $e) {
            // Ignorar para la demo local sin DB
        }

        // 3. Patrón Observador para despachar el evento de Analítica / Feature Store
        $dispatcher = new EventDispatcher();
        $dispatcher->subscribe('curriculum.updated', new FeatureStoreAnalyticsListener());
        
        // Disparamos el evento pasando el ID de usuario y los datos procesados
        $event = new CurriculumUpdatedEvent(2, $processedData);
        $dispatcher->dispatch($event);

        $ai_marker = $processedData['ai_marker'];
        $success = true;
        
        require __DIR__ . '/../../../templates/curriculum/create.php';
    }
}
