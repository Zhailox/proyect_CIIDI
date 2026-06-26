<?php
// modules/Investigaciones/controllers/InvestigacionesController.php

require_once __DIR__ . '/../models/InvestigacionesModel.php';

class InvestigacionesController {

    private InvestigacionesModel $model;

    public function __construct() {
        $this->model = new InvestigacionesModel();
    }

    // -------------------------------------------------------------------------
    // SHOWCASE: Cartelera principal de proyectos (ruta: investigaciones)
    // -------------------------------------------------------------------------
    public function showcase(): array {
        $proyectos = $this->model->getProyectosDestacados();
        $lineas    = $this->model->getLineas();

        return [
            'proyectos' => $proyectos,
            'lineas'    => $lineas,
        ];
    }

    // -------------------------------------------------------------------------
    // POSTULACIONES: Tablero Kanban de vacantes (ruta: postulaciones-investigacion)
    // -------------------------------------------------------------------------
    public function postulaciones(): array {
        $vacantes_por_nivel = $this->model->getVacantesPorNivel();

        // Verificamos si hay un mensaje de éxito de postulación previa (post-redirect-get)
        $mensaje_exito = null;
        if (isset($_GET['postulado']) && $_GET['postulado'] === '1') {
            $mensaje_exito = 'Tu postulación fue enviada correctamente. Nos pondremos en contacto contigo pronto.';
        }

        return [
            'vacantes_por_nivel' => $vacantes_por_nivel,
            'mensaje_exito'      => $mensaje_exito,
        ];
    }

    // -------------------------------------------------------------------------
    // DIRECTORIO: Fichas de investigadores (ruta: directorio-investigadores)
    // -------------------------------------------------------------------------
    public function directorio(): array {
        $investigadores = $this->model->getInvestigadores();

        return [
            'investigadores' => $investigadores,
        ];
    }

    // -------------------------------------------------------------------------
    // CARTELERA: Anuncios y convocatorias (ruta: cartelera-investigacion)
    // -------------------------------------------------------------------------
    public function cartelera(): array {
        $anuncios = $this->model->getAnuncios(20);

        return [
            'anuncios' => $anuncios,
        ];
    }

    // -------------------------------------------------------------------------
    // APLICAR: Procesa el formulario POST de postulación (ruta: aplicar-investigacion)
    // -------------------------------------------------------------------------
    public function aplicar(): false {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Si alguien accede por GET, redirigir al panel
            header('Location: ?ruta=postulaciones-investigacion');
            return false;
        }

        // Validación básica de campos obligatorios
        $campos_requeridos = ['vacante_id', 'nombre_solicitante', 'email', 'motivacion'];
        foreach ($campos_requeridos as $campo) {
            if (empty(trim($_POST[$campo] ?? ''))) {
                header('Location: ?ruta=postulaciones-investigacion&error=campos_vacios');
                return false;
            }
        }

        // Sanitización mínima
        $datos = [
            'vacante_id'         => (int) ($_POST['vacante_id'] ?? 0),
            'nombre_solicitante' => htmlspecialchars(strip_tags($_POST['nombre_solicitante'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'cedula'             => htmlspecialchars(strip_tags($_POST['cedula'] ?? ''),             ENT_QUOTES, 'UTF-8'),
            'email'              => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'motivacion'         => htmlspecialchars(strip_tags($_POST['motivacion'] ?? ''),         ENT_QUOTES, 'UTF-8'),
            'portfolio_url'      => filter_var($_POST['portfolio_url'] ?? '', FILTER_SANITIZE_URL),
        ];

        // Validar vacante_id válido
        if ($datos['vacante_id'] <= 0) {
            header('Location: ?ruta=postulaciones-investigacion&error=vacante_invalida');
            return false;
        }

        // Guardar en la BD
        $id_postulacion = $this->model->registrarPostulacion($datos);

        if ($id_postulacion) {
            // Patrón PRG: Redirigir con flag de éxito para evitar reenvío de formulario
            header('Location: ?ruta=postulaciones-investigacion&postulado=1');
        } else {
            header('Location: ?ruta=postulaciones-investigacion&error=fallo_bd');
        }

        return false; // Señal al Kernel para detener el renderizado (exit)
    }
}
