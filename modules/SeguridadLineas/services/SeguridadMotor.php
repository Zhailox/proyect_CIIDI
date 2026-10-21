<?php
// modules/SeguridadLineas/services/SeguridadMotor.php

require_once CORE_PATH . 'Database/Connection.php';
require_once __DIR__ . '/SeguridadSchema.php';

/**
 * Motor de seguridad del macro módulo "Líneas de Investigación".
 *
 * Orden de evaluación de un acceso (la primera regla que aplica decide):
 *   1. Nivel 0 (SuperAdmin)              → PERMITIDO siempre (anti auto-bloqueo)
 *   2. IP bloqueada                      → BLOQUEADO
 *   3. Modo Lockdown                     → DENEGADO para niveles > nivel máximo permitido
 *   4. Modo Solo Lectura                 → DENEGADO para acciones mutantes
 *   5. Excepción de usuario "denegar"    → DENEGADO
 *   6. Excepción de usuario "permitir"   → PERMITIDO
 *   7. Matriz de permisos (nivel)        → según la celda
 *   8. Valor por defecto                 → permitido en submódulos de negocio, denegado en Seguridad
 */
final class SeguridadMotor {

    public const MODULO          = 'SeguridadLineas';
    public const GRUPO           = 'lineas-investigacion';
    public const GENESIS         = 'GENESIS_SEGURIDAD_LINEAS_CIIDI_V1';
    public const NIVEL_VISITANTE = 999;
    private const LOCK_ID        = 731400125;
    private const CLAVE_RESPALDO = 'CIIDI_SEG_LINEAS_HMAC_FALLBACK_2026';

    public const ACCIONES = [
        'ver'        => ['etiqueta' => 'Ver',        'icono' => 'ph-eye',             'mutante' => false, 'desc' => 'Consultar páginas, fichas y carteleras'],
        'gestionar'  => ['etiqueta' => 'Gestionar',  'icono' => 'ph-squares-four',    'mutante' => false, 'desc' => 'Entrar a paneles de gestión y bandejas'],
        'crear'      => ['etiqueta' => 'Crear',      'icono' => 'ph-plus-circle',     'mutante' => true,  'desc' => 'Registrar nuevos elementos o postulaciones'],
        'editar'     => ['etiqueta' => 'Editar',     'icono' => 'ph-pencil-simple',   'mutante' => true,  'desc' => 'Modificar, aprobar, cambiar estados'],
        'eliminar'   => ['etiqueta' => 'Eliminar',   'icono' => 'ph-trash',           'mutante' => true,  'desc' => 'Borrar o archivar elementos'],
        'exportar'   => ['etiqueta' => 'Exportar',   'icono' => 'ph-download-simple', 'mutante' => false, 'desc' => 'Descargar reportes, PDF, CSV / auditar'],
        'configurar' => ['etiqueta' => 'Configurar', 'icono' => 'ph-sliders',         'mutante' => true,  'desc' => 'Cambiar configuración del submódulo'],
    ];

    /** Acciones aplicables al propio módulo de Seguridad */
    public const ACCIONES_SEGURIDAD = ['ver', 'exportar', 'configurar'];

    public const SEVERIDADES = ['INFO', 'AVISO', 'ALERTA', 'CRITICO'];
    public const RESULTADOS  = ['PERMITIDO', 'DENEGADO', 'BLOQUEADO', 'ADMIN', 'SISTEMA'];

    public const POLITICAS_DEFECTO = [
        'modo_bloqueo'          => false,
        'bloqueo_nivel_maximo'  => 0,
        'bloqueo_mensaje'       => 'El macro módulo Líneas de Investigación se encuentra en modo de contención (Lockdown) por razones de seguridad.',
        'solo_lectura'          => false,
        'registrar_lecturas'    => true,
        'registrar_visitantes'  => true,
        'retencion_dias'        => 90,
        'umbral_denegaciones'   => 5,
        'ventana_minutos'       => 10,
        'auto_bloqueo_ip'       => true,
        'duracion_bloqueo_min'  => 30,
        'ips_confiables'        => [],
        'refresco_feed_seg'     => 5,
        'ancla_cadena'          => '',
        'ultima_purga'          => '',
    ];

    /** Heurística de clasificación de rutas (primera coincidencia gana) */
    private const REGLAS_HEURISTICAS = [
        'eliminar'   => ['eliminar', 'borrar', 'delete', 'remover', 'quitar', 'purgar', 'archivar'],
        'exportar'   => ['exportar', 'export', 'reporte', 'pdf', 'csv', 'imprimir', 'descargar', 'excel', 'xls'],
        'configurar' => ['config', 'ajuste', 'parametro'],
        'crear'      => ['crear', 'nuevo', 'nueva', 'registrar', 'agregar', 'insertar', 'postular', 'aplicar', 'subir', 'enviar', 'solicitar', 'invitar'],
        'editar'     => ['editar', 'actualizar', 'guardar', 'modificar', 'cambiar', 'alternar', 'aprobar', 'rechazar', 'asignar', 'procesar', 'evaluar', 'estado', 'toggle', 'mover', 'vincular'],
        'gestionar'  => ['gestion', 'gestor', 'panel', 'admin', 'administrar', 'mis', 'bandeja', 'seguimiento-admin'],
    ];

    private static ?array $politicas       = null;
    private static array  $matrizCache     = [];
    private static array  $excepcionCache  = [];
    private static ?array $rutasManuales   = null;
    private static array  $bloqueoCache    = [];
    private static array  $evaluacionCache = [];
    private static ?bool  $disponible      = null;
    private static ?array $submodulosCache = null;

    /* =====================================================================
     *  Infraestructura
     * ===================================================================== */

    public static function db(): ?PDO {
        if (self::$disponible === false) {
            return null;
        }
        try {
            $db = Connection::getInstance(true);
        } catch (Throwable $e) {
            return null;
        }
        if (!$db) {
            return null;
        }
        if (self::$disponible === null) {
            self::$disponible = SeguridadSchema::asegurar($db);
        }
        return self::$disponible ? $db : null;
    }

    public static function disponible(): bool {
        return self::db() !== null;
    }

    public static function error(Throwable $e): void {
        if ($e instanceof PDOException && $e->getCode() === '42P01') {
            // Tabla inexistente: se fuerza la reinstalación del esquema en el próximo request
            SeguridadSchema::invalidar();
        }
        if (class_exists('Connection')) {
            Connection::logSystemError($e);
        }
    }

    public static function ahora(int $desplazamientoSeg = 0): string {
        return date('Y-m-d H:i:s', time() + $desplazamientoSeg);
    }

    public static function ip(): string {
        // Solo REMOTE_ADDR: las cabeceras X-Forwarded-For son falsificables por el cliente.
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        return substr($ip, 0, 64);
    }

    public static function contexto(): array {
        $logueado = isset($_SESSION['usuario_id']);
        $nivel    = $logueado ? (int) ($_SESSION['nivel_privilegio'] ?? self::NIVEL_VISITANTE) : self::NIVEL_VISITANTE;
        $nombre   = $_SESSION['usuario_nombre']
            ?? $_SESSION['nombre']
            ?? $_SESSION['nombre_completo']
            ?? $_SESSION['emerg_user_nombre']
            ?? null;

        return [
            'logueado' => $logueado,
            'id'       => $logueado ? (int) $_SESSION['usuario_id'] : null,
            'nivel'    => $nivel,
            'nombre'   => $nombre ?: ($logueado ? 'Usuario #' . $_SESSION['usuario_id'] : 'Visitante anónimo'),
            'ip'       => self::ip(),
        ];
    }

    public static function firmaActor(): string {
        $c = self::contexto();
        return mb_substr($c['nombre'] . ($c['id'] !== null ? " (ID {$c['id']})" : ''), 0, 160);
    }

    private static function claveHmac(): string {
        $clave = class_exists('Env') ? (string) Env::get('APP_KEY', '') : '';
        return $clave !== '' ? $clave : self::CLAVE_RESPALDO;
    }

    public static function limpiarCaches(): void {
        self::$politicas       = null;
        self::$matrizCache     = [];
        self::$excepcionCache  = [];
        self::$rutasManuales   = null;
        self::$bloqueoCache    = [];
        self::$evaluacionCache = [];
    }

    /* =====================================================================
     *  Políticas
     * ===================================================================== */

    public static function politicas(): array {
        if (self::$politicas !== null) {
            return self::$politicas;
        }
        $p  = self::POLITICAS_DEFECTO;
        $db = self::db();
        if ($db) {
            try {
                foreach ($db->query("SELECT clave, valor::text AS valor FROM seg_lineas_politicas") as $fila) {
                    if (array_key_exists($fila['clave'], $p)) {
                        $p[$fila['clave']] = json_decode($fila['valor'], true);
                    }
                }
            } catch (Throwable $e) {
                self::error($e);
            }
        }
        return self::$politicas = $p;
    }

    public static function politica(string $clave) {
        return self::politicas()[$clave] ?? (self::POLITICAS_DEFECTO[$clave] ?? null);
    }

    public static function guardarPoliticas(array $valores): void {
        $db = self::db();
        if (!$db) {
            throw new RuntimeException('Motor de seguridad sin base de datos.');
        }
        $autor = self::firmaActor();
        $stmt  = $db->prepare("
            INSERT INTO seg_lineas_politicas (clave, valor, actualizado_por, actualizado_en)
            VALUES (?, CAST(? AS JSONB), ?, ?)
            ON CONFLICT (clave) DO UPDATE
               SET valor = EXCLUDED.valor, actualizado_por = EXCLUDED.actualizado_por, actualizado_en = EXCLUDED.actualizado_en
        ");
        foreach ($valores as $clave => $valor) {
            if (!array_key_exists($clave, self::POLITICAS_DEFECTO)) {
                continue;
            }
            $stmt->execute([$clave, json_encode($valor, JSON_UNESCAPED_UNICODE), $autor, self::ahora()]);
        }
        self::$politicas = null;
    }

    /* =====================================================================
     *  Submódulos del macro módulo
     * ===================================================================== */

    /**
     * Devuelve los módulos que pertenecen al macro módulo (descubiertos dinámicamente
     * desde el core) con sus rutas y las acciones aplicables.
     */
    public static function submodulos(): array {
        if (self::$submodulosCache !== null) {
            return self::$submodulosCache;
        }
        $mapa = [];
        if (class_exists('ModuleLoader') && ModuleLoader::actual()) {
            $mapa = ModuleLoader::actual()->getMapaGrupo(self::GRUPO);
        }
        if (!isset($mapa[self::MODULO])) {
            $mapa[self::MODULO] = ['titulo' => 'Seguridad', 'icono' => 'ph-fill ph-shield-checkered', 'estado' => 'online', 'rutas' => []];
        }
        foreach ($mapa as $carpeta => &$info) {
            $info['acciones'] = $carpeta === self::MODULO ? self::ACCIONES_SEGURIDAD : array_keys(self::ACCIONES);
        }
        unset($info);
        return self::$submodulosCache = $mapa;
    }

    public static function tituloSubmodulo(string $carpeta): string {
        if ($carpeta === '*') {
            return 'Todo el macro módulo';
        }
        $mapa = self::submodulos();
        return $mapa[$carpeta]['titulo'] ?? $carpeta;
    }

    public static function accionAplica(string $submodulo, string $accion): bool {
        if (!isset(self::ACCIONES[$accion])) {
            return false;
        }
        return $submodulo !== self::MODULO || in_array($accion, self::ACCIONES_SEGURIDAD, true);
    }

    /* =====================================================================
     *  Clasificación de rutas → acción
     * ===================================================================== */

    public static function heuristica(string $ruta): string {
        $tokens = preg_split('/[-_\/]+/', strtolower($ruta)) ?: [];
        foreach (self::REGLAS_HEURISTICAS as $accion => $claves) {
            foreach ($claves as $clave) {
                if (str_contains($clave, '-')) {
                    if (str_contains(strtolower($ruta), $clave)) {
                        return $accion;
                    }
                    continue;
                }
                foreach ($tokens as $t) {
                    if ($t !== '' && str_starts_with($t, $clave)) {
                        return $accion;
                    }
                }
            }
        }
        return 'ver';
    }

    public static function rutasManuales(): array {
        if (self::$rutasManuales !== null) {
            return self::$rutasManuales;
        }
        $res = [];
        $db  = self::db();
        if ($db) {
            try {
                foreach ($db->query("SELECT ruta, accion FROM seg_lineas_rutas") as $f) {
                    $res[$f['ruta']] = $f['accion'];
                }
            } catch (Throwable $e) {
                self::error($e);
            }
        }
        return self::$rutasManuales = $res;
    }

    /**
     * @return array{accion:string, origen:string}
     *   origen: fija (rutas del módulo de seguridad) | manual (BD) | declarada (config de la ruta) | heuristica
     */
    public static function clasificarRuta(string $ruta, array $config = [], ?string $modulo = null): array {
        if ($modulo === self::MODULO) {
            $acc = $config['seguridad_accion'] ?? 'ver';
            return ['accion' => isset(self::ACCIONES[$acc]) ? $acc : 'ver', 'origen' => 'fija'];
        }
        $manuales = self::rutasManuales();
        if (isset($manuales[$ruta]) && isset(self::ACCIONES[$manuales[$ruta]])) {
            return ['accion' => $manuales[$ruta], 'origen' => 'manual'];
        }
        if (!empty($config['seguridad_accion']) && isset(self::ACCIONES[$config['seguridad_accion']])) {
            return ['accion' => $config['seguridad_accion'], 'origen' => 'declarada'];
        }
        return ['accion' => self::heuristica($ruta), 'origen' => 'heuristica'];
    }

    /* =====================================================================
     *  Evaluación de permisos
     * ===================================================================== */

    private static function matrizNivel(int $nivel): array {
        if (isset(self::$matrizCache[$nivel])) {
            return self::$matrizCache[$nivel];
        }
        $m  = [];
        $db = self::db();
        if ($db) {
            try {
                $stmt = $db->prepare("SELECT submodulo, accion, permitido FROM seg_lineas_permisos WHERE nivel = ?");
                $stmt->execute([$nivel]);
                foreach ($stmt->fetchAll() as $f) {
                    $m[$f['submodulo']][$f['accion']] = (bool) $f['permitido'];
                }
            } catch (Throwable $e) {
                self::error($e);
            }
        }
        return self::$matrizCache[$nivel] = $m;
    }

    public static function permisoPorDefecto(string $submodulo, string $accion, int $nivel): bool {
        if ($nivel === 0) {
            return true;
        }
        return $submodulo !== self::MODULO;
    }

    public static function permisoMatriz(int $nivel, string $submodulo, string $accion): bool {
        if ($nivel === 0) {
            return true;
        }
        $m = self::matrizNivel($nivel);
        if (isset($m[$submodulo][$accion])) {
            return $m[$submodulo][$accion];
        }
        return self::permisoPorDefecto($submodulo, $accion, $nivel);
    }

    private static function excepcionesUsuario(int $usuarioId): array {
        if (isset(self::$excepcionCache[$usuarioId])) {
            return self::$excepcionCache[$usuarioId];
        }
        $lista = [];
        $db    = self::db();
        if ($db) {
            try {
                $stmt = $db->prepare("
                    SELECT id, submodulo, accion, efecto, motivo, expira_en
                      FROM seg_lineas_excepciones
                     WHERE usuario_id = ? AND (expira_en IS NULL OR expira_en > ?)
                ");
                $stmt->execute([$usuarioId, self::ahora()]);
                $lista = $stmt->fetchAll();
            } catch (Throwable $e) {
                self::error($e);
            }
        }
        return self::$excepcionCache[$usuarioId] = $lista;
    }

    public static function ipBloqueada(string $ip): ?array {
        if (array_key_exists($ip, self::$bloqueoCache)) {
            return self::$bloqueoCache[$ip];
        }
        $res = null;
        $db  = self::db();
        if ($db && $ip !== '') {
            try {
                $stmt = $db->prepare("
                    SELECT id, motivo, expira_en, origen
                      FROM seg_lineas_bloqueos
                     WHERE ip = ? AND activo = TRUE AND (expira_en IS NULL OR expira_en > ?)
                     ORDER BY id DESC LIMIT 1
                ");
                $stmt->execute([$ip, self::ahora()]);
                $res = $stmt->fetch() ?: null;
            } catch (Throwable $e) {
                self::error($e);
            }
        }
        return self::$bloqueoCache[$ip] = $res;
    }

    private static function decision(bool $permitido, string $codigo, string $motivo): array {
        return ['permitido' => $permitido, 'codigo' => $codigo, 'motivo' => $motivo];
    }

    /**
     * Evalúa si un contexto (usuario/nivel/IP) puede ejecutar una acción sobre un submódulo.
     */
    public static function evaluar(string $submodulo, string $accion, ?array $ctx = null): array {
        $esActual = $ctx === null;
        $ctx ??= self::contexto();
        $cacheKey = $esActual ? "{$submodulo}|{$accion}" : null;
        if ($cacheKey !== null && isset(self::$evaluacionCache[$cacheKey])) {
            return self::$evaluacionCache[$cacheKey];
        }

        $nivel  = (int) $ctx['nivel'];
        $accTxt = self::ACCIONES[$accion]['etiqueta'] ?? $accion;
        $subTxt = self::tituloSubmodulo($submodulo);

        if ($nivel === 0) {
            $d = self::decision(true, 'NIVEL_0', 'Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.');
        } elseif (!self::disponible()) {
            $d = self::decision(true, 'DEGRADADO', 'Motor de seguridad degradado (sin BD): se aplican solo los controles propios del módulo.');
        } elseif ($bloqueo = self::ipBloqueada((string) $ctx['ip'])) {
            $hasta = $bloqueo['expira_en'] ? "hasta {$bloqueo['expira_en']}" : 'de forma permanente';
            $d = self::decision(false, 'IP_BLOQUEADA', "La IP {$ctx['ip']} está bloqueada {$hasta}. Motivo: {$bloqueo['motivo']}");
        } elseif (self::politica('modo_bloqueo') && $nivel > (int) self::politica('bloqueo_nivel_maximo')) {
            $d = self::decision(false, 'LOCKDOWN', (string) self::politica('bloqueo_mensaje'));
        } elseif (self::politica('solo_lectura') && $submodulo !== self::MODULO && (self::ACCIONES[$accion]['mutante'] ?? false)) {
            $d = self::decision(false, 'SOLO_LECTURA', "Modo Solo Lectura activo: la acción «{$accTxt}» está congelada temporalmente.");
        } else {
            $d = null;
            if ($ctx['id'] !== null) {
                $permitir = null;
                foreach (self::excepcionesUsuario((int) $ctx['id']) as $ex) {
                    $aplicaSub = $ex['submodulo'] === '*' || $ex['submodulo'] === $submodulo;
                    $aplicaAcc = $ex['accion'] === '*' || $ex['accion'] === $accion;
                    if (!$aplicaSub || !$aplicaAcc) {
                        continue;
                    }
                    if ($ex['efecto'] === 'denegar') {
                        $d = self::decision(false, 'EXCEPCION', "Excepción #{$ex['id']} deniega «{$accTxt}» en «{$subTxt}» a este usuario. " . ($ex['motivo'] ? "Motivo: {$ex['motivo']}" : ''));
                        break;
                    }
                    $permitir ??= $ex;
                }
                if ($d === null && $permitir !== null) {
                    $d = self::decision(true, 'EXCEPCION', "Excepción #{$permitir['id']} concede «{$accTxt}» en «{$subTxt}» a este usuario.");
                }
            }
            if ($d === null) {
                $nivelTxt = $nivel === self::NIVEL_VISITANTE ? 'Visitante (sin sesión)' : "Nivel {$nivel}";
                $d = self::permisoMatriz($nivel, $submodulo, $accion)
                    ? self::decision(true, 'MATRIZ', "Matriz de permisos: {$nivelTxt} tiene «{$accTxt}» en «{$subTxt}».")
                    : self::decision(false, 'MATRIZ', "Matriz de permisos: {$nivelTxt} no tiene «{$accTxt}» en «{$subTxt}».");
            }
        }

        if ($cacheKey !== null) {
            self::$evaluacionCache[$cacheKey] = $d;
        }
        return $d;
    }

    public static function puede(string $accion, string $submodulo = self::MODULO): bool {
        return self::evaluar($submodulo, $accion)['permitido'];
    }

    /* =====================================================================
     *  Guardián de rutas (invocado por el core)
     * ===================================================================== */

    public static function interceptar(string $ruta, array $config, string $modulo): ?array {
        $ctx  = self::contexto();
        $clas = self::clasificarRuta($ruta, $config, $modulo);
        $acc  = $clas['accion'];
        $dec  = self::evaluar($modulo, $acc, $ctx);
        $esPropio = $modulo === self::MODULO;

        if ($dec['permitido']) {
            if (!$esPropio && $dec['codigo'] !== 'DEGRADADO') {
                $mutante   = self::ACCIONES[$acc]['mutante'] ?? false;
                $registrar = $mutante
                    || $acc === 'exportar'
                    || (self::politica('registrar_lecturas') && ($ctx['logueado'] || self::politica('registrar_visitantes')));
                if ($registrar) {
                    self::registrar([
                        'severidad' => in_array($acc, ['eliminar', 'configurar'], true) ? 'AVISO' : 'INFO',
                        'submodulo' => $modulo,
                        'accion'    => $acc,
                        'ruta'      => $ruta,
                        'resultado' => 'PERMITIDO',
                        'detalles'  => $dec['motivo'],
                    ], $ctx);
                }
            }
            return null;
        }

        $resultado = $dec['codigo'] === 'IP_BLOQUEADA' ? 'BLOQUEADO' : 'DENEGADO';
        $idEvento  = self::registrar([
            'severidad' => in_array($dec['codigo'], ['IP_BLOQUEADA', 'LOCKDOWN'], true) ? 'ALERTA' : 'AVISO',
            'submodulo' => $modulo,
            'accion'    => $acc,
            'ruta'      => $ruta,
            'resultado' => $resultado,
            'detalles'  => "[{$dec['codigo']}] {$dec['motivo']}",
        ], $ctx);

        if ($resultado === 'DENEGADO') {
            self::analizarAmenaza($ctx);
        }

        $incidente = $idEvento ? 'SEG-' . str_pad((string) $idEvento, 6, '0', STR_PAD_LEFT) : 'SEG-SIN-REGISTRO';

        return [
            'status' => 403,
            'json'   => [
                'status'    => 'error',
                'success'   => false,
                'codigo'    => $dec['codigo'],
                'incidente' => $incidente,
                'message'   => $dec['motivo'],
            ],
            'vista'  => __DIR__ . '/../views/acceso_denegado.php',
            'titulo' => '403 · Acceso denegado — Seguridad Líneas de Investigación',
            'css'    => ['../modules/' . self::MODULO . '/assets/css/seguridad_lineas.css'],
            'datos'  => [
                'segDenegacion' => [
                    'codigo'    => $dec['codigo'],
                    'motivo'    => $dec['motivo'],
                    'incidente' => $incidente,
                    'ruta'      => $ruta,
                    'accion'    => $acc,
                    'submodulo' => self::tituloSubmodulo($modulo),
                    'ip'        => $ctx['ip'],
                    'logueado'  => $ctx['logueado'],
                    'usuario'   => $ctx['nombre'],
                    'fecha'     => self::ahora(),
                ],
            ],
        ];
    }

    public static function rutaVisible(string $ruta, array $config, string $modulo): bool {
        $clas = self::clasificarRuta($ruta, $config, $modulo);
        return self::evaluar($modulo, $clas['accion'])['permitido'];
    }

    /* =====================================================================
     *  Bitácora con cadena HMAC
     * ===================================================================== */

    public static function firmar(array $r): string {
        $campos = [
            $r['id'], $r['fecha'], $r['severidad'], $r['submodulo'], $r['accion'], $r['ruta'],
            $r['resultado'], $r['usuario_id'] ?? '', $r['usuario_nombre'], $r['nivel'], $r['ip'],
            $r['metodo'], $r['user_agent'], $r['detalles'], $r['hash_anterior'],
        ];
        $cadena = implode("\x1F", array_map(static fn($v) => (string) ($v ?? ''), $campos));
        return hash_hmac('sha256', $cadena, self::claveHmac());
    }

    /**
     * Registra un evento en la bitácora del macro módulo. Devuelve el ID o null.
     */
    public static function registrar(array $evento, ?array $ctx = null): ?int {
        $db = self::db();
        if (!$db) {
            return null;
        }
        $ctx ??= self::contexto();

        $sev = strtoupper((string) ($evento['severidad'] ?? 'INFO'));
        $res = strtoupper((string) ($evento['resultado'] ?? 'SISTEMA'));

        $registro = [
            'id'             => 0,
            'fecha'          => self::ahora(),
            'severidad'      => in_array($sev, self::SEVERIDADES, true) ? $sev : 'INFO',
            'submodulo'      => mb_substr((string) ($evento['submodulo'] ?? self::MODULO), 0, 80),
            'accion'         => mb_substr((string) ($evento['accion'] ?? 'sistema'), 0, 30),
            'ruta'           => mb_substr((string) ($evento['ruta'] ?? ($_GET['ruta'] ?? '')), 0, 160),
            'resultado'      => in_array($res, self::RESULTADOS, true) ? $res : 'SISTEMA',
            'usuario_id'     => $ctx['id'],
            'usuario_nombre' => mb_substr((string) $ctx['nombre'], 0, 160),
            'nivel'          => (int) $ctx['nivel'],
            'ip'             => mb_substr((string) $ctx['ip'], 0, 64),
            'metodo'         => mb_substr(strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'CLI')), 0, 10),
            'user_agent'     => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'detalles'       => (string) ($evento['detalles'] ?? ''),
            'hash_anterior'  => '',
        ];

        $propia = !$db->inTransaction();
        try {
            if ($propia) {
                $db->beginTransaction();
            }
            $db->query("SELECT pg_advisory_xact_lock(" . self::LOCK_ID . ")");

            $prev = $db->query("SELECT hash FROM seg_lineas_logs ORDER BY id DESC LIMIT 1")->fetchColumn();
            if (!$prev) {
                $prev = (string) self::politica('ancla_cadena') ?: self::GENESIS;
            }
            $registro['hash_anterior'] = $prev;
            $registro['id']            = (int) $db->query("SELECT nextval(pg_get_serial_sequence('seg_lineas_logs', 'id'))")->fetchColumn();
            $registro['hash']          = self::firmar($registro);

            $stmt = $db->prepare("
                INSERT INTO seg_lineas_logs
                    (id, fecha, severidad, submodulo, accion, ruta, resultado, usuario_id, usuario_nombre,
                     nivel, ip, metodo, user_agent, detalles, hash_anterior, hash)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $registro['id'], $registro['fecha'], $registro['severidad'], $registro['submodulo'],
                $registro['accion'], $registro['ruta'], $registro['resultado'], $registro['usuario_id'],
                $registro['usuario_nombre'], $registro['nivel'], $registro['ip'], $registro['metodo'],
                $registro['user_agent'], $registro['detalles'], $registro['hash_anterior'], $registro['hash'],
            ]);

            if ($propia) {
                $db->commit();
            }
            return $registro['id'];
        } catch (Throwable $e) {
            if ($propia && $db->inTransaction()) {
                $db->rollBack();
            }
            self::error($e);
            return null;
        }
    }

    /**
     * Registra una acción administrativa del módulo de seguridad (bitácora propia + auditoría global).
     */
    public static function registrarAdmin(string $accion, string $detalles, string $severidad = 'AVISO'): ?int {
        $id = self::registrar([
            'severidad' => $severidad,
            'submodulo' => self::MODULO,
            'accion'    => $accion,
            'resultado' => 'ADMIN',
            'detalles'  => $detalles,
        ]);
        if (class_exists('AuditLogger')) {
            try {
                AuditLogger::registrar($severidad === 'CRITICO' ? 'CRITICAL' : 'WARNING', self::MODULO, $accion, $detalles);
            } catch (Throwable $e) {
                // La auditoría global es complementaria; no debe interrumpir la operación
            }
        }
        return $id;
    }

    /**
     * Recalcula la cadena completa. Detecta registros alterados, eliminados o reordenados.
     */
    public static function verificarIntegridad(): array {
        $db = self::db();
        if (!$db) {
            return ['integro' => false, 'total' => 0, 'corruptos' => [], 'mensaje' => 'Motor sin base de datos.'];
        }
        $inicio    = microtime(true);
        $ancla     = (string) self::politica('ancla_cadena');
        $esperado  = null;
        $total     = 0;
        $corruptos = [];

        try {
            $stmt = $db->query("SELECT * FROM seg_lineas_logs ORDER BY id ASC");
            while ($r = $stmt->fetch()) {
                $total++;
                $r['usuario_id'] = $r['usuario_id'] === null ? '' : (string) $r['usuario_id'];

                if ($esperado === null) {
                    $validos = array_filter([self::GENESIS, $ancla]);
                    if (!in_array($r['hash_anterior'], $validos, true)) {
                        $corruptos[] = ['id' => (int) $r['id'], 'motivo' => 'El primer registro no enlaza con el génesis ni con el ancla de la última purga (posible borrado de registros iniciales).'];
                    }
                } elseif ($r['hash_anterior'] !== $esperado) {
                    $corruptos[] = ['id' => (int) $r['id'], 'motivo' => 'Eslabón roto: el hash anterior no coincide (registro intermedio eliminado o reordenado).'];
                }

                if (!hash_equals(self::firmar($r), (string) $r['hash'])) {
                    $corruptos[] = ['id' => (int) $r['id'], 'motivo' => 'Firma HMAC inválida: el contenido del registro fue alterado.'];
                }
                $esperado = $r['hash'];

                if (count($corruptos) >= 200) {
                    break;
                }
            }
        } catch (Throwable $e) {
            self::error($e);
            return ['integro' => false, 'total' => $total, 'corruptos' => [], 'mensaje' => 'Error al verificar: ' . $e->getMessage()];
        }

        return [
            'integro'   => empty($corruptos),
            'total'     => $total,
            'corruptos' => $corruptos,
            'ms'        => (int) round((microtime(true) - $inicio) * 1000),
            'mensaje'   => empty($corruptos)
                ? "Cadena íntegra: {$total} eslabones verificados con HMAC-SHA256."
                : count($corruptos) . ' anomalía(s) detectada(s) en la cadena de custodia.',
        ];
    }

    /**
     * Elimina eventos más antiguos que $dias y re-ancla la cadena.
     */
    public static function purgar(int $dias): int {
        $db = self::db();
        if (!$db) {
            return 0;
        }
        $dias  = max(0, $dias);
        $corte = self::ahora(-$dias * 86400);

        $db->beginTransaction();
        try {
            $db->query("SELECT pg_advisory_xact_lock(" . self::LOCK_ID . ")");
            $stmtUlt = $db->prepare("SELECT hash FROM seg_lineas_logs WHERE fecha < ? ORDER BY id DESC LIMIT 1");
            $stmtUlt->execute([$corte]);
            $ultimoBorrado = $stmtUlt->fetchColumn();

            $stmt = $db->prepare("DELETE FROM seg_lineas_logs WHERE fecha < ?");
            $stmt->execute([$corte]);
            $borrados = $stmt->rowCount();

            if ($borrados > 0 && $ultimoBorrado) {
                $primero = $db->query("SELECT hash_anterior FROM seg_lineas_logs ORDER BY id ASC LIMIT 1")->fetchColumn();
                $ancla   = $primero ?: $ultimoBorrado;
                $up = $db->prepare("
                    INSERT INTO seg_lineas_politicas (clave, valor, actualizado_por, actualizado_en)
                    VALUES (?, CAST(? AS JSONB), ?, ?)
                    ON CONFLICT (clave) DO UPDATE SET valor = EXCLUDED.valor, actualizado_por = EXCLUDED.actualizado_por, actualizado_en = EXCLUDED.actualizado_en
                ");
                $up->execute(['ancla_cadena', json_encode($ancla), self::firmaActor(), self::ahora()]);
            }
            $up2 = $db->prepare("
                INSERT INTO seg_lineas_politicas (clave, valor, actualizado_por, actualizado_en)
                VALUES ('ultima_purga', CAST(? AS JSONB), ?, ?)
                ON CONFLICT (clave) DO UPDATE SET valor = EXCLUDED.valor, actualizado_por = EXCLUDED.actualizado_por, actualizado_en = EXCLUDED.actualizado_en
            ");
            $up2->execute([json_encode(self::ahora()), self::firmaActor(), self::ahora()]);
            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
        self::$politicas = null;
        return $borrados;
    }

    /** Purga automática según la política de retención (máx. una vez cada 24 h). */
    public static function purgaAutomatica(): void {
        $ultima = (string) self::politica('ultima_purga');
        if ($ultima !== '' && strtotime($ultima) > time() - 86400) {
            return;
        }
        try {
            $n = self::purgar((int) self::politica('retencion_dias'));
            if ($n > 0) {
                self::registrar([
                    'severidad' => 'INFO', 'submodulo' => self::MODULO, 'accion' => 'retencion',
                    'resultado' => 'SISTEMA', 'detalles' => "Purga automática por retención: {$n} evento(s) anteriores a " . self::politica('retencion_dias') . ' días. Cadena re-anclada.',
                ]);
            }
        } catch (Throwable $e) {
            self::error($e);
        }
    }

    /* =====================================================================
     *  Detección de amenazas, alertas y bloqueos
     * ===================================================================== */

    private static function analizarAmenaza(array $ctx): void {
        $db = self::db();
        if (!$db || (int) $ctx['nivel'] === 0) {
            return;
        }
        $umbral  = max(1, (int) self::politica('umbral_denegaciones'));
        $ventana = max(1, (int) self::politica('ventana_minutos'));
        $desde   = self::ahora(-$ventana * 60);
        $ip      = (string) $ctx['ip'];

        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM seg_lineas_logs WHERE ip = ? AND resultado = 'DENEGADO' AND fecha >= ?");
            $stmt->execute([$ip, $desde]);
            $conteo = (int) $stmt->fetchColumn();
            if ($conteo < $umbral) {
                return;
            }

            $stmtA = $db->prepare("SELECT 1 FROM seg_lineas_alertas WHERE ip = ? AND tipo = 'RAFAGA_DENEGACIONES' AND estado = 'abierta' AND fecha >= ? LIMIT 1");
            $stmtA->execute([$ip, $desde]);
            if ($stmtA->fetchColumn()) {
                return;
            }

            $detalle = "{$conteo} accesos denegados desde {$ip} en {$ventana} min (umbral {$umbral}). Último actor: {$ctx['nombre']}.";
            self::crearAlerta('RAFAGA_DENEGACIONES', 'CRITICO', 'Ráfaga de accesos denegados', $detalle, $ip, $ctx['id']);

            $confiables = (array) self::politica('ips_confiables');
            if (self::politica('auto_bloqueo_ip') && !in_array($ip, $confiables, true)) {
                $min = max(1, (int) self::politica('duracion_bloqueo_min'));
                self::bloquearIp($ip, "Auto-bloqueo: {$detalle}", $min, 'auto', 'Motor de Seguridad');
                self::registrar([
                    'severidad' => 'CRITICO', 'submodulo' => self::MODULO, 'accion' => 'auto-bloqueo',
                    'resultado' => 'SISTEMA', 'detalles' => "IP {$ip} bloqueada automáticamente por {$min} min. {$detalle}",
                ], $ctx);
            } else {
                self::registrar([
                    'severidad' => 'CRITICO', 'submodulo' => self::MODULO, 'accion' => 'alerta',
                    'resultado' => 'SISTEMA', 'detalles' => "Alerta generada (sin auto-bloqueo). {$detalle}",
                ], $ctx);
            }
        } catch (Throwable $e) {
            self::error($e);
        }
    }

    public static function crearAlerta(string $tipo, string $severidad, string $titulo, string $detalle, string $ip = '', ?int $usuarioId = null): void {
        $db = self::db();
        if (!$db) {
            return;
        }
        $stmt = $db->prepare("
            INSERT INTO seg_lineas_alertas (fecha, tipo, severidad, titulo, detalle, ip, usuario_id, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'abierta')
        ");
        $stmt->execute([self::ahora(), mb_substr($tipo, 0, 40), $severidad, mb_substr($titulo, 0, 200), $detalle, mb_substr($ip, 0, 64), $usuarioId]);
    }

    /** @param int $minutos 0 = permanente */
    public static function bloquearIp(string $ip, string $motivo, int $minutos, string $origen = 'manual', ?string $autor = null): int {
        $db = self::db();
        if (!$db) {
            throw new RuntimeException('Motor de seguridad sin base de datos.');
        }
        $db->prepare("UPDATE seg_lineas_bloqueos SET activo = FALSE, levantado_por = ?, levantado_en = ? WHERE ip = ? AND activo = TRUE")
           ->execute(['Reemplazado por nuevo bloqueo', self::ahora(), $ip]);

        $stmt = $db->prepare("
            INSERT INTO seg_lineas_bloqueos (ip, motivo, origen, creado_en, expira_en, creado_por, activo)
            VALUES (?, ?, ?, ?, ?, ?, TRUE) RETURNING id
        ");
        $stmt->execute([
            $ip, $motivo, $origen, self::ahora(),
            $minutos > 0 ? self::ahora($minutos * 60) : null,
            $autor ?? self::firmaActor(),
        ]);
        unset(self::$bloqueoCache[$ip]);
        self::$evaluacionCache = [];
        return (int) $stmt->fetchColumn();
    }
}
