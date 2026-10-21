<?php
// modules/Autenticacion/services/PerfilPSTService.php
//
// Servicio compartido para el perfil de Profesor PST (Proyecto Sociotecnológico)
// y el catálogo dinámico de Trayectos / Niveles Académicos (Pregrado y Postgrado).
// Se ubica en Autenticacion (módulo de infraestructura, siempre presente) porque lo
// usan tanto el registro en línea de docentes, el módulo de perfil docente/investigador
// y Vinculación Empresarial.

require_once CORE_PATH . 'Database/Connection.php';

class PerfilPSTService {

    public const TRIMESTRES = [1, 2, 3];
    public const CLAVE_CONFIG_TRAYECTOS = 'catalogo_trayectos_pst';
    private const ROMANOS = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
        6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X'
    ];

    private static ?array $cacheDetallado = null;

    /**
     * Catálogo base del sistema (Trayectos I al IV).
     */
    public static function catalogoPorDefecto(): array {
        return [
            1 => [
                'numero'      => 1,
                'nombre'      => 'Trayecto I',
                'descripcion' => 'Primer año / Iniciación sociotecnológica',
                'codigo_inv'  => 't1',
                'sistema'     => true
            ],
            2 => [
                'numero'      => 2,
                'nombre'      => 'Trayecto II',
                'descripcion' => 'Segundo año / Desarrollo y aplicaciones intermedias',
                'codigo_inv'  => 't2',
                'sistema'     => true
            ],
            3 => [
                'numero'      => 3,
                'nombre'      => 'Trayecto III',
                'descripcion' => 'Tercer año / Soluciones avanzadas e interoperabilidad',
                'codigo_inv'  => 't3',
                'sistema'     => true
            ],
            4 => [
                'numero'      => 4,
                'nombre'      => 'Trayecto IV',
                'descripcion' => 'Cuarto año / Proyectos integrales de grado',
                'codigo_inv'  => 't4',
                'sistema'     => true
            ]
        ];
    }

    /**
     * Ruta al archivo config_vinculacion.json donde se persiste el catálogo dinámico.
     */
    private static function getRutaConfigVinculacion(): string {
        $base = defined('MODULES_PATH') ? MODULES_PATH : dirname(__DIR__, 2) . '/';
        return rtrim($base, '/\\') . '/VinculacionEmpresarial/config_vinculacion.json';
    }

    /**
     * Catálogo detallado de trayectos y niveles académicos (incluyendo opciones adicionales
     * configuradas desde Administración como Trayecto V, Especialidad, Maestría, Doctorado, etc.).
     * Retorna arreglo indexado por 'numero': [numero => ['numero', 'nombre', 'descripcion', 'codigo_inv', 'sistema']]
     */
    public static function catalogoTrayectosDetallado(bool $forzarRecarga = false): array {
        if (self::$cacheDetallado !== null && !$forzarRecarga) {
            return self::$cacheDetallado;
        }

        $catalogo = self::catalogoPorDefecto();

        // 1. Intentar sincronizar nombres base 1..4 desde public.trayectos si existen
        try {
            $db = Connection::getInstance();
            $rows = $db->query("SELECT DISTINCT ON (numero) numero, nombre
                                FROM trayectos
                                WHERE activo = true AND numero BETWEEN 1 AND 4
                                ORDER BY numero, id_carrera")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                $num = (int)$r['numero'];
                if (isset($catalogo[$num]) && !empty($r['nombre'])) {
                    $catalogo[$num]['nombre'] = $r['nombre'];
                }
            }
        } catch (Throwable $e) {
            // Usar respaldo estático
        }

        // 2. Cargar opciones configuradas en config_vinculacion.json
        $rutaJson = self::getRutaConfigVinculacion();
        if (file_exists($rutaJson)) {
            $json = @json_decode((string)@file_get_contents($rutaJson), true);
            if (is_array($json) && !empty($json[self::CLAVE_CONFIG_TRAYECTOS]) && is_array($json[self::CLAVE_CONFIG_TRAYECTOS])) {
                foreach ($json[self::CLAVE_CONFIG_TRAYECTOS] as $item) {
                    if (!is_array($item)) continue;
                    $num = (int)($item['numero'] ?? 0);
                    $nombre = trim((string)($item['nombre'] ?? ''));
                    $desc = trim((string)($item['descripcion'] ?? ''));
                    if ($num < 1 || $num > 99 || $nombre === '') continue;

                    $esSistema = ($num >= 1 && $num <= 4);
                    $slug = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre) ?: $nombre)));
                    $codigoInv = $esSistema ? ('t' . $num) : (!empty($item['codigo_inv']) ? (string)$item['codigo_inv'] : ($slug ?: 'nivel_' . $num));

                    $catalogo[$num] = [
                        'numero'      => $num,
                        'nombre'      => $nombre,
                        'descripcion' => $desc !== '' ? $desc : ($catalogo[$num]['descripcion'] ?? 'Nivel académico / postgrado habilitado'),
                        'codigo_inv'  => $codigoInv,
                        'sistema'     => $esSistema
                    ];
                }
            }
        }

        ksort($catalogo);
        return self::$cacheDetallado = $catalogo;
    }

    /**
     * Catálogo simple de trayectos/niveles: retorna [numero => nombre].
     */
    public static function catalogoTrayectos(): array {
        $detallado = self::catalogoTrayectosDetallado();
        $out = [];
        foreach ($detallado as $num => $info) {
            $out[(int)$num] = $info['nombre'];
        }
        return $out;
    }

    /**
     * Retorna [numero => descripcion] para vistas como selector_pst.php.
     */
    public static function descripcionesTrayectos(): array {
        $detallado = self::catalogoTrayectosDetallado();
        $out = [];
        foreach ($detallado as $num => $info) {
            $out[(int)$num] = $info['descripcion'] ?? '';
        }
        return $out;
    }

    /**
     * Sanea y normaliza una lista de trayectos/niveles enviada desde el panel de Configuración.
     * Garantiza que los trayectos 1..4 de sistema permanezcan siempre presentes.
     */
    public static function sanearCatalogoTrayectos(array $recibidos): array {
        $base = self::catalogoPorDefecto();
        $resultado = [];

        // Primero asegurar 1..4
        foreach ($base as $num => $def) {
            $resultado[$num] = $def;
        }

        $maxNum = 4;
        $pendientesSinNumero = [];

        foreach ($recibidos as $raw) {
            if (!is_array($raw)) continue;
            $nombre = mb_substr(trim((string)($raw['nombre'] ?? '')), 0, 80);
            $desc   = mb_substr(trim((string)($raw['descripcion'] ?? '')), 0, 180);
            if ($nombre === '') continue;

            $num = isset($raw['numero']) && is_numeric($raw['numero']) ? (int)$raw['numero'] : 0;

            if ($num >= 1 && $num <= 4) {
                $resultado[$num]['nombre'] = $nombre;
                if ($desc !== '') {
                    $resultado[$num]['descripcion'] = $desc;
                }
            } elseif ($num >= 5 && $num <= 99 && !isset($resultado[$num])) {
                $slug = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre) ?: $nombre)));
                $resultado[$num] = [
                    'numero'      => $num,
                    'nombre'      => $nombre,
                    'descripcion' => $desc !== '' ? $desc : 'Nivel académico / postgrado habilitado',
                    'codigo_inv'  => $slug ?: ('nivel_' . $num),
                    'sistema'     => false
                ];
                if ($num > $maxNum) $maxNum = $num;
            } else {
                $pendientesSinNumero[] = ['nombre' => $nombre, 'descripcion' => $desc];
            }
        }

        foreach ($pendientesSinNumero as $item) {
            $maxNum++;
            if ($maxNum > 99) break;
            $slug = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $item['nombre']) ?: $item['nombre'])));
            $resultado[$maxNum] = [
                'numero'      => $maxNum,
                'nombre'      => $item['nombre'],
                'descripcion' => $item['descripcion'] !== '' ? $item['descripcion'] : 'Nivel académico / postgrado habilitado',
                'codigo_inv'  => $slug ?: ('nivel_' . $maxNum),
                'sistema'     => false
            ];
        }

        ksort($resultado);
        self::$cacheDetallado = null;
        return array_values($resultado);
    }

    /** Catálogo de trimestres académicos: [numero => nombre]. */
    public static function catalogoTrimestres(): array {
        $out = [];
        foreach (self::TRIMESTRES as $t) {
            $out[$t] = 'Trimestre ' . $t;
        }
        return $out;
    }

    public static function nombreTrayecto(?int $numero): string {
        if (!$numero) return 'Sin trayecto';
        $cat = self::catalogoTrayectos();
        if (isset($cat[$numero])) {
            return $cat[$numero];
        }
        return 'Trayecto ' . (self::ROMANOS[$numero] ?? $numero);
    }

    public static function trayectoValido($v): bool {
        if (!is_numeric($v)) return false;
        $n = (int)$v;
        if ($n < 1 || $n > 99) return false;
        $cat = self::catalogoTrayectos();
        return isset($cat[$n]) || ($n >= 1 && $n <= 4);
    }

    public static function trimestreValido($v): bool {
        return is_numeric($v) && in_array((int)$v, self::TRIMESTRES, true);
    }

    /**
     * Convierte un número de trayecto desde un texto tipo 'Trayecto II', 'Trayecto V', 'Maestría', etc.
     */
    public static function numeroDesdeTexto(?string $texto): ?int {
        if (!$texto) return null;
        $textoTrim = trim($texto);

        // 1. Buscar coincidencia directa o parcial en el catálogo configurado
        foreach (self::catalogoTrayectos() as $num => $nombre) {
            if (mb_strtolower($nombre) === mb_strtolower($textoTrim)) {
                return (int)$num;
            }
        }
        foreach (self::catalogoTrayectos() as $num => $nombre) {
            if (stripos($textoTrim, $nombre) !== false || stripos($nombre, $textoTrim) !== false) {
                return (int)$num;
            }
        }

        // 2. Números romanos (X..I)
        if (preg_match('/\b(X|IX|VIII|VII|VI|V|IV|III|II|I)\b/i', $textoTrim, $m)) {
            return array_search(strtoupper($m[1]), self::ROMANOS, true) ?: null;
        }
        // 3. Dígitos
        if (preg_match('/\b([1-9][0-9]?)\b/', $textoTrim, $m)) {
            return (int)$m[1];
        }
        return null;
    }

    /**
     * Normaliza arreglo de trayectos recibido (enteros 1..99, o formato 'trayecto-trimestre').
     * Retorna array único de números de trayecto [1, 2, ...].
     */
    public static function parsearTrayectos($raw): array {
        if (!is_array($raw)) return [];
        $trayectos = [];
        foreach ($raw as $v) {
            if (is_numeric($v) && (int)$v >= 1 && (int)$v <= 99) {
                $trayectos[(int)$v] = (int)$v;
            } elseif (is_string($v) && preg_match('/^([1-9][0-9]?)(-[1-3])?$/', trim($v), $m)) {
                $trayectos[(int)$m[1]] = (int)$m[1];
            }
        }
        ksort($trayectos);
        return array_values($trayectos);
    }

    /**
     * Normaliza para el almacenamiento en profesores_pst.
     * Retorna lista de ['trayecto' => int, 'trimestre' => int].
     */
    public static function parsearAsignaciones($raw): array {
        $trayectos = self::parsearTrayectos($raw);
        $out = [];
        foreach ($trayectos as $t) {
            $out[] = ['trayecto' => $t, 'trimestre' => 1];
        }
        return $out;
    }

    /** Retorna los trayectos activos de un profesor como array de enteros [1, 2, ...]. */
    public static function obtenerTrayectos(int $idProfesor): array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT DISTINCT trayecto FROM profesores_pst WHERE id_profesor = ? ORDER BY trayecto");
        $stmt->execute([$idProfesor]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /** Compatibilidad legacy: retorna como claves tipo "1-1" o "1". */
    public static function obtenerClavesAsignacion(int $idProfesor): array {
        return self::obtenerTrayectos($idProfesor);
    }

    public static function esProfesorPST(int $idUsuario): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT es_profesor_pst FROM usuarios WHERE id = ?");
        $stmt->execute([$idUsuario]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Guarda la marca de profesor PST y reemplaza sus trayectos.
     * Si $esPst es false, se eliminan los trayectos.
     */
    public static function guardarPerfil(int $idUsuario, bool $esPst, array $trayectos, ?PDO $db = null): bool {
        $db = $db ?? Connection::getInstance();
        $propiaTransaccion = !$db->inTransaction();
        if ($propiaTransaccion) $db->beginTransaction();
        try {
            $db->prepare("UPDATE usuarios SET es_profesor_pst = ? WHERE id = ?")
               ->execute([$esPst ? 'true' : 'false', $idUsuario]);
            $db->prepare("DELETE FROM profesores_pst WHERE id_profesor = ?")->execute([$idUsuario]);
            if ($esPst) {
                $ins = $db->prepare("INSERT INTO profesores_pst (id_profesor, trayecto, trimestre) VALUES (?, ?, 1) ON CONFLICT DO NOTHING");
                $trList = self::parsearTrayectos($trayectos);
                foreach ($trList as $t) {
                    $ins->execute([$idUsuario, $t]);
                }
            }
            if ($propiaTransaccion) $db->commit();
            return true;
        } catch (Throwable $e) {
            if ($propiaTransaccion && $db->inTransaction()) $db->rollBack();
            error_log('PerfilPSTService::guardarPerfil -> ' . $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // FLUJO DE SOLICITUDES DE ACREDITACIÓN DOCENTE PST
    // -------------------------------------------------------------------------

    /**
     * Crea una solicitud de acreditación o actualización PST desde el perfil del profesor.
     */
    public static function crearSolicitud(int $idProfesor, bool $esPst, array $trayectos): bool {
        try {
            $db = Connection::getInstance();
            $trList = self::parsearTrayectos($trayectos);
            
            // Si ya tiene una solicitud pendiente, la actualizamos
            $stmtCheck = $db->prepare("SELECT id FROM solicitudes_docentes_pst WHERE id_profesor = ? AND estado = 'pendiente'");
            $stmtCheck->execute([$idProfesor]);
            $existenteId = $stmtCheck->fetchColumn();

            if ($existenteId) {
                $stmt = $db->prepare("UPDATE solicitudes_docentes_pst 
                                      SET es_profesor_pst = ?, trayectos = ?::jsonb, fecha_solicitud = CURRENT_TIMESTAMP
                                      WHERE id = ?");
                return $stmt->execute([$esPst ? 'true' : 'false', json_encode($trList), $existenteId]);
            } else {
                $stmt = $db->prepare("INSERT INTO solicitudes_docentes_pst (id_profesor, es_profesor_pst, trayectos, estado) 
                                      VALUES (?, ?, ?::jsonb, 'pendiente')");
                return $stmt->execute([$idProfesor, $esPst ? 'true' : 'false', json_encode($trList)]);
            }
        } catch (Throwable $e) {
            error_log('PerfilPSTService::crearSolicitud -> ' . $e->getMessage());
            return false;
        }
    }

    /** Retorna la solicitud pendiente del profesor si existe. */
    public static function obtenerSolicitudPendiente(int $idProfesor): ?array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM solicitudes_docentes_pst WHERE id_profesor = ? AND estado = 'pendiente' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$idProfesor]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $res['trayectos'] = json_decode($res['trayectos'] ?? '[]', true) ?: [];
        }
        return $res ?: null;
    }

    /** Retorna la última solicitud del profesor (cualquier estado). */
    public static function obtenerUltimaSolicitud(int $idProfesor): ?array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM solicitudes_docentes_pst WHERE id_profesor = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$idProfesor]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $res['trayectos'] = json_decode($res['trayectos'] ?? '[]', true) ?: [];
        }
        return $res ?: null;
    }

    /**
     * Retorna todas las solicitudes pendientes para el panel de Acreditación Docentes PST.
     */
    public static function obtenerSolicitudesPendientes(): array {
        $db = Connection::getInstance();
        $sql = "SELECT s.*, u.nombre_completo, u.cedula, u.email
                FROM solicitudes_docentes_pst s
                JOIN usuarios u ON s.id_profesor = u.id
                WHERE s.estado = 'pendiente'
                ORDER BY s.fecha_solicitud ASC";
        $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$r) {
            $r['trayectos'] = json_decode($r['trayectos'] ?? '[]', true) ?: [];
        }
        return $rows;
    }

    /**
     * Resuelve (aprueba o rechaza) una solicitud de acreditación PST.
     */
    public static function resolverSolicitud(int $idSolicitud, bool $aprobar, ?string $motivo, int $idRevisor): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM solicitudes_docentes_pst WHERE id = ? AND estado = 'pendiente'");
        $stmt->execute([$idSolicitud]);
        $sol = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$sol) return false;

        $trayectos = json_decode($sol['trayectos'] ?? '[]', true) ?: [];
        $estado = $aprobar ? 'aprobado' : 'rechazado';

        $db->beginTransaction();
        try {
            $up = $db->prepare("UPDATE solicitudes_docentes_pst 
                                SET estado = ?, motivo = ?, fecha_resolucion = CURRENT_TIMESTAMP, id_revisor = ? 
                                WHERE id = ?");
            $up->execute([$estado, $motivo, $idRevisor, $idSolicitud]);

            if ($aprobar) {
                self::guardarPerfil((int)$sol['id_profesor'], (bool)$sol['es_profesor_pst'], $trayectos, $db);
            }

            $db->commit();
            return true;
        } catch (Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('PerfilPSTService::resolverSolicitud -> ' . $e->getMessage());
            return false;
        }
    }

    /** Busca dinámicamente el ID del rol docente/profesor. */
    public static function idRolDocente(): int {
        $db = Connection::getInstance();
        foreach ($db->query("SELECT id, nombre FROM roles ORDER BY id")->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $nom = mb_strtolower($r['nombre']);
            if (str_contains($nom, 'docente') || str_contains($nom, 'profesor')) {
                return (int)$r['id'];
            }
        }
        return 4; // Rol 'Docentes' por defecto
    }
}
