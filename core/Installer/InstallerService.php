<?php
// core/Installer/InstallerService.php

class InstallerService {

    public static function lockFileExists(): bool {
        $lockPath = defined('STORAGE_PATH') ? STORAGE_PATH . 'installed.lock' : __DIR__ . '/../../storage/installed.lock';
        return file_exists($lockPath);
    }

    public static function checkSystemEnvironment(): array {
        $phpVersionOk = version_compare(PHP_VERSION, '8.1.0', '>=');
        
        $requiredExtensions = ['pdo', 'pdo_pgsql', 'mbstring', 'json', 'gd', 'curl', 'zip'];
        $extStatus = [];
        $allExtOk = true;

        foreach ($requiredExtensions as $ext) {
            $loaded = extension_loaded($ext);
            if (!$loaded && $ext === 'pdo_pgsql') {
                $drivers = class_exists('PDO') ? PDO::getAvailableDrivers() : [];
                if (in_array('pgsql', $drivers, true)) {
                    $loaded = true;
                }
            }
            $extStatus[$ext] = $loaded;
            if (!$loaded) $allExtOk = false;
        }

        // --- PREVISOR DE FALLOS & DIAGNÓSTICO PROFUNDO DE HARDWARE / SERVIDORES ---
        $baseStorage = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        $baseUploads = defined('BASE_PATH') ? BASE_PATH . '/public/uploads/' : __DIR__ . '/../../public/uploads/';

        $dirsToCheck = [
            'storage' => $baseStorage,
            'storage/backups' => $baseStorage . 'backups/',
            'storage/scheduler' => $baseStorage . 'scheduler/',
            'public/uploads' => $baseUploads
        ];

        $dirStatus = [];
        $allDirsOk = true;

        foreach ($dirsToCheck as $label => $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0777, true);
            }
            $writable = is_dir($path) && is_writable($path);
            $dirStatus[$label] = [
                'path' => $path,
                'writable' => $writable
            ];
            if (!$writable) $allDirsOk = false;
        }

        // Diagnóstico de memoria RAM asignada a PHP (Recomendado mínimo 128M)
        $memoryLimit = ini_get('memory_limit');
        $memoryBytes = self::returnBytes($memoryLimit);
        $memoryOk = ($memoryBytes === -1 || $memoryBytes >= 128 * 1024 * 1024);

        // Previsor de puertos y binario psql
        $psqlBinOk = false;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $psqlBinOk = file_exists('C:\\Program Files\\PostgreSQL\\18\\bin\\psql.exe') || file_exists('C:\\Program Files\\PostgreSQL\\16\\bin\\psql.exe') || file_exists('C:\\Program Files\\PostgreSQL\\15\\bin\\psql.exe') || file_exists('C:\\Program Files\\PostgreSQL\\14\\bin\\psql.exe');
        } else {
            $salida = [];
            $codigo = 0;
            @exec('which psql', $salida, $codigo);
            $psqlBinOk = ($codigo === 0);
        }

        return [
            'php_version' => PHP_VERSION,
            'php_version_ok' => $phpVersionOk,
            'extensions' => $extStatus,
            'extensions_ok' => $allExtOk,
            'directories' => $dirStatus,
            'directories_ok' => $allDirsOk,
            'memory_limit' => $memoryLimit,
            'memory_ok' => $memoryOk,
            'psql_bin_ok' => $psqlBinOk,
            'ready' => ($phpVersionOk && $allExtOk && $allDirsOk)
        ];
    }

    private static function returnBytes(string $val): int {
        $val = trim($val);
        if ($val === '-1') return -1;
        $last = strtolower($val[strlen($val)-1]);
        $valNum = (int)$val;
        switch($last) {
            case 'g': $valNum *= 1024;
            case 'm': $valNum *= 1024;
            case 'k': $valNum *= 1024;
        }
        return $valNum;
    }

    public static function testDbConnection(string $host, string $port, string $user, string $pass, string $dbName): array {
        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ];
            $pdo = new PDO($dsnTarget, $user, $pass, $options);
            return [
                'exito' => true,
                'bd_existe' => true,
                'mensaje' => "Conexión a la base de datos '{$dbName}' establecida con éxito."
            ];
        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'does not exist') !== false || strpos($errorMsg, 'no existe') !== false || $e->getCode() == '3D000') {
                try {
                    $dsnSystem = "pgsql:host={$host};port={$port};dbname=postgres";
                    $pdoSys = new PDO($dsnSystem, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]);
                    return [
                        'exito' => true,
                        'bd_existe' => false,
                        'ofrecer_crear' => true,
                        'mensaje' => "Las credenciales son válidas, pero la base de datos '{$dbName}' no existe aún en PostgreSQL."
                    ];
                } catch (PDOException $e2) {
                    return [
                        'exito' => false,
                        'bd_existe' => false,
                        'mensaje' => "Credenciales incorrectas o servidor PostgreSQL inaccesible: " . $e2->getMessage()
                    ];
                }
            }
            return [
                'exito' => false,
                'bd_existe' => false,
                'mensaje' => "Fallo de conexión PDO PostgreSQL: " . $errorMsg
            ];
        }
    }

    public static function createDatabase(string $host, string $port, string $user, string $pass, string $dbName): array {
        try {
            $dsnSystem = "pgsql:host={$host};port={$port};dbname=postgres";
            $pdoSys = new PDO($dsnSystem, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            
            $cleanDb = preg_replace('/[^a-zA-Z0-9_]/', '', $dbName);
            $pdoSys->exec("CREATE DATABASE \"{$cleanDb}\" WITH OWNER = \"{$user}\" ENCODING = 'UTF8'");

            return [
                'exito' => true,
                'mensaje' => "Base de datos '{$cleanDb}' creada exitosamente en PostgreSQL."
            ];
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'mensaje' => "No se pudo crear la base de datos: " . $e->getMessage()
            ];
        }
    }

    public static function runSqlSchema(string $host, string $port, string $user, string $pass, string $dbName): array {
        $sqlPath = defined('BASE_PATH') ? BASE_PATH . '/ciidi.sql' : __DIR__ . '/../../ciidi.sql';
        if (!file_exists($sqlPath)) {
            return ['exito' => false, 'mensaje' => "El archivo de esquema base 'ciidi.sql' no fue encontrado en la raíz del proyecto."];
        }

        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $pdo = new PDO($dsnTarget, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            $sqlContent = file_get_contents($sqlPath);

            // Filtrar metas-comandos de psql (metas que inician con '\' como \restrict o \unrestrict no válidos en PDO exec)
            $sqlLineas = explode("\n", $sqlContent);
            $cleanLines = [];
            foreach ($sqlLineas as $linea) {
                $trimmed = trim($linea);
                if (strpos($trimmed, '\\') === 0) {
                    continue; // Omitir comandos psql metas (\restrict, \connect, etc.)
                }
                $cleanLines[] = $linea;
            }
            $cleanSql = implode("\n", $cleanLines);

            // Ejecutar migración completa dentro de transacción
            $pdo->beginTransaction();
            $pdo->exec($cleanSql);
            $pdo->commit();

            return [
                'exito' => true,
                'mensaje' => "Esquema e infraestructuras de tablas 'ciidi.sql' importados exitosamente."
            ];
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return [
                'exito' => false,
                'mensaje' => "Error al ejecutar la migración SQL: " . $e->getMessage()
            ];
        }
    }

    public static function createSuperAdmin(string $host, string $port, string $user, string $pass, string $dbName, array $adminData): array {
        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $pdo = new PDO($dsnTarget, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $cedula = (int)$adminData['cedula'];
            $nombre = trim($adminData['nombre']);
            $email  = trim($adminData['correo']);
            $claveRaw = $adminData['clave'];

            if ($cedula <= 0 || empty($nombre) || empty($email) || empty($claveRaw)) {
                return ['exito' => false, 'mensaje' => 'Todos los campos del Administrador son obligatorios y la cédula debe ser mayor a 0.'];
            }

            $claveHash = password_hash($claveRaw, PASSWORD_BCRYPT, ['cost' => 12]);

            // 1. Obtener o verificar rol SuperAdmin / Admin (Nivel de privilegio 0 o 3 según esquema)
            $privilegioId = 1;
            $rolId = 1;

            $stmtPriv = $pdo->query("SELECT privilegio_id FROM privilegios WHERE nivel_privilegio = 0 LIMIT 1");
            $resPriv = $stmtPriv->fetch(PDO::FETCH_ASSOC);
            if ($resPriv) {
                $privilegioId = $resPriv['privilegio_id'];
            }

            $stmtRol = $pdo->query("SELECT id FROM roles WHERE privilegio_id = {$privilegioId} LIMIT 1");
            $resRol = $stmtRol->fetch(PDO::FETCH_ASSOC);
            if ($resRol) {
                $rolId = $resRol['id'];
            }

            // 2. Limpiar usuarios previos si existen para evitar duplicados en la instalación fresca
            $pdo->exec("DELETE FROM usuarios WHERE cedula = '{$cedula}' OR email = " . $pdo->quote($email));

            // 3. Insertar usuario Dios del Sistema
            $stmtIns = $pdo->prepare("
                INSERT INTO usuarios (cedula, nombre_completo, email, contrasena, id_rol, activo)
                VALUES (:cedula, :nombre, :email, :clave, :id_rol, true)
            ");
            $stmtIns->execute([
                ':cedula' => (string)$cedula,
                ':nombre' => $nombre,
                ':email'  => $email,
                ':clave'  => $claveHash,
                ':id_rol' => $rolId
            ]);

            return [
                'exito' => true,
                'mensaje' => "SuperAdmin '{$nombre}' creado correctamente con privilegio de acceso absoluto."
            ];
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'mensaje' => "Error al registrar el SuperAdmin: " . $e->getMessage()
            ];
        }
    }

    public static function createLockFile(): bool {
        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $lockPath = $storageDir . 'installed.lock';
        $metaData = [
            'installed_at' => date('Y-m-d H:i:s'),
            'system_version' => '2.0.0-PROD',
            'installed_by_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'php_version' => PHP_VERSION,
            'os' => PHP_OS
        ];

        return file_put_contents($lockPath, json_encode($metaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
}
