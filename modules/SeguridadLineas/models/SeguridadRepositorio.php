<?php
// modules/SeguridadLineas/models/SeguridadRepositorio.php

require_once __DIR__ . '/../services/SeguridadMotor.php';

/**
 * Consultas de lectura y escritura para las pantallas del módulo de Seguridad.
 * Las fechas se comparan siempre con valores calculados en PHP (zona America/Caracas)
 * para que coincidan con lo almacenado por el motor.
 */
class SeguridadRepositorio {

    private PDO $db;

    public function __construct() {
        $db = SeguridadMotor::db();
        if (!$db) {
            throw new RuntimeException('El motor de seguridad no pudo inicializar su esquema en la base de datos.');
        }
        $this->db = $db;
    }

    public function db(): PDO {
        return $this->db;
    }

    /* ── Indicadores ─────────────────────────────────────────────────── */

    public function kpis(): array {
        $h24  = SeguridadMotor::ahora(-86400);
        $m15  = SeguridadMotor::ahora(-900);
        $now  = SeguridadMotor::ahora();

        $s = $this->db->prepare("
            SELECT
                COUNT(*)                                                         AS eventos_24h,
                COUNT(*) FILTER (WHERE resultado = 'PERMITIDO')                  AS permitidos_24h,
                COUNT(*) FILTER (WHERE resultado IN ('DENEGADO', 'BLOQUEADO'))   AS denegados_24h,
                COUNT(*) FILTER (WHERE severidad IN ('ALERTA', 'CRITICO'))       AS criticos_24h,
                COUNT(DISTINCT ip)                                               AS ips_24h
            FROM seg_lineas_logs WHERE fecha >= ?
        ");
        $s->execute([$h24]);
        $k = $s->fetch() ?: [];

        $s = $this->db->prepare("SELECT COUNT(DISTINCT COALESCE(usuario_id::text, ip)) FROM seg_lineas_logs WHERE fecha >= ? AND resultado <> 'SISTEMA'");
        $s->execute([$m15]);
        $k['activos_15m'] = (int) $s->fetchColumn();

        $k['alertas_abiertas'] = (int) $this->db->query("SELECT COUNT(*) FROM seg_lineas_alertas WHERE estado = 'abierta'")->fetchColumn();

        $s = $this->db->prepare("SELECT COUNT(*) FROM seg_lineas_bloqueos WHERE activo = TRUE AND (expira_en IS NULL OR expira_en > ?)");
        $s->execute([$now]);
        $k['ips_bloqueadas'] = (int) $s->fetchColumn();

        $s = $this->db->prepare("SELECT COUNT(*) FROM seg_lineas_excepciones WHERE expira_en IS NULL OR expira_en > ?");
        $s->execute([$now]);
        $k['excepciones'] = (int) $s->fetchColumn();

        $k['total_eventos'] = (int) $this->db->query("SELECT COUNT(*) FROM seg_lineas_logs")->fetchColumn();
        $k['ultimo_id']     = (int) $this->db->query("SELECT COALESCE(MAX(id), 0) FROM seg_lineas_logs")->fetchColumn();

        foreach (['eventos_24h', 'permitidos_24h', 'denegados_24h', 'criticos_24h', 'ips_24h'] as $c) {
            $k[$c] = (int) ($k[$c] ?? 0);
        }
        $k['tasa_denegacion'] = $k['eventos_24h'] > 0 ? round($k['denegados_24h'] * 100 / $k['eventos_24h'], 1) : 0;

        // Nivel de amenaza (0-100) a partir de denegaciones, eventos críticos, alertas e IPs bloqueadas
        $score = min(100, (int) round($k['tasa_denegacion'] * 1.2 + $k['criticos_24h'] * 4 + $k['alertas_abiertas'] * 10 + $k['ips_bloqueadas'] * 6));
        $k['amenaza']       = $score;
        $k['amenaza_nivel'] = $score >= 70 ? 'CRITICO' : ($score >= 40 ? 'ELEVADO' : ($score >= 15 ? 'MODERADO' : 'BAJO'));
        return $k;
    }

    /** Serie por hora de las últimas $horas horas (incluye la actual). */
    public function serieHoras(int $horas = 24): array {
        $inicio = strtotime(date('Y-m-d H:00:00')) - ($horas - 1) * 3600;
        $s = $this->db->prepare("
            SELECT to_char(date_trunc('hour', fecha), 'YYYY-MM-DD HH24') AS h,
                   COUNT(*) FILTER (WHERE resultado = 'PERMITIDO')                AS permitidos,
                   COUNT(*) FILTER (WHERE resultado IN ('DENEGADO', 'BLOQUEADO')) AS denegados,
                   COUNT(*) FILTER (WHERE resultado IN ('ADMIN', 'SISTEMA'))      AS sistema
              FROM seg_lineas_logs
             WHERE fecha >= ?
             GROUP BY 1
        ");
        $s->execute([date('Y-m-d H:i:s', $inicio)]);
        $datos = [];
        foreach ($s->fetchAll() as $f) {
            $datos[$f['h']] = $f;
        }
        $serie = [];
        for ($i = 0; $i < $horas; $i++) {
            $t = $inicio + $i * 3600;
            $k = date('Y-m-d H', $t);
            $serie[] = [
                'hora'       => date('H:00', $t),
                'permitidos' => (int) ($datos[$k]['permitidos'] ?? 0),
                'denegados'  => (int) ($datos[$k]['denegados'] ?? 0),
                'sistema'    => (int) ($datos[$k]['sistema'] ?? 0),
            ];
        }
        return $serie;
    }

    public function porSubmodulo(): array {
        $s = $this->db->prepare("
            SELECT submodulo, COUNT(*) AS total,
                   COUNT(*) FILTER (WHERE resultado IN ('DENEGADO', 'BLOQUEADO')) AS denegados
              FROM seg_lineas_logs WHERE fecha >= ?
             GROUP BY submodulo ORDER BY total DESC
        ");
        $s->execute([SeguridadMotor::ahora(-86400)]);
        return $s->fetchAll();
    }

    public function topIpsDenegadas(int $limite = 6): array {
        $s = $this->db->prepare("
            SELECT ip, COUNT(*) AS total, MAX(fecha) AS ultima
              FROM seg_lineas_logs
             WHERE resultado IN ('DENEGADO', 'BLOQUEADO') AND fecha >= ?
             GROUP BY ip ORDER BY total DESC LIMIT ?
        ");
        $s->execute([SeguridadMotor::ahora(-86400), $limite]);
        return $s->fetchAll();
    }

    public function usuariosActivos(int $minutos = 15): array {
        $s = $this->db->prepare("
            SELECT usuario_id, MAX(usuario_nombre) AS nombre, MAX(nivel) AS nivel, MAX(ip) AS ip,
                   MAX(fecha) AS ultima, COUNT(*) AS eventos,
                   COUNT(*) FILTER (WHERE resultado IN ('DENEGADO', 'BLOQUEADO')) AS denegados
              FROM seg_lineas_logs
             WHERE fecha >= ? AND usuario_id IS NOT NULL
             GROUP BY usuario_id ORDER BY ultima DESC LIMIT 25
        ");
        $s->execute([SeguridadMotor::ahora(-$minutos * 60)]);
        return $s->fetchAll();
    }

    public function ultimosEventos(int $limite = 40, int $desdeId = 0): array {
        $s = $this->db->prepare("
            SELECT id, fecha, severidad, submodulo, accion, ruta, resultado, usuario_id, usuario_nombre, nivel, ip, metodo, detalles
              FROM seg_lineas_logs WHERE id > ?
             ORDER BY id DESC LIMIT ?
        ");
        $s->execute([$desdeId, $limite]);
        return $s->fetchAll();
    }

    /* ── Bitácora ────────────────────────────────────────────────────── */

    private function filtrosSql(array $f): array {
        $where = [];
        $params = [];
        if (!empty($f['q'])) {
            $where[] = "(detalles ILIKE ? OR ruta ILIKE ? OR usuario_nombre ILIKE ? OR ip ILIKE ? OR accion ILIKE ?)";
            $like = '%' . $f['q'] . '%';
            array_push($params, $like, $like, $like, $like, $like);
        }
        foreach (['severidad', 'resultado', 'submodulo', 'accion'] as $campo) {
            if (!empty($f[$campo])) {
                $where[] = "{$campo} = ?";
                $params[] = $f[$campo];
            }
        }
        if (!empty($f['ip'])) {
            $where[] = "ip = ?";
            $params[] = $f['ip'];
        }
        if (!empty($f['usuario_id'])) {
            $where[] = "usuario_id = ?";
            $params[] = (int) $f['usuario_id'];
        }
        if (!empty($f['desde'])) {
            $where[] = "fecha >= ?";
            $params[] = $f['desde'] . ' 00:00:00';
        }
        if (!empty($f['hasta'])) {
            $where[] = "fecha <= ?";
            $params[] = $f['hasta'] . ' 23:59:59';
        }
        return [$where ? 'WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public function buscarLogs(array $filtros, int $pagina, int $porPagina): array {
        [$where, $params] = $this->filtrosSql($filtros);
        $s = $this->db->prepare("SELECT COUNT(*) FROM seg_lineas_logs {$where}");
        $s->execute($params);
        $total = (int) $s->fetchColumn();

        $offset = max(0, ($pagina - 1) * $porPagina);
        $s = $this->db->prepare("
            SELECT id, fecha, severidad, submodulo, accion, ruta, resultado, usuario_id, usuario_nombre, nivel, ip, metodo, detalles
              FROM seg_lineas_logs {$where}
             ORDER BY id DESC LIMIT {$porPagina} OFFSET {$offset}
        ");
        $s->execute($params);
        return ['filas' => $s->fetchAll(), 'total' => $total];
    }

    public function iterarLogs(array $filtros): Generator {
        [$where, $params] = $this->filtrosSql($filtros);
        $s = $this->db->prepare("SELECT * FROM seg_lineas_logs {$where} ORDER BY id DESC LIMIT 50000");
        $s->execute($params);
        while ($f = $s->fetch()) {
            yield $f;
        }
    }

    public function evento(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM seg_lineas_logs WHERE id = ?");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    /* ── Alertas y bloqueos ─────────────────────────────────────────── */

    public function alertas(?string $estado = null, int $limite = 100): array {
        $sql = "SELECT * FROM seg_lineas_alertas" . ($estado ? " WHERE estado = ?" : "") . " ORDER BY (estado = 'abierta') DESC, id DESC LIMIT {$limite}";
        $s = $this->db->prepare($sql);
        $s->execute($estado ? [$estado] : []);
        return $s->fetchAll();
    }

    public function atenderAlertas(?int $id): int {
        $autor = SeguridadMotor::firmaActor();
        if ($id) {
            $s = $this->db->prepare("UPDATE seg_lineas_alertas SET estado = 'atendida', atendida_por = ?, atendida_en = ? WHERE id = ? AND estado = 'abierta'");
            $s->execute([$autor, SeguridadMotor::ahora(), $id]);
        } else {
            $s = $this->db->prepare("UPDATE seg_lineas_alertas SET estado = 'atendida', atendida_por = ?, atendida_en = ? WHERE estado = 'abierta'");
            $s->execute([$autor, SeguridadMotor::ahora()]);
        }
        return $s->rowCount();
    }

    public function bloqueosActivos(): array {
        $s = $this->db->prepare("
            SELECT b.*,
                   (SELECT COUNT(*) FROM seg_lineas_logs l WHERE l.ip = b.ip AND l.resultado = 'BLOQUEADO' AND l.fecha >= b.creado_en) AS intentos
              FROM seg_lineas_bloqueos b
             WHERE b.activo = TRUE AND (b.expira_en IS NULL OR b.expira_en > ?)
             ORDER BY b.id DESC
        ");
        $s->execute([SeguridadMotor::ahora()]);
        return $s->fetchAll();
    }

    public function historialBloqueos(int $limite = 30): array {
        $s = $this->db->prepare("
            SELECT * FROM seg_lineas_bloqueos
             WHERE NOT (activo = TRUE AND (expira_en IS NULL OR expira_en > ?))
             ORDER BY id DESC LIMIT {$limite}
        ");
        $s->execute([SeguridadMotor::ahora()]);
        return $s->fetchAll();
    }

    public function desbloquear(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM seg_lineas_bloqueos WHERE id = ?");
        $s->execute([$id]);
        $b = $s->fetch();
        if (!$b) {
            return null;
        }
        $this->db->prepare("UPDATE seg_lineas_bloqueos SET activo = FALSE, levantado_por = ?, levantado_en = ? WHERE id = ?")
                 ->execute([SeguridadMotor::firmaActor(), SeguridadMotor::ahora(), $id]);
        return $b;
    }

    /* ── Permisos ────────────────────────────────────────────────────── */

    /** Niveles de privilegio existentes + visitante, con los roles y usuarios asociados. */
    public function niveles(): array {
        $niveles = [];
        try {
            $s = $this->db->query("
                SELECT p.nivel_privilegio AS nivel,
                       COALESCE(string_agg(DISTINCT r.nombre, ', '), '') AS roles,
                       COUNT(DISTINCT u.id) AS usuarios
                  FROM privilegios p
                  LEFT JOIN roles r    ON r.privilegio_id = p.privilegio_id
                  LEFT JOIN usuarios u ON u.id_rol = r.id
                 GROUP BY p.nivel_privilegio
                 ORDER BY p.nivel_privilegio ASC
            ");
            foreach ($s->fetchAll() as $f) {
                $niveles[(int) $f['nivel']] = ['nivel' => (int) $f['nivel'], 'roles' => $f['roles'], 'usuarios' => (int) $f['usuarios']];
            }
        } catch (Throwable $e) {
            SeguridadMotor::error($e);
        }
        if (!isset($niveles[0])) {
            $niveles = [0 => ['nivel' => 0, 'roles' => 'SuperAdmin', 'usuarios' => 0]] + $niveles;
        }
        $niveles[SeguridadMotor::NIVEL_VISITANTE] = ['nivel' => SeguridadMotor::NIVEL_VISITANTE, 'roles' => 'Visitante sin sesión', 'usuarios' => 0];
        return $niveles;
    }

    /** @return array nivel => submodulo => accion => bool (solo filas explícitas) */
    public function matrizExplicita(): array {
        $m = [];
        foreach ($this->db->query("SELECT nivel, submodulo, accion, permitido FROM seg_lineas_permisos") as $f) {
            $m[(int) $f['nivel']][$f['submodulo']][$f['accion']] = (bool) $f['permitido'];
        }
        return $m;
    }

    public function guardarMatriz(array $celdas): int {
        $autor = SeguridadMotor::firmaActor();
        $ahora = SeguridadMotor::ahora();
        $stmt = $this->db->prepare("
            INSERT INTO seg_lineas_permisos (nivel, submodulo, accion, permitido, actualizado_por, actualizado_en)
            VALUES (?, ?, ?, ?, ?, ?)
            ON CONFLICT (nivel, submodulo, accion) DO UPDATE
               SET permitido = EXCLUDED.permitido, actualizado_por = EXCLUDED.actualizado_por, actualizado_en = EXCLUDED.actualizado_en
        ");
        $n = 0;
        $this->db->beginTransaction();
        try {
            foreach ($celdas as $c) {
                $stmt->execute([(int) $c['nivel'], $c['submodulo'], $c['accion'], $c['permitido'] ? 'true' : 'false', $autor, $ahora]);
                $n++;
            }
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
        return $n;
    }

    public function restablecerMatriz(): int {
        return $this->db->exec("DELETE FROM seg_lineas_permisos");
    }

    public function excepciones(): array {
        $s = $this->db->prepare("
            SELECT e.*, u.nombre_completo, u.cedula, p.nivel_privilegio,
                   (e.expira_en IS NOT NULL AND e.expira_en <= ?) AS expirada
              FROM seg_lineas_excepciones e
              LEFT JOIN usuarios u    ON u.id = e.usuario_id
              LEFT JOIN roles r       ON r.id = u.id_rol
              LEFT JOIN privilegios p ON p.privilegio_id = r.privilegio_id
             ORDER BY expirada ASC, e.id DESC
        ");
        $s->execute([SeguridadMotor::ahora()]);
        return $s->fetchAll();
    }

    public function crearExcepcion(int $usuarioId, string $sub, string $acc, string $efecto, string $motivo, ?string $expira): int {
        $s = $this->db->prepare("
            INSERT INTO seg_lineas_excepciones (usuario_id, submodulo, accion, efecto, motivo, expira_en, creado_por, creado_en)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?) RETURNING id
        ");
        $s->execute([$usuarioId, $sub, $acc, $efecto, $motivo, $expira, SeguridadMotor::firmaActor(), SeguridadMotor::ahora()]);
        return (int) $s->fetchColumn();
    }

    public function eliminarExcepcion(int $id): ?array {
        $s = $this->db->prepare("DELETE FROM seg_lineas_excepciones WHERE id = ? RETURNING *");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function usuario(int $id): ?array {
        $s = $this->db->prepare("
            SELECT u.id, u.cedula, u.nombre_completo, u.email, u.activo, r.nombre AS rol, p.nivel_privilegio
              FROM usuarios u
              JOIN roles r       ON r.id = u.id_rol
              JOIN privilegios p ON p.privilegio_id = r.privilegio_id
             WHERE u.id = ?
        ");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function buscarUsuarios(string $q): array {
        $like = '%' . $q . '%';
        $s = $this->db->prepare("
            SELECT u.id, u.cedula, u.nombre_completo, u.email, r.nombre AS rol, p.nivel_privilegio
              FROM usuarios u
              JOIN roles r       ON r.id = u.id_rol
              JOIN privilegios p ON p.privilegio_id = r.privilegio_id
             WHERE (u.cedula ILIKE ? OR u.nombre_completo ILIKE ? OR u.email ILIKE ?)
               AND u.cedula NOT LIKE '%\\_x%' AND u.email NOT LIKE '%\\_deleted\\_%'
             ORDER BY u.nombre_completo ASC LIMIT 10
        ");
        $s->execute([$like, $like, $like]);
        return $s->fetchAll();
    }

    /* ── Rutas ───────────────────────────────────────────────────────── */

    public function guardarRuta(string $ruta, ?string $accion): void {
        if ($accion === null) {
            $this->db->prepare("DELETE FROM seg_lineas_rutas WHERE ruta = ?")->execute([$ruta]);
            return;
        }
        $this->db->prepare("
            INSERT INTO seg_lineas_rutas (ruta, accion, actualizado_por, actualizado_en) VALUES (?, ?, ?, ?)
            ON CONFLICT (ruta) DO UPDATE SET accion = EXCLUDED.accion, actualizado_por = EXCLUDED.actualizado_por, actualizado_en = EXCLUDED.actualizado_en
        ")->execute([$ruta, $accion, SeguridadMotor::firmaActor(), SeguridadMotor::ahora()]);
    }

    /** Estadística de uso por ruta (últimos 7 días). */
    public function usoRutas(): array {
        $s = $this->db->prepare("
            SELECT ruta, COUNT(*) AS total, COUNT(*) FILTER (WHERE resultado IN ('DENEGADO', 'BLOQUEADO')) AS denegados
              FROM seg_lineas_logs WHERE fecha >= ? GROUP BY ruta
        ");
        $s->execute([SeguridadMotor::ahora(-7 * 86400)]);
        $r = [];
        foreach ($s->fetchAll() as $f) {
            $r[$f['ruta']] = $f;
        }
        return $r;
    }
}
