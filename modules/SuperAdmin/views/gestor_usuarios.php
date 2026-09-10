<div class="sa-hero-header mb-2" style="background: #ffffff !important; border: 1px solid rgba(80, 89, 132, 0.18); padding: 1.5rem 2rem; border-radius: var(--radius-md); box-shadow: 0 4px 20px rgba(18, 26, 62, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-terciario) !important; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 0.3rem;">
                <i class="ph-bold ph-users-three"></i> ADMINISTRACIÓN DE CREDENCIALES Y ROLES
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #121a3e !important; margin: 0;">
                Gestión de Usuarios & Matriz RBAC
            </h1>
            <p style="margin: 0.3rem 0 0 0; color: #64748b !important; font-size: 0.9rem;">
                Control centralizado de usuarios, registro directo, matriz de privilegios y reseteo de claves.
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="toggleModalCrearUsuario(true)" class="btn sa-btn-primary" style="background: var(--color-secundario) !important; color: #ffffff !important; padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; border: none;">
                <i class="ph-bold ph-user-plus"></i> + Crear Usuario
            </button>
            <a href="sudoadmin" class="btn btn-outline" style="border-color: var(--color-secundario); color: var(--color-secundario) !important; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                <i class="ph-bold ph-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<!-- ALERTAS DE ÉXITO O ERROR DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_gestor_exito'])): ?>
    <div style="background: rgba(16,185,129,0.12); color: #047857; border: 1px solid rgba(16,185,129,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_gestor_exito']) ?>
        <?php unset($_SESSION['mensaje_gestor_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_gestor_error'])): ?>
    <div style="background: rgba(239,68,68,0.12); color: #b91c1c; border: 1px solid rgba(239,68,68,0.3); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_gestor_error']) ?>
        <?php unset($_SESSION['mensaje_gestor_error']); ?>
    </div>
<?php endif; ?>

<!-- NAVEGACIÓN INTERNA EN PESTAÑAS (TABS) PARA SECCIONAR RESPONSABILIDADES -->
<div class="sa-tabs-header glass-panel mb-2" style="background: #ffffff; padding: 8px; border-radius: var(--radius-sm); border: 1px solid rgba(80,89,132,0.15);">
    <button class="sa-tab-btn tab-active" onclick="switchUserTab('tab-comunidad', this)">
        <i class="ph-bold ph-users"></i> Comunidad de Usuarios (<?= count($todosLosUsuarios) ?>)
    </button>
    <button class="sa-tab-btn" onclick="switchUserTab('tab-rbac', this)">
        <i class="ph-bold ph-shield-check"></i> Matriz de Permisos (RBAC) & Roles
    </button>
    <button class="sa-tab-btn" onclick="switchUserTab('tab-docentes', this)">
        <i class="ph-bold ph-chalkboard-teacher"></i> Plantel Docente (<?= count($profesores) ?>)
    </button>
</div>

<!-- ==========================================================================
     BLOQUE 1: COMUNIDAD DE USUARIOS & DIRECTORIO GENERAL
     ========================================================================== -->
<div id="tab-comunidad" class="sa-tab-content active">
    
    <!-- TABLA GENERAL DE COMUNIDAD DE USUARIOS UNIFICADA CON BÚSQUEDA Y FILTROS -->
    <div class="glass-panel" style="padding: 1.25rem; border-radius: var(--radius-sm);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
            <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-users-three" style="color: var(--color-principal);"></i> Directorio General de Usuarios Registrados
            </h4>

            <!-- CONTROLES UNIFICADOS DE BÚSQUEDA Y FILTRADO -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                <input type="text" id="filterUserText" onkeyup="filterUserTable()" class="sa-filter-input" placeholder="Buscar cédula, nombre o email..." style="width: 260px;" value="<?= htmlspecialchars($cedulaBusqueda) ?>">
                <select id="filterUserRole" onchange="filterUserTable()" class="sa-filter-input">
                    <option value="">Todos los Roles</option>
                    <?php foreach ($roles as $rOption): ?>
                        <option value="<?= htmlspecialchars(strtolower($rOption['nombre'])) ?>"><?= htmlspecialchars($rOption['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="filterUserStatus" onchange="filterUserTable()" class="sa-filter-input">
                    <option value="">Todos los Estados</option>
                    <option value="activo">Activos</option>
                    <option value="suspendido">Suspendidos</option>
                </select>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table id="userDirectoryTable" style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: rgba(244,247,251,0.95);">
                        <th style="padding: 10px 12px; color: #121a3e !important; font-weight: 800;">Usuario / Cédula</th>
                        <th style="padding: 10px 12px; color: #121a3e !important; font-weight: 800;">Rol de Acceso</th>
                        <th style="padding: 10px 12px; color: #121a3e !important; font-weight: 800;">Última Actividad</th>
                        <th style="padding: 10px 12px; color: #121a3e !important; font-weight: 800;">Estado</th>
                        <th style="padding: 10px 12px; text-align: center; color: #121a3e !important; font-weight: 800;">Acciones de Seguridad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $usuariosFiltrados = array_filter($todosLosUsuarios, function($u) {
                        return (int)$u['id'] !== (int)$_SESSION['usuario_id'];
                    });
                    ?>
                    <?php if (empty($usuariosFiltrados)): ?>
                        <tr><td colspan="5" style="padding: 1rem; text-align: center; color: #94a3b8;">No se encontraron otros usuarios en el directorio.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuariosFiltrados as $usr): ?>
                            <tr class="user-row-item" data-role="<?= htmlspecialchars(strtolower($usr['rol_nombre'])) ?>" data-status="<?= $usr['activo'] ? 'activo' : 'suspendido' ?>" style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px 12px;">
                                    <strong class="user-search-name" style="color: var(--texto-titulos); display: block; font-size: 0.9rem;"><?= htmlspecialchars($usr['nombre_completo']) ?></strong>
                                    <span class="user-search-email" style="color: #64748b; font-size: 0.78rem;"><?= htmlspecialchars($usr['cedula']) ?> | <?= htmlspecialchars($usr['email']) ?></span>
                                </td>
                                <td style="padding: 10px 12px;">
                                    <span style="background: rgba(80, 89, 132, 0.12); color: var(--color-secundario); padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 0.78rem;">
                                        <?= htmlspecialchars($usr['rol_nombre']) ?>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; color: #475569; font-size: 0.8rem;">
                                    <?= !empty($usr['ultima_actividad']) ? date('d/m/Y H:i', strtotime($usr['ultima_actividad'])) : 'Sin registros' ?>
                                </td>
                                <td style="padding: 10px 12px;">
                                    <span style="background: <?= $usr['activo'] ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)' ?>; color: <?= $usr['activo'] ? '#10b981' : '#ef4444' ?>; padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 0.78rem;">
                                        <?= $usr['activo'] ? 'Activo' : 'Suspendido' ?>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; text-align: center;">
                                    <div style="display: flex; gap: 0.4rem; justify-content: center; flex-wrap: wrap;">
                                        <a href="editar-usuario?cedula=<?= htmlspecialchars($usr['cedula']) ?>" class="btn btn-outline" title="Editar Credenciales" style="padding: 4px 8px; font-size: 0.78rem; border-color: #cbd5e1; color: #334155; text-decoration: none; border-radius: 4px;">
                                            <i class="ph-bold ph-pencil-simple"></i> Editar
                                        </a>

                                        <!-- ACCIÓN RÁPIDA: RESETEAR CLAVE TEMPORAL -->
                                        <form action="resetear-clave-usuario" method="POST" style="margin:0;" onsubmit="return confirm('¿Restablecer contraseña de esta cuenta a Temporal2026!?');">
                                            <input type="hidden" name="usuario_id" value="<?= $usr['id'] ?>">
                                            <input type="hidden" name="cedula" value="<?= htmlspecialchars($usr['cedula']) ?>">
                                            <button type="submit" class="btn" title="Restablecer Clave a Temporal2026!" style="background: rgba(245, 158, 11, 0.15); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 8px; font-size: 0.78rem; border-radius: 4px; cursor: pointer; font-weight: 600;">
                                                <i class="ph-bold ph-key"></i> Clave
                                            </button>
                                        </form>

                                        <form action="revocar-sesion" method="POST" style="margin:0;">
                                            <input type="hidden" name="usuario_id" value="<?= $usr['id'] ?>">
                                            <button type="submit" class="btn" title="Cerrar Sesión Remota (Kill Session)" style="background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.25); padding: 4px 8px; font-size: 0.78rem; border-radius: 4px; cursor: pointer; font-weight: 600;">
                                                <i class="ph-bold ph-power"></i> Revocar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- BARRAS Y CONTROLES DE PAGINACIÓN FLUIDA -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem; flex-wrap: wrap; gap: 0.75rem; border-top: 1px solid #e2e8f0; padding-top: 0.75rem;">
            <span id="userPaginationInfo" style="font-size: 0.82rem; color: #64748b; font-weight: 600;">Mostrando registros</span>
            <div style="display: flex; gap: 0.4rem; align-items: center;">
                <button id="btnPrevUserPage" onclick="changeUserPage(-1)" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.8rem; border-color: #cbd5e1; color: #334155;">‹ Anterior</button>
                <span id="userPageNum" style="font-size: 0.85rem; font-weight: 700; color: var(--color-principal); padding: 0 6px;">1</span>
                <button id="btnNextUserPage" onclick="changeUserPage(1)" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.8rem; border-color: #cbd5e1; color: #334155;">Siguiente ›</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     BLOQUE 2: MATRIZ DE PERMISOS (RBAC) & DENOMINACIÓN DE ROLES
     ========================================================================== -->
<div id="tab-rbac" class="sa-tab-content" style="display: none;">
    <div class="glass-panel mb-2" style="padding: 1.5rem; border-radius: var(--radius-sm);">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-shield-check" style="color: var(--color-secundario);"></i> Matriz Granular de Permisos por Acción (RBAC)
        </h4>
        <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin-bottom: 1.25rem;">
            Asigne permisos dinámicos por tipo de acción (Crear, Editar, Eliminar, Auditar) para controlar el comportamiento del sistema por cada rol.
        </p>

        <form action="guardar-matriz-rbac" method="POST">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; background: rgba(244,247,251,0.95);">
                            <th style="padding: 10px 12px; color: #121a3e !important; font-weight: 800;">Rol del Sistema</th>
                            <th style="padding: 10px 12px; text-align: center; color: #121a3e !important; font-weight: 800;">Crear (POST)</th>
                            <th style="padding: 10px 12px; text-align: center; color: #121a3e !important; font-weight: 800;">Editar (UPDATE)</th>
                            <th style="padding: 10px 12px; text-align: center; color: #121a3e !important; font-weight: 800;">Eliminar (DELETE)</th>
                            <th style="padding: 10px 12px; text-align: center; color: #121a3e !important; font-weight: 800;">Auditar (LOGS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($roles as $rItem): 
                            $rolNombre = $rItem['nombre'];
                            $permisosRol = $matrizRBAC[$rolNombre] ?? ['crear' => true, 'editar' => false, 'eliminar' => false, 'auditar' => false];
                        ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px 12px; font-weight: 700; color: var(--texto-titulos);">
                                    <i class="ph-bold ph-shield" style="color: var(--color-terciario); margin-right: 6px;"></i>
                                    <?= htmlspecialchars($rolNombre) ?>
                                </td>
                                <?php foreach (['crear', 'editar', 'eliminar', 'auditar'] as $accion): ?>
                                    <td style="padding: 10px 12px; text-align: center;">
                                        <input type="hidden" name="matrix[<?= htmlspecialchars($rolNombre) ?>][<?= $accion ?>]" value="0">
                                        <input type="checkbox" name="matrix[<?= htmlspecialchars($rolNombre) ?>][<?= $accion ?>]" value="1" <?= !empty($permisosRol[$accion]) ? 'checked' : '' ?> style="width: 18px; height: 18px; cursor: pointer;">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.25rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-solid" style="background: var(--color-secundario) !important; color: #ffffff !important; border-radius: 6px; padding: 8px 16px; font-weight: 700;">
                    <i class="ph-bold ph-floppy-disk"></i> Guardar Matriz RBAC
                </button>
            </div>
        </form>

        <!-- SECCIÓN EDICIÓN Y RENOMBRADO DE ROLES -->
        <div style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.25rem;">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--texto-titulos); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 6px;">
                <i class="ph-bold ph-pencil-line" style="color: var(--color-terciario);"></i> Renombrar Denominación de Roles (Sincronización en Cascada)
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                <?php foreach ($roles as $rItem): ?>
                    <form action="actualizar-rol" method="POST" style="background: #ffffff; border: 1px solid rgba(80, 89, 132, 0.2); padding: 0.85rem 1rem; border-radius: 8px; display: flex; gap: 0.5rem; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(18,26,62,0.02);">
                        <input type="hidden" name="rol_id" value="<?= $rItem['id'] ?>">
                        <input type="hidden" name="nombre_anterior" value="<?= htmlspecialchars($rItem['nombre']) ?>">
                        
                        <input type="text" name="nuevo_nombre" value="<?= htmlspecialchars($rItem['nombre']) ?>" class="sa-filter-input" style="flex: 1; min-width: 0;" required>
                        
                        <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; border-color: var(--color-secundario); color: var(--color-secundario) !important; cursor: pointer; white-space: nowrap; flex-shrink: 0;" title="Guardar Nuevo Nombre de Rol">
                            <i class="ph-bold ph-check"></i> Renombrar
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     BLOQUE 3: PLANTEL DOCENTE DEL SISTEMA
     ========================================================================== -->
<div id="tab-docentes" class="sa-tab-content" style="display: none;">
    <div class="glass-panel" style="padding: 1.5rem; border-radius: var(--radius-sm);">
        <h4 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 700; color: var(--texto-titulos); display: flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-chalkboard-teacher" style="color: var(--color-terciario);"></i> Plantel de Profesores y Docentes Registrados
        </h4>

        <div class="gestor-teachers-grid">
            <?php if (empty($profesores)): ?>
                <p style="font-size: 0.85rem; color: #94a3b8; margin: 1rem 0;">No hay profesores o docentes registrados actualmente en la base de datos.</p>
            <?php else: ?>
                <?php foreach ($profesores as $profe): ?>
                    <div class="gestor-teacher-card">
                        <div class="gestor-teacher-avatar">
                            <?= strtoupper(substr($profe['nombre_completo'], 0, 1)) ?>
                        </div>
                        <div class="gestor-teacher-info">
                            <h4 class="gestor-teacher-name"><?= htmlspecialchars($profe['nombre_completo']) ?></h4>
                            <span class="gestor-teacher-cedula"><?= htmlspecialchars($profe['cedula']) ?></span>
                        </div>
                        <a href="gestor-usuarios?cedula=<?= htmlspecialchars($profe['cedula']) ?>" title="Administrar Docente" class="gestor-teacher-remove" style="color: var(--color-terciario); text-decoration: none;">
                            <i class="ph-bold ph-arrow-circle-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL PARA CREAR NUEVO USUARIO -->
<div id="modalCrearUsuario" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 12px; padding: 1.75rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1px solid rgba(226, 232, 240, 0.8);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--color-principal); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-user-plus" style="color: var(--color-secundario);"></i> Registrar Nuevo Usuario
            </h3>
            <button onclick="toggleModalCrearUsuario(false)" style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <form action="crear-usuario" method="POST" style="display: flex; flex-direction: column; gap: 0.85rem;">
            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Cédula de Identidad</label>
                <input type="text" name="cedula" class="sa-filter-input" placeholder="Ej: V-12345678" required style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Nombre Completo</label>
                <input type="text" name="nombre" class="sa-filter-input" placeholder="Ej: Juan Pérez" required style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Correo Electrónico</label>
                <input type="email" name="email" class="sa-filter-input" placeholder="ejemplo@upttmbi.edu.ve" required style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Rol Inicial</label>
                <select name="id_rol" class="sa-filter-input" required style="width: 100%; box-sizing: border-box;">
                    <?php foreach ($roles as $rOpt): ?>
                        <option value="<?= $rOpt['id'] ?>"><?= htmlspecialchars($rOpt['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Contraseña Inicial</label>
                <input type="password" name="password" class="sa-filter-input" placeholder="••••••••" required style="width: 100%; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.75rem;">
                <button type="button" onclick="toggleModalCrearUsuario(false)" class="btn btn-outline" style="border-color: #cbd5e1; color: #64748b; padding: 0.65rem 1rem; border-radius: 6px; font-size: 0.85rem;">Cancelar</button>
                <button type="submit" class="btn btn-solid" style="background: var(--color-secundario) !important; color: #ffffff !important; border: none; padding: 0.65rem 1.25rem; border-radius: 6px; font-size: 0.85rem; font-weight: 700;">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS LOCALES JS DE NAVEGACIÓN & FILTROS -->
<script>
function switchUserTab(tabId, btnElement) {
    const tabs = document.querySelectorAll('.sa-tab-content');
    tabs.forEach(t => t.style.display = 'none');

    const btns = document.querySelectorAll('.sa-tab-btn');
    btns.forEach(b => b.classList.remove('tab-active'));

    const selectedTab = document.getElementById(tabId);
    if (selectedTab) {
        selectedTab.style.display = 'block';
    }

    if (btnElement) {
        btnElement.classList.add('tab-active');
    }
}

function toggleModalCrearUsuario(show) {
    const modal = document.getElementById('modalCrearUsuario');
    if (modal) {
        modal.style.display = show ? 'flex' : 'none';
    }
}

let currentUserPage = 1;
const itemsPerPage = 10;

function filterUserTable() {
    currentUserPage = 1;
    applyPaginationAndFilter();
}

function applyPaginationAndFilter() {
    const txt = document.getElementById('filterUserText').value.toLowerCase();
    const role = document.getElementById('filterUserRole').value.toLowerCase();
    const status = document.getElementById('filterUserStatus').value.toLowerCase();
    const rows = Array.from(document.querySelectorAll('.user-row-item'));

    const matchingRows = rows.filter(r => {
        const rowText = r.textContent.toLowerCase();
        const rowRole = (r.getAttribute('data-role') || '').toLowerCase();
        const rowStatus = (r.getAttribute('data-status') || '').toLowerCase();

        const matchesText = !txt || rowText.includes(txt);
        const matchesRole = !role || rowRole === role;
        const matchesStatus = !status || rowStatus === status;

        return matchesText && matchesRole && matchesStatus;
    });

    // Ocultar todas las filas primero
    rows.forEach(r => r.style.display = 'none');

    const totalMatches = matchingRows.length;
    const totalPages = Math.ceil(totalMatches / itemsPerPage) || 1;

    if (currentUserPage > totalPages) currentUserPage = totalPages;
    if (currentUserPage < 1) currentUserPage = 1;

    const startIdx = (currentUserPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    const visibleRows = matchingRows.slice(startIdx, endIdx);
    visibleRows.forEach(r => r.style.display = '');

    // Actualizar controles e información de paginación
    const infoSpan = document.getElementById('userPaginationInfo');
    const pageNumSpan = document.getElementById('userPageNum');
    const btnPrev = document.getElementById('btnPrevUserPage');
    const btnNext = document.getElementById('btnNextUserPage');

    if (infoSpan) {
        if (totalMatches === 0) {
            infoSpan.textContent = 'Mostrando 0 de 0 registros';
        } else {
            const displayStart = startIdx + 1;
            const displayEnd = Math.min(endIdx, totalMatches);
            infoSpan.textContent = `Mostrando ${displayStart}-${displayEnd} de ${totalMatches} usuarios registrados`;
        }
    }

    if (pageNumSpan) {
        pageNumSpan.textContent = `Página ${currentUserPage} de ${totalPages}`;
    }

    if (btnPrev) {
        btnPrev.disabled = (currentUserPage <= 1);
        btnPrev.style.opacity = (currentUserPage <= 1) ? '0.5' : '1';
        btnPrev.style.cursor = (currentUserPage <= 1) ? 'not-allowed' : 'pointer';
    }

    if (btnNext) {
        btnNext.disabled = (currentUserPage >= totalPages);
        btnNext.style.opacity = (currentUserPage >= totalPages) ? '0.5' : '1';
        btnNext.style.cursor = (currentUserPage >= totalPages) ? 'not-allowed' : 'pointer';
    }
}

function changeUserPage(dir) {
    currentUserPage += dir;
    applyPaginationAndFilter();
}

document.addEventListener('DOMContentLoaded', () => {
    applyPaginationAndFilter();
});
</script>