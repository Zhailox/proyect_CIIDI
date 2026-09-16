<style>
/* ==========================================================================
   Antigravity UI & Motion Design Expert Style Guide - WAF Security Monitor
   Alineado 100% a las Variables Globales CSS de CIIDI (style.css)
   ========================================================================== */

/* Banner Hero Espacial */
.ag-sec-hero {
    background: #ffffff !important;
    border: 1px solid rgba(80, 89, 132, 0.16) !important;
    padding: 1.6rem 2rem !important;
    border-radius: var(--radius-md) !important;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04) !important;
    margin-bottom: 2rem !important;
}

/* Tarjetas de Cristal Antigravity */
.ag-sec-card {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(80, 89, 132, 0.15) !important;
    border-radius: var(--radius-md) !important;
    padding: 1.6rem !important;
    box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04) !important;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
}

.ag-sec-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(18, 26, 62, 0.08) !important;
    border-color: rgba(80, 89, 132, 0.28) !important;
}

/* Botones Estandarizados (Paleta Padre CIIDI) */
.ag-btn {
    height: 38px !important;
    padding: 0 18px !important;
    border-radius: var(--radius-sm) !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    cursor: pointer !important;
    border: none !important;
    text-decoration: none !important;
    box-sizing: border-box !important;
}

.ag-btn-primary {
    background: var(--color-secundario) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(80, 89, 132, 0.25) !important;
}

.ag-btn-primary:hover {
    transform: translateY(-2px) !important;
    background: #3C456A !important;
    box-shadow: 0 6px 18px rgba(80, 89, 132, 0.38) !important;
    color: #ffffff !important;
}

.ag-btn-secondary {
    background: #ffffff !important;
    color: var(--color-secundario) !important;
    border: 1px solid rgba(80, 89, 132, 0.25) !important;
}

.ag-btn-secondary:hover {
    transform: translateY(-2px) !important;
    background: var(--blanco) !important;
    border-color: var(--color-secundario) !important;
    box-shadow: 0 4px 12px rgba(18, 26, 62, 0.06) !important;
}

.ag-btn-danger {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #dc2626 !important;
    border: 1px solid rgba(239, 68, 68, 0.25) !important;
}

.ag-btn-danger:hover {
    transform: translateY(-2px) !important;
    background: #dc2626 !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(220, 38, 38, 0.3) !important;
}

/* Badges Armónicos */
.ag-badge {
    border-radius: var(--radius-sm);
    padding: 4px 10px;
    font-weight: 800;
    font-size: 0.75rem;
    letter-spacing: 0.4px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.ag-badge-ok {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.25);
}

.ag-badge-warn {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.25);
}

.ag-badge-danger {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.25);
}

/* Inputs de Formulario */
.ag-form-input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid rgba(80, 89, 132, 0.2);
    background: rgba(248, 250, 252, 0.9);
    font-size: 0.88rem;
    color: var(--texto-titulos);
    transition: all 0.25s ease-out;
    box-sizing: border-box;
}

.ag-form-input:focus {
    outline: none;
    border-color: var(--color-terciario);
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(112, 144, 203, 0.18);
}

.ag-form-label {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--texto-titulos);
    display: block;
    margin-bottom: 6px;
}
</style>

<!-- BANNER HERO PRINCIPAL -->
<div class="ag-sec-hero">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-shield-check"></i> SEGURIDAD INTELIGENTE & MONITOR WAF
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--texto-titulos) !important; margin: 0;">
                Monitor WAF, Detección de Inyecciones & Whitelist
            </h1>
            <p style="margin: 0.4rem 0 0 0; color: var(--texto-silenciado) !important; font-size: 0.95rem;">
                Inspección heurística SQLi/XSS, control de Rate Limiting, lista blanca de IPs y hash criptográfico imborrable.
            </p>
        </div>
        <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
            <button type="button" onclick="abrirModalBloquearIP()" class="ag-btn ag-btn-danger">
                <i class="ph-bold ph-prohibit"></i> Bloquear IP
            </button>
            <button type="button" onclick="abrirModalWhitelist()" class="ag-btn ag-btn-primary">
                <i class="ph-bold ph-plus-circle"></i> + Añadir Lista Blanca
            </button>
            <a href="sudoadmin" class="ag-btn ag-btn-secondary">
                <i class="ph-bold ph-arrow-left"></i> Volver al Centro
            </a>
        </div>
    </div>
</div>

<!-- ALERTAS DE ÉXITO O ERROR DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div style="background: rgba(16,185,129,0.1); color: #047857; border: 1px solid rgba(16,185,129,0.25); padding: 0.9rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.6rem;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.25rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
        <?php unset($_SESSION['mensaje_admin_exito']); ?>
    </div>
<?php endif; ?>

<!-- PANEL DE VERIFICACIÓN DE HASH DE AUDITORÍA IMBORRABLE (TAMPER-PROOF) -->
<div class="ag-sec-card mb-2" style="border-left: 5px solid <?= $integridad['integro'] ? '#10b981' : '#ef4444' ?> !important;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: var(--texto-titulos);">
                    Cadena Criptográfica de Auditoría (Hash SHA-256)
                </h3>
                <span class="<?= $integridad['integro'] ? 'ag-badge ag-badge-ok' : 'ag-badge ag-badge-danger' ?>">
                    <i class="ph-bold <?= $integridad['integro'] ? 'ph-lock-key' : 'ph-warning-octagon' ?>"></i>
                    <?= $integridad['integro'] ? 'CADENA ÍNTEGRA Y FIRMADA' : 'FIRMA DE AUDITORÍA ALTERADA' ?>
                </span>
            </div>
            <p style="margin:0; font-size: 0.88rem; color: var(--texto-silenciado);">
                <?= htmlspecialchars($integridad['mensaje']) ?>
            </p>
        </div>

        <div style="font-family: monospace; font-size: 0.82rem; background: var(--blanco); padding: 8px 16px; border-radius: var(--radius-sm); border: 1px solid rgba(80, 89, 132, 0.18); color: var(--texto-titulos);">
            Registros Auditados: <b><?= $integridad['total'] ?></b> | Manipulados: <b style="color: <?= $integridad['corruptos'] > 0 ? '#dc2626' : '#059669' ?>;"><?= $integridad['corruptos'] ?></b>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;" class="mb-2">
    
    <!-- 1. MONITOR DE FUERZA BRUTA & RATE LIMITING -->
    <div class="ag-sec-card">
        <h3 style="margin: 0 0 1rem 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-shield-warning" style="color: #d97706;"></i> Intentos de Fuerza Bruta en Login (Rate Limiting)
        </h3>

        <?php if (empty($intentos)): ?>
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--texto-silenciado); font-size: 0.88rem;">
                <i class="ph-bold ph-shield-check" style="font-size: 2.5rem; color: #10b981; display: block; margin-bottom: 8px;"></i>
                Sin IPs en monitoreo de fuerza bruta.
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: var(--blanco);">
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">IP Atacante</th>
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">Intentos</th>
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">Estado</th>
                            <th style="padding: 10px; text-align: center; color: var(--texto-titulos); font-weight: 700;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($intentos as $ipKey => $d): 
                            $bloqueado = $d['bloqueado_hasta'] > time();
                        ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px; font-weight: 700; font-family: monospace; color: var(--texto-titulos);">
                                    <?= htmlspecialchars($ipKey) ?>
                                    <div style="font-size: 0.72rem; color: var(--texto-silenciado); font-family: var(--font-family); font-weight: normal;">
                                        Cédulas: <?= htmlspecialchars(implode(', ', $d['historial_cedulas'] ?? [])) ?>
                                    </div>
                                </td>
                                <td style="padding: 10px;">
                                    <span class="ag-badge ag-badge-warn"><?= $d['intentos'] ?> / 5</span>
                                </td>
                                <td style="padding: 10px;">
                                    <?php if ($bloqueado): ?>
                                        <span class="ag-badge ag-badge-danger">Bloqueada (<?= ceil(($d['bloqueado_hasta'] - time()) / 60) ?>m)</span>
                                    <?php else: ?>
                                        <span style="color: var(--texto-silenciado); font-size: 0.78rem;">En Seguimiento</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <form action="desbloquear-ip" method="POST" style="margin:0; display: inline-block;">
                                        <input type="hidden" name="ip" value="<?= htmlspecialchars($ipKey) ?>">
                                        <button type="submit" class="ag-btn ag-btn-secondary" style="height: 32px !important; padding: 0 12px !important; font-size: 0.78rem !important;">
                                            Limpiar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- 2. LISTA NEGRA GLOBAL DE IPS (BLACKLIST PERMANENTE WAF / SQLi / XSS) -->
    <div class="ag-sec-card">
        <h3 style="margin: 0 0 1rem 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-prohibit" style="color: #dc2626;"></i> Lista Negra WAF (Blacklist)
        </h3>

        <?php if (empty($blacklist)): ?>
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--texto-silenciado); font-size: 0.88rem;">
                <i class="ph-bold ph-lock-key" style="font-size: 2.5rem; color: var(--texto-silenciado); display: block; margin-bottom: 8px;"></i>
                La lista negra de IPs se encuentra vacía.
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: var(--blanco);">
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">IP Bloqueada</th>
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">Motivo / Razón</th>
                            <th style="padding: 10px; text-align: center; color: var(--texto-titulos); font-weight: 700;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blacklist as $ipKey => $b): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px; font-weight: 700; font-family: monospace; color: #dc2626;">
                                    <?= htmlspecialchars($ipKey) ?>
                                </td>
                                <td style="padding: 10px; color: var(--texto-titulos);">
                                    <?= htmlspecialchars($b['razon']) ?>
                                    <div style="font-size: 0.72rem; color: var(--texto-silenciado);"><?= htmlspecialchars($b['fecha_bloqueo']) ?></div>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <form action="desbloquear-ip" method="POST" style="margin:0;">
                                        <input type="hidden" name="ip" value="<?= htmlspecialchars($ipKey) ?>">
                                        <button type="submit" class="ag-btn ag-btn-secondary" style="height: 32px !important; padding: 0 10px !important; font-size: 0.78rem !important;">
                                            Desbloquear
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. LISTA BLANCA DE CONFIANZA (WHITELIST) -->
    <div class="ag-sec-card">
        <h3 style="margin: 0 0 1rem 0; font-size: 1.05rem; font-weight: 800; color: var(--texto-titulos); display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-shield-check" style="color: var(--color-secundario);"></i> Lista Blanca de Confianza (Whitelist)
        </h3>

        <?php if (empty($whitelist)): ?>
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--texto-silenciado); font-size: 0.88rem;">
                <i class="ph-bold ph-shield-check" style="font-size: 2.5rem; color: var(--color-secundario); display: block; margin-bottom: 8px;"></i>
                No hay IPs asignadas a la lista blanca de confianza.
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: var(--blanco);">
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">IP Autorizada</th>
                            <th style="padding: 10px; color: var(--texto-titulos); font-weight: 700;">Nota</th>
                            <th style="padding: 10px; text-align: center; color: var(--texto-titulos); font-weight: 700;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($whitelist as $ipKey => $w): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px; font-weight: 700; font-family: monospace; color: var(--color-secundario);">
                                    <?= htmlspecialchars($ipKey) ?>
                                </td>
                                <td style="padding: 10px; color: var(--texto-titulos);">
                                    <?= htmlspecialchars($w['nota']) ?>
                                    <div style="font-size: 0.72rem; color: var(--texto-silenciado);">Por: <?= htmlspecialchars($w['responsable']) ?></div>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <form action="desbloquear-ip" method="POST" style="margin:0;">
                                        <input type="hidden" name="ip" value="<?= htmlspecialchars($ipKey) ?>">
                                        <button type="submit" class="ag-btn ag-btn-secondary" style="height: 32px !important; padding: 0 10px !important; font-size: 0.78rem !important;">
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL BLOQUEAR IP EN LISTA NEGRA GLOBAL -->
<div id="modalBloquearIP" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: rgba(255, 255, 255, 0.98); border-radius: 16px; max-width: 460px; width: 100%; padding: 2rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.18); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #dc2626;">
                Añadir IP a Lista Negra Global
            </h3>
            <button type="button" onclick="cerrarModalBloquearIP()" style="background: none; border: none; font-size: 1.2rem; color: var(--texto-silenciado); cursor: pointer; padding: 4px;">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <form action="bloquear-ip-lista-negra" method="POST">
            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Dirección IP a Bloquear</label>
                <input type="text" name="ip" class="ag-form-input" style="font-family: monospace; font-weight: 700;" placeholder="Ej: 192.168.1.100..." required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="ag-form-label">Razón del Bloqueo</label>
                <textarea name="razon" class="ag-form-input" style="height: 75px; resize: vertical;" placeholder="Motivo o reporte de ataque por el que se restringe el acceso..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="cerrarModalBloquearIP()" class="ag-btn ag-btn-secondary">Cancelar</button>
                <button type="submit" class="ag-btn ag-btn-danger">Bloquear IP</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL AGREGAR IP A LISTA BLANCA (WHITELIST) -->
<div id="modalWhitelist" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: rgba(255, 255, 255, 0.98); border-radius: 16px; max-width: 460px; width: 100%; padding: 2rem; box-shadow: 0 24px 48px rgba(18, 26, 62, 0.18); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--color-secundario);">
                Añadir IP a Lista Blanca de Confianza
            </h3>
            <button type="button" onclick="cerrarModalWhitelist()" style="background: none; border: none; font-size: 1.2rem; color: var(--texto-silenciado); cursor: pointer; padding: 4px;">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <form action="agregar-lista-blanca" method="POST">
            <div style="margin-bottom: 1.1rem;">
                <label class="ag-form-label">Dirección IP Autorizada</label>
                <input type="text" name="ip" class="ag-form-input" style="font-family: monospace; font-weight: 700;" placeholder="Ej: 10.0.0.15..." required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="ag-form-label">Nota u Observación</label>
                <textarea name="nota" class="ag-form-input" style="height: 75px; resize: vertical;" placeholder="Servidor autorizado o IP fija de administración..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="cerrarModalWhitelist()" class="ag-btn ag-btn-secondary">Cancelar</button>
                <button type="submit" class="ag-btn ag-btn-primary">Autorizar IP</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalBloquearIP() {
    document.getElementById('modalBloquearIP').style.display = 'flex';
}
function cerrarModalBloquearIP() {
    document.getElementById('modalBloquearIP').style.display = 'none';
}
function abrirModalWhitelist() {
    document.getElementById('modalWhitelist').style.display = 'flex';
}
function cerrarModalWhitelist() {
    document.getElementById('modalWhitelist').style.display = 'none';
}
</script>
