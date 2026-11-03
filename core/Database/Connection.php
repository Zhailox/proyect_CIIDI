<?php
// core/Database/Connection.php

require_once __DIR__ . '/Exceptions/DatabaseConnectionException.php';

class Connection {
    // La única instancia de la clase (Patrón Singleton)
    private static $instance = null;
    
    // El objeto PDO real
    private $pdo;

    // Credenciales por defecto
    private $host = 'localhost';
    private $port = '5432';
    private $db   = 'ciidi';
    private $user = 'miki';
    private $pass = '1234';

    private static bool $schemaCheckedInProcess = false;

    private static function getConfigPath(): string {
        return defined('STORAGE_PATH') ? STORAGE_PATH . 'db_config.json' : __DIR__ . '/../../storage/db_config.json';
    }

    private function cargarCredenciales(): void {
        if (class_exists('Env')) {
            $this->host = (string)Env::get('DB_HOST', $this->host);
            $this->port = (string)Env::get('DB_PORT', $this->port);
            $this->db   = (string)Env::get('DB_NAME', $this->db);
            $this->user = (string)Env::get('DB_USER', $this->user);
            $this->pass = (string)Env::get('DB_PASS', $this->pass);
        }

        $file = self::getConfigPath();
        if (file_exists($file)) {
            $data = json_decode((string)@file_get_contents($file), true) ?: [];
            if (!empty($data['host'])) $this->host = trim((string)$data['host']);
            if (!empty($data['port'])) $this->port = trim((string)$data['port']);
            if (!empty($data['db']))   $this->db   = trim((string)$data['db']);
            if (!empty($data['user'])) $this->user = trim((string)$data['user']);
            if (isset($data['pass']))  $this->pass = (string)$data['pass'];
        }
    }

    /**
     * Devuelve las credenciales de la base de datos de forma estática.
     */
    public static function getCredentials(): array {
        $dummy = new self(true, true);
        return [
            'host' => $dummy->host,
            'port' => $dummy->port,
            'db'   => $dummy->db,
            'user' => $dummy->user,
            'pass' => $dummy->pass
        ];
    }

    /**
     * Guarda las credenciales de la base de datos en storage/db_config.json y sincroniza .env si es escribible.
     */
    public static function saveCredentials(string $host, string $port, string $db, string $user, string $pass): bool {
        $file = self::getConfigPath();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $host = trim($host);
        $port = trim($port);
        $db   = trim($db);
        $user = trim($user);

        $saveData = [
            'host' => $host,
            'port' => $port,
            'db'   => $db,
            'user' => $user,
            'pass' => $pass,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $savedJson = @file_put_contents($file, json_encode($saveData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;

        // Sincronizar también en .env para evitar discrepancias entre entornos Windows/Linux
        $envFile = defined('BASE_PATH') ? BASE_PATH . '/.env' : __DIR__ . '/../../.env';
        if (file_exists($envFile) && is_writable($envFile)) {
            $envContent = (string)@file_get_contents($envFile);
            $replacements = [
                '/^DB_HOST=.*$/m' => 'DB_HOST=' . $host,
                '/^DB_PORT=.*$/m' => 'DB_PORT=' . $port,
                '/^DB_NAME=.*$/m' => 'DB_NAME=' . $db,
                '/^DB_USER=.*$/m' => 'DB_USER=' . $user,
                '/^DB_PASS=.*$/m' => 'DB_PASS=' . $pass,
            ];
            foreach ($replacements as $pattern => $replacement) {
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    $envContent = rtrim($envContent) . "\n" . $replacement . "\n";
                }
            }
            @file_put_contents($envFile, $envContent);
        }

        self::resetConnection();
        return $savedJson;
    }

    /**
     * Detecta la ruta ejecutable de pg_dump en el sistema operativo (Windows y Linux Debian/Ubuntu).
     */
    public static function getPgDumpPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $rutasCandidatas = [
                'C:\Program Files\PostgreSQL\18\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\17\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\16\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\15\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\14\bin\pg_dump.exe',
                'C:\Program Files\PostgreSQL\13\bin\pg_dump.exe',
                'C:\wamp64\bin\postgresql\bin\pg_dump.exe'
            ];

            foreach ($rutasCandidatas as $ruta) {
                if (file_exists($ruta)) {
                    return '"' . $ruta . '"';
                }
            }

            return 'pg_dump';
        }

        $linuxCandidatas = [
            '/usr/bin/pg_dump',
            '/usr/local/bin/pg_dump',
            '/bin/pg_dump'
        ];
        foreach ($linuxCandidatas as $ruta) {
            if (@is_executable($ruta) || @file_exists($ruta)) {
                return $ruta;
            }
        }

        return 'pg_dump';
    }

    /**
     * Detecta la ruta ejecutable de psql en el sistema operativo (Windows y Linux Debian/Ubuntu).
     */
    public static function getPsqlPath(): string {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $rutasCandidatas = [
                'C:\Program Files\PostgreSQL\18\bin\psql.exe',
                'C:\Program Files\PostgreSQL\17\bin\psql.exe',
                'C:\Program Files\PostgreSQL\16\bin\psql.exe',
                'C:\Program Files\PostgreSQL\15\bin\psql.exe',
                'C:\Program Files\PostgreSQL\14\bin\psql.exe',
                'C:\Program Files\PostgreSQL\13\bin\psql.exe',
                'C:\wamp64\bin\postgresql\bin\psql.exe'
            ];

            foreach ($rutasCandidatas as $ruta) {
                if (file_exists($ruta)) {
                    return '"' . $ruta . '"';
                }
            }

            return 'psql';
        }

        $linuxCandidatas = [
            '/usr/bin/psql',
            '/usr/local/bin/psql',
            '/bin/psql'
        ];
        foreach ($linuxCandidatas as $ruta) {
            if (@is_executable($ruta) || @file_exists($ruta)) {
                return $ruta;
            }
        }

        return 'psql';
    }

    private function __construct(bool $silencioso = false, bool $soloCredenciales = false) {
        $this->cargarCredenciales();
        if ($soloCredenciales) {
            return;
        }
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db};options='--client_encoding=UTF8'";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 5,
            ];

            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch (PDOException $e) {
            if ($silencioso) {
                return;
            }

            self::logSystemError($e);

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['db_connection_error_raw'] = $e->getMessage();
            }

            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            $detalleDev = $e->getMessage();

            throw new DatabaseConnectionException(
                'Error de infraestructura de datos. Por favor reintente más tarde.',
                (int)$e->getCode(),
                $e,
                $isAjax,
                $detalleDev
            );
        }
    }

    private static ?int $lastSyncedUserId = null;

    public static function getInstance(bool $silencioso = false): ?PDO {
        if (self::$instance === null) {
            $conexionObjeto = new self($silencioso);
            self::$instance = $conexionObjeto->pdo;
            self::$lastSyncedUserId = null;

            if (self::$instance !== null && !self::$schemaCheckedInProcess) {
                self::$schemaCheckedInProcess = true;
                self::autoHealSchemaIfNeeded(self::$instance);
            }
        }
        if (self::$instance !== null && isset($_SESSION['usuario_id']) && is_numeric($_SESSION['usuario_id'])) {
            $uid = (int)$_SESSION['usuario_id'];
            if ($uid > 0 && self::$lastSyncedUserId !== $uid) {
                try {
                    $stmt = self::$instance->prepare("SELECT set_config('app.usuario_actual', ?, false)");
                    $stmt->execute([(string)$uid]);
                    self::$lastSyncedUserId = $uid;
                } catch (Throwable) {
                    // Ignorar si la sesión de BD está en transacción abortada
                }
            }
        }
        return self::$instance;
    }

    /**
     * Verifica y repara automáticamente incompatibilidades de esquema en Linux Debian / PostgreSQL
     * (por ejemplo, cuando detalles_proyectos o unaccent fallaron al importar por falta de pgvector o transaction_timeout).
     */
    public static function autoHealSchemaIfNeeded(?PDO $pdo = null, bool $force = false): array {
        $pdo = $pdo ?? self::$instance;
        if (!$pdo) {
            return ['exito' => false, 'mensaje' => 'Sin conexión PDO activa.'];
        }

        if (!$force && isset($_SESSION['ciidi_schema_verified']) && $_SESSION['ciidi_schema_verified'] === true) {
            return ['exito' => true, 'mensaje' => 'Esquema ya verificado en la sesión actual.'];
        }

        $acciones = [];

        try {
            // 1. Verificar si existen las tablas críticas usuarios y detalles_proyectos
            $stmtCheck = $pdo->query("
                SELECT
                    to_regclass('public.usuarios') IS NOT NULL AS has_usuarios,
                    to_regclass('public.recursos') IS NOT NULL AS has_recursos,
                    to_regclass('public.detalles_proyectos') IS NOT NULL AS has_detalles_proyectos,
                    to_regclass('public.proyecto_carreras_vinculadas') IS NOT NULL AS has_pcv,
                    to_regclass('public.niveles_academicos') IS NOT NULL AS has_niveles,
                    to_regclass('public.investigadores') IS NOT NULL AS has_investigadores
            ");
            $status = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // Caso A: La base de datos está vacía (p. ej. falló el import inicial en Debian por transaction_timeout o vector)
            if (empty($status['has_usuarios']) || empty($status['has_recursos'])) {
                $schemaPath = defined('CORE_PATH')
                    ? CORE_PATH . 'Installer/schema.sql'
                    : __DIR__ . '/../Installer/schema.sql';
                if (file_exists($schemaPath)) {
                    $sqlContent = (string)file_get_contents($schemaPath);
                    $sqlClean = self::sanitizarSqlCompatible($sqlContent);
                    $pdo->exec($sqlClean);
                    $acciones[] = 'Esquema base completo importado automáticamente.';
                }
            } else {
                // Caso B: Existen las tablas base pero detalles_proyectos no se creó porque falló public.vector(384) en Debian
                if (empty($status['has_detalles_proyectos'])) {
                    $pdo->exec("
                        DO $$ BEGIN
                            CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public;
                        EXCEPTION WHEN OTHERS THEN NULL;
                        END $$;

                        CREATE TABLE IF NOT EXISTS public.detalles_proyectos (
                            id_recurso integer NOT NULL PRIMARY KEY,
                            fecha_defensa date,
                            nivel_academico character varying(50) DEFAULT 'Pregrado'::character varying,
                            resumen text,
                            id_carrera integer,
                            comunidad_beneficiada text,
                            palabras_clave text,
                            created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
                            id_investigacion_padre integer,
                            url_repositorio text,
                            obj_general text,
                            activo boolean DEFAULT true,
                            vector_semantico text,
                            id_trayecto integer,
                            vistas integer DEFAULT 0 NOT NULL,
                            CONSTRAINT detalles_proyectos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE,
                            CONSTRAINT detalles_proyectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE SET NULL,
                            CONSTRAINT detalles_proyectos_id_trayecto_fkey FOREIGN KEY (id_trayecto) REFERENCES public.trayectos(id) ON UPDATE CASCADE ON DELETE RESTRICT
                        );

                        CREATE INDEX IF NOT EXISTS idx_detalles_proyectos_trayecto ON public.detalles_proyectos USING btree (id_trayecto);
                        CREATE INDEX IF NOT EXISTS idx_detalles_vector_null ON public.detalles_proyectos USING btree (id_recurso) WHERE (vector_semantico IS NULL);

                        DO $$ BEGIN
                            IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'vector') THEN
                                ALTER TABLE public.detalles_proyectos ALTER COLUMN vector_semantico TYPE public.vector(384) USING vector_semantico::public.vector;
                                CREATE INDEX IF NOT EXISTS idx_detalles_vector_hnsw ON public.detalles_proyectos USING hnsw (vector_semantico public.vector_cosine_ops) WITH (m='16', ef_construction='64');
                            END IF;
                        EXCEPTION WHEN OTHERS THEN NULL;
                        END $$;
                    ");
                    $acciones[] = 'Tabla public.detalles_proyectos reconstruida de forma compatible con Debian.';
                }

                if (empty($status['has_pcv'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS public.proyecto_carreras_vinculadas (
                            id_recurso INTEGER NOT NULL,
                            id_carrera INTEGER NOT NULL,
                            created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
                            CONSTRAINT pk_proyecto_carreras_vinculadas PRIMARY KEY (id_recurso, id_carrera),
                            CONSTRAINT fk_pcv_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE,
                            CONSTRAINT fk_pcv_carrera FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE
                        );
                    ");
                    $acciones[] = 'Tabla public.proyecto_carreras_vinculadas verificada.';
                }

                if (empty($status['has_niveles'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS public.niveles_academicos (
                            id SERIAL PRIMARY KEY,
                            codigo VARCHAR(50) UNIQUE NOT NULL,
                            nombre VARCHAR(100) NOT NULL,
                            descripcion TEXT,
                            requiere_trayecto BOOLEAN DEFAULT FALSE,
                            orden INT DEFAULT 0,
                            activo BOOLEAN DEFAULT TRUE,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        );
                        INSERT INTO public.niveles_academicos (codigo, nombre, descripcion, requiere_trayecto, orden, activo)
                        VALUES 
                            ('Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', true, 1, true),
                            ('Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', false, 2, true),
                            ('Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', false, 3, true),
                            ('Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', false, 4, true)
                        ON CONFLICT (codigo) DO NOTHING;
                    ");
                    $acciones[] = 'Catálogo public.niveles_academicos verificado.';
                }

                if (empty($status['has_investigadores'])) {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS public.investigadores (
                            id SERIAL PRIMARY KEY,
                            nombre_completo VARCHAR(150) NOT NULL,
                            orcid VARCHAR(50) UNIQUE NOT NULL,
                            biografia TEXT NULL,
                            pagina_web VARCHAR(255) NULL
                        );
                        CREATE TABLE IF NOT EXISTS public.recurso_investigadores (
                            id_recurso INTEGER NOT NULL,
                            id_investigador INTEGER NOT NULL,
                            PRIMARY KEY (id_recurso, id_investigador),
                            CONSTRAINT fk_recurso_inv FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE,
                            CONSTRAINT fk_investigador FOREIGN KEY (id_investigador) REFERENCES public.investigadores(id) ON DELETE CASCADE
                        );
                    ");
                    $acciones[] = 'Tablas de investigadores verificadas.';
                }
            }

            // 2. Garantizar siempre que exista public.unaccent(text) y public.normalizar_busqueda_pst(text)
            $pdo->exec("
                DO $$
                BEGIN
                    CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;
                EXCEPTION WHEN OTHERS THEN
                    NULL;
                END $$;

                DO $$
                BEGIN
                    PERFORM public.unaccent('prueba');
                EXCEPTION WHEN undefined_function THEN
                    CREATE OR REPLACE FUNCTION public.unaccent(text)
                    RETURNS text
                    LANGUAGE sql
                    IMMUTABLE STRICT
                    AS \$func\$
                        SELECT translate(
                            \$1,
                            'áàäâãåéèëêíìïîóòöôõúùüûñçÁÀÄÂÃÅÉÈËÊÍÌÏÎÓÒÖÔÕÚÙÜÛÑÇ',
                            'aaaaaaeeeeiiiiooooouuuuncAAAAAAEEEEIIIIOOOOOUUUUNC'
                        );
                    \$func\$;
                END $$;

                CREATE OR REPLACE FUNCTION public.normalizar_busqueda_pst(input_text TEXT)
                RETURNS TEXT
                LANGUAGE sql
                IMMUTABLE
                AS $$
                    SELECT LOWER(public.unaccent(COALESCE(input_text, '')));
                $$;
            ");

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['ciidi_schema_verified'] = true;
            }

            return [
                'exito' => true,
                'acciones' => $acciones,
                'mensaje' => empty($acciones)
                    ? 'Esquema PostgreSQL verificado y 100% operativo.'
                    : implode(' ', $acciones)
            ];
        } catch (Throwable $e) {
            self::logSystemError($e);
            return [
                'exito' => false,
                'mensaje' => 'Error al verificar/reparar esquema: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sanitiza un script SQL de pg_dump para compatibilidad total con PostgreSQL 13-18 y Linux Debian (con o sin pgvector).
     */
    public static function sanitizarSqlCompatible(string $sql): string {
        $sql = str_replace("\r\n", "\n", $sql);

        // 1. Eliminar meta-comandos de psql (\restrict, \unrestrict, \connect, etc.)
        $sql = preg_replace('/^\\\\[a-zA-Z]+.*$/m', '', $sql);

        // 2. Eliminar SET transaction_timeout (exclusivo de PostgreSQL 17+)
        $sql = preg_replace('/^SET\s+transaction_timeout\s*=.*$/mi', '', $sql);

        // 3. Eliminar asignaciones de propietario rígidas (ALTER ... OWNER TO ...)
        $sql = preg_replace('/^ALTER\s+[^\n;]+?\s+OWNER\s+TO\s+[^\n;]+?;$/mi', '', $sql);

        // 4. Hacer que la extensión vector sea opcional y tolerante a fallos
        $sql = preg_replace('/^DROP\s+EXTENSION\s+IF\s+EXISTS\s+vector\s*;$/mi', '', $sql);
        $sql = preg_replace(
            '/^CREATE\s+EXTENSION\s+(?:IF\s+NOT\s+EXISTS\s+)?vector\b[^;]*;$/mi',
            "DO $$ BEGIN CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public; EXCEPTION WHEN OTHERS THEN NULL; END $$;",
            $sql
        );
        $sql = preg_replace('/^COMMENT\s+ON\s+EXTENSION\s+vector\s+IS\s+[^;]*;$/mi', '', $sql);

        // 5. Usar tipo text por defecto en CREATE TABLE para vector_semantico y promover a vector(384) solo si pgvector está disponible
        $sql = preg_replace('/\bvector_semantico\s+(?:public\.)?vector\s*\(\s*\d+\s*\)/i', 'vector_semantico text', $sql);

        // 6. Envolver el índice HNSW en un bloque DO seguro
        $sql = preg_replace(
            '/^CREATE\s+INDEX\s+(?:IF\s+NOT\s+EXISTS\s+)?idx_detalles_vector_hnsw\s+ON\s+public\.detalles_proyectos\s+USING\s+hnsw\s*\([^;]+?\)\s*(?:WITH\s*\([^;]+?\))?\s*;/mis',
            "DO $$ BEGIN\n"
            . "    IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'vector') THEN\n"
            . "        ALTER TABLE public.detalles_proyectos ALTER COLUMN vector_semantico TYPE public.vector(384) USING vector_semantico::public.vector;\n"
            . "        CREATE INDEX IF NOT EXISTS idx_detalles_vector_hnsw ON public.detalles_proyectos USING hnsw (vector_semantico public.vector_cosine_ops) WITH (m='16', ef_construction='64');\n"
            . "    END IF;\n"
            . "EXCEPTION WHEN OTHERS THEN NULL;\n"
            . "END $$;",
            $sql
        );

        return $sql;
    }

    public static function resetConnection(): void {
        self::$instance = null;
        self::$lastSyncedUserId = null;
        self::$schemaCheckedInProcess = false;
        if (session_status() === PHP_SESSION_ACTIVE) {
            unset($_SESSION['ciidi_schema_verified']);
        }
    }

    public static function logSystemError(Throwable $e): void {
        $logDir = defined('STORAGE_PATH') ? STORAGE_PATH . 'logs/' : __DIR__ . '/../../storage/logs/';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . 'system_errors.log';

        $mensaje = sprintf(
            "[%s] ERROR: %s en %s:%d\nTrace:\n%s\n%s\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString(),
            str_repeat('-', 80)
        );

        @file_put_contents($logFile, $mensaje, FILE_APPEND);
    }
}
