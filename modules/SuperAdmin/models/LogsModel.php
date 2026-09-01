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
            ->limit(50) // Usando "limit" como tú lo definiste
            ->get();
    }
}