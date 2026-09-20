<?php
// core/Services/MaintenanceService.php

class MaintenanceService {
    private string $mantenimientoFilePath;
    private array $rutasPermitidas = ['login', 'procesar-login', 'cerrar-sesion', 'captcha-imagen'];

    public function __construct(?string $customPath = null) {
        if ($customPath !== null) {
            $this->mantenimientoFilePath = $customPath;
        } elseif (defined('STORAGE_PATH')) {
            $this->mantenimientoFilePath = STORAGE_PATH . 'maintenance.json';
        } else {
            $this->mantenimientoFilePath = __DIR__ . '/../../storage/maintenance.json';
        }
    }

    /**
     * Intercepta la petición y valida si el sitio se encuentra en ventana de mantenimiento.
     * Retorna true si detuvo la ejecución o rindió la vista 503.
     */
    public function intercept(string $ruta): bool {
        if (!file_exists($this->mantenimientoFilePath)) {
            return false;
        }

        $dataMantenimiento = json_decode(file_get_contents($this->mantenimientoFilePath), true) ?: [];
        $ahora = time();
        $cambioArchivo = false;

        $agenda = $dataMantenimiento['agenda'] ?? [];
        $mantMasCercano = null;
        $hayActivoEnAgenda = false;

        foreach ($agenda as &$item) {
            $inicioTs = strtotime($item['fecha_inicio'] ?? '');
            $finTs = strtotime($item['fecha_fin'] ?? '');

            if ($inicioTs && $finTs) {
                if ($inicioTs <= $ahora && $finTs > $ahora) {
                    $item['activo'] = true;
                    $item['programado'] = false;
                    $hayActivoEnAgenda = true;
                    $mantMasCercano = $item;
                } elseif ($inicioTs > $ahora) {
                    $item['activo'] = false;
                    $item['programado'] = true;
                    if ($mantMasCercano === null || $inicioTs < strtotime($mantMasCercano['fecha_inicio'])) {
                        $mantMasCercano = $item;
                    }
                } else {
                    $item['activo'] = false;
                    $item['programado'] = false;
                }
            }
        }
        unset($item);

        if ($hayActivoEnAgenda && empty($dataMantenimiento['activo'])) {
            $dataMantenimiento['activo'] = true;
            $dataMantenimiento['fecha_inicio'] = $mantMasCercano['fecha_inicio'];
            $dataMantenimiento['fecha_fin'] = $mantMasCercano['fecha_fin'];
            $dataMantenimiento['mensaje'] = $mantMasCercano['mensaje'];
            $cambioArchivo = true;
        } elseif (!$hayActivoEnAgenda && !empty($dataMantenimiento['activo']) && !empty($dataMantenimiento['fecha_fin']) && strtotime($dataMantenimiento['fecha_fin']) <= $ahora) {
            $dataMantenimiento['activo'] = false;
            $cambioArchivo = true;
        }

        if (!empty($mantMasCercano) && empty($dataMantenimiento['activo'])) {
            if (($dataMantenimiento['fecha_inicio'] ?? '') !== $mantMasCercano['fecha_inicio']) {
                $dataMantenimiento['fecha_inicio'] = $mantMasCercano['fecha_inicio'];
                $dataMantenimiento['fecha_fin'] = $mantMasCercano['fecha_fin'];
                $dataMantenimiento['mensaje'] = $mantMasCercano['mensaje'];
                $cambioArchivo = true;
            }
        }

        if ($cambioArchivo) {
            $dataMantenimiento['agenda'] = $agenda;
            file_put_contents(
                $this->mantenimientoFilePath,
                json_encode($dataMantenimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        if (isset($dataMantenimiento['activo']) && $dataMantenimiento['activo'] === true) {
            $esAdmin = isset($_SESSION['nivel_privilegio']) && (int)$_SESSION['nivel_privilegio'] <= 2;
            
            if (!$esAdmin && !in_array($ruta, $this->rutasPermitidas, true)) {
                $mensajeCustom = !empty($dataMantenimiento['mensaje']) 
                    ? $dataMantenimiento['mensaje'] 
                    : "Estamos realizando labores de optimización. Vuelve en un momento.";
                $fechaFinMantenimiento = $dataMantenimiento['fecha_fin'] ?? null;
                
                http_response_code(503);
                require_once CORE_VIEWS . 'mantenimiento.php';
                exit;
            }
        }

        return false;
    }
}
