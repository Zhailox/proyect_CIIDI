<?php
// modules/VinculacionEmpresarial/controllers/VinculacionController.php

require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';

class VinculacionController {

    private $modelo;

    public function __construct() {
        $this->modelo = new PropuestaEmpresaModel();
    }

    public function guardarPropuesta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre_empresa' => $_POST['nombre_empresa'] ?? '',
                'rif_empresa' => $_POST['rif_empresa'] ?? '',
                'persona_contacto' => $_POST['persona_contacto'] ?? '',
                'telefono_contacto' => $_POST['telefono_contacto'] ?? '',
                'correo_contacto' => $_POST['correo_contacto'] ?? '',
                'area_afectada' => $_POST['area_afectada'] ?? '',
                'descripcion_problema' => $_POST['descripcion_problema'] ?? ''
            ];
            
            $id = $this->modelo->guardar($datos);
            if ($id) {
                $_SESSION['mensaje_exito'] = "Su propuesta fue enviada correctamente. Evaluaremos su requerimiento pronto.";
            } else {
                $_SESSION['mensaje_error'] = "Ocurrió un error al enviar su propuesta.";
            }
            echo "<script>window.location.href='?ruta=empresas-inicio#registro-problematica';</script>";
            exit;
        }
    }

    public function procesarPropuesta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_propuesta'], $_POST['accion'])) {
            $roles_permitidos = ['Profesor', 'Super Administrador', 'Comite'];
            if (!isset($_SESSION['rol_nombre']) || !in_array($_SESSION['rol_nombre'], $roles_permitidos)) {
                die("Acceso denegado");
            }
            
            $id = $_POST['id_propuesta'];
            $accion = $_POST['accion'];
            
            if ($accion === 'aceptar') {
                $nivel = $_POST['nivel_trayecto'] ?? 'Trayecto I (T1)';
                $this->modelo->actualizarEstado($id, 'aceptada', $nivel);
            } elseif ($accion === 'rechazar') {
                $this->modelo->actualizarEstado($id, 'rechazada');
            }
            
            echo "<script>window.location.href='?ruta=banco-propuestas';</script>";
            exit;
        }
    }
}

// Bootstrap
$controller = new VinculacionController();
$ruta = $_GET['ruta'] ?? '';
if ($ruta === 'guardar-propuesta') {
    $controller->guardarPropuesta();
} elseif ($ruta === 'procesar-propuesta') {
    $controller->procesarPropuesta();
}
