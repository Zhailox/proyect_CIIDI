<?php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class AdminDashboardModel {
    
    public function obtenerEstadisticas(): array {
        $qb = new QueryBuilder();
        $db = Connection::getInstance(); // Para consultas nativas
        
        // 1. Usuarios activos (no suspendidos)
        $activos = $qb->tabla('usuarios')->where('activo', '=', 1)->count();
        
        // 2. Usuarios suspendidos/bloqueados
        $bloqueados = $qb->tabla('usuarios')->where('activo', '=', 0)->count();
        
        // 3. Cantidad de docentes/profesores (filtrado por nivel de privilegio 1)
        $docentes = $qb->tabla('usuarios u')
            ->join('roles r', 'u.id_rol = r.id')
            ->join('privilegios p', 'r.privilegio_id = p.privilegio_id')
            ->where('p.nivel_privilegio', '=', 1)
            ->count();
            
        // 4. Usuarios Online (Actividad en los últimos 15 minutos)
        $sqlOnline = "SELECT COUNT(*) FROM registro_actividad WHERE ultima_actividad >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
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

        // 1. Peso de la Base de Datos MariaDB
        $dbSizeFormatted = "0 MB";
        try {
            $sqlSize = "SELECT CONCAT(ROUND(COALESCE(SUM(data_length + index_length), 0) / 1048576, 2), ' MB') FROM information_schema.tables WHERE table_schema = DATABASE()";
            $dbSizeFormatted = $db->query($sqlSize)->fetchColumn() ?: "0 MB";
        } catch (Exception $e) {
            $dbSizeFormatted = "N/D";
        }

        // 2. Conexiones Activas a MariaDB
        $activeConnections = 1;
        $maxConnections = 100;
        try {
            $rowConn = $db->query("SHOW STATUS LIKE 'Threads_connected'")->fetch(PDO::FETCH_ASSOC);
            if ($rowConn && isset($rowConn['Value'])) {
                $activeConnections = (int) $rowConn['Value'];
            }
            $rowMax = $db->query("SHOW VARIABLES LIKE 'max_connections'")->fetch(PDO::FETCH_ASSOC);
            if ($rowMax && isset($rowMax['Value'])) {
                $maxConnections = (int) $rowMax['Value'];
            }
        } catch (Exception $e) {
            $activeConnections = 1;
        }

        // 3. Espacio, archivos e inodos en directorio storage/ (Con caché temporal de 5 minutos)
        $storageDir = CORE_PATH . '../storage';
        $storageMb = 0;
        $totalFilesCount = 0;
        $usarCache = false;

        $stmtCache = $db->query("SELECT datos FROM telemetria_cache WHERE id = 1");
        $cacheData = $stmtCache->fetch(PDO::FETCH_ASSOC);

        if ($cacheData && !empty($cacheData['datos'])) {
            $datos = is_string($cacheData['datos']) ? json_decode($cacheData['datos'], true) : $cacheData['datos'];
            // Validar que la caché tenga menos de 5 minutos (300 segundos)
            if (isset($datos['timestamp']) && (time() - $datos['timestamp'] < 300)) {
                $storageMb = $datos['storage_mb'] ?? 0;
                $totalFilesCount = $datos['files_count'] ?? 0;
                $usarCache = true;
            }
        }

        if (!$usarCache) {
            $storageBytes = 0;
            if (is_dir($storageDir)) {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir, RecursiveDirectoryIterator::SKIP_DOTS));
                foreach ($iterator as $file) {
                    $storageBytes += $file->getSize();
                    $totalFilesCount++;
                }
            }
            $storageMb = round($storageBytes / (1024 * 1024), 2);
            
            $nuevosDatos = json_encode([
                'storage_mb' => $storageMb, 
                'files_count' => $totalFilesCount, 
                'timestamp' => time()
            ]);
            
            // Upsert nativo para PostgreSQL (Actualiza si existe, inserta si no)
            $db->prepare("INSERT INTO telemetria_cache (id, datos) VALUES (1, ?) ON DUPLICATE KEY UPDATE datos = VALUES(datos)")
               ->execute([$nuevosDatos]);
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
                if (preg_match('/([\d]+\.[\d]+\.[\d]+)/', $rawVer, $m)) {
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
        $db = Connection::getInstance();
        $lim = max(1, (int)$limit);
        $stmt = $db->query("SELECT * FROM system_audit_log ORDER BY fecha_hora DESC LIMIT {$lim}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerTablasSistema(): array {
        $db = Connection::getInstance();
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE' ORDER BY table_name ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function optimizarBaseDatos(): array {
        $db = Connection::getInstance();
        try {
            $tablas = $this->obtenerTablasSistema();
            foreach ($tablas as $t) {
                if (preg_match('/^[a-zA-Z0-9_]+$/', $t)) {
                    $db->query("ANALYZE TABLE `{$t}`")->fetchAll();
                }
            }
            return ['exito' => true, 'mensaje' => 'Optimización de índices y estadísticas (ANALYZE TABLE) ejecutada con éxito en MariaDB.'];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error al optimizar BD: ' . $e->getMessage()];
        }
    }

    public function obtenerMetricasTablas(): array {
        $db = Connection::getInstance();
        try {
            $sql = "SELECT 
                        table_name AS tabla,
                        CONCAT(ROUND(COALESCE(data_length + index_length, 0) / 1024, 2), ' KB') AS tamano,
                        COALESCE(data_length + index_length, 0) AS bytes,
                        COALESCE(table_rows, 0) AS total_filas
                    FROM information_schema.tables
                    WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE'
                    ORDER BY (data_length + index_length) DESC
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
            $sql = "SELECT ID AS pid, USER AS usename, INFO AS query, STATE AS state, CONCAT(TIME, ' s') AS duracion
                    FROM information_schema.processlist 
                    WHERE COMMAND != 'Sleep' AND ID != CONNECTION_ID()
                    ORDER BY TIME DESC LIMIT 5";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }
}