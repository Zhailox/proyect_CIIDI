<?php
// modules/Cursos/services/ApiExternaCursosService.php

require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../../Investigaciones/models/InvestigacionModel.php';

/**
 * Servicio de API REST Externa y Sincronización Híbrida (Pull + Push Webhook)
 * para el Portal Externo de Cursos y Directorio de Investigadores (compatible con MySQL / servidores remotos).
 */
class ApiExternaCursosService {

    private CursoModel $cursoModel;
    private InvestigacionModel $invModel;
    private array $cfg;

    public function __construct(?CursoModel $cursoModel = null) {
        $this->cursoModel = $cursoModel ?? new CursoModel();
        $this->invModel   = new InvestigacionModel();
        $this->cfg        = $this->cursoModel->cargarConfig();
    }

    /**
     * Determina la URL base pública del CIIDI para construir enlaces absolutos de imágenes y recursos.
     */
    public function obtenerBaseUrlCiidi(): string {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'));
            $rootDir = preg_replace('#/public$#i', '', rtrim($scriptDir, '/'));
            return $scheme . '://' . $_SERVER['HTTP_HOST'] . $rootDir;
        }
        $envUrl = getenv('APP_URL') ?: 'http://localhost/proyect_CIIDI/proyect_CIIDI/public';
        return preg_replace('#/public(/inicio)?$#i', '', rtrim($envUrl, '/'));
    }

    /**
     * Convierte una imagen local subida al CIIDI en una URL absoluta y, si es local y liviana (< 1.5 MB),
     * genera su representación Data URI (Base64) para que el servidor externo pueda mostrarla
     * incluso si ambos servidores están en redes distintas.
     */
    private function resolverImagenCurso(?string $imagenPortada, string $baseUrl, bool $incluirBase64 = true): array {
        $placeholder = $this->cfg['imagenes']['placeholder_url']
            ?? 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&q=80&w=600';

        $raw = trim((string)$imagenPortada);
        if ($raw === '') {
            return [
                'url_absoluta' => $placeholder,
                'data_uri'     => null,
            ];
        }

        if (preg_match('#^https?://#i', $raw)) {
            return [
                'url_absoluta' => $raw,
                'data_uri'     => null,
            ];
        }

        $relPath = ltrim(str_replace('\\', '/', $raw), '/');
        $urlAbsoluta = rtrim($baseUrl, '/') . '/' . $relPath;

        $rootPath = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);
        $baseName = basename($relPath);
        $candidatos = [
            $rootPath . '/' . $relPath,
            $rootPath . '/public/' . $relPath,
            $rootPath . '/public/uploads/cursos/' . $baseName,
            $rootPath . '/storage/uploads/cursos/' . $baseName,
            $rootPath . '/public/uploads/perfiles/' . $baseName,
        ];
        foreach ($candidatos as $filePath) {
            if (file_exists($filePath) && is_file($filePath) && filesize($filePath) <= 1572864) {
                $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $mimeMap = [
                    'webp' => 'image/webp',
                    'png'  => 'image/png',
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif'  => 'image/gif',
                ];
                $mime = $mimeMap[$ext] ?? 'image/webp';
                $bin = @file_get_contents($filePath);
                if ($bin !== false) {
                    $dataUri = 'data:' . $mime . ';base64,' . base64_encode($bin);
                }
                break;
            }
        }

        // Evitar enviar URLs 'http://localhost/...' a dominios públicos (evita el aviso de Chrome "quiere acceder a otros servicios y apps en este dispositivo")
        if (preg_match('#^https?://(localhost|127\.0\.0\.1)#i', $urlAbsoluta)) {
            $urlAbsoluta = $dataUri ?? $placeholder;
        }

        return [
            'url_absoluta' => $urlAbsoluta,
            'data_uri'     => $dataUri,
        ];
    }

    /**
     * Construye el paquete completo de datos para la API REST y para la sincronización con MySQL externo.
     */
    public function construirPayloadCompleto(string $recurso = 'todo', bool $incluirBase64 = true): array {
        $baseUrl = $this->obtenerBaseUrlCiidi();
        $cfgGoogle = $this->cfg['formulario_google'] ?? [];
        $urlMoodleFallback = $this->cfg['moodle']['url_fallback'] ?? 'https://www.youtube.com';

        // 1. Líneas de Investigación y Dimensiones Operativas
        $lineas = $this->cursoModel->obtenerLineas();
        $dimensiones = $this->cursoModel->obtenerTodasDimensiones();
        $dimsPorLinea = [];
        foreach ($dimensiones as $d) {
            $dimsPorLinea[(int)$d['id_linea']][] = [
                'id'       => (int)$d['id'],
                'id_linea' => (int)$d['id_linea'],
                'nombre'   => (string)$d['nombre'],
            ];
        }
        $lineasFormateadas = [];
        foreach ($lineas as $l) {
            $lid = (int)$l['id'];
            $lineasFormateadas[] = [
                'id'          => $lid,
                'nombre'      => (string)$l['nombre'],
                'dimensiones' => $dimsPorLinea[$lid] ?? [],
            ];
        }

        // 2. Cursos Publicados
        $cursosFormateados = [];
        if ($recurso === 'todo' || $recurso === 'cursos') {
            $resCursos = $this->cursoModel->listarCursos(['estado' => 'publicado'], 1, 500);
            foreach ($resCursos['cursos'] as $c) {
                $imgInfo = $this->resolverImagenCurso($c['imagen_portada'] ?? '', $baseUrl, $incluirBase64);
                $urlForm = !empty($c['url_formulario_google'])
                    ? $c['url_formulario_google']
                    : ($cfgGoogle['url_default'] ?? 'https://docs.google.com/forms');
                $urlMoodle = !empty($c['url_moodle']) ? $c['url_moodle'] : $urlMoodleFallback;

                $cursosFormateados[] = [
                    'id'                     => (int)$c['id'],
                    'slug'                   => (string)($c['slug'] ?? ('curso-' . $c['id'])),
                    'titulo'                 => (string)$c['titulo'],
                    'descripcion'            => (string)($c['descripcion'] ?? ''),
                    'id_docente'             => (int)($c['id_docente'] ?? 0),
                    'nombre_docente'         => (string)($c['nombre_docente'] ?? 'Docente CIIDI'),
                    'modalidad'              => (string)($c['modalidad'] ?? 'Virtual'),
                    'nivel'                  => (string)($c['nivel'] ?? 'Básico'),
                    'duracion'               => (string)($c['duracion'] ?? ''),
                    'cupo_maximo'            => (int)($c['cupo_maximo'] ?? 0),
                    'nota_minima_aprobacion' => (float)($c['nota_minima_aprobacion'] ?? 70.0),
                    'fecha_inicio'           => $c['fecha_inicio'] ?? null,
                    'fecha_fin'              => $c['fecha_fin'] ?? null,
                    'estado'                 => (string)($c['estado'] ?? 'publicado'),
                    'estado_inscripcion'     => (string)($c['estado_inscripcion'] ?? 'Abierta'),
                    'url_moodle'             => (string)$urlMoodle,
                    'url_formulario_google'  => (string)$urlForm,
                    'url_video_preview'      => (string)($c['url_video_preview'] ?? ''),
                    'imagen_portada'         => (string)($c['imagen_portada'] ?? ''),
                    'imagen_url'             => $imgInfo['url_absoluta'],
                    'imagen_data_uri'        => $imgInfo['data_uri'],
                    'fecha_creacion'         => $c['fecha_creacion'] ?? date('Y-m-d H:i:s'),
                    'lineas'                 => array_map(fn($l) => [
                        'id'     => (int)$l['id'],
                        'nombre' => (string)$l['nombre'],
                    ], $c['lineas'] ?? []),
                    'dimensiones'            => array_map(fn($d) => [
                        'id'       => (int)$d['id'],
                        'id_linea' => (int)$d['id_linea'],
                        'nombre'   => (string)$d['nombre'],
                    ], $c['dimensiones'] ?? []),
                ];
            }
        }

        // 3. Directorio de Investigadores
        $investigadoresFormateados = [];
        if ($recurso === 'todo' || $recurso === 'investigadores') {
            $rawInvs = $this->invModel->obtenerInvestigadores([]);
            foreach ($rawInvs as $inv) {
                $proyectos = [];
                foreach ($inv['investigaciones'] ?? [] as $p) {
                    $proyectos[] = [
                        'id'                     => (int)$p['id'],
                        'titulo'                 => (string)($p['titulo'] ?? ''),
                        'estado'                 => (string)($p['estado'] ?? 'En Curso'),
                        'cupos_disponibles'      => (int)($p['cupos_disponibles'] ?? 0),
                        'fecha_creacion'         => $p['fecha_creacion'] ?? null,
                        'planteamiento_problema' => (string)($p['planteamiento_problema'] ?? ''),
                        'objetivo_general'       => (string)($p['objetivo_general'] ?? ''),
                    ];
                }

                $fotoRaw = trim((string)($inv['foto_perfil'] ?? ''));
                $fotoUrl = '';
                $fotoDataUri = null;
                if ($fotoRaw !== '') {
                    $fotoInfo = $this->resolverImagenCurso($fotoRaw, $baseUrl, $incluirBase64);
                    $fotoDataUri = $fotoInfo['data_uri'] ?? null;
                    $candidateUrl = $fotoInfo['url_absoluta'] ?? '';
                    $fotoUrl = ($fotoDataUri !== null) ? $fotoDataUri : (preg_match('#^https?://(localhost|127\.0\.0\.1)#i', $candidateUrl) ? '' : $candidateUrl);
                }

                $orcidRaw = trim((string)($inv['orcid'] ?? ''));
                $orcidId = '';
                $orcidUrl = '';
                if ($orcidRaw !== '' && preg_match('/(\d{4}-\d{4}-\d{4}-\d{3}[\dX])/i', $orcidRaw, $mO)) {
                    $orcidId = strtoupper($mO[1]);
                    $orcidUrl = 'https://orcid.org/' . $orcidId;
                }

                $investigadoresFormateados[] = [
                    'id'                    => (int)$inv['id'],
                    'nombre_completo'       => (string)$inv['nombre_completo'],
                    'email'                 => (string)($inv['email'] ?? ''),
                    'id_rol'                => 0,
                    'rol_nombre'            => 'Profesor / Investigador Acreditado',
                    'es_investigador'       => !empty($inv['es_investigador']),
                    'es_profesor_pst'       => !empty($inv['es_profesor_pst']),
                    'foto_perfil'           => $fotoRaw,
                    'foto_url'              => $fotoUrl,
                    'foto_data_uri'         => $fotoDataUri,
                    'orcid'                 => $orcidId,
                    'orcid_url'             => $orcidUrl,
                    'sitio_web'             => trim((string)($inv['sitio_web'] ?? '')),
                    'grado_academico'       => trim((string)($inv['grado_academico'] ?? '')),
                    'biografia'             => trim((string)($inv['biografia'] ?? '')),
                    'total_investigaciones' => count($proyectos),
                    'lineas'                => array_map(fn($l) => [
                        'id'             => (int)$l['id'],
                        'nombre'         => (string)$l['nombre'],
                        'carrera_nombre' => (string)($l['carrera_nombre'] ?? 'General / Institucional'),
                    ], $inv['lineas'] ?? []),
                    'dimensiones'           => array_map(fn($d) => [
                        'id'       => (int)$d['id'],
                        'id_linea' => (int)$d['id_linea'],
                        'nombre'   => (string)$d['nombre'],
                    ], $inv['dimensiones'] ?? []),
                    'trayectos_pst'         => array_map(fn($t) => [
                        'trayecto'  => (int)$t['trayecto'],
                        'trimestre' => (int)$t['trimestre'],
                    ], $inv['trayectos_pst'] ?? []),
                    'investigaciones'       => $proyectos,
                ];
            }
        }

        // Hash de versión para que el servidor externo detecte cambios sin reprocesar si no hubo modificaciones
        $hashSource = json_encode([
            'lineas'         => $lineasFormateadas,
            'cursos_count'   => count($cursosFormateados),
            'cursos_ids'     => array_map(fn($c) => [$c['id'], $c['titulo'], $c['estado_inscripcion'], $c['url_formulario_google'], $c['url_video_preview'], $c['descripcion']], $cursosFormateados),
            'inv_count'      => count($investigadoresFormateados),
            'inv_ids'        => array_map(fn($i) => [$i['id'], $i['nombre_completo'], $i['email'], $i['orcid'], $i['sitio_web'], $i['grado_academico'], $i['biografia'], $i['foto_perfil'], $i['total_investigaciones'], count($i['lineas'])], $investigadoresFormateados),
            'google_form'    => $cfgGoogle,
        ], JSON_UNESCAPED_UNICODE);
        $versionHash = hash('sha256', (string)$hashSource);

        $totalProyectosActivos = 0;
        foreach ($investigadoresFormateados as $i) {
            $totalProyectosActivos += (int)$i['total_investigaciones'];
        }

        $logoInfo = $this->resolverImagenCurso('public/assets/img/SinfondoCIIDI.webp', $baseUrl, $incluirBase64);

        return [
            'status'         => 'success',
            'meta'           => [
                'institucion'       => 'Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" (UPTTMBI)',
                'sistema'           => 'CIIDI — Nodo Maestro de Formación e Investigación',
                'version_hash'      => $versionHash,
                'generado_en'       => date('c'),
                'timestamp'         => time(),
                'base_url_ciidi'    => $baseUrl,
                'endpoint_api'      => $baseUrl . '/public/?ruta=api-cursos-externo',
                'moodle_fallback'   => $urlMoodleFallback,
                'logo_url'          => $logoInfo['url_absoluta'] ?? '',
                'logo_data_uri'     => $logoInfo['data_uri'] ?? null,
                'formulario_google' => [
                    'habilitado'  => !empty($cfgGoogle['habilitado']),
                    'url_default' => (string)($cfgGoogle['url_default'] ?? 'https://docs.google.com/forms'),
                    'modo'        => (string)($cfgGoogle['modo'] ?? 'embebido'),
                    'titulo'      => (string)($cfgGoogle['titulo'] ?? 'Formulario Oficial de Inscripción a Cursos CIIDI'),
                ],
            ],
            'estadisticas'   => [
                'total_cursos'         => count($cursosFormateados),
                'total_lineas'         => count($lineasFormateadas),
                'total_investigadores' => count($investigadoresFormateados),
                'total_proyectos_id'   => $totalProyectosActivos,
            ],
            'lineas'         => $lineasFormateadas,
            'cursos'         => $cursosFormateados,
            'investigadores' => $investigadoresFormateados,
        ];
    }

    /**
     * Empuja (Push Webhook) el catálogo completo hacia el endpoint receptor del servidor externo (MySQL).
     */
    public function empujarAServidorExterno(?string $targetUrl = null, ?string $apiKey = null, string $evento = 'manual'): array {
        $this->cfg = $this->cursoModel->cargarConfig();
        $apiCfg    = $this->cfg['api_externa'] ?? [];

        $url = trim((string)($targetUrl ?? ($apiCfg['webhook_url'] ?? '')));
        $key = trim((string)($apiKey ?? ($apiCfg['api_key'] ?? 'ciidi_sync_secret_2026')));

        if ($url === '') {
            return [
                'ok'      => false,
                'mensaje' => 'No se ha configurado la URL del Servidor Externo receptor.',
                'codigo'  => 0,
            ];
        }

        $payload = $this->construirPayloadCompleto('todo', true);
        $payload['evento_origen'] = $evento;
        $jsonBody = json_encode($payload, JSON_UNESCAPED_UNICODE);

        // Si existe la carpeta local PORTAL_EXTERNO_MYSQL en el mismo servidor (WAMP), actualizarla de inmediato
        $localSyncDone = false;
        $localPortalDir = dirname(__DIR__, 4) . '/PORTAL_EXTERNO_MYSQL';
        if (is_dir($localPortalDir) && file_exists($localPortalDir . '/includes/SyncEngine.php') && file_exists($localPortalDir . '/config.php')) {
            try {
                require_once $localPortalDir . '/includes/SyncEngine.php';
                $localCfg = require $localPortalDir . '/config.php';
                $localEngine = new \SyncEngine($localCfg);
                $localEngine->guardarPayload($payload, 'local_push_' . $evento);
                $localSyncDone = true;
            } catch (\Throwable $e) {
                error_log('Aviso sync portal local: ' . $e->getMessage());
            }
        }

        $signature = hash_hmac('sha256', (string)$jsonBody, $key);
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json',
            'X-CIIDI-API-Key: ' . $key,
            'X-CIIDI-Signature: ' . $signature,
            'X-CIIDI-Event: ' . $evento,
        ];

        $httpCode = 0;
        $responseBody = '';
        $errorMsg = '';

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $jsonBody,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ]);
            $res = curl_exec($ch);
            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($res === false) {
                $errorMsg = curl_error($ch);
            } else {
                $responseBody = (string)$res;
            }
            if (PHP_VERSION_ID < 80000) {
                curl_close($ch);
            }
        } else {
            $context = stream_context_create([
                'http' => [
                    'method'        => 'POST',
                    'header'        => implode("\r\n", $headers),
                    'content'       => $jsonBody,
                    'timeout'       => 15,
                    'ignore_errors' => true,
                ],
                'ssl' => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $res = @file_get_contents($url, false, $context);
            if ($res === false) {
                $errorMsg = 'No fue posible conectar con el servidor externo mediante stream HTTP.';
            } else {
                $responseBody = (string)$res;
                $httpCode = 200;
                $respHeaders = function_exists('http_get_last_response_headers')
                    ? (http_get_last_response_headers() ?: [])
                    : ($http_response_header ?? []);
                if (is_array($respHeaders)) {
                    foreach ($respHeaders as $hdr) {
                        if (preg_match('#^HTTP/\S+\s+(\d{3})#i', $hdr, $m)) {
                            $httpCode = (int)$m[1];
                        }
                    }
                }
            }
        }

        $decoded = @json_decode($responseBody, true);
        $remoteOk = ($httpCode >= 200 && $httpCode < 300) && (is_array($decoded) ? (($decoded['status'] ?? '') === 'success') : true);
        $ok = $remoteOk || $localSyncDone;

        if ($remoteOk && $localSyncDone) {
            $detalleMensaje = 'Portal Externo Local y Subdominio Remoto sincronizados correctamente.';
        } elseif ($localSyncDone) {
            $detalleMensaje = 'Portal Externo Local (PORTAL_EXTERNO_MYSQL) sincronizado al instante.';
        } else {
            $detalleMensaje = $remoteOk
                ? ($decoded['message'] ?? 'Sincronización completada exitosamente con el servidor externo.')
                : ($errorMsg !== '' ? "Error de conexión: {$errorMsg}" : ($decoded['message'] ?? "Respuesta HTTP {$httpCode} del servidor externo."));
        }

        // Registrar estado del último sync en config_cursos.json
        $this->cfg['api_externa']['ultimo_sync'] = [
            'fecha'          => date('Y-m-d H:i:s'),
            'ok'             => $ok,
            'codigo_http'    => $httpCode,
            'evento'         => $evento,
            'mensaje'        => $detalleMensaje,
            'cursos_enviados'=> count($payload['cursos']),
            'inv_enviados'   => count($payload['investigadores']),
            'version_hash'   => $payload['meta']['version_hash'],
        ];
        $this->cursoModel->guardarConfig($this->cfg);

        return [
            'ok'              => $ok,
            'codigo'          => $httpCode,
            'mensaje'         => $detalleMensaje,
            'cursos_enviados' => count($payload['cursos']),
            'inv_enviados'    => count($payload['investigadores']),
            'version_hash'    => $payload['meta']['version_hash'],
            'respuesta_remota'=> $decoded,
        ];
    }

    /**
     * Se invoca automáticamente tras crear, editar o eliminar un curso en el CIIDI.
     * Si el Auto-Push está habilitado y hay una URL de servidor externo configurada,
     * empuja los cambios de forma transparente sin interrumpir el flujo del usuario.
     */
    public static function dispararAutoPushSiHabilitado(string $evento = 'auto_update'): void {
        try {
            $svc = new self();
            $cfg = $svc->cursoModel->cargarConfig();
            $apiCfg = $cfg['api_externa'] ?? [];
            if (!empty($apiCfg['habilitada']) && !empty($apiCfg['auto_push']) && !empty($apiCfg['webhook_url'])) {
                $svc->empujarAServidorExterno($apiCfg['webhook_url'], $apiCfg['api_key'] ?? null, $evento);
            }
        } catch (\Throwable $e) {
            error_log('Aviso Auto-Push Servidor Externo: ' . $e->getMessage());
        }
    }
}
