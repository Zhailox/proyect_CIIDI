<?php
// modules/SuperAdmin/models/LogsModel.php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class LogsModel {
    
    public function obtenerAuditoriaDB() {
        $qb = new QueryBuilder();
        return $qb->tabla('auditoria a')
            ->select('a.*, u.nombre_completo AS responsable')
            ->join('usuarios u', 'a.usuario_responsable = u.id', 'LEFT')
            ->orderBy('a.fecha_hora', 'DESC')
            ->limit(50) // Usando "limit" como tú lo definiste
            ->get();
    }

    public function obtenerAccesos() {
        $qb = new QueryBuilder();
        return $qb->tabla('registro_actividad r')
            ->select('r.*, u.nombre_completo, u.cedula, u.email')
            ->join('usuarios u', 'r.id_usuario = u.id')
            ->orderBy('r.ultima_actividad', 'DESC')
            ->limit(50)
            ->get();
    }

    public function obtenerAuditTrailPaginado(?string $nivel = null, ?string $modulo = null, ?string $fechaInicio = null, ?string $fechaFin = null, int $pagina = 1, int $porPagina = 15): array {
        // Instanciar dos constructores separados para evitar colisión de estados
        $qbCount = new QueryBuilder();
        $qbItems = new QueryBuilder();
        
        $qbCount->tabla('system_audit_log')->select('COUNT(*) as total');
        $qbItems->tabla('system_audit_log')->select('*');
        
        if (!empty($nivel)) {
            $qbCount->where('nivel', '=', strtoupper($nivel));
            $qbItems->where('nivel', '=', strtoupper($nivel));
        }
        if (!empty($modulo)) {
            $qbCount->where('modulo', '=', $modulo);
            $qbItems->where('modulo', '=', $modulo);
        }
        if (!empty($fechaInicio)) {
            $qbCount->where('fecha_hora', '>=', $fechaInicio . ' 00:00:00');
            $qbItems->where('fecha_hora', '>=', $fechaInicio . ' 00:00:00');
        }
        if (!empty($fechaFin)) {
            $qbCount->where('fecha_hora', '<=', $fechaFin . ' 23:59:59');
            $qbItems->where('fecha_hora', '<=', $fechaFin . ' 23:59:59');
        }

        $totalRes = $qbCount->first();
        $total = $totalRes ? (int)$totalRes['total'] : 0;
        
        $paginas = max(1, (int) ceil($total / $porPagina));
        $paginaActual = max(1, min($pagina, $paginas));
        $offset = ($paginaActual - 1) * $porPagina;

        // El ORDER BY ahora se aplica estrictamente solo a la consulta de extracción de datos
        $items = $qbItems->orderBy('fecha_hora', 'DESC')
                         ->limit($porPagina)
                         ->offset($offset)
                         ->get();

        return [
            'data'       => $items,
            'total'      => $total,
            'pagina'     => $paginaActual,
            'paginas'    => $paginas,
            'por_pagina' => $porPagina
        ];
    }

    public function obtenerAuditTrail(?string $nivel = null, ?string $modulo = null, ?string $fechaInicio = null, ?string $fechaFin = null): array {
        $res = $this->obtenerAuditTrailPaginado($nivel, $modulo, $fechaInicio, $fechaFin, 1, 5000);
        return $res['data'];
    }

    public function limpiarLogsAudit(): bool {
        try {
            $db = Connection::getInstance();
            $db->exec("TRUNCATE TABLE system_audit_log");
        } catch (Throwable $e) {
            // Ignorar si la tabla no está disponible
        }

        $archivo = CORE_PATH . '../storage/system_audit.json';
        if (file_exists($archivo)) {
            @file_put_contents($archivo, json_encode([], JSON_PRETTY_PRINT));
        }
        return true;
    }
}