<?php
// modules/SuperAdmin/controllers/SchedulerController.php

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../services/SchedulerService.php';

class SchedulerController {

    public function index() {
        Auth::requierePrivilegioMinimo(0);

        $tareas = SchedulerService::obtenerTareas();
        $diagnostico = SchedulerService::diagnosticarEntornoCron();

        return [
            'tareas' => $tareas,
            'diagnostico' => $diagnostico
        ];
    }

    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idTarea = trim($_POST['tarea_id'] ?? '');
            $nuevoEstado = trim($_POST['nuevo_estado'] ?? 'activo');

            if (!empty($idTarea)) {
                SchedulerService::alternarEstado($idTarea, $nuevoEstado);
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "Estado de la tarea programada actualizado a '{$nuevoEstado}'.";
            }
        }
        header("Location: gestor-scheduler");
        exit;
    }

    public function ejecutarManual() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idTarea = trim($_POST['tarea_id'] ?? '');
            $esAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if (!empty($idTarea)) {
                $res = SchedulerService::ejecutarTareaManual($idTarea);

                if ($esAjax) {
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    exit;
                }

                if (session_status() === PHP_SESSION_NONE) session_start();
                if ($res['exito']) {
                    $_SESSION['mensaje_admin_exito'] = $res['mensaje'];
                } else {
                    $_SESSION['mensaje_admin_error'] = $res['mensaje'];
                }
            }
        }
        header("Location: gestor-scheduler");
        exit;
    }

    public function guardarTarea() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id'               => trim($_POST['tarea_id'] ?? ''),
                'nombre'           => trim($_POST['nombre'] ?? ''),
                'descripcion'      => trim($_POST['descripcion'] ?? ''),
                'expresion_cron'   => trim($_POST['expresion_cron'] ?? '0 0 * * *'),
                'tipo_ejecucion'   => trim($_POST['tipo_ejecucion'] ?? 'metodo_interno'),
                'script'           => trim($_POST['script'] ?? ''),
                'comando_custom'   => trim($_POST['comando_custom'] ?? ''),
                'timeout_segundos' => (int)($_POST['timeout_segundos'] ?? 60),
                'estado'           => trim($_POST['estado'] ?? 'activo')
            ];

            $archivoSubido = $_FILES['archivo_script'] ?? null;

            if (!empty($datos['nombre'])) {
                $res = SchedulerService::guardarTareaCompleta($datos, $archivoSubido);
                if (session_status() === PHP_SESSION_NONE) session_start();
                if ($res['exito']) {
                    $_SESSION['mensaje_admin_exito'] = $res['mensaje'];
                } else {
                    $_SESSION['mensaje_admin_error'] = $res['mensaje'];
                }
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_error'] = "Debe especificar un nombre para la tarea programada.";
            }
        }
        header("Location: gestor-scheduler");
        exit;
    }

    public function verLog() {
        Auth::requierePrivilegioMinimo(0);

        $idTarea = trim($_GET['id'] ?? '');
        header('Content-Type: text/plain; charset=utf-8');
        if (!empty($idTarea)) {
            echo SchedulerService::obtenerLogTarea($idTarea);
        } else {
            echo "ID de tarea no especificado.";
        }
        exit;
    }

    public function eliminarTarea() {
        Auth::requierePrivilegioMinimo(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['tarea_id'] ?? '');
            if (!empty($id)) {
                SchedulerService::eliminarTarea($id);
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "Tarea programada eliminada correctamente.";
            }
        }
        header("Location: gestor-scheduler");
        exit;
    }
}
