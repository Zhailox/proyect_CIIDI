<?php
// modules/VinculacionEmpresarial/controllers/TransparenciaController.php

require_once __DIR__ . '/../../../core/Database/Connection.php';

class TransparenciaController {

    // API para buscar el estatus mediante Fetch/AJAX
    public function rastrear(): void {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método no permitido.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $tipo_busqueda = $input['tipo'] ?? ''; // 'codigo' o 'rif'
        
        $pdo = Connection::getInstance();

        try {
            $resultados = match ($tipo_busqueda) {
                'codigo' => $this->buscarPorCodigo($pdo, trim($input['codigo'] ?? '')),
                'rif'    => $this->buscarPorRifOEmail($pdo, trim($input['rif'] ?? ''), trim($input['correo'] ?? '')),
                default  => throw new Exception("Tipo de búsqueda inválido.")
            };

            if (empty($resultados)) {
                echo json_encode(['error' => 'No se encontraron registros. Verifique los datos.']);
                exit;
            }

            echo json_encode(['exito' => true, 'datos' => $resultados]);

        } catch (\Throwable $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    private function buscarPorCodigo(PDO $pdo, string $codigo): array {
        if (empty($codigo)) {
            throw new Exception("Debe ingresar un código.");
        }

        $sql = "SELECT p.*, i.estado as estado_oferta, i.id as id_oferta 
                FROM propuestas_empresa p
                LEFT JOIN investigaciones_ofertadas i ON p.id = i.id_propuesta_empresa
                WHERE p.codigo_seguimiento = :codigo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':codigo' => $codigo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buscarPorRifOEmail(PDO $pdo, string $valor_busqueda, string $correo): array {
        if (empty($valor_busqueda) || empty($correo)) {
            throw new Exception("Debe ingresar un Identificador y un Correo.");
        }

        $sql = "SELECT p.*, i.estado as estado_oferta, i.id as id_oferta 
                FROM propuestas_empresa p
                LEFT JOIN investigaciones_ofertadas i ON p.id = i.id_propuesta_empresa
                WHERE (p.rif_empresa = :val OR p.nombre_empresa ILIKE :val2) 
                AND p.correo_contacto = :correo
                ORDER BY p.fecha_creacion DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':val'    => $valor_busqueda, 
            ':val2'   => '%' . $valor_busqueda . '%', 
            ':correo' => $correo
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
