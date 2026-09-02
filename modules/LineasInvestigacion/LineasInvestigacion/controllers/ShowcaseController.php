<?php
// modules/LineasInvestigacion/controllers/ShowcaseController.php

require_once __DIR__ . '/../models/LineasModel.php';

/**
 * ShowcaseLineasController
 * Provee los datos de la vista pública de todas las líneas de investigación.
 */
class ShowcaseLineasController {

    public function index(): array {
        $model = new LineasModel();

        // Carga todas las líneas con sus estadísticas desde la BD
        $lineas = $model->getTodasConEstadisticas();

        // Calcular totales globales para el hero
        $total_dimensiones  = 0;
        $total_proyectos    = 0;
        $total_invest       = 0;

        foreach ($lineas as $linea) {
            $total_dimensiones += (int) $linea['total_dimensiones'];
            $total_proyectos   += (int) $linea['total_proyectos'];
            $total_invest      += (int) $linea['total_investigaciones'];
        }

        // Mapa de iconos por palabras clave del nombre de la línea
        $mapa_iconos = [
            'SISTEMA'       => 'ph-fill ph-database',
            'DATOS'         => 'ph-fill ph-database',
            'EDUMATICA'     => 'ph-fill ph-chalkboard-teacher',
            'EDUCACION'     => 'ph-fill ph-chalkboard-teacher',
            'WEB'           => 'ph-fill ph-browser',
            'APLICACION'    => 'ph-fill ph-browser',
            'RED'           => 'ph-fill ph-wifi-high',
            'TELECOMUNICAC' => 'ph-fill ph-wifi-high',
            'SEGURIDAD'     => 'ph-fill ph-shield-check',
            'IA'            => 'ph-fill ph-robot',
            'INTELIGENCIA'  => 'ph-fill ph-robot',
        ];

        // Asignar icono a cada línea basándose en su nombre
        foreach ($lineas as &$linea) {
            $nombre_upper = strtoupper($linea['nombre']);
            $icono = 'ph-fill ph-flask'; // Por defecto
            foreach ($mapa_iconos as $clave => $icono_candidato) {
                if (strpos($nombre_upper, $clave) !== false) {
                    $icono = $icono_candidato;
                    break;
                }
            }
            $linea['icono'] = $icono;
        }
        unset($linea);

        return [
            'lineas'            => $lineas,
            'total_dimensiones' => $total_dimensiones,
            'total_proyectos'   => $total_proyectos,
            'total_invest'      => $total_invest,
        ];
    }
}
