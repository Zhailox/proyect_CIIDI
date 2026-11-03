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

                    // --- Easter Egg: Toggle Hacker Theme & Audio ---
    document.addEventListener('DOMContentLoaded', () => {
        const titleEl = document.getElementById('sec-lineas-title');
        const appEl = document.getElementById('sec-lineas-app');
        
        // Elemento de audio con ruta relativa correcta
        const easterEggAudio = new Audio('../modules/SeguridadLineas/assets/audio/cancion_hacker.mp3');
        easterEggAudio.loop = true;
        let autoplayBlocked = false;

        // Guardar el tiempo actual continuamente para que al cambiar de pestaa no se pierda
        easterEggAudio.addEventListener('timeupdate', () => {
            if (!easterEggAudio.paused) {
                sessionStorage.setItem('sl_hacker_time', easterEggAudio.currentTime);
            }
        });

        // Al cargar la pgina, revisar si el modo est activo
        if (localStorage.getItem('sl_theme') === 'hacker') {
            appEl.classList.add('theme-hacker');
            
            // Restaurar el segundo exacto en el que iba la cancin
            const savedTime = sessionStorage.getItem('sl_hacker_time');
            if (savedTime) {
                easterEggAudio.currentTime = parseFloat(savedTime);
            }

            easterEggAudio.play().catch(e => { 
                autoplayBlocked = true;
                console.log('El autoplay fue bloqueado por el navegador tras recargar la pgina. Haz clic en cualquier parte para reanudar la cancin.'); 
            });
        }

        // Si el autoplay se bloque al cambiar de pestaa, reanudar al primer clic en la pantalla
        document.body.addEventListener('click', () => {
            if (autoplayBlocked && localStorage.getItem('sl_theme') === 'hacker') {
                easterEggAudio.play().catch(e => {});
                autoplayBlocked = false;
            }
        });

        if (titleEl && appEl) {
            titleEl.addEventListener('click', () => {
                appEl.classList.toggle('theme-hacker');
                if (appEl.classList.contains('theme-hacker')) {
                    localStorage.setItem('sl_theme', 'hacker');
                    easterEggAudio.currentTime = 0; // Al activarlo manualmente, empieza de cero
                    easterEggAudio.play().catch(e => { console.error('Error de audio:', e); }); window.dispatchEvent(new Event('resize'));
                } else {
                    localStorage.removeItem('sl_theme');
                    sessionStorage.removeItem('sl_hacker_time');
                    easterEggAudio.pause();
                    easterEggAudio.currentTime = 0;
                    console.log('Audio pausado y modo desactivado.');
                }
            });
        }
    });
</script>


