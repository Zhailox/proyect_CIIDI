<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-sliders"></i> INFRAESTRUCTURA DEL SISTEMA
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #121a3e !important; margin: 0;">
                Variables de Entorno y Configuración
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: #64748b !important; font-size: 0.9rem;">
                Ajustes globales del sistema, seguridad, paginación y servidor de correos.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="sudoadmin" class="sa-btn sa-btn-outline">
                <i class="ph-bold ph-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<?php if (!empty($mensajeExito)): ?>
    <div class="sa-alert sa-alert-success">
        <i class="ph-bold ph-check-circle"></i> <?= htmlspecialchars($mensajeExito) ?>
    </div>
<?php endif; ?>

<?php if (!empty($mensajeError)): ?>
    <div class="sa-alert sa-alert-error">
        <i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($mensajeError) ?>
    </div>
<?php endif; ?>

<form action="guardar-configuracion-sistema" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    
    <!-- Navegación de Pestañas (Estilo SuperAdmin) -->
    <div class="sa-tabs-header glass-panel mb-2" style="background: #ffffff; padding: 8px; border-radius: var(--radius-sm); border: 1px solid rgba(80,89,132,0.15);">
        <button type="button" class="sa-tab-btn tab-active" onclick="switchConfigTab('tabPaginacion', this)">
            <i class="ph-bold ph-list-numbers"></i> Paginación
        </button>
        <button type="button" class="sa-tab-btn" onclick="switchConfigTab('tabSeguridad', this)">
            <i class="ph-bold ph-shield-check"></i> Seguridad Base
        </button>
        <button type="button" class="sa-tab-btn" onclick="switchConfigTab('tabEnv', this)">
            <i class="ph-bold ph-bug"></i> Variables .env & Depuración
        </button>
        <button type="button" class="sa-tab-btn" onclick="switchConfigTab('tabAccesos', this)">
            <i class="ph-bold ph-lock-key"></i> Accesos por Módulo
        </button>
        <button type="button" class="sa-tab-btn" onclick="switchConfigTab('tabFooter', this)">
            <i class="ph-bold ph-layout"></i> Footer Institucional
        </button>
    </div>

    <!-- TAB PAGINACIÓN -->
    <div id="tabPaginacion" class="sa-tab-content active">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-list-numbers" style="color: var(--color-terciario);"></i> Límites de Renderizado en Tablas
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Controla cuántos elementos se cargan por defecto en las tablas para evitar saturación de memoria RAM.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Usuarios por página (Gestor)</label>
                    <input type="number" name="pag_usuarios" value="<?= (int)($config['paginacion']['usuarios'] ?? 15) ?>" min="5" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Docentes por página</label>
                    <input type="number" name="pag_docentes" value="<?= (int)($config['paginacion']['docentes'] ?? 15) ?>" min="5" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Logs de Auditoría por página</label>
                    <input type="number" name="pag_logs" value="<?= (int)($config['paginacion']['logs'] ?? 50) ?>" min="10" class="sa-filter-input" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- TAB SEGURIDAD -->
    <div id="tabSeguridad" class="sa-tab-content" style="display:none;">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-shield-check" style="color: var(--color-terciario);"></i> Reglas del Entorno de Autenticación
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Parámetros de defensa contra fuerza bruta y control de sesiones activas.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Tiempo de Inactividad (Minutos)</label>
                    <input type="number" name="seg_timeout" value="<?= (int)($config['seguridad']['timeout_minutos'] ?? 120) ?>" min="5" class="sa-filter-input" style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">Intentos de Login Fallidos</label>
                    <input type="number" name="seg_intentos" value="<?= (int)($config['seguridad']['intentos_login'] ?? 5) ?>" min="1" class="sa-filter-input" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <!-- TAB VARIABLES .ENV & DEPURACIÓN -->
    <?php
    $appDebugActual = class_exists('Env') ? Env::get('APP_DEBUG', false) : false;
    $appEnvActual   = class_exists('Env') ? Env::get('APP_ENV', 'production') : 'production';
    ?>
    <div id="tabEnv" class="sa-tab-content" style="display:none;">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-bug" style="color: var(--color-terciario);"></i> Modo de Depuración y Entorno de Ejecución (.env Shield)
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Controla la exposición de excepciones PDO y errores del servidor. En producción, desactiva <code>APP_DEBUG</code> para evitar fuga de información.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div style="background: #f8fafc; padding: 1.2rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">
                        <i class="ph-bold ph-toggle-left"></i> Modo Depuración (APP_DEBUG)
                    </label>
                    <select name="app_debug" class="sa-filter-input" style="width: 100%; font-weight: 600;">
                        <option value="false" <?= !$appDebugActual ? 'selected' : '' ?>>false (Producción - Errores genéricos seguros)</option>
                        <option value="true" <?= $appDebugActual ? 'selected' : '' ?>>true (Desarrollo - Muestra trazas de excepciones PDO)</option>
                    </select>
                    <div style="margin-top: 8px; font-size: 0.78rem; color: var(--texto-silenciado);">
                        Si está en <code>false</code>, los usuarios recibirán un mensaje seguro de Error 500 y las trazas se guardarán silenciosamente en <code>storage/logs/system_errors.log</code>.
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.2rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 6px;">
                        <i class="ph-bold ph-tree-structure"></i> Entorno de Ejecución (APP_ENV)
                    </label>
                    <select name="app_env" class="sa-filter-input" style="width: 100%; font-weight: 600;">
                        <option value="production" <?= $appEnvActual === 'production' ? 'selected' : '' ?>>Production (Servidor de Producción UPTTMBI)</option>
                        <option value="development" <?= $appEnvActual === 'development' ? 'selected' : '' ?>>Development (Entorno Local de Desarrollo)</option>
                        <option value="staging" <?= $appEnvActual === 'staging' ? 'selected' : '' ?>>Staging (Servidor de Pruebas / QA)</option>
                    </select>
                    <div style="margin-top: 8px; font-size: 0.78rem; color: var(--texto-silenciado);">
                        Define el perfil operacional del sistema.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB ACCESOS POR MÓDULO -->
    <div id="tabAccesos" class="sa-tab-content" style="display:none;">
        <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-lock-key" style="color: var(--color-terciario);"></i> Niveles de Privilegio por Módulo
            </h4>
            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
                Define el nivel numérico mínimo requerido para visualizar y administrar. Nivel 0 = Máximo Poder.
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                
                <?php 
                $rutaModulos = realpath(CORE_PATH . '../modules');
                $modulosDinamicos = [];
                
                if ($rutaModulos && is_dir($rutaModulos)) {
                    foreach (array_diff(scandir($rutaModulos), ['.', '..']) as $carpeta) {
                        if (is_dir($rutaModulos . '/' . $carpeta)) {
                            $slug = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $carpeta));
                            if (in_array($slug, ['superadmin', 'super_admin', 'autenticacion', 'core'])) continue;
                            
                            // Formatea el nombre separando mayúsculas (Ej. RepositorioPST -> Repositorio PST)
                           $titulo = trim(preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $carpeta));
                            $modulosDinamicos[$slug] = $titulo;
                        }
                    }
                }

                foreach ($modulosDinamicos as $slug => $titulo): 
                    $valPub = $config['accesos_modulos'][$slug]['publico'] ?? 999;
                    $valAdm = $config['accesos_modulos'][$slug]['admin'] ?? 1;
                ?>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h5 style="margin: 0 0 10px 0; color: var(--negro); font-size: 0.9rem;"><i class="ph-bold ph-plugs-connected"></i> Módulo: <?= htmlspecialchars($titulo) ?></h5>
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex: 1;">
                            <label style="font-size: 0.75rem; font-weight: 700; color: var(--texto-silenciado);">Nivel Público (Ver)</label>
                            <input type="number" min="0" name="accesos[<?= $slug ?>][publico]" value="<?= $valPub ?>" class="sa-filter-input" style="width: 100%; margin-top: 4px;">
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 0.75rem; font-weight: 700; color: var(--texto-silenciado);">Nivel Admin (Gestionar)</label>
                            <input type="number" min="0" name="accesos[<?= $slug ?>][admin]" value="<?= $valAdm ?>" class="sa-filter-input" style="width: 100%; margin-top: 4px;">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Módulo Especial Autenticación -->
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h5 style="margin: 0 0 10px 0; color: var(--color-secundario); font-size: 0.9rem;"><i class="ph-bold ph-sign-in"></i> Autenticación</h5>
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex: 1;">
                            <label style="font-size: 0.75rem; font-weight: 700; color: var(--texto-silenciado);">Nivel Login/Registro</label>
                            <!-- Límite estricto establecido a 998 visualmente -->
                            <input type="number" min="0" max="998" name="accesos[autenticacion][publico]" value="<?= $config['accesos_modulos']['autenticacion']['publico'] ?? 998 ?>" class="sa-filter-input" style="width: 100%; margin-top: 4px;">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- TAB FOOTER INSTITUCIONAL -->
    <?php
    $footerConfig = $config['footer'] ?? (class_exists('SystemConfigService') ? SystemConfigService::getDefaultFooter() : []);
    $columnasActuales = $footerConfig['columnas'] ?? [];
    ?>
    <div id="tabFooter" class="sa-tab-content">
        <div class="glass-panel sa-footer-builder-panel">
            <div class="sa-footer-builder-header">
                <div>
                    <h4>
                        <i class="ph-bold ph-layout"></i> Personalización Escalable del Footer Institucional
                    </h4>
                    <p>
                        Añade, edita o elimina columnas, bloques de texto, enlaces con íconos Phosphor y los textos de la barra inferior.
                    </p>
                </div>
                <div class="sa-footer-builder-actions">
                    <input type="hidden" name="restaurar_footer_defecto" id="inputRestaurarFooter" value="0">
                    <button type="button" class="sa-btn sa-btn-danger-outline" onclick="confirmarRestaurarFooter()">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i> Restaurar por Defecto
                    </button>
                    <button type="button" class="sa-btn sa-btn-outline" onclick="agregarColumnaFooter()">
                        <i class="ph-bold ph-plus-circle"></i> Añadir Columna
                    </button>
                </div>
            </div>

            <div id="contenedorColumnasFooter" class="sa-footer-columns-grid">
                <?php foreach ($columnasActuales as $cIdx => $col): ?>
                <div class="sa-footer-col-card" data-col-index="<?= $cIdx ?>">
                    <div class="sa-footer-col-card-top">
                        <strong>Columna #<span class="col-num-label"><?= $cIdx + 1 ?></span></strong>
                        <button type="button" class="sa-btn-icon-danger" onclick="eliminarElementoPadre(this, '.sa-footer-col-card')" title="Eliminar Columna">
                            <i class="ph-bold ph-trash"></i> Eliminar Columna
                        </button>
                    </div>

                    <div class="sa-footer-field-row">
                        <div class="sa-footer-field">
                            <label>Título de la Columna</label>
                            <input type="text" name="footer[columnas][<?= $cIdx ?>][titulo]" value="<?= htmlspecialchars($col['titulo'] ?? '') ?>" class="sa-filter-input" placeholder="Ej: Ubícanos">
                        </div>
                        <div class="sa-footer-field">
                            <label>Ícono del Título (Phosphor)</label>
                            <input type="text" name="footer[columnas][<?= $cIdx ?>][icono]" value="<?= htmlspecialchars($col['icono'] ?? '') ?>" class="sa-filter-input" placeholder="ph-fill ph-map-pin">
                        </div>
                    </div>

                    <div class="sa-footer-subbox">
                        <div class="sa-footer-subbox-head">
                            <label>Bloques de Texto / Párrafos (Soporta &lt;strong&gt; y saltos de línea)</label>
                            <button type="button" class="sa-btn sa-btn-sm" onclick="agregarBloqueTexto(this, <?= $cIdx ?>)">
                                <i class="ph-bold ph-plus"></i> Párrafo
                            </button>
                        </div>
                        <div class="lista-bloques-texto">
                            <?php foreach (($col['bloques_texto'] ?? []) as $bloqueTxt): ?>
                            <div class="sa-footer-item-row">
                                <textarea name="footer[columnas][<?= $cIdx ?>][bloques_texto][]" rows="3" class="sa-filter-input"><?= htmlspecialchars($bloqueTxt) ?></textarea>
                                <button type="button" class="sa-btn-icon-danger" onclick="eliminarElementoPadre(this, '.sa-footer-item-row')" title="Quitar párrafo">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="sa-footer-subbox">
                        <div class="sa-footer-subbox-head">
                            <label>Enlaces / Redes Sociales</label>
                            <button type="button" class="sa-btn sa-btn-sm" onclick="agregarEnlaceFooter(this, <?= $cIdx ?>)">
                                <i class="ph-bold ph-plus"></i> Enlace
                            </button>
                        </div>
                        <div class="lista-enlaces-footer">
                            <?php foreach (($col['enlaces'] ?? []) as $lIdx => $lnk): ?>
                            <div class="sa-footer-link-row">
                                <input type="text" name="footer[columnas][<?= $cIdx ?>][enlaces][<?= $lIdx ?>][icono]" value="<?= htmlspecialchars($lnk['icono'] ?? '') ?>" class="sa-filter-input" placeholder="Ícono (ph-fill ph-link)">
                                <input type="text" name="footer[columnas][<?= $cIdx ?>][enlaces][<?= $lIdx ?>][texto]" value="<?= htmlspecialchars($lnk['texto'] ?? '') ?>" class="sa-filter-input" placeholder="Texto del enlace">
                                <input type="text" name="footer[columnas][<?= $cIdx ?>][enlaces][<?= $lIdx ?>][url]" value="<?= htmlspecialchars($lnk['url'] ?? '#') ?>" class="sa-filter-input" placeholder="URL (https://... o #)">
                                <button type="button" class="sa-btn-icon-danger" onclick="eliminarElementoPadre(this, '.sa-footer-link-row')" title="Quitar enlace">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="sa-footer-field">
                        <label>Lema o Nota Destacada (Opcional)</label>
                        <input type="text" name="footer[columnas][<?= $cIdx ?>][lema]" value="<?= htmlspecialchars($col['lema'] ?? '') ?>" class="sa-filter-input" placeholder="Ej: &quot;Somos Excelencia Académica...&quot;">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="sa-footer-bottom-config">
                <h5><i class="ph-bold ph-rows"></i> Barra Inferior de Derechos y Créditos (Footer Bottom)</h5>
                <div class="sa-footer-field-row">
                    <div class="sa-footer-field">
                        <label>Texto Izquierdo (Copyright)</label>
                        <input type="text" name="footer[bottom_izquierda]" value="<?= htmlspecialchars($footerConfig['bottom_izquierda'] ?? '© 2026 UPTTMBI. Todos los derechos reservados.') ?>" class="sa-filter-input">
                    </div>
                    <div class="sa-footer-field">
                        <label>Texto Derecho (Créditos / Desarrollador)</label>
                        <input type="text" name="footer[bottom_derecha]" value="<?= htmlspecialchars($footerConfig['bottom_derecha'] ?? 'Desarrollado por PNF Informática') ?>" class="sa-filter-input">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
        <button type="submit" class="sa-btn sa-btn-primary">
            <i class="ph-bold ph-floppy-disk"></i> Aplicar Cambios Globales
        </button>
    </div>
</form>

<script>
let contadorColumnasFooter = <?= count($columnasActuales) ?>;

function switchConfigTab(tabId, btnElement) {
    const tabs = document.querySelectorAll('.sa-tab-content');
    tabs.forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });

    const buttons = document.querySelectorAll('.sa-tab-btn');
    buttons.forEach(button => button.classList.remove('tab-active'));

    const selectedTab = document.getElementById(tabId);
    if (selectedTab) {
        selectedTab.style.display = 'block';
        selectedTab.classList.add('active');
    }

    if (btnElement) {
        btnElement.classList.add('tab-active');
    }

    sessionStorage.setItem('gestorConfigTab', tabId);
}

function eliminarElementoPadre(btn, selector) {
    const el = btn.closest(selector);
    if (el) el.remove();
}

function agregarBloqueTexto(btn, colIdx) {
    const contenedor = btn.closest('.sa-footer-subbox').querySelector('.lista-bloques-texto');
    const div = document.createElement('div');
    div.className = 'sa-footer-item-row';
    div.innerHTML = `
        <textarea name="footer[columnas][${colIdx}][bloques_texto][]" rows="3" class="sa-filter-input" placeholder="Escribe el contenido del párrafo..."></textarea>
        <button type="button" class="sa-btn-icon-danger" onclick="eliminarElementoPadre(this, '.sa-footer-item-row')">
            <i class="ph-bold ph-x"></i>
        </button>
    `;
    contenedor.appendChild(div);
}

function agregarEnlaceFooter(btn, colIdx) {
    const contenedor = btn.closest('.sa-footer-subbox').querySelector('.lista-enlaces-footer');
    const linkIdx = Date.now() + Math.floor(Math.random() * 1000);
    const div = document.createElement('div');
    div.className = 'sa-footer-link-row';
    div.innerHTML = `
        <input type="text" name="footer[columnas][${colIdx}][enlaces][${linkIdx}][icono]" class="sa-filter-input" placeholder="ph-fill ph-globe">
        <input type="text" name="footer[columnas][${colIdx}][enlaces][${linkIdx}][texto]" class="sa-filter-input" placeholder="Nombre del enlace">
        <input type="text" name="footer[columnas][${colIdx}][enlaces][${linkIdx}][url]" value="#" class="sa-filter-input" placeholder="https://...">
        <button type="button" class="sa-btn-icon-danger" onclick="eliminarElementoPadre(this, '.sa-footer-link-row')">
            <i class="ph-bold ph-x"></i>
        </button>
    `;
    contenedor.appendChild(div);
}

function agregarColumnaFooter() {
    const idx = contadorColumnasFooter++;
    const contenedor = document.getElementById('contenedorColumnasFooter');
    const card = document.createElement('div');
    card.className = 'sa-footer-col-card';
    card.innerHTML = `
        <div class="sa-footer-col-card-top">
            <strong>Nueva Columna</strong>
            <button type="button" class="sa-btn-icon-danger" onclick="eliminarElementoPadre(this, '.sa-footer-col-card')">
                <i class="ph-bold ph-trash"></i> Eliminar Columna
            </button>
        </div>
        <div class="sa-footer-field-row">
            <div class="sa-footer-field">
                <label>Título de la Columna</label>
                <input type="text" name="footer[columnas][${idx}][titulo]" class="sa-filter-input" placeholder="Ej: Enlaces de Interés">
            </div>
            <div class="sa-footer-field">
                <label>Ícono del Título (Phosphor)</label>
                <input type="text" name="footer[columnas][${idx}][icono]" value="ph-fill ph-star" class="sa-filter-input" placeholder="ph-fill ph-star">
            </div>
        </div>
        <div class="sa-footer-subbox">
            <div class="sa-footer-subbox-head">
                <label>Bloques de Texto / Párrafos</label>
                <button type="button" class="sa-btn sa-btn-sm" onclick="agregarBloqueTexto(this, ${idx})">
                    <i class="ph-bold ph-plus"></i> Párrafo
                </button>
            </div>
            <div class="lista-bloques-texto"></div>
        </div>
        <div class="sa-footer-subbox">
            <div class="sa-footer-subbox-head">
                <label>Enlaces / Redes Sociales</label>
                <button type="button" class="sa-btn sa-btn-sm" onclick="agregarEnlaceFooter(this, ${idx})">
                    <i class="ph-bold ph-plus"></i> Enlace
                </button>
            </div>
            <div class="lista-enlaces-footer"></div>
        </div>
        <div class="sa-footer-field">
            <label>Lema o Nota Destacada (Opcional)</label>
            <input type="text" name="footer[columnas][${idx}][lema]" class="sa-filter-input" placeholder="Frase opcional al pie de la columna">
        </div>
    `;
    contenedor.appendChild(card);
}

function confirmarRestaurarFooter() {
    if (confirm('¿Estás seguro de que deseas restaurar el Footer Institucional a sus valores originales de la UPTTMBI? Se descartarán las columnas y enlaces personalizados actuales.')) {
        const inputReset = document.getElementById('inputRestaurarFooter');
        if (inputReset) {
            inputReset.value = '1';
            inputReset.closest('form').submit();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const parametros = new URLSearchParams(window.location.search);
    const tabDesdeUrl = parametros.get('tab');
    const tabGuardada = sessionStorage.getItem('gestorConfigTab');
    const tabId = tabDesdeUrl || tabGuardada || 'tabPaginacion';
    const boton = document.querySelector(`.sa-tab-btn[onclick*="'${tabId}'"]`);
    if (boton) {
        switchConfigTab(tabId, boton);
    }
});
</script>