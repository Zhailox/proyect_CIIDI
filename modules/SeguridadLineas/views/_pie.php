<?php
// modules/SeguridadLineas/views/_pie.php
// Cierre común: pie, contenedor de notificaciones, modal y configuración para el JS del módulo.

$slConfigJs = [
    'csrf'     => $slCsrf ?? '',
    'refresco' => max(2, (int) ($slPol['refresco_feed_seg'] ?? 5)),
    'puede'    => $slPuede ?? [],
    'pagina'   => $slPagina ?? 'soc',
    'ip'       => $slCtx['ip'] ?? '',
    'usuarioId'=> $slCtx['id'] ?? null,
    'error'    => !empty($slError),
];
?>
    </section>

    <footer class="sl-foot">
        <span>SEC::LINEAS v1 · Cadena HMAC-SHA256 · Guardián de rutas del core</span>
        <span>Operador: <?= sl_e($slCtx['nombre'] ?? '') ?> · <?= sl_e(sl_nivel($slCtx['nivel'] ?? 999)) ?> · <?= sl_e(date('Y-m-d H:i:s')) ?></span>
    </footer>

    <div class="sl-toasts" id="slToasts" aria-live="polite"></div>

    <div class="sl-modal" id="slModal" hidden>
        <div class="sl-modal-box" role="dialog" aria-modal="true">
            <div class="sl-modal-head">
                <span id="slModalTitle">&gt; sistema</span>
                <button type="button" class="sl-btn sl-btn-ghost sl-btn-sm" data-sl-close><i class="ph ph-x"></i></button>
            </div>
            <div class="sl-modal-body" id="slModalBody"></div>
            <div class="sl-modal-foot" id="slModalFoot"></div>
        </div>
    </div>
</div>

<script>
    window.SEG_LINEAS = <?= json_encode($slConfigJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    try {
        localStorage.removeItem('sl_theme');
        sessionStorage.removeItem('sl_hacker_time');
    } catch (e) {}
</script>


