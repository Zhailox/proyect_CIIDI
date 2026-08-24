<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/LogsModel.php';

class LogsController {
    
    private $logsModel;

    public function __construct() {
        $this->logsModel = new LogsModel();
    }

    public function index() {
        // Nivel 3 (SuperAdmin) requerido para ver logs
        Auth::requierePrivilegioMinimo(3);

        $auditoriaDB = $this->logsModel->obtenerAuditoriaDB();
        $accesos = $this->logsModel->obtenerAccesos();

        // Para los errores de sistema, intentaríamos leer un archivo de log de PHP físico
        // Si no existe, pasamos un arreglo vacío
        $archivo_errores = ini_get('error_log');
        $errores_php = [];
        if ($archivo_errores && file_exists($archivo_errores)) {
            // Lógica avanzada para leer las últimas líneas (omitida por brevedad)
            $errores_php = []; 
        }

        return [
            'logs_db' => $auditoriaDB,
            'logs_auth' => $accesos,
            'logs_err' => $errores_php
        ];
    }
}