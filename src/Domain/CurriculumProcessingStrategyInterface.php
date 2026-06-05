<?php

declare(strict_types=1);

namespace App\Domain;

interface CurriculumProcessingStrategyInterface
{
    /**
     * Procesa los datos crudos del currículo y extrae métricas o perfiles.
     * En futuras iteraciones, una clase inyectará un modelo NLP aquí.
     * 
     * @param array $rawCurriculumData Datos provenientes del formulario o API
     * @return array Datos limpios estructurados para el dominio
     */
    public function process(array $rawCurriculumData): array;
}
