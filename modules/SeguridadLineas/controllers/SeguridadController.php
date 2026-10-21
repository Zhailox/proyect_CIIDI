<?php
// modules/SeguridadLineas/controllers/SeguridadController.php

require_once CORE_PATH . 'Security/Auth.php';
require_once CORE_PATH . 'Security/CSRF.php';
require_once __DIR__ . '/../services/SeguridadMotor.php';
require_once __DIR__ . '/../models/SeguridadRepositorio.php';

class SeguridadController {

    private ?SeguridadRepositorio $repo = null;

    /* =====================================================================
     *  Utilidades
     * ===================================================================== */

    private function repo(): SeguridadRepositorio {
        return $this->repo ??= new SeguridadRepositorio();
    }

    private function json(array $data, int $status = 200): bool {
        if (!headers_sent()) {
            http_response_code($status);
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-store');
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        return false; // El Dispatcher termina la ejecución
    }

    private function ok(string $mensaje, array $extra = []): bool {
        return $this->json(array_merge(['status' => 'success', 'success' => true, 'message' => $mensaje], $extra));
    }

    private function fallo(string $mensaje, int $status = 400): bool {
        return $this->json(['status' => 'error', 'success' => false, 'message' => $mensaje], $status);
    }

    /**
     * Defensa en profundidad: aunque el guardián del core ya validó la ruta, cada acción
     * vuelve a comprobar el permiso y, si muta datos, el método POST y el token CSRF.
     */
    private function exigir(string $accion, bool $mutante = false): ?bool {
        if (!SeguridadMotor::disponible()) {
            return $this->fallo('El motor de seguridad no está disponible (sin base de datos).', 503);
        }
        if (!SeguridadMotor::puede($accion)) {
            return $this->fallo('Permiso insuficiente: se requiere «' . (SeguridadMotor::ACCIONES[$accion]['etiqueta'] ?? $accion) . '» en Seguridad.', 403);
        }
        if ($mutante) {
            if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
                return $this->fallo('Método no permitido.', 405);
            }
            $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
            if (!CSRF::validarToken($token)) {
                SeguridadMotor::registrar([
                    'severidad' => 'ALERTA', 'submodulo' => SeguridadMotor::MODULO, 'accion' => 'csrf',
                    'resultado' => 'DENEGADO', 'detalles' => 'Token CSRF inválido o ausente en una operación administrativa.',
                ]);
                return $this->fallo('Token CSRF inválido. Recarga la página.', 419);
            }
        }
        return null;
    }

    /** Datos comunes para la cabecera de todas las páginas. */
    private function comun(string $pagina): array {
        $ctx = SeguridadMotor::contexto();
        return [
            'slPagina'    => $pagina,
            'slCtx'       => $ctx,
            'slPol'       => SeguridadMotor::politicas(),
            'slCsrf'      => CSRF::generarToken(),
            'slPuede'     => [
                'ver'        => SeguridadMotor::puede('ver'),
                'exportar'   => SeguridadMotor::puede('exportar'),
                'configurar' => SeguridadMotor::puede('configurar'),
            ],
            'slSubmodulos' => SeguridadMotor::submodulos(),
            'slAcciones'   => SeguridadMotor::ACCIONES,
        ];
    }

    private function vistaError(Throwable $e, string $pagina): array {
        SeguridadMotor::error($e);
        return $this->comun($pagina) + ['slError' => $e->getMessage()];
    }

    private function filtrosBitacora(array $src): array {
        $f = [
            'q'          => trim((string) ($src['q'] ?? '')),
            'severidad'  => strtoupper(trim((string) ($src['severidad'] ?? ''))),
            'resultado'  => strtoupper(trim((string) ($src['resultado'] ?? ''))),
            'submodulo'  => trim((string) ($src['submodulo'] ?? '')),
            'accion'     => trim((string) ($src['accion'] ?? '')),
            'ip'         => trim((string) ($src['ip'] ?? '')),
            'usuario_id' => (int) ($src['usuario_id'] ?? 0) ?: '',
            'desde'      => trim((string) ($src['desde'] ?? '')),
            'hasta'      => trim((string) ($src['hasta'] ?? '')),
        ];
        if (!in_array($f['severidad'], SeguridadMotor::SEVERIDADES, true)) $f['severidad'] = '';
        if (!in_array($f['resultado'], SeguridadMotor::RESULTADOS, true)) $f['resultado'] = '';
        foreach (['desde', 'hasta'] as $d) {
            if ($f[$d] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $f[$d])) $f[$d] = '';
        }
        $f['q'] = mb_substr($f['q'], 0, 120);
        return $f;
    }

    /* =====================================================================
     *  Páginas
     * ===================================================================== */

    public function soc() {
        try {
            SeguridadMotor::purgaAutomatica();
            $repo = $this->repo();
            return $this->comun('soc') + [
                'kpis'         => $repo->kpis(),
                'serie'        => $repo->serieHoras(24),
                'porSubmodulo' => $repo->porSubmodulo(),
                'topIps'       => $repo->topIpsDenegadas(),
                'activos'      => $repo->usuariosActivos(15),
                'eventos'      => $repo->ultimosEventos(40),
                'alertas'      => $repo->alertas('abierta', 6),
            ];
        } catch (Throwable $e) {
            return $this->vistaError($e, 'soc');
        }
    }

    public function bitacora() {
        try {
            $filtros   = $this->filtrosBitacora($_GET);
            $pagina    = max(1, (int) ($_GET['p'] ?? 1));
            $porPagina = 25;
            $res       = $this->repo()->buscarLogs($filtros, $pagina, $porPagina);
            return $this->comun('bitacora') + [
                'filtros'   => $filtros,
                'logs'      => $res['filas'],
                'total'     => $res['total'],
                'pagina'    => $pagina,
                'paginas'   => max(1, (int) ceil($res['total'] / $porPagina)),
                'porPagina' => $porPagina,
            ];
        } catch (Throwable $e) {
            return $this->vistaError($e, 'bitacora');
        }
    }

    public function permisos() {
        try {
            $repo = $this->repo();
            $subs = SeguridadMotor::submodulos();

            // Mapa de rutas del macro módulo con su clasificación efectiva
            $uso   = $repo->usoRutas();
            $rutas = [];
            foreach ($subs as $carpeta => $info) {
                if ($carpeta === SeguridadMotor::MODULO) {
                    continue;
                }
                foreach ($info['rutas'] as $nombre => $cfg) {
                    $c = SeguridadMotor::clasificarRuta($nombre, $cfg, $carpeta);
                    $rutas[] = [
                        'ruta'       => $nombre,
                        'submodulo'  => $carpeta,
                        'accion'     => $c['accion'],
                        'origen'     => $c['origen'],
                        'heuristica' => SeguridadMotor::heuristica($nombre),
                        'tipo'       => !empty($cfg['vista']) ? 'vista' : 'endpoint',
                        'uso'        => (int) ($uso[$nombre]['total'] ?? 0),
                        'denegados'  => (int) ($uso[$nombre]['denegados'] ?? 0),
                    ];
                }
            }

            return $this->comun('permisos') + [
                'niveles'      => $repo->niveles(),
                'matriz'       => $repo->matrizExplicita(),
                'excepciones'  => $repo->excepciones(),
                'rutasMapa'    => $rutas,
            ];
        } catch (Throwable $e) {
            return $this->vistaError($e, 'permisos');
        }
    }

    public function amenazas() {
        try {
            $repo = $this->repo();
            return $this->comun('amenazas') + [
                'alertas'    => $repo->alertas(null, 100),
                'bloqueos'   => $repo->bloqueosActivos(),
                'historial'  => $repo->historialBloqueos(30),
                'topIps'     => $repo->topIpsDenegadas(10),
                'kpis'       => $repo->kpis(),
            ];
        } catch (Throwable $e) {
            return $this->vistaError($e, 'amenazas');
        }
    }

    public function politicas() {
        try {
            $repo = $this->repo();
            return $this->comun('politicas') + [
                'kpis' => $repo->kpis(),
            ];
        } catch (Throwable $e) {
            return $this->vistaError($e, 'politicas');
        }
    }

    /* =====================================================================
     *  API de lectura
     * ===================================================================== */

    public function apiFeed() {
        if (($r = $this->exigir('ver')) !== null) return $r;
        try {
            $desde = max(0, (int) ($_GET['desde'] ?? 0));
            $repo  = $this->repo();
            $eventos = $repo->ultimosEventos(50, $desde);
            $data = [
                'status'  => 'success',
                'eventos' => array_reverse($eventos),
                'kpis'    => $repo->kpis(),
                'hora'    => SeguridadMotor::ahora(),
            ];
            if (!empty($_GET['completo'])) {
                $data['serie']   = $repo->serieHoras(24);
                $data['activos'] = $repo->usuariosActivos(15);
                $data['alertas'] = $repo->alertas('abierta', 6);
            }
            return $this->json($data);
        } catch (Throwable $e) {
            SeguridadMotor::error($e);
            return $this->fallo('Error al consultar el feed.', 500);
        }
    }

    public function apiEvento() {
        if (($r = $this->exigir('ver')) !== null) return $r;
        $ev = $this->repo()->evento((int) ($_GET['id'] ?? 0));
        if (!$ev) {
            return $this->fallo('Evento no encontrado.', 404);
        }
        $ev['submodulo_titulo'] = SeguridadMotor::tituloSubmodulo($ev['submodulo']);
        $recalc = $ev;
        $recalc['usuario_id'] = $ev['usuario_id'] === null ? '' : (string) $ev['usuario_id'];
        $ev['firma_valida'] = hash_equals(SeguridadMotor::firmar($recalc), (string) $ev['hash']);
        return $this->json(['status' => 'success', 'evento' => $ev]);
    }

    public function simular() {
        if (($r = $this->exigir('ver')) !== null) return $r;
        $sub = (string) ($_REQUEST['submodulo'] ?? '');
        $acc = (string) ($_REQUEST['accion'] ?? '');
        if (!isset(SeguridadMotor::submodulos()[$sub]) || !SeguridadMotor::accionAplica($sub, $acc)) {
            return $this->fallo('Submódulo o acción inválidos.');
        }

        $usuarioId = (int) ($_REQUEST['usuario_id'] ?? 0);
        $ip        = trim((string) ($_REQUEST['ip'] ?? '')) ?: SeguridadMotor::ip();
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return $this->fallo('La IP indicada no es válida.');
        }

        if ($usuarioId > 0) {
            $u = $this->repo()->usuario($usuarioId);
            if (!$u) {
                return $this->fallo('Usuario no encontrado.', 404);
            }
            $ctx = ['logueado' => true, 'id' => (int) $u['id'], 'nivel' => (int) $u['nivel_privilegio'], 'nombre' => $u['nombre_completo'], 'ip' => $ip];
        } else {
            $nivel = (int) ($_REQUEST['nivel'] ?? SeguridadMotor::NIVEL_VISITANTE);
            $ctx = ['logueado' => $nivel !== SeguridadMotor::NIVEL_VISITANTE, 'id' => null, 'nivel' => $nivel, 'nombre' => $nivel === SeguridadMotor::NIVEL_VISITANTE ? 'Visitante' : "Nivel {$nivel}", 'ip' => $ip];
        }

        $d = SeguridadMotor::evaluar($sub, $acc, $ctx);
        return $this->json(['status' => 'success', 'decision' => $d, 'contexto' => $ctx]);
    }

    public function verificarIntegridad() {
        if (($r = $this->exigir('exportar')) !== null) return $r;
        $res = SeguridadMotor::verificarIntegridad();
        SeguridadMotor::registrar([
            'severidad' => $res['integro'] ? 'INFO' : 'CRITICO',
            'submodulo' => SeguridadMotor::MODULO,
            'accion'    => 'verificar-cadena',
            'resultado' => 'ADMIN',
            'detalles'  => $res['mensaje'],
        ]);
        if (!$res['integro']) {
            SeguridadMotor::crearAlerta('CADENA_ALTERADA', 'CRITICO', 'Cadena de custodia alterada', $res['mensaje'] . ' Primer registro afectado: #' . ($res['corruptos'][0]['id'] ?? '?'));
        }
        return $this->json(['status' => 'success'] + $res);
    }

    public function exportarCsv() {
        if (!SeguridadMotor::puede('exportar')) {
            http_response_code(403);
            echo 'Permiso insuficiente para exportar la bitácora.';
            return false;
        }
        $filtros = $this->filtrosBitacora($_GET);
        SeguridadMotor::registrarAdmin('exportar-bitacora', 'Exportación CSV de la bitácora. Filtros: ' . json_encode(array_filter($filtros), JSON_UNESCAPED_UNICODE), 'INFO');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="bitacora_seguridad_lineas_' . date('Ymd_His') . '.csv"');
        header('Cache-Control: no-store');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['ID', 'Fecha', 'Severidad', 'Submódulo', 'Acción', 'Ruta', 'Resultado', 'Usuario ID', 'Usuario', 'Nivel', 'IP', 'Método', 'Detalles', 'Hash anterior', 'Hash'], ';', '"', '');
        foreach ($this->repo()->iterarLogs($filtros) as $f) {
            fputcsv($out, [
                $f['id'], $f['fecha'], $f['severidad'], SeguridadMotor::tituloSubmodulo($f['submodulo']), $f['accion'], $f['ruta'],
                $f['resultado'], $f['usuario_id'], $f['usuario_nombre'], $f['nivel'], $f['ip'], $f['metodo'],
                $f['detalles'], $f['hash_anterior'], $f['hash'],
            ], ';', '"', '');
        }
        fclose($out);
        return false;
    }

    /* =====================================================================
     *  Administración
     * ===================================================================== */

    public function guardarMatriz() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;

        $entrada = json_decode((string) ($_POST['matriz'] ?? ''), true);
        if (!is_array($entrada)) {
            return $this->fallo('Formato de matriz inválido.');
        }
        $niveles = array_keys($this->repo()->niveles());
        $subs    = SeguridadMotor::submodulos();
        $celdas  = [];
        foreach ($entrada as $c) {
            $nivel = (int) ($c['nivel'] ?? -1);
            $sub   = (string) ($c['submodulo'] ?? '');
            $acc   = (string) ($c['accion'] ?? '');
            if ($nivel === 0 || !in_array($nivel, $niveles, true) || !isset($subs[$sub]) || !SeguridadMotor::accionAplica($sub, $acc)) {
                continue; // El nivel 0 es inmutable; se ignoran celdas inválidas
            }
            $celdas[] = ['nivel' => $nivel, 'submodulo' => $sub, 'accion' => $acc, 'permitido' => !empty($c['permitido'])];
        }
        if (!$celdas) {
            return $this->fallo('No se recibieron celdas válidas.');
        }

        // Anti auto-bloqueo: quien guarda no puede quitarse a sí mismo "configurar" en Seguridad
        $ctx = SeguridadMotor::contexto();
        foreach ($celdas as $c) {
            if ($ctx['nivel'] !== 0 && $c['nivel'] === $ctx['nivel'] && $c['submodulo'] === SeguridadMotor::MODULO
                && in_array($c['accion'], ['ver', 'configurar'], true) && !$c['permitido']) {
                return $this->fallo('Protección anti auto-bloqueo: no puedes retirarte «Ver/Configurar» de Seguridad a tu propio nivel.', 409);
            }
        }

        $n = $this->repo()->guardarMatriz($celdas);
        SeguridadMotor::limpiarCaches();
        $denegadas = count(array_filter($celdas, fn($c) => !$c['permitido']));
        SeguridadMotor::registrarAdmin('guardar-matriz', "Matriz de permisos actualizada: {$n} celdas ({$denegadas} restricciones activas).", 'CRITICO');
        return $this->ok("Matriz aplicada: {$n} celdas sincronizadas ({$denegadas} restricciones).");
    }

    public function restablecerMatriz() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $n = $this->repo()->restablecerMatriz();
        SeguridadMotor::limpiarCaches();
        SeguridadMotor::registrarAdmin('restablecer-matriz', "Matriz restablecida a valores por defecto ({$n} celdas eliminadas).", 'CRITICO');
        return $this->ok("Matriz restablecida a valores por defecto ({$n} celdas eliminadas).");
    }

    public function buscarUsuario() {
        if (($r = $this->exigir('configurar')) !== null) return $r;
        $q = trim((string) ($_GET['q'] ?? ''));
        if (mb_strlen($q) < 2) {
            return $this->json(['status' => 'success', 'usuarios' => []]);
        }
        return $this->json(['status' => 'success', 'usuarios' => $this->repo()->buscarUsuarios(mb_substr($q, 0, 60))]);
    }

    public function guardarExcepcion() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;

        $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
        $sub       = (string) ($_POST['submodulo'] ?? '');
        $acc       = (string) ($_POST['accion'] ?? '');
        $efecto    = (string) ($_POST['efecto'] ?? '');
        $motivo    = mb_substr(trim((string) ($_POST['motivo'] ?? '')), 0, 500);
        $horas     = (int) ($_POST['duracion_horas'] ?? 0);

        $u = $usuarioId > 0 ? $this->repo()->usuario($usuarioId) : null;
        if (!$u) {
            return $this->fallo('Selecciona un usuario válido.');
        }
        if ((int) $u['nivel_privilegio'] === 0) {
            return $this->fallo('Los usuarios de nivel 0 (SuperAdmin) son inmunes: no admiten excepciones.');
        }
        $subs = SeguridadMotor::submodulos();
        if ($sub !== '*' && !isset($subs[$sub])) {
            return $this->fallo('Submódulo inválido.');
        }
        if ($acc !== '*' && !isset(SeguridadMotor::ACCIONES[$acc])) {
            return $this->fallo('Acción inválida.');
        }
        if ($sub !== '*' && $acc !== '*' && !SeguridadMotor::accionAplica($sub, $acc)) {
            return $this->fallo('Esa acción no aplica al submódulo seleccionado.');
        }
        if (!in_array($efecto, ['permitir', 'denegar'], true)) {
            return $this->fallo('Efecto inválido.');
        }
        if ($motivo === '') {
            return $this->fallo('Indica el motivo (queda en la bitácora).');
        }
        $ctx = SeguridadMotor::contexto();
        if ($efecto === 'denegar' && $ctx['id'] === (int) $u['id']) {
            return $this->fallo('Protección anti auto-bloqueo: no puedes crearte una excepción de denegación.', 409);
        }

        $expira = $horas > 0 ? SeguridadMotor::ahora($horas * 3600) : null;
        $id = $this->repo()->crearExcepcion((int) $u['id'], $sub, $acc, $efecto, $motivo, $expira);
        SeguridadMotor::limpiarCaches();

        $txtAcc = $acc === '*' ? 'todas las acciones' : SeguridadMotor::ACCIONES[$acc]['etiqueta'];
        SeguridadMotor::registrarAdmin('crear-excepcion', sprintf(
            'Excepción #%d: %s «%s» en «%s» a %s (C.I. %s)%s. Motivo: %s',
            $id, strtoupper($efecto), $txtAcc, SeguridadMotor::tituloSubmodulo($sub), $u['nombre_completo'], $u['cedula'],
            $expira ? " hasta {$expira}" : ' sin vencimiento', $motivo
        ), $efecto === 'denegar' ? 'CRITICO' : 'AVISO');

        return $this->ok("Excepción #{$id} creada para {$u['nombre_completo']}.");
    }

    public function eliminarExcepcion() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $ex = $this->repo()->eliminarExcepcion((int) ($_POST['id'] ?? 0));
        if (!$ex) {
            return $this->fallo('La excepción no existe.', 404);
        }
        SeguridadMotor::limpiarCaches();
        SeguridadMotor::registrarAdmin('eliminar-excepcion', "Excepción #{$ex['id']} ({$ex['efecto']} {$ex['accion']} en {$ex['submodulo']}) del usuario ID {$ex['usuario_id']} revocada.", 'AVISO');
        return $this->ok("Excepción #{$ex['id']} revocada.");
    }

    public function guardarRuta() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $ruta   = (string) ($_POST['ruta'] ?? '');
        $accion = (string) ($_POST['accion'] ?? '');

        $loader = class_exists('ModuleLoader') ? ModuleLoader::actual() : null;
        $modulo = $loader ? $loader->getModuloDeRuta($ruta) : null;
        if ($modulo === null || $modulo === SeguridadMotor::MODULO || !isset(SeguridadMotor::submodulos()[$modulo])) {
            return $this->fallo('La ruta no pertenece a un submódulo protegido o es una ruta fija.');
        }
        if ($accion !== '' && !isset(SeguridadMotor::ACCIONES[$accion])) {
            return $this->fallo('Acción inválida.');
        }
        $this->repo()->guardarRuta($ruta, $accion === '' ? null : $accion);
        SeguridadMotor::limpiarCaches();
        $efectiva = SeguridadMotor::clasificarRuta($ruta, $loader->getRutasGlobales()[$ruta] ?? [], $modulo);
        SeguridadMotor::registrarAdmin('reclasificar-ruta', "Ruta «{$ruta}» clasificada como «{$efectiva['accion']}» (origen: {$efectiva['origen']}).", 'AVISO');
        return $this->ok("Ruta «{$ruta}» → {$efectiva['accion']}", ['efectiva' => $efectiva]);
    }

    public function bloquearIp() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $ip      = trim((string) ($_POST['ip'] ?? ''));
        $motivo  = mb_substr(trim((string) ($_POST['motivo'] ?? '')), 0, 500);
        $minutos = max(0, (int) ($_POST['minutos'] ?? 0));

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return $this->fallo('Dirección IP inválida.');
        }
        if ($ip === SeguridadMotor::ip()) {
            return $this->fallo('Protección anti auto-bloqueo: no puedes bloquear tu propia IP.', 409);
        }
        if ($motivo === '') {
            return $this->fallo('Indica el motivo del bloqueo.');
        }
        $id = SeguridadMotor::bloquearIp($ip, $motivo, $minutos);
        SeguridadMotor::registrarAdmin('bloquear-ip', "IP {$ip} bloqueada " . ($minutos ? "por {$minutos} min" : 'permanentemente') . ". Motivo: {$motivo}", 'CRITICO');
        return $this->ok("IP {$ip} bloqueada (#{$id}).");
    }

    public function desbloquearIp() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $b = $this->repo()->desbloquear((int) ($_POST['id'] ?? 0));
        if (!$b) {
            return $this->fallo('Bloqueo no encontrado.', 404);
        }
        SeguridadMotor::limpiarCaches();
        SeguridadMotor::registrarAdmin('desbloquear-ip', "Bloqueo #{$b['id']} levantado para la IP {$b['ip']}.", 'AVISO');
        return $this->ok("IP {$b['ip']} desbloqueada.");
    }

    public function atenderAlerta() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $id = ($_POST['id'] ?? '') === 'todas' ? null : (int) ($_POST['id'] ?? 0);
        if ($id === 0) {
            return $this->fallo('Alerta inválida.');
        }
        $n = $this->repo()->atenderAlertas($id);
        SeguridadMotor::registrarAdmin('atender-alerta', $id ? "Alerta #{$id} marcada como atendida." : "{$n} alerta(s) marcadas como atendidas.", 'INFO');
        return $this->ok($n ? "{$n} alerta(s) atendida(s)." : 'No había alertas abiertas.', ['atendidas' => $n]);
    }

    public function expulsarUsuario() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        $uid = (int) ($_POST['usuario_id'] ?? 0);
        $ctx = SeguridadMotor::contexto();
        if ($uid <= 0) {
            return $this->fallo('Usuario inválido.');
        }
        if ($uid === $ctx['id']) {
            return $this->fallo('No puedes expulsar tu propia sesión.', 409);
        }
        $u = $this->repo()->usuario($uid);
        if ($u && (int) $u['nivel_privilegio'] === 0 && $ctx['nivel'] !== 0) {
            return $this->fallo('Solo un SuperAdmin puede expulsar a otro SuperAdmin.', 403);
        }

        // Mismo mecanismo que usa el core (Auth::check) para revocar sesiones
        $archivo = (defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/') . 'revoked_sessions.json';
        $revocadas = is_file($archivo) ? (json_decode((string) file_get_contents($archivo), true) ?: []) : [];
        $revocadas[(string) $uid] = time();
        file_put_contents($archivo, json_encode($revocadas, JSON_PRETTY_PRINT), LOCK_EX);

        $nombre = $u['nombre_completo'] ?? "ID {$uid}";
        SeguridadMotor::registrarAdmin('expulsar-sesion', "Sesión de {$nombre} (ID {$uid}) revocada desde el Centro de Operaciones.", 'CRITICO');
        return $this->ok("Sesión de {$nombre} revocada. Será expulsado en su próxima petición.");
    }

    public function guardarPoliticas() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;

        $bool = fn(string $k) => !empty($_POST[$k]) && $_POST[$k] !== '0' && $_POST[$k] !== 'false';
        $int  = fn(string $k, int $min, int $max) => max($min, min($max, (int) ($_POST[$k] ?? 0)));

        $ips = [];
        foreach (preg_split('/[\s,;]+/', (string) ($_POST['ips_confiables'] ?? '')) ?: [] as $ip) {
            $ip = trim($ip);
            if ($ip === '') continue;
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                return $this->fallo("IP confiable inválida: {$ip}");
            }
            $ips[] = $ip;
        }

        $antes = SeguridadMotor::politicas();
        $nuevas = [
            'modo_bloqueo'         => $bool('modo_bloqueo'),
            'bloqueo_nivel_maximo' => $int('bloqueo_nivel_maximo', 0, 998),
            'bloqueo_mensaje'      => mb_substr(trim((string) ($_POST['bloqueo_mensaje'] ?? '')), 0, 400) ?: SeguridadMotor::POLITICAS_DEFECTO['bloqueo_mensaje'],
            'solo_lectura'         => $bool('solo_lectura'),
            'registrar_lecturas'   => $bool('registrar_lecturas'),
            'registrar_visitantes' => $bool('registrar_visitantes'),
            'retencion_dias'       => $int('retencion_dias', 1, 3650),
            'umbral_denegaciones'  => $int('umbral_denegaciones', 1, 1000),
            'ventana_minutos'      => $int('ventana_minutos', 1, 1440),
            'auto_bloqueo_ip'      => $bool('auto_bloqueo_ip'),
            'duracion_bloqueo_min' => $int('duracion_bloqueo_min', 1, 43200),
            'ips_confiables'       => array_values(array_unique($ips)),
            'refresco_feed_seg'    => $int('refresco_feed_seg', 2, 120),
        ];

        $ctx = SeguridadMotor::contexto();
        if ($nuevas['modo_bloqueo'] && $ctx['nivel'] > $nuevas['bloqueo_nivel_maximo']) {
            return $this->fallo("Protección anti auto-bloqueo: con ese Lockdown tu propio nivel ({$ctx['nivel']}) quedaría fuera. Sube el «nivel máximo permitido».", 409);
        }

        SeguridadMotor::guardarPoliticas($nuevas);
        SeguridadMotor::limpiarCaches();

        $cambios = [];
        foreach ($nuevas as $k => $v) {
            if (($antes[$k] ?? null) !== $v) {
                $cambios[] = $k . '=' . (is_bool($v) ? ($v ? 'ON' : 'OFF') : (is_array($v) ? implode(',', $v) : mb_substr((string) $v, 0, 40)));
            }
        }
        $critico = ($nuevas['modo_bloqueo'] !== (bool) $antes['modo_bloqueo']) || ($nuevas['solo_lectura'] !== (bool) $antes['solo_lectura']);
        SeguridadMotor::registrarAdmin('guardar-politicas', $cambios ? 'Políticas actualizadas: ' . implode(' · ', $cambios) : 'Políticas guardadas sin cambios.', $critico ? 'CRITICO' : 'AVISO');

        if ($nuevas['modo_bloqueo'] && !$antes['modo_bloqueo']) {
            SeguridadMotor::crearAlerta('LOCKDOWN', 'CRITICO', 'Lockdown activado', 'El macro módulo quedó restringido a niveles ≤ ' . $nuevas['bloqueo_nivel_maximo'] . ' por ' . SeguridadMotor::firmaActor(), SeguridadMotor::ip(), $ctx['id']);
        }
        return $this->ok($cambios ? count($cambios) . ' política(s) actualizada(s).' : 'Sin cambios.');
    }

    public function purgarLogs() {
        if (($r = $this->exigir('configurar', true)) !== null) return $r;
        if (trim((string) ($_POST['confirmacion'] ?? '')) !== 'PURGAR') {
            return $this->fallo('Escribe PURGAR para confirmar.');
        }
        $dias = max(0, min(3650, (int) ($_POST['dias'] ?? 0)));
        try {
            $n = SeguridadMotor::purgar($dias);
        } catch (Throwable $e) {
            SeguridadMotor::error($e);
            return $this->fallo('No se pudo purgar: ' . $e->getMessage(), 500);
        }
        SeguridadMotor::registrarAdmin('purgar-bitacora', "Purga manual: {$n} evento(s) " . ($dias ? "con más de {$dias} días" : 'eliminados (todos)') . '. Cadena re-anclada.', 'CRITICO');
        return $this->ok("{$n} evento(s) purgados. La cadena fue re-anclada y sigue verificable.", ['purgados' => $n]);
    }
}
