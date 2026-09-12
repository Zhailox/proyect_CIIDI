<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.75rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.4rem;">
                <i class="ph-bold ph-shield-check"></i> CONSOLA ADMINISTRATIVA CENTRAL
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: #121a3e !important; margin: 0; line-height: 1.2;">
                Panel de Super Administrador
            </h1>
            <p style="margin: 0.4rem 0 0 0; color: #64748b !important; font-size: 0.95rem;">
                Supervisión global del sistema y métricas en tiempo real.
            </p>
        </div>

        <div>
            <button type="button" onclick="ejecutarTestCore()" class="btn btn-primary" style="background: #2563eb !important; border: none !important; color: #ffffff !important; font-weight: 700; padding: 10px 18px; border-radius: 8px; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(37,99,235,0.25); cursor: pointer; transition: all 0.25s ease;">
                <i class="ph-bold ph-heartbeat"></i> Testear Respuesta del Core
            </button>
        </div>
    </div>
</div>

<!-- TOAST CONTAINER FLOTANTE -->
<div id="sa-toast-container" class="sa-toast-container"></div>

<!-- ACCESOS DIRECTOS POR RESPONSABILIDAD (CLEAN & DIRECT WORKFLOW) -->
<div class="sa-quick-grid mb-2">
    <a href="gestor-usuarios" class="sa-quick-card glass-card hover-glow" style="border-radius: var(--radius-sm); text-decoration: none;">
        <div class="sa-quick-icon" style="background: rgba(80, 89, 132, 0.15); color: var(--color-secundario);">
            <i class="ph-bold ph-users-three"></i>
        </div>
        <div>
            <h4 style="margin:0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Gestión de Usuarios & RBAC</h4>
            <p style="margin:3px 0 0 0; font-size: 0.78rem; color: var(--texto-silenciado);">Credenciales, matriz de permisos y revocación de sesiones.</p>
        </div>
    </a>

    <a href="gestor-modulos" class="sa-quick-card glass-card hover-glow" style="border-radius: var(--radius-sm); text-decoration: none;">
        <div class="sa-quick-icon" style="background: rgba(112, 144, 203, 0.15); color: var(--color-terciario);">
            <i class="ph-bold ph-squares-four"></i>
        </div>
        <div>
            <h4 style="margin:0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Gestor de Módulos & Rutas</h4>
            <p style="margin:3px 0 0 0; font-size: 0.78rem; color: var(--texto-silenciado);">Feature flags por ruta y alternancia de paquetes.</p>
        </div>
    </a>

    <a href="gestor-mantenimiento" class="sa-quick-card glass-card hover-glow" style="border-radius: var(--radius-sm); text-decoration: none;">
        <div class="sa-quick-icon" style="background: rgba(245, 158, 11, 0.15); color: #d97706;">
            <i class="ph-bold ph-wrench"></i>
        </div>
        <div>
            <h4 style="margin:0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Mantenimiento & Respaldos BD</h4>
            <p style="margin:3px 0 0 0; font-size: 0.78rem; color: var(--texto-silenciado);">Dump PostgreSQL, respaldo por tablas y modo mantenimiento.</p>
        </div>
    </a>

    <a href="gestor-scheduler" class="sa-quick-card glass-card hover-glow" style="border-radius: var(--radius-sm); text-decoration: none;">
        <div class="sa-quick-icon" style="background: rgba(16, 185, 129, 0.15); color: #059669;">
            <i class="ph-bold ph-clock-clockwise"></i>
        </div>
        <div>
            <h4 style="margin:0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Tareas Programadas & Cron</h4>
            <p style="margin:3px 0 0 0; font-size: 0.78rem; color: var(--texto-silenciado);">Procesos automáticos, limpiezas de temporales y backups.</p>
        </div>
    </a>

    <a href="visor-seguridad" class="sa-quick-card glass-card hover-glow" style="border-radius: var(--radius-sm); text-decoration: none;">
        <div class="sa-quick-icon" style="background: rgba(220, 38, 38, 0.15); color: #dc2626;">
            <i class="ph-bold ph-shield-warning"></i>
        </div>
        <div>
            <h4 style="margin:0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Monitor WAF & IPs</h4>
            <p style="margin:3px 0 0 0; font-size: 0.78rem; color: var(--texto-silenciado);">Control de fuerza bruta, lista negra de IPs e integridad SHA-256.</p>
        </div>
    </a>

    <a href="visor-logs" class="sa-quick-card glass-card hover-glow" style="border-radius: var(--radius-sm); text-decoration: none;">
        <div class="sa-quick-icon" style="background: rgba(18, 26, 62, 0.15); color: var(--color-principal);">
            <i class="ph-bold ph-shield-check"></i>
        </div>
        <div>
            <h4 style="margin:0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos);">Visor de Logs & Audit Trail</h4>
            <p style="margin:3px 0 0 0; font-size: 0.78rem; color: var(--texto-silenciado);">Histórico de eventos, trazabilidad y descargas PDF/CSV.</p>
        </div>
    </a>
</div>

<!-- TELEMETRÍA TÉCNICA DEL SERVIDOR & CONSUMO DE RECURSOS -->
<h3 class="admin-section-title" style="margin-top: 0; display: flex; align-items: center; gap: 8px;">
    <i class="ph-bold ph-cpu" style="color: var(--color-secundario);"></i> Telemetría Técnica & Estado del Servidor
</h3>

<!-- GRID DE MÉTRICAS RÁPIDAS (6 TARJETAS EN TOTAL) -->
<div class="sa-telemetry-grid mb-2">
    <div class="sa-telemetry-card glass-card" style="border-radius: var(--radius-sm);">
        <div class="sa-telemetry-icon" style="background: rgba(112, 144, 203, 0.15); color: var(--color-terciario);">
            <i class="ph-bold ph-hard-drives"></i>
        </div>
        <div class="sa-telemetry-info">
            <h4>Ocupación `storage/`</h4>
            <div class="sa-telemetry-val"><?= $telemetria['storage_mb'] ?> MB</div>
            <div class="sa-telemetry-sub"><?= $telemetria['files_count'] ?> archivos (<?= $telemetria['disk_free'] ?>)</div>
        </div>
    </div>

    <div class="sa-telemetry-card glass-card" style="border-radius: var(--radius-sm);">
        <div class="sa-telemetry-icon" style="background: rgba(80, 89, 132, 0.15); color: var(--color-secundario);">
            <i class="ph-bold ph-database"></i>
        </div>
        <div class="sa-telemetry-info">
            <h4>Base de Datos PostgreSQL</h4>
            <div class="sa-telemetry-val"><?= $telemetria['db_size'] ?></div>
            <div class="sa-telemetry-sub">Conex: <?= $telemetria['active_connections'] ?>/<?= $telemetria['max_connections'] ?> (<?= htmlspecialchars($telemetria['db_name']) ?>)</div>
        </div>
    </div>

    <div class="sa-telemetry-card glass-card" style="border-radius: var(--radius-sm);">
        <div class="sa-telemetry-icon" style="background: rgba(18, 26, 62, 0.15); color: var(--color-principal);">
            <i class="ph-bold ph-gauge"></i>
        </div>
        <div class="sa-telemetry-info">
            <h4>Memoria RAM PHP</h4>
            <div class="sa-telemetry-val"><?= $telemetria['memory_usage_mb'] ?> MB</div>
            <div class="sa-telemetry-sub">Pico: <?= $telemetria['memory_peak_mb'] ?> MB</div>
        </div>
    </div>

    <div class="sa-telemetry-card glass-card" style="border-radius: var(--radius-sm);">
        <div class="sa-telemetry-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
            <i class="ph-bold ph-chart-line-up"></i>
        </div>
        <div class="sa-telemetry-info">
            <h4>Accesos de Usuarios Hoy</h4>
            <div class="sa-telemetry-val"><?= $stats['accesos_hoy'] ?></div>
            <div class="sa-telemetry-sub"><?= $stats['usuarios_online'] ?> en vivo (15 min)</div>
        </div>
    </div>

    <div class="sa-telemetry-card glass-card" style="border-radius: var(--radius-sm);">
        <div class="sa-telemetry-icon" style="background: rgba(245, 158, 11, 0.15); color: #d97706;">
            <i class="ph-bold ph-squares-four"></i>
        </div>
        <div class="sa-telemetry-info">
            <h4>Estado de Módulos</h4>
            <div class="sa-telemetry-val"><?= $stats['modulos_activos'] ?> / <?= $stats['modulos_total'] ?></div>
            <div class="sa-telemetry-sub">Módulos en línea en Kernel</div>
        </div>
    </div>

    <div class="sa-telemetry-card glass-card" style="border-radius: var(--radius-sm);">
        <div class="sa-telemetry-icon" style="background: rgba(80, 89, 132, 0.15); color: var(--color-secundario);">
            <i class="ph-bold ph-code"></i>
        </div>
        <div class="sa-telemetry-info">
            <h4>Entorno de Ejecución</h4>
            <div class="sa-telemetry-val">PHP v<?= $telemetria['php_version'] ?></div>
            <div class="sa-telemetry-sub">PGSQL <?= htmlspecialchars($telemetria['pg_version']) ?> (<?= $telemetria['pg_status'] ?>)</div>
        </div>
    </div>
</div>

<!-- SECCIÓN GRÁFICA & FEED EN VIVO DE AUDITORÍA (DISPOSICIÓN EN 3 COLUMNAS/PANELES) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;" class="mb-2">
    
    <!-- 1. GRÁFICO DISTRIBUCIÓN DE USUARIOS -->
    <div class="glass-panel" style="padding: 1.25rem; border-radius: var(--radius-sm);">
        <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-chart-pie" style="color: var(--color-terciario);"></i> Distribución de Usuarios del Sistema
        </h4>
        <div style="height: 220px; position: relative;">
            <canvas id="saUserPieChart"></canvas>
        </div>
    </div>

    <!-- 2. GRÁFICO TELEMETRÍA DE RECURSOS -->
    <div class="glass-panel" style="padding: 1.25rem; border-radius: var(--radius-sm);">
        <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-chart-bar" style="color: var(--color-secundario);"></i> Consumo de Recursos del Servidor (MB)
        </h4>
        <div style="height: 220px; position: relative;">
            <canvas id="saResourceBarChart"></canvas>
        </div>
    </div>

    <!-- 3. FEED DE EVENTOS DE AUDITORÍA EN VIVO -->
    <div class="glass-panel" style="padding: 1.25rem; border-radius: var(--radius-sm); display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-shield-check" style="color: var(--color-principal);"></i> Eventos de Auditoría Recientes
            </h4>
            <a href="visor-logs" style="font-size: 0.78rem; font-weight: 600; color: var(--color-terciario); text-decoration: none;">Ver todo →</a>
        </div>

        <div style="flex-grow: 1; overflow-y: auto; max-height: 210px; display: flex; flex-direction: column; gap: 0.5rem;">
            <?php if (empty($ultimosLogs)): ?>
                <p style="font-size: 0.8rem; color: #94a3b8; margin: auto; text-align: center;">Sin registro de auditoría en storage/system_audit.json</p>
            <?php else: ?>
                <?php foreach ($ultimosLogs as $logItem): ?>
                    <?php 
                    $badgeColor = '#64748b';
                    $bgBadge = 'rgba(100, 116, 139, 0.1)';
                    $lvl = strtoupper($logItem['nivel'] ?? 'INFO');
                    if ($lvl === 'CRITICAL' || $lvl === 'ERROR') {
                        $badgeColor = '#ef4444'; $bgBadge = 'rgba(239, 68, 68, 0.12)';
                    } elseif ($lvl === 'WARNING') {
                        $badgeColor = '#d97706'; $bgBadge = 'rgba(245, 158, 11, 0.12)';
                    } elseif ($lvl === 'INFO') {
                        $badgeColor = '#10b981'; $bgBadge = 'rgba(16, 185, 129, 0.12)';
                    }
                    ?>
                    <div style="background: rgba(248, 250, 252, 0.8); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 6px; padding: 6px 10px; font-size: 0.78rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                            <span style="font-weight: 700; color: var(--texto-titulos);"><?= htmlspecialchars($logItem['accion'] ?? 'Acción') ?></span>
                            <span style="background: <?= $bgBadge ?>; color: <?= $badgeColor ?>; padding: 1px 6px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;"><?= htmlspecialchars($lvl) ?></span>
                        </div>
                        <p style="margin: 0; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.75rem;" title="<?= htmlspecialchars($logItem['detalle'] ?? '') ?>">
                            <?= htmlspecialchars($logItem['detalle'] ?? '') ?>
                        </p>
                        <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px; display: flex; justify-content: space-between;">
                            <span>Módulo: <?= htmlspecialchars($logItem['modulo'] ?? 'Core') ?></span>
                            <span><?= date('H:i - d/m', strtotime($logItem['fecha_hora'] ?? 'now')) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- SCRIPT DE CHARTS E INTERACTIVIDAD DE CHART.JS CON CARGA DE FALLBACK -->
<script>
function initSuperAdminCharts() {
    if (typeof Chart === 'undefined') {
        console.warn("Chart.js no está cargado aún, reintentando...");
        return;
    }

    // 1. Gráfico de Pastel de Usuarios
    const ctxPie = document.getElementById('saUserPieChart');
    if (ctxPie) {
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Online (15m)', 'Docentes', 'Bloqueados'],
                datasets: [{
                    data: [
                        <?= (int)($stats['usuarios_activos'] ?? 0) ?>,
                        <?= (int)($stats['usuarios_online'] ?? 0) ?>,
                        <?= (int)($stats['docentes'] ?? 0) ?>,
                        <?= (int)($stats['usuarios_bloqueados'] ?? 0) ?>
                    ],
                    backgroundColor: [
                        'rgb(80, 89, 132)',
                        'rgb(112, 144, 203)',
                        'rgb(18, 26, 62)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }

    // 2. Gráfico de Barras de Recursos
    const ctxBar = document.getElementById('saResourceBarChart');
    if (ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Storage MB', 'RAM PHP MB', 'RAM Pico MB'],
                datasets: [{
                    label: 'Consumo (MB)',
                    data: [
                        <?= (float)($telemetria['storage_mb'] ?? 0) ?>,
                        <?= (float)($telemetria['memory_usage_mb'] ?? 0) ?>,
                        <?= (float)($telemetria['memory_peak_mb'] ?? 0) ?>
                    ],
                    backgroundColor: [
                        'rgba(112, 144, 203, 0.85)',
                        'rgba(80, 89, 132, 0.85)',
                        'rgba(18, 26, 62, 0.85)'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
}

if (typeof Chart === 'undefined') {
    const scriptTag = document.createElement('script');
    scriptTag.src = '../modules/SuperAdmin/assets/js/chart.min.js';
    scriptTag.onload = initSuperAdminCharts;
    document.head.appendChild(scriptTag);
} else {
    document.addEventListener('DOMContentLoaded', initSuperAdminCharts);
}
</script>

<!-- MODAL GLASSMORPHISM PARA DIAGNÓSTICO DE SALUD DEL CORE -->
<div id="ag-core-health-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: rgba(255, 255, 255, 0.98); border: 1px solid rgba(255, 255, 255, 0.8); border-radius: 16px; width: 100%; max-width: 640px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; animation: agModalFade 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="padding: 1.4rem 1.8rem; background: #0f172a; color: #ffffff; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(37, 99, 235, 0.2); display: flex; align-items: center; justify-content: center; color: #38bdf8; font-size: 1.3rem;">
                    <i class="ph-bold ph-heartbeat"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-weight: 800; font-size: 1.1rem; color: #ffffff;">Diagnóstico de Salud del Core</h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #94a3b8;">Prueba en tiempo real del Núcleo del Sistema</p>
                </div>
            </div>
            <button type="button" onclick="cerrarTestCoreModal()" style="background: none; border: none; color: #94a3b8; font-size: 1.4rem; cursor: pointer;">&times;</button>
        </div>

        <div id="ag-core-health-body" style="padding: 1.6rem; max-height: 480px; overflow-y: auto;">
            <div style="text-align: center; padding: 2rem 0; color: #64748b;">
                <i class="ph-bold ph-spinner spin" style="font-size: 2.2rem; color: #2563eb;"></i>
                <p style="margin-top: 0.8rem; font-weight: 700;">Ejecutando pings sintéticos al Kernel, BD y Módulos Core...</p>
            </div>
        </div>

        <div style="padding: 1rem 1.6rem; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: right;">
            <button type="button" onclick="cerrarTestCoreModal()" class="btn btn-outline" style="background: #ffffff; border-color: #cbd5e1; color: #334155; font-weight: 700; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; cursor: pointer;">
                Cerrar Diagnóstico
            </button>
        </div>
    </div>
</div>

<script>
function ejecutarTestCore() {
    const modal = document.getElementById('ag-core-health-modal-overlay');
    const body = document.getElementById('ag-core-health-body');
    if (!modal || !body) return;

    modal.style.display = 'flex';
    body.innerHTML = `
        <div style="text-align: center; padding: 2.5rem 0; color: #64748b;">
            <i class="ph-bold ph-spinner spin" style="font-size: 2.5rem; color: #2563eb;"></i>
            <p style="margin-top: 1rem; font-weight: 800; color: #0f172a; font-size: 1rem;">Diagnosticando Respuesta del Core...</p>
            <p style="font-size: 0.82rem; color: #64748b; margin-top: 0.2rem;">Evaluando latencia de BD, constantes de Kernel y permisos de storage.</p>
        </div>
    `;

    fetch('testear-core', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        let htmlPruebas = '';
        if (data.pruebas && Array.isArray(data.pruebas)) {
            data.pruebas.forEach(item => {
                const esOk = item.estado === 'OK';
                const bgIcon = esOk ? '#dcfce7' : '#fee2e2';
                const colorIcon = esOk ? '#166534' : '#991b1b';
                const iconClass = esOk ? 'ph-check-circle' : 'ph-x-circle';

                htmlPruebas += `
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; margin-bottom: 0.8rem; display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: ${bgIcon}; color: ${colorIcon}; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; margin-top: 2px;">
                            <i class="ph-bold ${iconClass}"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h4 style="margin: 0; font-size: 0.92rem; font-weight: 800; color: #0f172a;">${item.modulo}</h4>
                                <span style="font-size: 0.75rem; font-weight: 800; background: ${bgIcon}; color: ${colorIcon}; padding: 2px 8px; border-radius: 6px;">${item.info}</span>
                            </div>
                            <p style="margin: 0.3rem 0 0 0; font-size: 0.82rem; color: #475569;">${item.detalles}</p>
                        </div>
                    </div>
                `;
            });
        }

        const headerColor = data.saludable ? '#059669' : '#dc2626';
        const statusBadge = data.saludable ? 'NÚCLEO 100% SALUDABLE' : 'ATENCIÓN: REVISAR CORRECCIONES';

        body.innerHTML = `
            <div style="background: rgba(37,99,235,0.06); border: 1px solid rgba(37,99,235,0.18); border-radius: 12px; padding: 1.1rem; margin-bottom: 1.2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.8rem;">
                <div>
                    <span style="background: ${headerColor}; color: #ffffff; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">
                        ${statusBadge}
                    </span>
                    <h4 style="margin: 0.5rem 0 0 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                        Respuesta del Core: ${data.duracion_ms} ms
                    </h4>
                </div>
                <div style="text-align: right; font-size: 0.82rem; color: #64748b;">
                    <div><strong>PHP Version:</strong> ${data.php_version}</div>
                    <div><strong>Memoria en Uso:</strong> ${data.memoria_mb} MB</div>
                </div>
            </div>

            <h4 style="margin: 0 0 0.8rem 0; font-size: 0.88rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Desglose de Diagnóstico Sintáctico</h4>
            ${htmlPruebas}
        `;
    })
    .catch(err => {
        body.innerHTML = `
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1.2rem; border-radius: 10px; font-size: 0.9rem;">
                <strong>✖ Error al ejecutar el diagnóstico de salud del Core.</strong><br>
                Verifique la conexión del servidor o revise los logs de auditoría.
            </div>
        `;
    });
}

function cerrarTestCoreModal() {
    const modal = document.getElementById('ag-core-health-modal-overlay');
    if (modal) modal.style.display = 'none';
}
</script>