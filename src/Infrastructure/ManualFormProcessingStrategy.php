<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\CurriculumProcessingStrategyInterface;

class ManualFormProcessingStrategy implements CurriculumProcessingStrategyInterface
{
    public function process(array $rawCurriculumData): array
    {
        // En esta iteración (manual), extraemos habilidades separadas por comas.
        // Sirve como limpieza básica antes de persistir o emitir eventos.
        $skillsText = $rawCurriculumData['skills'] ?? '';
        $skillsArray = array_map('trim', explode(',', $skillsText));
        
        return [
            'summary' => trim($rawCurriculumData['summary'] ?? ''),
            'education' => trim($rawCurriculumData['education'] ?? ''),
            'extracted_skills' => array_filter($skillsArray),
            'processed_at' => date('Y-m-d H:i:s'),
            'ai_marker' => 'manual_v1' // Marcador de versión para analítica futura
        ];
    }
}
