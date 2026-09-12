<?php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class AdminDashboardModel {
    
    public function obtenerEstadisticas(): array {
        $qb = new QueryBuilder();
        $db = Connection::getInstance(); // Para consultas nativas
        
        // 1. Usuarios activos (no suspendidos)
        $activos = $qb->tabla('usuarios')->where('activo', '=', 'true')->count();
        
        // 2. Usuarios suspendidos/bloqueados
        $bloqueados = $qb->tabla('usuarios')->where('activo', '=', 'false')->count();
        
        // 3. Cantidad de docentes/profesores (filtrado por nivel de privilegio 1)
        $docentes = $qb->tabla('usuarios u')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('p.nivel_privilegio', '=', 1)
            ->count();
            
        // 4. Usuarios Online (Actividad en los últimos 15 minutos)
        $sqlOnline = "SELECT COUNT(*) FROM registro_actividad WHERE ultima_actividad >= NOW() - INTERVAL '15 minutes'";
        $online = (int) ($db->query($sqlOnline)->fetchColumn() ?: 0);

        // 5. Accesos Exitosos Hoy (desde las 00:00:00)
        $sqlAccesosHoy = "SELECT COUNT(*) FROM registro_actividad WHERE DATE(ultima_actividad) = CURRENT_DATE";
        $accesosHoy = (int) ($db->query($sqlAccesosHoy)->fetchColumn() ?: 0);

        // 6. Conteo de Módulos (Detectando carpetas en MODULES_PATH)
        $modulosActivos = 0;
        $modulosTotal = 0;
        if (defined('MODULES_PATH') && is_dir(MODULES_PATH)) {
            $configPath = CORE_PATH . '../storage/config_system.json';
            $config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
            $carpetas = array_diff(scandir(MODULES_PATH), array('.', '..'));
            foreach ($carpetas as $c) {
                if (file_exists(MODULES_PATH . $c . '/index.php')) {
                    $modulosTotal++;
                    $st = $config['modulos'][$c]['estado'] ?? 'online';
                    if ($st === 'online') $modulosActivos++;
                }
            }
        }

        return [
            'usuarios_activos' => $activos,
            'usuarios_online' => $online,
            'usuarios_bloqueados' => $bloqueados,
            'docentes' => $docentes,
            'accesos_hoy' => $accesosHoy,
            'modulos_activos' => $modulosActivos,
            'modulos_total' => $modulosTotal,
            'empresas_pendientes' => 0 
        ];
    }

    public function obtenerTelemetriaServidor(): array {
        $db = Connection::getInstance();
        $creds = Connection::getCredentials();

        // 1. Peso de la Base de Datos PostgreSQL
        $dbSizeFormatted = "0 MB";
        try {
            $sqlSize = "SELECT pg_size_pretty(pg_database_size(current_database()))";
            $dbSizeFormatted = $db->query($sqlSize)->fetchColumn() ?: "0 MB";
        } catch (Exception $e) {
            $dbSizeFormatted = "N/D";
        }

        // 2. Conexiones Activas a PostgreSQL
        $activeConnections = 0;
        $maxConnections = 100;
        try {
            $sqlConn = "SELECT count(*) FROM pg_stat_activity WHERE datname = current_database()";
            $activeConnections = (int) $db->query($sqlConn)->fetchColumn();
            $sqlMaxConn = "SHOW max_connections";
            $maxConnections = (int) $db->query($sqlMaxConn)->fetchColumn();
        } catch (Exception $e) {
            $activeConnections = 1;
        }

        // 3. Espacio, archivos e inodos en directorio storage/ (Con caché temporal de 5 minutos)
        $storageDir = CORE_PATH . '../storage';
        $cacheFile = $storageDir . '/.telemetry_cache.json';
        $storageMb = 0;
        $totalFilesCount = 0;

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 300)) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                $storageMb = $cached['storage_mb'] ?? 0;
                $totalFilesCount = $cached['files_count'] ?? 0;
            }
        } else {
            $storageBytes = 0;
            if (is_dir($storageDir)) {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir, RecursiveDirectoryIterator::SKIP_DOTS));
                foreach ($iterator as $file) {
                    $storageBytes += $file->getSize();
                    $totalFilesCount++;
                }
            }
            $storageMb = round($storageBytes / (1024 * 1024), 2);
            @file_put_contents($cacheFile, json_encode(['storage_mb' => $storageMb, 'files_count' => $totalFilesCount]));
        }

        // Espacio libre y total en disco
        $diskFree = @disk_free_space($storageDir);
        $diskFreeFormatted = $diskFree !== false ? round($diskFree / (1024 * 1024 * 1024), 2) . " GB libre" : "N/D";

        // 4. Uso de memoria PHP
        $memUsage = round(memory_get_usage(true) / (1024 * 1024), 2);
        $memPeak = round(memory_get_peak_usage(true) / (1024 * 1024), 2);

        // 5. Versión y estado de PostgreSQL
        $pgVersion = "Desconocida";
        $pgStatus = "Desconectado";
        try {
            $versionStmt = $db->query("SELECT version()");
            $rawVer = $versionStmt->fetchColumn();
            if ($rawVer) {
                $pgStatus = "Online";
                if (preg_match('/PostgreSQL\s+([\d\.]+)/i', $rawVer, $m)) {
                    $pgVersion = "v" . $m[1];
                } else {
                    $pgVersion = "Activo";
                }
            }
        } catch (Exception $e) {
            $pgStatus = "Error";
        }

        return [
            'db_size' => $dbSizeFormatted,
            'storage_mb' => $storageMb,
            'files_count' => $totalFilesCount,
            'disk_free' => $diskFreeFormatted,
            'memory_usage_mb' => $memUsage,
            'memory_peak_mb' => $memPeak,
            'php_version' => PHP_VERSION,
            'pg_version' => $pgVersion,
            'pg_status' => $pgStatus,
            'active_connections' => $activeConnections,
            'max_connections' => $maxConnections,
            'db_name' => $creds['db'] ?? 'PostgreSQL'
        ];
    }

    public function obtenerUltimasAccionesAudit(int $limit = 5): array {
        $archivo = CORE_PATH . '../storage/system_audit.json';
        if (!file_exists($archivo)) return [];
        $logs = json_decode(file_get_contents($archivo), true) ?: [];
        $logs = array_reverse($logs);
        return array_slice($logs, 0, $limit);
    }

    public function obtenerTablasSistema(): array {
        $db = Connection::getInstance();
        $sql = "SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function optimizarBaseDatos(): array {
        $db = Connection::getInstance();
        try {
            $db->exec("VACUUM ANALYZE");
            return ['exito' => true, 'mensaje' => 'Optimización de tablas (VACUUM ANALYZE) ejecutada con éxito en PostgreSQL.'];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error al optimizar BD: ' . $e->getMessage()];
        }
    }

    public function obtenerMetricasTablas(): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT 
                        relname AS tabla,
                        pg_size_pretty(pg_total_relation_size(relid)) AS tamano,
                        pg_total_relation_size(relid) AS bytes,
                        n_live_tup AS total_filas
                    FROM pg_catalog.pg_stat_user_tables
                    ORDER BY pg_total_relation_size(relid) DESC
                    LIMIT 8";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function obtenerConsultasActivas(): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT pid, usename, query, state, NOW() - query_start AS duracion
                    FROM pg_stat_activity 
                    WHERE state != 'idle' AND pid != pg_backend_pid()
                    ORDER BY duracion DESC LIMIT 5";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }
}