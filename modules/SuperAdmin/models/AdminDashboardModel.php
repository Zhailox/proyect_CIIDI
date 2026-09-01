<?php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class AdminDashboardModel {
    
    public function obtenerEstadisticas(): array {
        $qb = new QueryBuilder();
        $db = Connection::getInstance(); // Para la consulta especial de tiempo
        
        // 1. Usuarios activos (no suspendidos)
        $activos = $qb->tabla('usuarios')->where('activo', '=', 'true')->count();
        
        // 2. Usuarios suspendidos/bloqueados
        $bloqueados = $qb->tabla('usuarios')->where('activo', '=', 'false')->count();
        
        // 3. Cantidad de profesores
        $docentes = $qb->tabla('usuarios u')
            ->join('roles r', 'u.id_rol = r.id')
            ->where('r.nombre', '=', 'Profesor')
            ->count();
            
        // 4. Usuarios Online (Actividad en los últimos 15 minutos)
        // Usamos una consulta directa porque involucra funciones de tiempo de PostgreSQL
        $sqlOnline = "SELECT COUNT(*) FROM registro_actividad WHERE ultima_actividad >= NOW() - INTERVAL '15 minutes'";
        $online = (int) $db->query($sqlOnline)->fetchColumn();

        return [
            'usuarios_activos' => $activos,
            'usuarios_online' => $online,
            'usuarios_bloqueados' => $bloqueados,
            'docentes' => $docentes,
            // Lo dejamos en 0 fijo hasta que el módulo de empresas esté conectado
            'empresas_pendientes' => 0 
        ];
    }
    public function obtenerTablasSistema(): array {
        $db = Connection::getInstance();
        // Consulta nativa del catálogo de PostgreSQL para listar las tablas del esquema público
        $sql = "SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename ASC";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve un arreglo simple: ['auditoria', 'usuarios', ...]
    }
}