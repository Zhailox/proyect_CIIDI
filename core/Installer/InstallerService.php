<?php
// core/Installer/InstallerService.php

class InstallerService {

    public static function lockFileExists(): bool {
        $lockPath = defined('STORAGE_PATH') ? STORAGE_PATH . 'installed.lock' : __DIR__ . '/../../storage/installed.lock';
        return file_exists($lockPath);
    }

    /**
     * Diagnóstico profundo de hardware, servidor web, extensiones PHP y permisos.
     */
    public static function checkSystemEnvironment(): array {
        $phpVersionOk = version_compare(PHP_VERSION, '8.1.0', '>=');
        $phpVersionRecommended = version_compare(PHP_VERSION, '8.2.0', '>=');

        // Extensiones PHP críticas requeridas por CIIDI
        $requiredExtensions = [
            'pdo'        => 'Controlador de base de datos base (PDO)',
            'pdo_pgsql'  => 'Conector nativo de PostgreSQL',
            'openssl'    => 'Motor criptográfico institucional (AES-256-CBC, Hashes, Tokens)',
            'mbstring'   => 'Manipulación segura de cadenas UTF-8',
            'fileinfo'   => 'Detección real de tipos MIME de archivos subidos',
            'json'       => 'Serialización y transporte de payloads API',
            'gd'         => 'Procesamiento y optimización de imágenes',
            'curl'       => 'Peticiones HTTP externas y APIs institucionales',
            'zip'        => 'Compresión y descompresión de respaldos'
        ];

        $extStatus = [];
        $allExtOk = true;

        foreach ($requiredExtensions as $ext => $desc) {
            $loaded = extension_loaded($ext);
            if (!$loaded && $ext === 'pdo_pgsql') {
                $drivers = class_exists('PDO') ? PDO::getAvailableDrivers() : [];
                if (in_array('pgsql', $drivers, true)) {
                    $loaded = true;
                }
            }
            $extStatus[$ext] = [
                'loaded' => $loaded,
                'description' => $desc
            ];
            if (!$loaded) {
                $allExtOk = false;
            }
        }

        // Permisos de escritura en carpetas del sistema
        $baseStorage = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        $baseUploads = defined('BASE_PATH') ? BASE_PATH . '/public/uploads/' : __DIR__ . '/../../public/uploads/';
        $baseRoot    = defined('BASE_PATH') ? BASE_PATH . '/' : __DIR__ . '/../../';

        $dirsToCheck = [
            'storage'           => $baseStorage,
            'storage/backups'   => $baseStorage . 'backups/',
            'storage/scheduler' => $baseStorage . 'scheduler/',
            'public/uploads'    => $baseUploads
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
            if (!$writable) {
                $allDirsOk = false;
            }
        }

        // Verificar si la raíz o el archivo .env es escribible
        $envPath = $baseRoot . '.env';
        $envWritable = file_exists($envPath) ? is_writable($envPath) : is_writable($baseRoot);
        $dirStatus['.env (Configuración)'] = [
            'path' => $envPath,
            'writable' => $envWritable
        ];
        if (!$envWritable) {
            $allDirsOk = false;
        }

        // Diagnóstico de memoria RAM asignada a PHP
        $memoryLimit = ini_get('memory_limit');
        $memoryBytes = self::returnBytes($memoryLimit);
        $memoryOk = ($memoryBytes === -1 || $memoryBytes >= 128 * 1024 * 1024);

        // Diagnóstico de tamaño máximo de subida
        $uploadMax = ini_get('upload_max_filesize');
        $uploadMaxBytes = self::returnBytes($uploadMax);
        $uploadOk = ($uploadMaxBytes >= 10 * 1024 * 1024);

        $postMax = ini_get('post_max_size');
        $postMaxBytes = self::returnBytes($postMax);
        $postOk = ($postMaxBytes >= 10 * 1024 * 1024);

        // Previsor de binarios psql y pg_dump
        $psqlBin = class_exists('Connection') ? Connection::getPsqlPath() : 'psql';
        $pgDumpBin = class_exists('Connection') ? Connection::getPgDumpPath() : 'pg_dump';
        $binariesOk = ($psqlBin !== 'psql' && $pgDumpBin !== 'pg_dump');

        return [
            'php_version'             => PHP_VERSION,
            'php_version_ok'          => $phpVersionOk,
            'php_version_recommended' => $phpVersionRecommended,
            'extensions'              => $extStatus,
            'extensions_ok'           => $allExtOk,
            'directories'             => $dirStatus,
            'directories_ok'          => $allDirsOk,
            'memory_limit'            => $memoryLimit,
            'memory_ok'               => $memoryOk,
            'upload_max_filesize'     => $uploadMax,
            'upload_ok'               => $uploadOk,
            'post_max_size'           => $postMax,
            'post_ok'                 => $postOk,
            'binaries_ok'             => $binariesOk,
            'ready'                   => ($phpVersionOk && $allExtOk && $allDirsOk)
        ];
    }

    public static function returnBytes(string $val): int {
        $val = trim($val);
        if ($val === '-1') return -1;
        $last = strtolower($val[strlen($val) - 1]);
        $valNum = (int)$val;
        switch ($last) {
            case 'g': $valNum *= 1024;
            case 'm': $valNum *= 1024;
            case 'k': $valNum *= 1024;
        }
        return $valNum;
    }

    /**
     * Prueba exhaustiva de conexión a la base de datos PostgreSQL, detectando tablas preexistentes.
     */
    public static function testDbConnection(string $host, string $port, string $user, string $pass, string $dbName): array {
        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ];
            $pdo = new PDO($dsnTarget, $user, $pass, $options);

            // Verificar conteo de tablas existentes en el esquema public
            $stmtCount = $pdo->query("SELECT count(*) FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'");
            $tableCount = (int) $stmtCount->fetchColumn();

            return [
                'exito'          => true,
                'bd_existe'      => true,
                'tiene_tablas'   => ($tableCount > 0),
                'num_tablas'     => $tableCount,
                'mensaje'        => ($tableCount > 0)
                    ? "Conexión exitosa. La base de datos '{$dbName}' existe y contiene {$tableCount} tabla(s) existente(s)."
                    : "Conexión exitosa a la base de datos '{$dbName}' (base de datos limpia y lista)."
            ];
        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();

            // Si la base de datos no existe (código 3D000 o mensaje 'does not exist')
            if (strpos($errorMsg, 'does not exist') !== false || strpos($errorMsg, 'no existe') !== false || $e->getCode() == '3D000') {
                try {
                    $dsnSystem = "pgsql:host={$host};port={$port};dbname=postgres";
                    $pdoSys = new PDO($dsnSystem, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5
                    ]);
                    return [
                        'exito'         => true,
                        'bd_existe'     => false,
                        'ofrecer_crear' => true,
                        'tiene_tablas'  => false,
                        'num_tablas'    => 0,
                        'mensaje'       => "Las credenciales son válidas. La base de datos '{$dbName}' no existe aún en PostgreSQL pero puede ser creada automáticamente."
                    ];
                } catch (PDOException $e2) {
                    return [
                        'exito'         => false,
                        'bd_existe'     => false,
                        'tiene_tablas'  => false,
                        'num_tablas'    => 0,
                        'mensaje'       => "Credenciales incorrectas o servidor PostgreSQL inaccesible: " . $e2->getMessage()
                    ];
                }
            }

            return [
                'exito'        => false,
                'bd_existe'    => false,
                'tiene_tablas' => false,
                'num_tablas'   => 0,
                'mensaje'      => "Fallo al verificar el servidor PostgreSQL: " . $errorMsg
            ];
        }
    }

    /**
     * Crea automáticamente la base de datos en PostgreSQL con codificación UTF-8.
     */
    public static function createDatabase(string $host, string $port, string $user, string $pass, string $dbName): array {
        try {
            $dsnSystem = "pgsql:host={$host};port={$port};dbname=postgres";
            $pdoSys = new PDO($dsnSystem, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $cleanDb = preg_replace('/[^a-zA-Z0-9_]/', '', $dbName);
            $cleanUser = preg_replace('/[^a-zA-Z0-9_]/', '', $user);

            $pdoSys->exec("CREATE DATABASE \"{$cleanDb}\" WITH OWNER = \"{$cleanUser}\" ENCODING = 'UTF8'");

            return [
                'exito'   => true,
                'mensaje' => "Base de datos '{$cleanDb}' creada exitosamente en PostgreSQL."
            ];
        } catch (PDOException $e) {
            return [
                'exito'   => false,
                'mensaje' => "No se pudo crear la base de datos. Detalle: " . $e->getMessage()
            ];
        }
    }

    /**
     * Limpia completamente el esquema public para una reinstalación limpia.
     */
    public static function cleanDatabaseSchema(string $host, string $port, string $user, string $pass, string $dbName): array {
        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $pdo->exec("DROP SCHEMA IF EXISTS public CASCADE; CREATE SCHEMA public; GRANT ALL ON SCHEMA public TO \"{$user}\";");

            return [
                'exito' => true,
                'mensaje' => "Esquema 'public' limpiado exitosamente para una instalación fresca."
            ];
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'mensaje' => "Fallo al limpiar el esquema preexistente: " . $e->getMessage()
            ];
        }
    }

    /**
     * Sincroniza las credenciales en .env y storage/db_config.json, y asegura un APP_KEY seguro.
     */
    public static function syncEnvAndKey(string $host, string $port, string $user, string $pass, string $dbName, array $extraEnv = []): array {
        $baseRoot = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        $envPath = $baseRoot . '/.env';

        // 1. Generar APP_KEY criptográficamente segura si no existe
        $appKey = '';
        if (file_exists($envPath)) {
            $currentEnv = file_get_contents($envPath);
            if (preg_match('/^APP_KEY=(.+)$/m', $currentEnv, $matches)) {
                $candidate = trim($matches[1]);
                if (!empty($candidate) && strlen($candidate) > 20) {
                    $appKey = $candidate;
                }
            }
        }

        if (empty($appKey)) {
            $appKey = 'base64:' . base64_encode(random_bytes(32));
        }

        // Parámetros adicionales
        $appEnv      = $extraEnv['app_env'] ?? 'production';
        $appDebug    = !empty($extraEnv['app_debug']) ? 'true' : 'false';
        $appUrl      = $extraEnv['app_url'] ?? 'http://localhost/proyect_CIIDI';
        $instName    = $extraEnv['inst_nombre'] ?? 'Universidad Politécnica Territorial de Trujillo Mario Briceño Iragorry';
        $instAcronym = $extraEnv['inst_siglas'] ?? 'UPTTMBI';
        $timezone    = $extraEnv['timezone'] ?? 'America/Caracas';

        // 2. Construir contenido de .env estructurado
        $newEnvContent = "# ==========================================================================\n"
                       . "# Configuración del Entorno Institucional CIIDI\n"
                       . "# Generado automáticamente por el Asistente de Instalación\n"
                       . "# Fecha: " . date('Y-m-d H:i:s') . "\n"
                       . "# ==========================================================================\n\n"
                       . "APP_ENV={$appEnv}\n"
                       . "APP_DEBUG={$appDebug}\n"
                       . "APP_KEY={$appKey}\n"
                       . "APP_URL={$appUrl}\n"
                       . "TIMEZONE={$timezone}\n\n"
                       . "# Parámetros de Identidad Institucional\n"
                       . "INSTITUTION_NAME=\"{$instName}\"\n"
                       . "INSTITUTION_ACRONYM=\"{$instAcronym}\"\n\n"
                       . "# Base de Datos PostgreSQL\n"
                       . "DB_HOST={$host}\n"
                       . "DB_PORT={$port}\n"
                       . "DB_NAME={$dbName}\n"
                       . "DB_USER={$user}\n"
                       . "DB_PASS={$pass}\n";

        $envSaved = @file_put_contents($envPath, $newEnvContent) !== false;

        // 3. Sincronizar en paralelo storage/db_config.json
        $configSaved = true;
        if (class_exists('Connection')) {
            $configSaved = Connection::saveCredentials($host, $port, $dbName, $user, $pass);
        }

        if (!$envSaved && !$configSaved) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo escribir ni el archivo .env ni storage/db_config.json. Verifique los permisos de escritura.'
            ];
        }

        return [
            'exito' => true,
            'mensaje' => 'Variables de entorno (.env) y configuración de base de datos sincronizadas con éxito.'
        ];
    }

    /**
     * Configura el estado de las tareas programadas (Scheduler & Cron) en storage/scheduler_tasks.json.
     */
    public static function configureSchedulerTasks(bool $habilitar): bool {
        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0777, true);
        }

        $tasksPath = $storageDir . 'scheduler_tasks.json';
        $estado = $habilitar ? 'activo' : 'inactivo';

        $tareas = [
            'limpieza_temporales' => [
                'id' => 'limpieza_temporales',
                'nombre' => 'Limpieza de Archivos Temporales & Caché',
                'descripcion' => 'Elimina archivos temporales en storage/tmp/, cachés expiradas y residuos de descargas.',
                'expresion_cron' => '0 3 * * *',
                'tipo_ejecucion' => 'metodo_interno',
                'script' => 'limpiarTemporales',
                'comando_custom' => '',
                'archivo_script' => '',
                'timeout_segundos' => 60,
                'estado' => $estado,
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución'
            ],
            'backup_automatico_medianoche' => [
                'id' => 'backup_automatico_medianoche',
                'nombre' => 'Respaldo Automático de Medianoche (PostgreSQL Dump)',
                'descripcion' => 'Genera un backup completo comprimido (.sql.gz) de la base de datos y purga respaldos antiguos.',
                'expresion_cron' => '0 0 * * *',
                'tipo_ejecucion' => 'metodo_interno',
                'script' => 'ejecutarBackupAutomatico',
                'comando_custom' => '',
                'archivo_script' => '',
                'timeout_segundos' => 300,
                'estado' => $estado,
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución'
            ],
            'purga_logs_viejos' => [
                'id' => 'purga_logs_viejos',
                'nombre' => 'Optimización y Rotación de Logs de Auditoría',
                'descripcion' => 'Mantener los logs de auditoría dentro de un límite saludable de almacenamiento.',
                'expresion_cron' => '0 4 * * 0',
                'tipo_ejecucion' => 'metodo_interno',
                'script' => 'purgarLogs',
                'comando_custom' => '',
                'archivo_script' => '',
                'timeout_segundos' => 60,
                'estado' => $estado,
                'ultima_ejecucion' => null,
                'resultado_ultimo' => 'Pendiente de primera ejecución'
            ]
        ];

        // Si ya existía el archivo, preservar tareas adicionales que pudiera tener
        if (file_exists($tasksPath)) {
            $existentes = json_decode(file_get_contents($tasksPath), true);
            if (is_array($existentes)) {
                foreach ($tareas as $k => $t) {
                    if (isset($existentes[$k])) {
                        $existentes[$k]['estado'] = $estado;
                    } else {
                        $existentes[$k] = $t;
                    }
                }
                $tareas = $existentes;
            }
        }

        return @file_put_contents($tasksPath, json_encode($tareas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Guarda la configuración de seguridad y SMTP institucional en storage/system_config.json.
     */
    public static function configureSystemSettings(array $seguridad, ?array $smtp = null): bool {
        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0777, true);
        }

        $configPath = $storageDir . 'system_config.json';
        $currentConfig = [];

        if (file_exists($configPath)) {
            $currentConfig = json_decode(file_get_contents($configPath), true) ?: [];
        }

        // 1. Configuración de Seguridad
        $currentConfig['seguridad'] = [
            'timeout_minutos' => (int)($seguridad['timeout_minutos'] ?? 120),
            'intentos_login'  => (int)($seguridad['intentos_login'] ?? 5)
        ];

        if (!isset($currentConfig['paginacion'])) {
            $currentConfig['paginacion'] = ["logs" => 50, "usuarios" => 15, "docentes" => 15];
        }

        // 2. Configuración de Correo SMTP
        if ($smtp && !empty($smtp['habilitado'])) {
            require_once defined('CORE_PATH') ? CORE_PATH . 'Security/Crypto.php' : __DIR__ . '/../Security/Crypto.php';

            $passRaw = trim($smtp['pass'] ?? '');
            $passEnc = !empty($passRaw) ? Crypto::encrypt($passRaw) : '';

            $currentConfig['smtp'] = [
                'host'       => trim($smtp['host'] ?? 'localhost'),
                'port'       => (int)($smtp['port'] ?? 587),
                'user'       => trim($smtp['user'] ?? ''),
                'pass'       => $passEnc,
                'from_email' => trim($smtp['from_email'] ?? $smtp['user'] ?? ''),
                'from_name'  => trim($smtp['from_name'] ?? 'Sistema CIIDI - UPTTMBI')
            ];
        } elseif (!isset($currentConfig['smtp'])) {
            $currentConfig['smtp'] = [
                'host'       => '',
                'port'       => 587,
                'user'       => '',
                'pass'       => '',
                'from_email' => '',
                'from_name'  => 'Sistema CIIDI - UPTTMBI'
            ];
        }

        return @file_put_contents($configPath, json_encode($currentConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Prueba en vivo de conexión SMTP enviando un correo de diagnóstico.
     */
    public static function testSmtpConnection(string $host, int $port, string $user, string $pass, string $fromEmail, string $testEmail): array {
        $baseRoot = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        if (file_exists($baseRoot . '/vendor/autoload.php')) {
            require_once $baseRoot . '/vendor/autoload.php';
        }

        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return [
                'exito' => false,
                'mensaje' => 'La librería PHPMailer no se encuentra disponible en vendor.'
            ];
        }

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = !empty($host) ? $host : 'localhost';
            $mail->SMTPAuth   = !empty($user) && !empty($pass);
            $mail->Username   = $user;
            $mail->Password   = $pass;
            $mail->SMTPSecure = ($port === 465) ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $port;
            $mail->Timeout    = 10;

            $mail->setFrom(!empty($fromEmail) ? $fromEmail : $user, 'Instalador CIIDI');
            $mail->addAddress($testEmail, 'Administrador CIIDI');

            $mail->isHTML(true);
            $mail->Subject = 'Prueba de Conexión SMTP - Instalador CIIDI';
            $mail->Body    = '<h3>¡Conexión SMTP Exitosa!</h3><p>Este es un correo de prueba generado durante el despliegue del Repositorio Institucional CIIDI.</p><p>Fecha y Hora: ' . date('Y-m-d H:i:s') . '</p>';

            $mail->send();

            return [
                'exito' => true,
                'mensaje' => "Conexión SMTP exitosa. Correo de prueba enviado a {$testEmail}."
            ];
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => "Error de conexión SMTP: " . $mail->ErrorInfo
            ];
        }
    }

    /**
     * Localiza el archivo de esquema base preferido (dentro de core/Installer o ciidi.sql).
     */
    public static function getSchemaFilePath(): ?string {
        $candidatos = [
            __DIR__ . '/schema.sql',
            __DIR__ . '/sql/schema.sql',
            (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)) . '/ciidi.sql'
        ];

        foreach ($candidatos as $path) {
            if (file_exists($path) && filesize($path) > 1000) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Ejecuta la importación del esquema base dentro de una transacción atómica protegida.
     */
    public static function runSqlSchema(string $host, string $port, string $user, string $pass, string $dbName): array {
        $sqlPath = self::getSchemaFilePath();
        if (!$sqlPath) {
            return [
                'exito' => false,
                'mensaje' => "No se encontró el archivo de esquema base ('schema.sql' o 'ciidi.sql') en la carpeta del instalador."
            ];
        }

        if (function_exists('set_time_limit')) @set_time_limit(300);
        @ini_set('memory_limit', '256M');

        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $pdo = new PDO($dsnTarget, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            $sqlContent = file_get_contents($sqlPath);

            $sqlLineas = explode("\n", $sqlContent);
            $cleanLines = [];
            foreach ($sqlLineas as $linea) {
                $trimmed = trim($linea);
                if (strpos($trimmed, '\\') === 0) {
                    continue;
                }
                $cleanLines[] = $linea;
            }
            $cleanSql = implode("\n", $cleanLines);

            $pdo->beginTransaction();
            $pdo->exec($cleanSql);
            $pdo->commit();

            return [
                'exito'   => true,
                'mensaje' => "Esquema e infraestructura de tablas importados exitosamente desde " . basename($sqlPath) . "."
            ];
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if (class_exists('Connection')) {
                Connection::logSystemError($e);
            }
            return [
                'exito'   => false,
                'mensaje' => "Error durante la importación del esquema SQL: " . $e->getMessage()
            ];
        }
    }

    /**
     * Ejecuta secuencialmente cualquier migración pendiente ubicada en migrations/.
     */
    public static function runPendingMigrations(string $host, string $port, string $user, string $pass, string $dbName): array {
        $baseRoot = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        $migrationsDir = $baseRoot . '/migrations';

        if (!is_dir($migrationsDir)) {
            return ['exito' => true, 'aplicadas' => 0, 'mensaje' => 'No hay directorio de migraciones.'];
        }

        $files = glob($migrationsDir . '/*.sql');
        sort($files, SORT_NATURAL);

        if (empty($files)) {
            return ['exito' => true, 'aplicadas' => 0, 'mensaje' => 'No hay migraciones pendientes.'];
        }

        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $pdo = new PDO($dsnTarget, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $aplicadas = 0;
            foreach ($files as $file) {
                $sql = file_get_contents($file);
                if (empty(trim($sql))) continue;

                try {
                    $pdo->exec($sql);
                    $aplicadas++;
                } catch (PDOException $migEx) {
                    if (strpos($migEx->getMessage(), 'already exists') !== false || strpos($migEx->getMessage(), 'ya existe') !== false) {
                        $aplicadas++;
                        continue;
                    }
                    if (class_exists('Connection')) Connection::logSystemError($migEx);
                }
            }

            return [
                'exito' => true,
                'aplicadas' => $aplicadas,
                'mensaje' => "Migraciones incrementales procesadas ({$aplicadas} scripts aplicados)."
            ];
        } catch (PDOException $e) {
            return [
                'exito' => false,
                'mensaje' => "Fallo al conectar para aplicar migraciones: " . $e->getMessage()
            ];
        }
    }

    /**
     * Crea o actualiza la cuenta primaria de SuperAdmin (Dios del Sistema).
     */
    public static function createSuperAdmin(string $host, string $port, string $user, string $pass, string $dbName, array $adminData): array {
        try {
            $dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $pdo = new PDO($dsnTarget, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $cedula = (int)($adminData['cedula'] ?? 0);
            $nombre = trim($adminData['nombre'] ?? '');
            $email  = trim($adminData['correo'] ?? '');
            $claveRaw = $adminData['clave'] ?? '';

            if ($cedula <= 0) {
                return ['exito' => false, 'mensaje' => 'La cédula de identidad debe ser un número entero positivo mayor a cero.'];
            }
            if (mb_strlen($nombre) < 3) {
                return ['exito' => false, 'mensaje' => 'El nombre completo del Administrador debe tener al menos 3 caracteres.'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['exito' => false, 'mensaje' => 'El formato del correo electrónico proporcionado no es válido.'];
            }
            if (strlen($claveRaw) < 8) {
                return ['exito' => false, 'mensaje' => 'La contraseña del SuperAdmin debe contener al menos 8 caracteres de seguridad.'];
            }

            $claveHash = password_hash($claveRaw, PASSWORD_BCRYPT, ['cost' => 12]);

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

            $pdo->exec("DELETE FROM usuarios WHERE cedula = '{$cedula}' OR email = " . $pdo->quote($email));

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
                'exito'   => true,
                'mensaje' => "SuperAdmin '{$nombre}' creado correctamente con privilegio de acceso absoluto."
            ];
        } catch (PDOException $e) {
            if (class_exists('Connection')) Connection::logSystemError($e);
            return [
                'exito'   => false,
                'mensaje' => "Error al registrar la cuenta de SuperAdmin: " . $e->getMessage()
            ];
        }
    }

    /**
     * Genera el candado de seguridad inmutable storage/installed.lock con metadatos del despliegue.
     */
    public static function createLockFile(): bool {
        $storageDir = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../storage/';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0777, true);
        }

        $lockPath = $storageDir . 'installed.lock';
        $metaData = [
            'installed_at'    => date('Y-m-d H:i:s'),
            'system_version'  => '2.0.0-PROD',
            'installed_by_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'php_version'     => PHP_VERSION,
            'os'              => PHP_OS,
            'schema_source'   => basename(self::getSchemaFilePath() ?? 'schema.sql'),
            'security_digest' => hash('sha256', php_uname() . date('YmdHis'))
        ];

        return @file_put_contents($lockPath, json_encode($metaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
}
