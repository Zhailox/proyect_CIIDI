<?php
// modules/VinculacionEmpresarial/services/FormularioPropuestaService.php
//
// Definición, saneamiento, renderizado y validación de los campos del formulario
// público "Postula tu Requerimiento Tecnológico". La definición se persiste como
// JSON en config_vinculacion.json (clave 'campos_formulario') a través de
// VinculacionConfigService. Los campos 'sistema' mapean a columnas reales de
// propuestas_empresa; los personalizados se guardan en propuestas_empresa.campos_extra.

require_once __DIR__ . '/ConfigService.php';

class FormularioPropuestaService {

    public const CLAVE_CONFIG = 'campos_formulario';

    /** Tipos admitidos => etiqueta legible para el administrador */
    public const TIPOS = [
        'texto'        => 'Texto',
        'numero'       => 'Número',
        'alfanumerico' => 'Alfanumérico (texto y número)',
        'fecha'        => 'Fecha',
        'correo'       => 'Correo electrónico',
        'telefono'     => 'Teléfono',
        'textarea'     => 'Texto largo (párrafo)',
        'seccion'      => 'Título de sección (sin respuesta)',
    ];

    /**
     * Campos de sistema: no se pueden eliminar ni cambiar de tipo porque otras
     * funciones (seguimiento, correos, carrusel, gestión) dependen de ellos.
     * 'requerido_fijo' => el administrador no puede desmarcarlos como obligatorios.
     */
    private const SISTEMA = [
        'nombre_empresa'       => ['tipo' => 'texto',        'requerido_fijo' => true],
        'rif_empresa'          => ['tipo' => 'alfanumerico', 'requerido_fijo' => false],
        'persona_contacto'     => ['tipo' => 'texto',        'requerido_fijo' => false],
        'telefono_contacto'    => ['tipo' => 'telefono',     'requerido_fijo' => false],
        'correo_contacto'      => ['tipo' => 'correo',       'requerido_fijo' => true],
        'descripcion_problema' => ['tipo' => 'textarea',     'requerido_fijo' => true],
    ];

    private const MAX_LONGITUD = ['textarea' => 5000, 'default' => 255];

    /** Configuración por defecto (equivalente al formulario original estático). */
    public static function camposPorDefecto(): array {
        return [
            ['clave' => 'sec_organizacion', 'tipo' => 'seccion', 'etiqueta' => 'Paso 1: Datos de la Organización', 'placeholder' => '', 'requerido' => false, 'sistema' => false, 'ancho' => 'completo'],
            ['clave' => 'nombre_empresa', 'tipo' => 'texto', 'etiqueta' => 'Nombre de la Empresa / Comercio / Institución', 'placeholder' => 'Ej. Distribuidora Los Andes C.A.', 'requerido' => true, 'sistema' => true, 'ancho' => 'medio'],
            ['clave' => 'rif_empresa', 'tipo' => 'alfanumerico', 'etiqueta' => 'RIF (Opcional si es comunidad)', 'placeholder' => 'Ej. J-12345678-9', 'requerido' => false, 'sistema' => true, 'ancho' => 'medio'],
            ['clave' => 'persona_contacto', 'tipo' => 'texto', 'etiqueta' => 'Persona de Contacto', 'placeholder' => 'Nombre y Apellido del responsable', 'requerido' => true, 'sistema' => true, 'ancho' => 'medio'],
            ['clave' => 'telefono_contacto', 'tipo' => 'telefono', 'etiqueta' => 'Teléfono Móvil o Fijo', 'placeholder' => 'Ej. 0414-0000000', 'requerido' => true, 'sistema' => true, 'ancho' => 'medio'],
            ['clave' => 'correo_contacto', 'tipo' => 'correo', 'etiqueta' => 'Correo Electrónico (Para recibir estatus del PST)', 'placeholder' => 'contacto@tuempresa.com', 'requerido' => true, 'sistema' => true, 'ancho' => 'completo'],
            ['clave' => 'sec_diagnostico', 'tipo' => 'seccion', 'etiqueta' => 'Paso 2: Diagnóstico del Problema', 'placeholder' => '', 'requerido' => false, 'sistema' => false, 'ancho' => 'completo'],
            ['clave' => 'descripcion_problema', 'tipo' => 'textarea', 'etiqueta' => 'Explica el problema y la solución que buscas de forma detallada:', 'placeholder' => 'Ej. Actualmente llevamos el inventario en un cuaderno y perdemos mucho tiempo cuadrando caja. Necesitamos un sistema de escritorio rápido que nos permita registrar entradas y salidas con reportes en PDF...', 'requerido' => true, 'sistema' => true, 'ancho' => 'completo'],
        ];
    }

    public static function esClaveSistema(string $clave): bool {
        return isset(self::SISTEMA[$clave]);
    }

    public static function esRequeridoFijo(string $clave): bool {
        return !empty(self::SISTEMA[$clave]['requerido_fijo']);
    }

    /** Devuelve la definición vigente (saneada) del formulario. */
    public static function getCampos(): array {
        $guardados = VinculacionConfigService::get(self::CLAVE_CONFIG, null);
        if (!is_array($guardados) || empty($guardados)) {
            return self::camposPorDefecto();
        }
        return self::sanear($guardados);
    }

    /**
     * Sanea una definición recibida (del JSON de configuración o del panel admin).
     * Garantiza que todos los campos de sistema existan una sola vez con su tipo fijo.
     */
    public static function sanear(array $entrada): array {
        $resultado = [];
        $vistas = [];
        $porDefecto = [];
        foreach (self::camposPorDefecto() as $d) {
            $porDefecto[$d['clave']] = $d;
        }

        foreach ($entrada as $c) {
            if (!is_array($c)) continue;
            $tipo = (string)($c['tipo'] ?? 'texto');
            $etiqueta = self::limpiarTexto((string)($c['etiqueta'] ?? ''), 200);
            $clave = self::normalizarClave((string)($c['clave'] ?? ''));

            if ($clave !== '' && self::esClaveSistema($clave)) {
                $tipo = self::SISTEMA[$clave]['tipo'];
                $sistema = true;
            } else {
                if (!isset(self::TIPOS[$tipo])) $tipo = 'texto';
                $sistema = false;
                if ($clave === '' || isset($porDefecto[$clave]) && $porDefecto[$clave]['tipo'] !== 'seccion') {
                    $clave = self::generarClave($etiqueta, $tipo, $vistas);
                }
                // Los campos personalizados siempre llevan prefijo para no colisionar con columnas reales
                if (!str_starts_with($clave, 'extra_') && !str_starts_with($clave, 'sec_')) {
                    $clave = ($tipo === 'seccion' ? 'sec_' : 'extra_') . $clave;
                }
            }

            if (isset($vistas[$clave])) {
                if ($sistema) continue; // sistema duplicado: se ignora la segunda aparición
                $clave = self::generarClave($etiqueta, $tipo, $vistas);
            }
            if ($etiqueta === '') {
                $etiqueta = $sistema ? $porDefecto[$clave]['etiqueta'] : 'Campo sin título';
            }

            $requerido = $tipo === 'seccion' ? false : !empty($c['requerido']);
            if ($sistema && self::esRequeridoFijo($clave)) $requerido = true;

            $ancho = ($c['ancho'] ?? 'completo') === 'medio' ? 'medio' : 'completo';
            if ($tipo === 'seccion' || $tipo === 'textarea') $ancho = 'completo';

            $vistas[$clave] = true;
            $resultado[] = [
                'clave'       => $clave,
                'tipo'        => $tipo,
                'etiqueta'    => $etiqueta,
                'placeholder' => $tipo === 'seccion' ? '' : self::limpiarTexto((string)($c['placeholder'] ?? ''), 255),
                'requerido'   => $requerido,
                'sistema'     => $sistema,
                'ancho'       => $ancho,
            ];
        }

        // Reponer campos de sistema faltantes (al final, con su configuración original)
        foreach (array_keys(self::SISTEMA) as $claveSis) {
            if (!isset($vistas[$claveSis])) {
                $resultado[] = $porDefecto[$claveSis];
            }
        }
        return $resultado;
    }

    /** Persiste la definición (saneada) en config_vinculacion.json */
    public static function guardar(array $campos): bool {
        return VinculacionConfigService::set(self::CLAVE_CONFIG, self::sanear($campos));
    }

    /**
     * Valida un envío del formulario público según la configuración vigente.
     * @return array{core: array, extras: array, errores: array, valores: array}
     */
    public static function validarEnvio(array $post): array {
        $core = [];
        $extras = [];
        $errores = [];
        $valores = [];

        foreach (self::getCampos() as $c) {
            if ($c['tipo'] === 'seccion') continue;
            $clave = $c['clave'];
            $raw = $post[$clave] ?? '';
            $valor = is_string($raw) ? trim($raw) : '';
            $valor = str_replace("\0", '', $valor);
            $valores[$clave] = $valor;
            $etq = $c['etiqueta'];

            if ($valor === '') {
                if ($c['requerido']) $errores[] = "El campo \"{$etq}\" es obligatorio.";
            } else {
                $error = self::validarTipo($c['tipo'], $valor, $etq);
                if ($error !== null) $errores[] = $error;
            }

            if ($c['sistema']) {
                $core[$clave] = $valor;
            } elseif ($valor !== '') {
                if ($c['tipo'] === 'correo') $valor = strtolower($valor);
                $extras[] = ['clave' => $clave, 'etiqueta' => $etq, 'tipo' => $c['tipo'], 'valor' => $valor];
            }
        }

        // Asegurar columnas NOT NULL aunque el admin haya hecho opcional algún campo de sistema
        foreach (array_keys(self::SISTEMA) as $claveSis) {
            if (!array_key_exists($claveSis, $core)) $core[$claveSis] = '';
        }

        return ['core' => $core, 'extras' => $extras, 'errores' => $errores, 'valores' => $valores];
    }

    public static function validarTipo(string $tipo, string $valor, string $etiqueta): ?string {
        $max = self::MAX_LONGITUD[$tipo] ?? self::MAX_LONGITUD['default'];
        if (mb_strlen($valor) > $max) {
            return "El campo \"{$etiqueta}\" supera la longitud máxima de {$max} caracteres.";
        }
        switch ($tipo) {
            case 'numero':
                if (!preg_match('/^-?\d+([.,]\d+)?$/', $valor)) return "El campo \"{$etiqueta}\" debe ser un número válido.";
                break;
            case 'alfanumerico':
                if (!preg_match('/^[\p{L}\p{N}\s\-\.\/#]+$/u', $valor)) return "El campo \"{$etiqueta}\" solo admite letras, números, espacios y los signos - . / #";
                break;
            case 'fecha':
                $d = DateTime::createFromFormat('!Y-m-d', $valor);
                if (!$d || $d->format('Y-m-d') !== $valor) return "El campo \"{$etiqueta}\" debe ser una fecha válida (AAAA-MM-DD).";
                break;
            case 'correo':
                if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) return "El campo \"{$etiqueta}\" debe ser un correo electrónico válido.";
                break;
            case 'telefono':
                if (!preg_match('/^\+?[0-9\s\-\(\)]{7,20}$/', $valor)) return "El campo \"{$etiqueta}\" debe ser un teléfono válido (7 a 20 dígitos; se permiten + - ( ) y espacios).";
                break;
        }
        return null;
    }

    /** Renderiza el HTML de un campo para el formulario público. */
    public static function renderInput(array $c, string $valor = ''): string {
        $id = htmlspecialchars($c['clave'], ENT_QUOTES, 'UTF-8');
        $ph = htmlspecialchars($c['placeholder'], ENT_QUOTES, 'UTF-8');
        $val = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        $req = $c['requerido'] ? ' required' : '';
        $etq = htmlspecialchars($c['etiqueta'], ENT_QUOTES, 'UTF-8');
        $marca = $c['requerido'] ? ' <span style="color:#e11d48;" title="Obligatorio">*</span>' : '';
        $html = "<label class=\"ve-form-label\" for=\"{$id}\">{$etq}{$marca}</label>\n";

        switch ($c['tipo']) {
            case 'textarea':
                return $html . "<textarea id=\"{$id}\" name=\"{$id}\" class=\"ve-textarea\" placeholder=\"{$ph}\" maxlength=\"5000\"{$req}>{$val}</textarea>";
            case 'numero':
                return $html . "<input type=\"number\" step=\"any\" id=\"{$id}\" name=\"{$id}\" class=\"ve-input\" placeholder=\"{$ph}\" value=\"{$val}\"{$req}>";
            case 'fecha':
                return $html . "<input type=\"date\" id=\"{$id}\" name=\"{$id}\" class=\"ve-input\" value=\"{$val}\"{$req}>";
            case 'correo':
                return $html . "<input type=\"email\" id=\"{$id}\" name=\"{$id}\" class=\"ve-input\" placeholder=\"{$ph}\" value=\"{$val}\" maxlength=\"255\"{$req}>";
            case 'telefono':
                return $html . "<input type=\"tel\" id=\"{$id}\" name=\"{$id}\" class=\"ve-input\" placeholder=\"{$ph}\" value=\"{$val}\" pattern=\"\\+?[0-9\\s\\-\\(\\)]{7,20}\" title=\"Solo dígitos, espacios y los signos + - ( )\" maxlength=\"20\"{$req}>";
            case 'alfanumerico':
                return $html . "<input type=\"text\" id=\"{$id}\" name=\"{$id}\" class=\"ve-input\" placeholder=\"{$ph}\" value=\"{$val}\" pattern=\"[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\\s\\-\\.\\/#]+\" title=\"Solo letras, números, espacios y los signos - . / #\" maxlength=\"255\"{$req}>";
            default:
                return $html . "<input type=\"text\" id=\"{$id}\" name=\"{$id}\" class=\"ve-input\" placeholder=\"{$ph}\" value=\"{$val}\" maxlength=\"255\"{$req}>";
        }
    }

    /**
     * Agrupa los campos para el render: secciones, filas de 2 campos 'medio' y campos completos.
     * @return array<int, array{tipo: string, campos: array}>
     */
    public static function agruparParaRender(array $campos): array {
        $bloques = [];
        $fila = [];
        foreach ($campos as $c) {
            if ($c['tipo'] !== 'seccion' && $c['ancho'] === 'medio') {
                $fila[] = $c;
                if (count($fila) === 2) {
                    $bloques[] = ['tipo' => 'fila', 'campos' => $fila];
                    $fila = [];
                }
                continue;
            }
            if ($fila) {
                $bloques[] = ['tipo' => 'fila', 'campos' => $fila];
                $fila = [];
            }
            $bloques[] = ['tipo' => $c['tipo'] === 'seccion' ? 'seccion' : 'campo', 'campos' => [$c]];
        }
        if ($fila) $bloques[] = ['tipo' => 'fila', 'campos' => $fila];
        return $bloques;
    }

    /** Decodifica propuestas_empresa.campos_extra a arreglo [{clave,etiqueta,tipo,valor}] */
    public static function decodificarExtras(mixed $json): array {
        if (is_array($json)) return $json;
        if (!is_string($json) || $json === '') return [];
        $data = json_decode($json, true);
        if (!is_array($data)) return [];
        return array_values(array_filter($data, fn($x) => is_array($x) && isset($x['etiqueta']) && array_key_exists('valor', $x)));
    }

    /** Formatea un valor para mostrarlo (p. ej. fechas a dd/mm/aaaa). */
    public static function formatearValor(array $extra): string {
        $v = (string)($extra['valor'] ?? '');
        if (($extra['tipo'] ?? '') === 'fecha') {
            $d = DateTime::createFromFormat('!Y-m-d', $v);
            if ($d) return $d->format('d/m/Y');
        }
        return $v;
    }

    /** Bloque HTML (estilos en línea) con los campos extra, apto para correo. */
    public static function extrasHtmlCorreo(array $extras): string {
        if (empty($extras)) return '';
        $html = "<p style='margin: 10px 0 5px 0;'><b>Información adicional suministrada:</b></p><ul style='margin:0; padding-left:18px;'>";
        foreach ($extras as $e) {
            $html .= "<li><b>" . htmlspecialchars((string)$e['etiqueta']) . ":</b> " . nl2br(htmlspecialchars(self::formatearValor($e))) . "</li>";
        }
        return $html . "</ul>";
    }

    private static function limpiarTexto(string $s, int $max): string {
        $s = trim(strip_tags($s));
        $s = preg_replace('/\s+/u', ' ', $s) ?? '';
        return mb_substr($s, 0, $max);
    }

    private static function normalizarClave(string $clave): string {
        $clave = strtolower(trim($clave));
        return preg_match('/^[a-z][a-z0-9_]{0,60}$/', $clave) ? $clave : '';
    }

    private static function generarClave(string $etiqueta, string $tipo, array $existentes): string {
        $base = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $etiqueta) ?: '');
        $base = trim(preg_replace('/[^a-z0-9]+/', '_', $base) ?? '', '_');
        $base = substr($base !== '' ? $base : 'campo', 0, 40);
        $pref = $tipo === 'seccion' ? 'sec_' : 'extra_';
        $clave = $pref . $base;
        $i = 2;
        while (isset($existentes[$clave]) || self::esClaveSistema($clave)) {
            $clave = $pref . $base . '_' . $i++;
        }
        return $clave;
    }
}
