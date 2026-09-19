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
        $qb = new QueryBuilder();
        
        $query = $qb->tabla('system_audit_log')->select('*');
        $countQuery = $qb->tabla('system_audit_log')->select('COUNT(*) as total');
        
        if (!empty($nivel)) {
            $query->where('nivel', '=', strtoupper($nivel));
            $countQuery->where('nivel', '=', strtoupper($nivel));
        }
        if (!empty($modulo)) {
            $query->where('modulo', '=', $modulo);
            $countQuery->where('modulo', '=', $modulo);
        }
        if (!empty($fechaInicio)) {
            $query->where('fecha_hora', '>=', $fechaInicio . ' 00:00:00');
            $countQuery->where('fecha_hora', '>=', $fechaInicio . ' 00:00:00');
        }
        if (!empty($fechaFin)) {
            $query->where('fecha_hora', '<=', $fechaFin . ' 23:59:59');
            $countQuery->where('fecha_hora', '<=', $fechaFin . ' 23:59:59');
        }

        $totalRes = $countQuery->first();
        $total = $totalRes ? (int)$totalRes['total'] : 0;
        
        $paginas = max(1, (int) ceil($total / $porPagina));
        $paginaActual = max(1, min($pagina, $paginas));
        $offset = ($paginaActual - 1) * $porPagina;

        $items = $query->orderBy('fecha_hora', 'DESC')->limit($porPagina)->offset($offset)->get();

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
        $archivo = CORE_PATH . '../storage/system_audit.json';
        if (file_exists($archivo)) {
            return (bool) file_put_contents($archivo, json_encode([], JSON_PRETTY_PRINT));
        }
        return true;
    }
}