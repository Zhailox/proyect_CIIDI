<?php
// modules/SuperAdmin/views/detalle_modulo.php
$mod = $moduloData ?? [];
if (empty($mod)) {
    echo "<div class='alert alert-danger'>Módulo no encontrado.</div>";
    return;
}
?>
<style>
/* Antigravity Glassmorphism & Weightless UI (estilo.md) */
.ag-glass-banner {
    background: rgba(255, 255, 255, 0.94) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(80, 89, 132, 0.16) !important;
    border-radius: var(--radius-md, 14px);
    padding: 1.6rem 2rem;
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.05);
}

.ag-glass-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(80, 89, 132, 0.15) !important;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.ag-metric-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid rgba(80, 89, 132, 0.14);
    box-shadow: 0 8px 24px rgba(18, 26, 62, 0.03);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.ag-metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px rgba(18, 26, 62, 0.07);
}

.ag-tab-btn {
    padding: 10px 18px;
    font-weight: 700;
    font-size: 0.88rem;
    border: none;
    background: none;
    color: var(--texto-silenciado, #64748b);
    border-bottom: 3px solid transparent;
    cursor: pointer;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.ag-tab-btn.active {
    color: var(--color-secundario, #2563eb) !important;
    border-bottom-color: var(--color-secundario, #2563eb) !important;
}

.ag-tab-btn:hover:not(.active) {
    color: var(--texto-titulos, #0f172a);
}

.ag-route-card {
    background: rgba(248, 250, 252, 0.95);
    border: 1px solid rgba(80, 89, 132, 0.14);
    border-radius: 10px;
    padding: 1.1rem;
    transition: all 0.25s ease;
}

.ag-route-card:hover {
    background: #ffffff;
    border-color: rgba(80, 89, 132, 0.28);
    box-shadow: 0 6px 20px rgba(18, 26, 62, 0.04);
}

.ag-btn-solid {
    background: var(--color-secundario, #2563eb) !important;
    color: #ffffff !important;
    border: none !important;
    padding: 9px 18px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    cursor: pointer !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    transition: all 0.25s ease !important;
}

.ag-btn-solid:hover {
    background: #1d4ed8 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
}

.ag-btn-orange {
    background: #d97706 !important;
    color: #ffffff !important;
    border: none !important;
    padding: 8px 14px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.83rem !important;
    cursor: pointer !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.ag-btn-orange:hover {
    background: #b45309 !important;
    transform: translateY(-1px);
}

.ag-btn-test {
    background: #0284c7 !important;
    color: #ffffff !important;
    border: none !important;
    padding: 7px 12px !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 0.82rem !important;
    cursor: pointer !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.ag-btn-test:hover {
    background: #0369a1 !important;
    transform: translateY(-1px);
}

.ag-badge-core {
    background: rgba(99, 102, 241, 0.1);
    color: #4f46e5;
    border: 1px solid rgba(99, 102, 241, 0.25);
    border-radius: 8px;
    padding: 5px 12px;
    font-weight: 800;
    font-size: 0.78rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.ag-badge-online {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: 8px;
    padding: 5px 12px;
    font-weight: 800;
    font-size: 0.78rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.ag-badge-offline {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-radius: 8px;
    padding: 5px 12px;
    font-weight: 800;
    font-size: 0.78rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
</style>

<div class="ag-glass-banner mb-2">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.2rem;">
        <div style="display: flex; align-items: center; gap: 1.2rem;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(80, 89, 132, 0.08); border: 1px solid rgba(80, 89, 132, 0.18); display: flex; align-items: center; justify-content: center; font-size: 1.9rem; color: var(--color-secundario, #2563eb);">
                <i class="<?= htmlspecialchars($mod['icono']) ?>"></i>
            </div>
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario, #475569); font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.2px;">
                    GESTOR DEDICADO DE MÓDULO &bull; <?= htmlspecialchars($mod['id']) ?>
                </div>
                <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin: 0.2rem 0 0 0;">
                    <?= htmlspecialchars($mod['nombre']) ?>
                </h1>
                <p style="margin: 0.2rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.9rem;">
                    <?= htmlspecialchars($mod['descripcion']) ?>
                </p>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <?php if ($mod['es_core']): ?>
                <span class="ag-badge-core">
                    <i class="ph-bold ph-shield-check"></i> NÚCLEO CORE
                </span>
            <?php else: ?>
                <span class="badge-status-<?= $mod['id'] ?> <?= $mod['estado'] === 'online' ? 'ag-badge-online' : 'ag-badge-offline' ?>">
                    <i class="ph-bold <?= $mod['estado'] === 'online' ? 'ph-check-circle' : 'ph-x-circle' ?>"></i> <?= strtoupper($mod['estado']) ?>
                </span>
                <label class="toggle-switch cursor-pointer" title="Alternar Estado del Módulo">
                    <input type="checkbox" 
                           id="chk-mod-dm-<?= htmlspecialchars($mod['id']) ?>" 
                           <?= $mod['estado'] === 'online' ? 'checked' : '' ?> 
                           onchange="toggleModuloAjaxDM('<?= htmlspecialchars($mod['id']) ?>', this)">
                    <span class="toggle-slider" style="border-radius: 6px;"></span>
                </label>
            <?php endif; ?>

            <button type="button" onclick="purgarCacheModuloDM('<?= htmlspecialchars($mod['id']) ?>')" class="ag-btn-orange" title="Limpiar archivos de caché de este módulo">
                <i class="ph-bold ph-broom"></i> Purgar Caché
            </button>

            <a href="gestor-modulos" class="btn btn-outline" style="border-color: var(--color-secundario, #2563eb); color: var(--color-secundario, #2563eb); background: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s ease;">
                <i class="ph-bold ph-arrow-left"></i> Volver a Módulos
            </a>
        </div>
    </div>
</div>

<!-- TOAST CONTAINER LOCAL -->
<div id="sa-toast-container" class="sa-toast-container"></div>

<!-- MÉTRICAS RÁPIDAS EN GRID DEDICADO -->
<div class="metrics-grid mb-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem;">
    <div class="ag-metric-card">
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--texto-silenciado, #64748b); text-transform: uppercase; letter-spacing: 0.5px;">Total Rutas Internas</div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin-top: 0.3rem;"><?= $mod['total_rutas'] ?></div>
    </div>

    <div class="ag-metric-card" style="border-color: rgba(16,185,129,0.25);">
        <div style="font-size: 0.75rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.5px;">Rutas Activas</div>
        <div style="font-size: 1.85rem; font-weight: 800; color: #10b981; margin-top: 0.3rem;"><?= $mod['rutas_activas'] ?></div>
    </div>

    <div class="ag-metric-card" style="border-color: rgba(239,68,68,0.25);">
        <div style="font-size: 0.75rem; font-weight: 800; color: #dc2626; text-transform: uppercase; letter-spacing: 0.5px;">Restringidas</div>
        <div style="font-size: 1.85rem; font-weight: 800; color: #ef4444; margin-top: 0.3rem;"><?= $mod['rutas_restringidas'] ?></div>
    </div>

    <div class="ag-metric-card">
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--texto-silenciado, #64748b); text-transform: uppercase; letter-spacing: 0.5px;">Tráfico Registrado</div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--color-secundario, #2563eb); margin-top: 0.3rem;"><?= number_format($mod['total_accesos'] ?? 0) ?> <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">clics</span></div>
    </div>

    <div class="ag-metric-card">
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--texto-silenciado, #64748b); text-transform: uppercase; letter-spacing: 0.5px;">Configuración Propia</div>
        <div style="font-size: 0.92rem; font-weight: 800; color: var(--texto-titulos, #0f172a); margin-top: 0.5rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            <?= $mod['configFile'] ? htmlspecialchars($mod['configFile']) : 'Sin JSON dedicado' ?>
        </div>
    </div>
</div>

<!-- NAVEGACIÓN DE PESTAÑAS DEDICADAS -->
<div style="border-bottom: 2px solid rgba(80,89,132,0.12); margin-bottom: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <button type="button" onclick="switchModTab('rutas')" id="tab-btn-rutas" class="ag-tab-btn active">
        <i class="ph-bold ph-tree-structure"></i> Rutas & Feature Flags (RBAC)
    </button>

    <?php if ($mod['configJsonData'] !== null): ?>
        <button type="button" onclick="switchModTab('config-editor')" id="tab-btn-config-editor" class="ag-tab-btn">
            <i class="ph-bold ph-sliders"></i> Editor Visual de Opciones
        </button>
        <button type="button" onclick="switchModTab('config-raw')" id="tab-btn-config-raw" class="ag-tab-btn">
            <i class="ph-bold ph-code"></i> Código Config JSON
        </button>
    <?php endif; ?>

    <button type="button" onclick="switchModTab('audit')" id="tab-btn-audit" class="ag-tab-btn">
        <i class="ph-bold ph-shield-warning"></i> Audit Logs de Módulo (<?= count($mod['auditLogs'] ?? []) ?>)
    </button>

    <button type="button" onclick="switchModTab('info')" id="tab-btn-info" class="ag-tab-btn">
        <i class="ph-bold ph-info"></i> Información & Especificaciones
    </button>
</div>

<!-- PESTAÑA 1: RUTAS INTERNAS & RBAC -->
<div id="mod-tab-content-rutas" class="mod-tab-pane">
    <div class="ag-glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="margin: 0; font-weight: 800; color: var(--texto-titulos, #0f172a); font-size: 1.2rem;">
                    Matriz de Permisos por Ruta (RBAC & Feature Flags)
                </h3>
                <p style="margin: 0.2rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.88rem;">
                    Configure el rol mínimo necesario, modifique estados y ejecute pings sintéticos de salud por ruta.
                </p>
            </div>
        </div>

        <?php if (!empty($mod['rutas'])): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($mod['rutas'] as $r): ?>
                    <div class="ag-route-card">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 260px;">
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <h4 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: var(--texto-titulos, #0f172a);"><?= htmlspecialchars($r['titulo']) ?></h4>
                                    <code style="background: rgba(80,89,132,0.1); color: var(--color-secundario, #2563eb); font-size: 0.8rem; padding: 3px 8px; border-radius: 6px; font-weight: 700;">?ruta=<?= htmlspecialchars($r['clave']) ?></code>
                                    <span style="background: #e2e8f0; color: #475569; font-size: 0.75rem; padding: 2px 7px; border-radius: 6px; font-weight: 700;">
                                        <?= $r['accesos'] ?> clics
                                    </span>
                                </div>
                                <div style="font-size: 0.82rem; color: var(--texto-silenciado, #64748b); margin-top: 0.5rem; display: flex; gap: 1.2rem; flex-wrap: wrap;">
                                    <span><strong style="color: var(--texto-titulos, #0f172a);">Controlador:</strong> <?= htmlspecialchars($r['controlador']) ?></span>
                                    <span><strong style="color: var(--texto-titulos, #0f172a);">Método:</strong> <?= htmlspecialchars($r['metodo']) ?>()</span>
                                    <span><strong style="color: var(--texto-titulos, #0f172a);">Vista:</strong> <?= htmlspecialchars($r['vista']) ?></span>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <button type="button" class="ag-btn-test" onclick="testearRutaDM('<?= htmlspecialchars($mod['id']) ?>', '<?= htmlspecialchars($r['clave']) ?>')" title="Ejecutar test sintáctico de salud">
                                    <i class="ph-bold ph-lightning"></i> Testear Ruta
                                </button>

                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Rol Mínimo Exigido</span>
                                    <select id="rol-ruta-<?= htmlspecialchars($r['clave']) ?>" onchange="toggleRutaAjaxDM('<?= htmlspecialchars($r['clave']) ?>')" style="font-size: 0.82rem; padding: 6px 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 700; background: #ffffff; cursor: pointer;">
                                        <?php if (!empty($mod['rolesDinamicos'])): ?>
                                            <?php foreach ($mod['rolesDinamicos'] as $rolDyn): ?>
                                                <option value="<?= $rolDyn['nivel'] ?>" <?= $r['rol_minimo'] === $rolDyn['nivel'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($rolDyn['nombre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Estado / Feature Flag</span>
                                    <select id="st-ruta-<?= htmlspecialchars($r['clave']) ?>" onchange="toggleRutaAjaxDM('<?= htmlspecialchars($r['clave']) ?>')" style="font-size: 0.82rem; padding: 6px 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 700; background: #ffffff; cursor: pointer;">
                                        <option value="online" <?= $r['estado'] === 'online' ? 'selected' : '' ?>>Activa (Online)</option>
                                        <option value="solo_lectura" <?= $r['estado'] === 'solo_lectura' ? 'selected' : '' ?>>Solo Lectura</option>
                                        <option value="offline" <?= $r['estado'] === 'offline' ? 'selected' : '' ?>>Deshabilitada (Offline)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- RESULTADO DEL DIAGNÓSTICO HEALTH CHECK -->
                        <div id="health-res-<?= htmlspecialchars($r['clave']) ?>" style="display: none; margin-top: 0.8rem; padding: 0.8rem; border-radius: 8px; font-size: 0.82rem; font-family: monospace;"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: var(--texto-silenciado, #64748b);">Este módulo no declara rutas internas configurables.</p>
        <?php endif; ?>
    </div>
</div>

<!-- PESTAÑA 2: EDITOR VISUAL DE OPCIONES (CONFIG FORMATTED) -->
<?php if ($mod['configJsonData'] !== null): ?>
<div id="mod-tab-content-config-editor" class="mod-tab-pane" style="display: none;">
    <div class="ag-glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
            <div>
                <h3 style="margin: 0; font-weight: 800; color: var(--texto-titulos, #0f172a); font-size: 1.2rem;">
                    Editor Visual Formateado de Opciones
                </h3>
                <p style="margin: 0.2rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.88rem;">
                    Modifique las propiedades del módulo mediante controles gráficos dinámicos sin manipular el código JSON.
                </p>
            </div>
        </div>

        <form onsubmit="guardarVisualConfigDM(event)">
            <div id="ag-visual-fields-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.2rem; margin-bottom: 1.5rem;">
                <!-- Se construye dinámicamente con JavaScript -->
            </div>

            <button type="submit" class="ag-btn-solid">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios Visuales
            </button>
        </form>
    </div>
</div>

<!-- PESTAÑA 3: CÓDIGO CONFIG JSON (RAW EDITOR) -->
<div id="mod-tab-content-config-raw" class="mod-tab-pane" style="display: none;">
    <div class="ag-glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
            <div>
                <h3 style="margin: 0; font-weight: 800; color: var(--texto-titulos, #0f172a); font-size: 1.2rem;">
                    Código Fuente JSON (<?= htmlspecialchars($mod['configFile']) ?>)
                </h3>
                <p style="margin: 0.2rem 0 0 0; color: var(--texto-silenciado, #64748b); font-size: 0.88rem;">
                    Editor avanzado de estructura de datos JSON cruda.
                </p>
            </div>
        </div>

        <form onsubmit="guardarConfigJsonDM(event)">
            <input type="hidden" id="dm_modulo_id" value="<?= htmlspecialchars($mod['id']) ?>">
            <input type="hidden" id="dm_config_file_path" value="<?= htmlspecialchars($mod['configFilePath']) ?>">
            
            <div style="margin-bottom: 1.2rem;">
                <textarea id="dm_raw_json" rows="16" style="width: 100%; font-family: 'Fira Code', Consolas, monospace; font-size: 0.88rem; padding: 1.2rem; border-radius: 10px; border: 1px solid #cbd5e1; background: #0f172a; color: #38bdf8; line-height: 1.5; box-shadow: inset 0 2px 8px rgba(0,0,0,0.3);"><?= htmlspecialchars(json_encode($mod['configJsonData'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></textarea>
            </div>

            <button type="submit" class="ag-btn-solid">
                <i class="ph-bold ph-floppy-disk"></i> Guardar JSON
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- PESTAÑA 4: AUDIT LOGS DEL MÓDULO -->
<div id="mod-tab-content-audit" class="mod-tab-pane" style="display: none;">
    <div class="ag-glass-card">
        <h3 style="margin: 0 0 1.2rem 0; font-weight: 800; color: var(--texto-titulos, #0f172a); font-size: 1.2rem;">
            Historial de Auditoría de este Módulo
        </h3>

        <?php if (!empty($mod['auditLogs'])): ?>
            <div style="overflow-x: auto;">
                <table class="table-v2" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <thead>
                        <tr style="background: rgba(80,89,132,0.06); text-align: left;">
                            <th style="padding: 10px; color: #0f172a; font-weight: 800;">Fecha / Hora</th>
                            <th style="padding: 10px; color: #0f172a; font-weight: 800;">Nivel</th>
                            <th style="padding: 10px; color: #0f172a; font-weight: 800;">Acción</th>
                            <th style="padding: 10px; color: #0f172a; font-weight: 800;">Responsable</th>
                            <th style="padding: 10px; color: #0f172a; font-weight: 800;">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mod['auditLogs'] as $log): ?>
                            <tr style="border-bottom: 1px solid rgba(80,89,132,0.1);">
                                <td style="padding: 10px; white-space: nowrap; font-weight: 600; color: #475569;"><?= htmlspecialchars($log['fecha_hora']) ?></td>
                                <td style="padding: 10px;">
                                    <?php
                                    $n = strtoupper($log['nivel']);
                                    $bg = $n === 'ERROR' || $n === 'CRITICAL' ? '#ef4444' : ($n === 'WARNING' ? '#d97706' : '#2563eb');
                                    ?>
                                    <span style="background: <?= $bg ?>; color: #ffffff; padding: 2px 7px; border-radius: 4px; font-weight: 800; font-size: 0.72rem;"><?= $n ?></span>
                                </td>
                                <td style="padding: 10px; font-weight: 700; color: #0f172a;"><?= htmlspecialchars($log['accion']) ?></td>
                                <td style="padding: 10px; color: #334155;"><?= htmlspecialchars($log['responsable']) ?></td>
                                <td style="padding: 10px; color: #64748b;"><?= htmlspecialchars($log['detalles']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="color: var(--texto-silenciado, #64748b);">No hay registros de auditoría aún para este módulo.</p>
        <?php endif; ?>
    </div>
</div>

<!-- PESTAÑA 5: INFORMACIÓN & DEPENDENCIAS -->
<div id="mod-tab-content-info" class="mod-tab-pane" style="display: none;">
    <div class="ag-glass-card">
        <h3 style="margin: 0 0 1.2rem 0; font-weight: 800; color: var(--texto-titulos, #0f172a); font-size: 1.2rem;">
            Especificaciones Técnicas del Módulo
        </h3>

        <table class="table-v2" style="width: 100%; border-collapse: collapse; font-size: 0.92rem;">
            <tbody>
                <tr style="border-bottom: 1px solid rgba(80,89,132,0.1);">
                    <td style="padding: 12px; font-weight: 800; width: 240px; color: var(--texto-titulos, #0f172a);">Identificador Único:</td>
                    <td style="padding: 12px;"><code><?= htmlspecialchars($mod['id']) ?></code></td>
                </tr>
                <tr style="border-bottom: 1px solid rgba(80,89,132,0.1);">
                    <td style="padding: 12px; font-weight: 800; color: var(--texto-titulos, #0f172a);">Tipo de Módulo:</td>
                    <td style="padding: 12px;"><?= $mod['es_core'] ? '<span class="ag-badge-core"><i class="ph-bold ph-shield-check"></i> Núcleo Indispensable (Core)</span>' : '<span style="background: rgba(80,89,132,0.1); color: var(--texto-titulos, #0f172a); padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.8rem;">Módulo Extensible / Opcional</span>' ?></td>
                </tr>
                <tr style="border-bottom: 1px solid rgba(80,89,132,0.1);">
                    <td style="padding: 12px; font-weight: 800; color: var(--texto-titulos, #0f172a);">Dependencias Declaradas:</td>
                    <td style="padding: 12px;">
                        <?php if (!empty($mod['dependencias'])): ?>
                            <?php foreach ($mod['dependencias'] as $dep): ?>
                                <span style="background: #e2e8f0; color: #334155; padding: 3px 10px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; margin-right: 6px;"><?= htmlspecialchars($dep) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span style="color: var(--texto-silenciado, #64748b);">Ninguna dependencia externa requerida.</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid rgba(80,89,132,0.1);">
                    <td style="padding: 12px; font-weight: 800; color: var(--texto-titulos, #0f172a);">Ubicación en Disco:</td>
                    <td style="padding: 12px;"><code>modules/<?= htmlspecialchars($mod['id']) ?>/index.php</code></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let currentConfigJson = <?= json_encode($mod['configJsonData'] ?? []) ?>;

document.addEventListener('DOMContentLoaded', () => {
    renderVisualFields();
});

function showSAToast(message, type = 'success') {
    const container = document.getElementById('sa-toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `sa-toast ${type}`;
    toast.innerHTML = `<i class="ph-bold ${type === 'success' ? 'ph-check-circle' : 'ph-warning-circle'}"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function switchModTab(tabName) {
    document.querySelectorAll('.mod-tab-pane').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.ag-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    const targetPane = document.getElementById(`mod-tab-content-${tabName}`);
    const targetBtn = document.getElementById(`tab-btn-${tabName}`);
    if (targetPane) targetPane.style.display = 'block';
    if (targetBtn) {
        targetBtn.classList.add('active');
    }
}

function toggleModuloAjaxDM(moduloId, inputEl) {
    const nuevoEstado = inputEl.checked ? 'online' : 'offline';
    const formData = new FormData();
    formData.append('modulo_id', moduloId);
    formData.append('nuevo_estado', nuevoEstado);

    fetch('alternar-modulo', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const badge = document.querySelector(`.badge-status-${moduloId}`);
            if (badge) {
                const iconClass = nuevoEstado === 'online' ? 'ph-check-circle' : 'ph-x-circle';
                badge.innerHTML = `<i class="ph-bold ${iconClass}"></i> ${nuevoEstado.toUpperCase()}`;
                badge.className = `badge-status-${moduloId} ${nuevoEstado === 'online' ? 'ag-badge-online' : 'ag-badge-offline'}`;
            }
            showSAToast(data.message, 'success');
        } else {
            inputEl.checked = !inputEl.checked;
            showSAToast(data.message || 'Error al modificar módulo', 'error');
        }
    })
    .catch(() => {
        inputEl.checked = !inputEl.checked;
        showSAToast('Error de conexión con el servidor', 'error');
    });
}

function toggleRutaAjaxDM(claveRuta) {
    const stEl = document.getElementById(`st-ruta-${claveRuta}`);
    const rolEl = document.getElementById(`rol-ruta-${claveRuta}`);

    const nuevoEstado = stEl ? stEl.value : 'online';
    const rolMinimo = rolEl ? rolEl.value : '1';

    const formData = new FormData();
    formData.append('ruta_clave', claveRuta);
    formData.append('nuevo_estado', nuevoEstado);
    formData.append('rol_minimo', rolMinimo);

    fetch('alternar-estado-ruta', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            showSAToast(data.message, 'success');
        } else {
            showSAToast(data.message || 'Error al modificar la ruta', 'error');
        }
    })
    .catch(() => showSAToast('Error de red al modificar ruta', 'error'));
}

function testearRutaDM(moduloId, claveRuta) {
    const resBox = document.getElementById(`health-res-${claveRuta}`);
    if (resBox) {
        resBox.style.display = 'block';
        resBox.style.background = '#f1f5f9';
        resBox.style.color = '#334155';
        resBox.innerHTML = `<i class="ph-bold ph-spinner spin"></i> Ejecutando diagnóstico de salud en '?ruta=${claveRuta}'...`;
    }

    const formData = new FormData();
    formData.append('modulo_id', moduloId);
    formData.append('clave_ruta', claveRuta);

    fetch('testear-ruta', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (resBox) {
            if (data.status === 'success') {
                resBox.style.background = '#dcfce7';
                resBox.style.color = '#166534';
                resBox.style.border = '1px solid #86efac';
                resBox.innerHTML = `<strong>✔ ${data.message}</strong><br>` + (data.diagnostico ? data.diagnostico.join('<br>') : '');
            } else {
                resBox.style.background = '#fee2e2';
                resBox.style.color = '#991b1b';
                resBox.style.border = '1px solid #fca5a5';
                resBox.innerHTML = `<strong>✖ ${data.message}</strong><br>` + (data.diagnostico ? data.diagnostico.join('<br>') : '');
            }
        }
    })
    .catch(() => {
        if (resBox) {
            resBox.style.background = '#fee2e2';
            resBox.style.color = '#991b1b';
            resBox.innerHTML = '✖ Error de conexión con el servidor al diagnosticar ruta.';
        }
    });
}

function purgarCacheModuloDM(moduloId) {
    const formData = new FormData();
    formData.append('modulo_id', moduloId);

    fetch('purgar-cache-modulo', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            showSAToast(data.message, 'success');
        } else {
            showSAToast(data.message || 'Error al purgar caché', 'error');
        }
    })
    .catch(() => showSAToast('Error de conexión al purgar caché', 'error'));
}

/* RENDERIZADOR DEL EDITOR VISUAL FORMATEADO */
function renderVisualFields() {
    const container = document.getElementById('ag-visual-fields-container');
    if (!container || !currentConfigJson || typeof currentConfigJson !== 'object') return;
    
    container.innerHTML = '';
    
    Object.keys(currentConfigJson).forEach(key => {
        const val = currentConfigJson[key];
        const wrapper = document.createElement('div');
        wrapper.style.background = '#f8fafc';
        wrapper.style.border = '1px solid #e2e8f0';
        wrapper.style.padding = '1.1rem';
        wrapper.style.borderRadius = '10px';

        const label = document.createElement('label');
        label.style.fontWeight = '800';
        label.style.fontSize = '0.85rem';
        label.style.color = '#0f172a';
        label.style.display = 'block';
        label.style.marginBottom = '0.5rem';
        label.innerText = key;

        wrapper.appendChild(label);

        if (typeof val === 'boolean') {
            const select = document.createElement('select');
            select.className = 'vf-field';
            select.dataset.key = key;
            select.style.width = '100%';
            select.style.padding = '8px 12px';
            select.style.borderRadius = '8px';
            select.style.border = '1px solid #cbd5e1';
            select.style.fontWeight = '700';
            select.style.fontSize = '0.85rem';
            select.innerHTML = `
                <option value="true" ${val === true ? 'selected' : ''}>HABILITADO (true)</option>
                <option value="false" ${val === false ? 'selected' : ''}>DESHABILITADO (false)</option>
            `;
            wrapper.appendChild(select);
        } else if (typeof val === 'number') {
            const input = document.createElement('input');
            input.type = 'number';
            input.className = 'vf-field';
            input.dataset.key = key;
            input.value = val;
            input.style.width = '100%';
            input.style.padding = '8px 12px';
            input.style.borderRadius = '8px';
            input.style.border = '1px solid #cbd5e1';
            input.style.fontWeight = '700';
            input.style.fontSize = '0.85rem';
            wrapper.appendChild(input);
        } else if (typeof val === 'string') {
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'vf-field';
            input.dataset.key = key;
            input.value = val;
            input.style.width = '100%';
            input.style.padding = '8px 12px';
            input.style.borderRadius = '8px';
            input.style.border = '1px solid #cbd5e1';
            input.style.fontWeight = '600';
            input.style.fontSize = '0.85rem';
            wrapper.appendChild(input);
        } else {
            const txt = document.createElement('textarea');
            txt.className = 'vf-field';
            txt.dataset.key = key;
            txt.rows = 3;
            txt.value = JSON.stringify(val, null, 2);
            txt.style.width = '100%';
            txt.style.padding = '8px 12px';
            txt.style.borderRadius = '8px';
            txt.style.border = '1px solid #cbd5e1';
            txt.style.fontFamily = 'monospace';
            txt.style.fontSize = '0.82rem';
            wrapper.appendChild(txt);
        }

        container.appendChild(wrapper);
    });
}

function guardarVisualConfigDM(e) {
    e.preventDefault();
    const updated = {};
    document.querySelectorAll('.vf-field').forEach(field => {
        const key = field.dataset.key;
        let rawVal = field.value;

        if (field.tagName === 'SELECT' && (rawVal === 'true' || rawVal === 'false')) {
            updated[key] = rawVal === 'true';
        } else if (field.type === 'number') {
            updated[key] = parseFloat(rawVal);
        } else if (field.tagName === 'TEXTAREA') {
            try {
                updated[key] = JSON.parse(rawVal);
            } catch (err) {
                updated[key] = rawVal;
            }
        } else {
            updated[key] = rawVal;
        }
    });

    currentConfigJson = updated;
    const rawJsonInput = document.getElementById('dm_raw_json');
    if (rawJsonInput) {
        rawJsonInput.value = JSON.stringify(updated, null, 4);
    }

    // Guardar vía AJAX
    const moduloId = document.getElementById('dm_modulo_id').value;
    const configFilePath = document.getElementById('dm_config_file_path').value;
    
    const formData = new FormData();
    formData.append('modulo_id', moduloId);
    formData.append('config_file_path', configFilePath);
    formData.append('raw_config_json', JSON.stringify(updated, null, 4));

    fetch('guardar-config-modulo', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            showSAToast(data.message, 'success');
        } else {
            showSAToast(data.message || 'Error al guardar la configuración', 'error');
        }
    })
    .catch(() => showSAToast('Error de red al guardar la configuración', 'error'));
}

function guardarConfigJsonDM(e) {
    e.preventDefault();
    const moduloId = document.getElementById('dm_modulo_id').value;
    const configFilePath = document.getElementById('dm_config_file_path').value;
    const rawJson = document.getElementById('dm_raw_json').value;

    const formData = new FormData();
    formData.append('modulo_id', moduloId);
    formData.append('config_file_path', configFilePath);
    formData.append('raw_config_json', rawJson);

    fetch('guardar-config-modulo', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            try {
                currentConfigJson = JSON.parse(rawJson);
                renderVisualFields();
            } catch (err) {}
            showSAToast(data.message, 'success');
        } else {
            showSAToast(data.message || 'Error al guardar la configuración JSON', 'error');
        }
    })
    .catch(() => showSAToast('Error de conexión al guardar configuración', 'error'));
}
</script>
