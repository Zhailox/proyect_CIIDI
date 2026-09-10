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
            
            // Generar código de seguimiento único
            $codigo_seguimiento = 'CIIDI-' . date('Y') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
            $datos['codigo_seguimiento'] = $codigo_seguimiento;

            $id = $this->modelo->guardar($datos);
            if ($id) {
                $_SESSION['mensaje_exito'] = "Su propuesta fue enviada correctamente.";
                $_SESSION['codigo_seguimiento'] = $codigo_seguimiento; // Para disparar el modal
            } else {
                $_SESSION['mensaje_error'] = "Ocurrió un error al enviar su propuesta.";
            }
            echo "<script>window.location.href='?ruta=empresas-inicio';</script>";
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
                $nivel = $_POST['nivel_trayecto'] ?? 'Trayecto I';
                $id_linea = $_POST['id_linea'] ?? null;
                
                // Actualizar estatus en la bolsa de propuestas
                $this->modelo->actualizarEstado($id, 'aceptada', $nivel);
                
                // Si seleccionaron una línea, inyectarlo directo a las ofertas!
                if ($id_linea) {
                    $pdo = Connection::getInstance();
                    // Buscamos los datos originales de la propuesta para crear la oferta
                    $stmt = $pdo->prepare("SELECT area_afectada, descripcion_problema, nombre_empresa FROM propuestas_empresa WHERE id = ?");
                    $stmt->execute([$id]);
                    $prop = $stmt->fetch();
                    
                    if ($prop) {
                        $titulo_oferta = "Requerimiento: " . $prop['area_afectada'];
                        $desc_oferta = $prop['descripcion_problema'] . "\n\n(Nivel Requerido: $nivel)";
                        
                        $sql_insert = "INSERT INTO investigaciones_ofertadas 
                                       (id_profesor, id_linea, titulo, planteamiento_problema, objetivo_general, estado, id_propuesta_empresa) 
                                       VALUES (?, ?, ?, ?, ?, 'Abierta', ?)";
                        $stmt_in = $pdo->prepare($sql_insert);
                        // Usamos el ID del profesor/comité actual
                        $stmt_in->execute([
                            $_SESSION['usuario_id'], 
                            $id_linea, 
                            $titulo_oferta, 
                            $desc_oferta, 
                            "Dar respuesta y solución tecnológica a los requerimientos de " . $prop['nombre_empresa'],
                            $id
                        ]);
                    }
                }

            } elseif ($accion === 'rechazar') {
                $motivo = trim($_POST['motivo_rechazo'] ?? '');
                $this->modelo->actualizarEstado($id, 'rechazada', null, $motivo);
            }
            
            echo "<script>window.location.href='?ruta=banco-propuestas';</script>";
            exit;
        }
    }
    public function postularOportunidad() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once CORE_PATH . 'Security/Auth.php';
            if (!Auth::check()) {
                $_SESSION['flash_error'] = "Debes iniciar sesión para postularte.";
                header("Location: ?ruta=cartelera-oportunidades");
                exit;
            }
            $user = Auth::usuario();
            $id_investigacion = (int)$_POST['id_investigacion'];
            $motivacion = trim($_POST['motivacion']);

            if ($this->modelo->crearPostulacion($id_investigacion, $user['id'], $motivacion)) {
                $_SESSION['flash_success'] = "¡Tu solicitud ha sido enviada al Comité!";
            } else {
                $_SESSION['flash_error'] = "Ocurrió un error al enviar tu solicitud.";
            }
            header("Location: ?ruta=cartelera-oportunidades");
            exit;
        }
    }

    public function procesarAsignacion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_postulacion = (int)$_POST['id_postulacion'];
            $id_investigacion = (int)$_POST['id_investigacion'];
            $estado = $_POST['estado']; // 'Aceptado' o 'Rechazado'

            if ($this->modelo->procesarAsignacion($id_postulacion, $estado, $id_investigacion)) {
                $_SESSION['flash_success'] = "Postulación procesada correctamente.";
            } else {
                $_SESSION['flash_error'] = "Ocurrió un error al procesar la postulación.";
            }
            header("Location: ?ruta=gestion-equipos");
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
} elseif ($ruta === 'postular-oportunidad') {
    $controller->postularOportunidad();
} elseif ($ruta === 'procesar-asignacion') {
    $controller->procesarAsignacion();
}
