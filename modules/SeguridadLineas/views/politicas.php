<?php
// modules/SeguridadLineas/views/politicas.php
// Políticas globales del macro módulo: contención, registro, detección y mantenimiento de la bitácora.

require __DIR__ . '/_cabecera.php';

if (empty($slError)):
    $rw  = !empty($slPuede['configurar']);
    $p   = $slPol;
    $dis = $rw ? '' : 'disabled';
    $chk = fn(string $k) => !empty($p[$k]) ? 'checked' : '';
?>

<form id="slFormPoliticas" autocomplete="off">
<div class="sl-grid sl-grid-2">

    <!-- Contención -->
    <div class="sl-card is-danger">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-shield-warning"></i>Contención de emergencia</span></div>
        <div class="sl-form">
            <label class="sl-switch is-danger">
                <input type="checkbox" name="modo_bloqueo" value="1" <?= $chk('modo_bloqueo') ?> <?= $dis ?>>
                <span class="sl-knob"></span>
                <span><b>Lockdown</b><small>Cierra todo el macro módulo a los niveles superiores al máximo permitido. Los SuperAdmin (nivel 0) nunca quedan fuera.</small></span>
            </label>
            <div class="sl-form-grid">
                <div class="sl-field">
                    <label>Nivel máximo permitido en Lockdown</label>
                    <input class="sl-input" type="number" name="bloqueo_nivel_maximo" min="0" max="998" value="<?= (int) $p['bloqueo_nivel_maximo'] ?>" <?= $dis ?>>
                    <span class="sl-help">Tu nivel: <?= sl_e(sl_nivel($slCtx['nivel'])) ?></span>
                </div>
            </div>
            <div class="sl-field">
                <label>Mensaje mostrado durante el Lockdown</label>
                <textarea class="sl-textarea" name="bloqueo_mensaje" maxlength="400" <?= $dis ?>><?= sl_e($p['bloqueo_mensaje']) ?></textarea>
            </div>
            <label class="sl-switch is-danger">
                <input type="checkbox" name="solo_lectura" value="1" <?= $chk('solo_lectura') ?> <?= $dis ?>>
                <span class="sl-knob"></span>
                <span><b>Modo Solo Lectura</b><small>Congela crear, editar, eliminar y configurar en Líneas, Investigaciones, Formación y Sector Productivo. La consulta sigue disponible.</small></span>
            </label>
        </div>
    </div>

    <!-- Detección -->
    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-radar"></i>Detección de intrusiones</span></div>
        <div class="sl-form">
            <div class="sl-form-grid">
                <div class="sl-field">
                    <label>Umbral de denegaciones</label>
                    <input class="sl-input" type="number" name="umbral_denegaciones" min="1" max="1000" value="<?= (int) $p['umbral_denegaciones'] ?>" <?= $dis ?>>
                </div>
                <div class="sl-field">
                    <label>Ventana (minutos)</label>
                    <input class="sl-input" type="number" name="ventana_minutos" min="1" max="1440" value="<?= (int) $p['ventana_minutos'] ?>" <?= $dis ?>>
                </div>
            </div>
            <label class="sl-switch">
                <input type="checkbox" name="auto_bloqueo_ip" value="1" <?= $chk('auto_bloqueo_ip') ?> <?= $dis ?>>
                <span class="sl-knob"></span>
                <span><b>Auto-bloqueo de IP</b><small>Al superar el umbral se genera una alerta crítica y la IP se bloquea automáticamente.</small></span>
            </label>
            <div class="sl-form-grid">
                <div class="sl-field">
                    <label>Duración del auto-bloqueo (min)</label>
                    <input class="sl-input" type="number" name="duracion_bloqueo_min" min="1" max="43200" value="<?= (int) $p['duracion_bloqueo_min'] ?>" <?= $dis ?>>
                </div>
            </div>
            <div class="sl-field">
                <label>IPs confiables (nunca se auto-bloquean)</label>
                <textarea class="sl-textarea" name="ips_confiables" placeholder="127.0.0.1&#10;192.168.1.10" <?= $dis ?>><?= sl_e(implode("\n", (array) $p['ips_confiables'])) ?></textarea>
                <span class="sl-help">Una por línea o separadas por coma.</span>
            </div>
        </div>
    </div>

    <!-- Registro -->
    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-notebook"></i>Registro y telemetría</span></div>
        <div class="sl-form">
            <label class="sl-switch">
                <input type="checkbox" name="registrar_lecturas" value="1" <?= $chk('registrar_lecturas') ?> <?= $dis ?>>
                <span class="sl-knob"></span>
                <span><b>Registrar lecturas</b><small>Además de las denegaciones y acciones mutantes, registra cada consulta permitida.</small></span>
            </label>
            <label class="sl-switch">
                <input type="checkbox" name="registrar_visitantes" value="1" <?= $chk('registrar_visitantes') ?> <?= $dis ?>>
                <span class="sl-knob"></span>
                <span><b>Registrar visitantes anónimos</b><small>Incluye las lecturas de páginas públicas realizadas sin sesión.</small></span>
            </label>
            <div class="sl-form-grid">
                <div class="sl-field">
                    <label>Retención (días)</label>
                    <input class="sl-input" type="number" name="retencion_dias" min="1" max="3650" value="<?= (int) $p['retencion_dias'] ?>" <?= $dis ?>>
                    <span class="sl-help">Purga automática diaria.</span>
                </div>
                <div class="sl-field">
                    <label>Refresco del feed (s)</label>
                    <input class="sl-input" type="number" name="refresco_feed_seg" min="2" max="120" value="<?= (int) $p['refresco_feed_seg'] ?>" <?= $dis ?>>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadena -->
    <div class="sl-card">
        <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-link-simple"></i>Cadena de custodia</span></div>
        <dl class="sl-dl">
            <dt>Algoritmo</dt><dd>HMAC-SHA256 encadenado (APP_KEY)</dd>
            <dt>Eventos</dt><dd><?= number_format((int) ($kpis['total_eventos'] ?? 0), 0, ',', '.') ?></dd>
            <dt>Último ID</dt><dd>#<?= (int) ($kpis['ultimo_id'] ?? 0) ?></dd>
            <dt>Ancla</dt><dd class="sl-hash"><?= sl_e($p['ancla_cadena'] ?: SeguridadMotor::GENESIS . ' (génesis)') ?></dd>
            <dt>Última purga</dt><dd><?= sl_e($p['ultima_purga'] ?: 'nunca') ?></dd>
        </dl>
        <div class="sl-row sl-mt">
            <button type="button" class="sl-btn sl-btn-cyan" data-sl-verify <?= $slPuede['exportar'] ? '' : 'disabled' ?>><i class="ph ph-fingerprint"></i>Verificar integridad</button>
        </div>
        <div id="slVerifyOut" class="sl-mt-sm"></div>
    </div>
</div>

<?php if ($rw): ?>
    <div class="sl-row sl-mt" style="justify-content:flex-end">
        <button type="reset" class="sl-btn sl-btn-ghost"><i class="ph ph-arrow-counter-clockwise"></i>Deshacer</button>
        <button type="submit" class="sl-btn"><i class="ph ph-floppy-disk"></i>Guardar políticas</button>
    </div>
<?php endif; ?>
</form>

<?php if ($rw): ?>
<div class="sl-card is-danger sl-mt">
    <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-skull"></i>Zona peligrosa · purga manual</span></div>
    <p class="sl-card-desc">Elimina eventos de la bitácora anteriores a N días (0 = todos). La cadena se re-ancla para seguir siendo verificable y la purga queda registrada como evento crítico.</p>
    <form class="sl-form-grid" id="slFormPurga" autocomplete="off">
        <div class="sl-field">
            <label>Antigüedad (días)</label>
            <input class="sl-input" type="number" name="dias" min="0" max="3650" value="<?= (int) $p['retencion_dias'] ?>">
        </div>
        <div class="sl-field">
            <label>Escribe PURGAR para confirmar</label>
            <input class="sl-input" name="confirmacion" placeholder="PURGAR">
        </div>
        <div class="sl-field" style="justify-content:flex-end">
            <button type="submit" class="sl-btn sl-btn-danger"><i class="ph ph-trash"></i>Ejecutar purga</button>
        </div>
    </form>
</div>
<?php endif; ?>

<?php endif; ?>

<?php require __DIR__ . '/_pie.php'; ?>
