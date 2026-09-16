<?php
$archivo_mant_widget = defined('STORAGE_PATH') ? STORAGE_PATH . 'maintenance.json' : __DIR__ . '/../../../storage/maintenance.json';
$dataWidget = file_exists($archivo_mant_widget) ? json_decode(file_get_contents($archivo_mant_widget), true) : [];
$isActWidget = $dataWidget['activo'] ?? false;
$fechaInicioWidget = $dataWidget['fecha_inicio'] ?? null;
$tsInicioWidget = !empty($fechaInicioWidget) ? strtotime($fechaInicioWidget) : 0;
$ahoraTs = time();
$mostrarCountdown = (!$isActWidget && $tsInicioWidget > $ahoraTs);
?>

<?php if ($mostrarCountdown): ?>
    <div id="main-header-countdown" style="background: rgba(80, 89, 132, 0.08); color: var(--color-secundario, #2563eb); border: 1px solid rgba(80, 89, 132, 0.2); padding: 3px 10px; border-radius: 6px; font-size: 0.73rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; height: 28px;" title="Mantenimiento Programado Próximo">
        <i class="ph-bold ph-timer" style="font-size: 0.85rem; color: var(--color-secundario);"></i> Mant: <span id="main-header-clock" style="font-family: monospace; font-size: 0.8rem; font-weight: 800;">...</span>
    </div>
    <script>
    (function() {
        const targetTime = new Date("<?= date('Y-m-d\TH:i:s', $tsInicioWidget) ?>").getTime();
        function updateClockHeader() {
            const now = new Date().getTime();
            const diff = targetTime - now;
            const clockEl = document.getElementById('main-header-clock');
            const containerEl = document.getElementById('main-header-countdown');
            if (!clockEl || !containerEl) return;
            
            if (diff <= 0) {
                containerEl.style.display = 'none'; // Desaparece cuando expira o inicia
                return;
            }
            const hrs = Math.floor(diff / (1000 * 60 * 60));
            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const secs = Math.floor((diff % (1000 * 60)) / 1000);
            clockEl.textContent = 
                `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        updateClockHeader();
        setInterval(updateClockHeader, 1000);
    })();
    </script>
<?php endif; ?>
