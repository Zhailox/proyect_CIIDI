<?php
// modules/VinculacionEmpresarial/controllers/TransparenciaController.php

require_once __DIR__ . '/../../../core/Database/Connection.php';

class TransparenciaController {

    // API para buscar el estatus mediante Fetch/AJAX
    public function rastrear() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método no permitido.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $tipo_busqueda = $input['tipo'] ?? ''; // 'codigo' o 'rif'
        
        $pdo = Connection::getInstance();

        try {
            if ($tipo_busqueda === 'codigo') {
                $codigo = trim($input['codigo'] ?? '');
                
                if (empty($codigo)) throw new Exception("Debe ingresar un código.");

                $sql = "SELECT p.*, i.estado as estado_oferta, i.id as id_oferta 
                        FROM propuestas_empresa p
                        LEFT JOIN investigaciones_ofertadas i ON p.id = i.id_propuesta_empresa
                        WHERE p.codigo_seguimiento = :codigo";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':codigo' => $codigo]);
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } elseif ($tipo_busqueda === 'rif') {
                $valor_busqueda = trim($input['rif'] ?? ''); // Puede ser Nombre o RIF
                $correo = trim($input['correo'] ?? '');
                
                if (empty($valor_busqueda) || empty($correo)) throw new Exception("Debe ingresar un Identificador y un Correo.");

                $sql = "SELECT p.*, i.estado as estado_oferta, i.id as id_oferta 
                        FROM propuestas_empresa p
                        LEFT JOIN investigaciones_ofertadas i ON p.id = i.id_propuesta_empresa
                        WHERE (p.rif_empresa = :val OR p.nombre_empresa ILIKE :val2) 
                        AND p.correo_contacto = :correo
                        ORDER BY p.fecha_creacion DESC";
                $stmt = $pdo->prepare($sql);
                // val2 usa % para buscar por similitud de nombre
                $stmt->execute([
                    ':val' => $valor_busqueda, 
                    ':val2' => '%' . $valor_busqueda . '%', 
                    ':correo' => $correo
                ]);
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("Tipo de búsqueda inválido.");
            }

            if (empty($resultados)) {
                echo json_encode(['success' => false, 'message' => 'No se encontraron propuestas con esos datos.']);
            } else {
                // TODO: Más adelante podemos cruzar esta data con los estudiantes asignados.
                echo json_encode(['success' => true, 'data' => $resultados]);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
        exit; // PREVIENE QUE EL KERNEL IMPRIMA EL HTML
    }
}
