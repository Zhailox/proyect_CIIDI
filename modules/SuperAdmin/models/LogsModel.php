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
        $archivo = CORE_PATH . '../storage/system_audit.json';
        if (!file_exists($archivo)) {
            return ['data' => [], 'total' => 0, 'pagina' => 1, 'paginas' => 1, 'por_pagina' => $porPagina];
        }

        $logs = json_decode(file_get_contents($archivo), true) ?: [];
        $logs = array_reverse($logs);

        $filtered = array_values(array_filter($logs, function($l) use ($nivel, $modulo, $fechaInicio, $fechaFin) {
            if (!empty($nivel) && strtoupper($l['nivel']) !== strtoupper($nivel)) return false;
            if (!empty($modulo) && strtolower($l['modulo']) !== strtolower($modulo)) return false;
            
            if (!empty($fechaInicio)) {
                $timeLog = strtotime($l['fecha_hora']);
                $timeInicio = strtotime($fechaInicio . ' 00:00:00');
                if ($timeLog < $timeInicio) return false;
            }

            if (!empty($fechaFin)) {
                $timeLog = strtotime($l['fecha_hora']);
                $timeFin = strtotime($fechaFin . ' 23:59:59');
                if ($timeLog > $timeFin) return false;
            }

            return true;
        }));

        $total = count($filtered);
        $paginas = max(1, (int) ceil($total / $porPagina));
        $paginaActual = max(1, min($pagina, $paginas));
        $offset = ($paginaActual - 1) * $porPagina;

        $items = array_slice($filtered, $offset, $porPagina);

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