<?php
// modules/SuperAdmin/controllers/SchedulerController.php

require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../services/SchedulerService.php';

class SchedulerController {

    public function index() {
        Auth::requierePrivilegioMinimo(3);

        $tareas = SchedulerService::obtenerTareas();
        $diagnostico = SchedulerService::diagnosticarEntornoCron();

        return [
            'tareas' => $tareas,
            'diagnostico' => $diagnostico
        ];
    }

    public function alternarEstado() {
        Auth::requierePrivilegioMinimo(3);

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
        Auth::requierePrivilegioMinimo(3);

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
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['tarea_id'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $cron = trim($_POST['expresion_cron'] ?? '0 0 * * *');
            $script = trim($_POST['script'] ?? '');

            if (empty($id)) {
                $id = 'tarea_' . time();
            }

            if (!empty($nombre) && !empty($script)) {
                SchedulerService::guardarTareaCustom($id, $nombre, $descripcion, $cron, $script);
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_exito'] = "Tarea programada '{$nombre}' guardada correctamente.";
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_error'] = "Debe especificar un nombre y el método script ejecutor.";
            }
        }
        header("Location: gestor-scheduler");
        exit;
    }

    public function eliminarTarea() {
        Auth::requierePrivilegioMinimo(3);

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
