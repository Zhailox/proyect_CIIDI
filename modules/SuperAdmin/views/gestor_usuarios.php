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
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button onclick="toggleModalInvitarProfesor(true)" class="sa-btn sa-btn-dark">
                <i class="ph-bold ph-envelope-simple-open"></i> + Invitar Profesor
            </button>
            <button onclick="toggleModalCrearUsuario(true)" class="sa-btn sa-btn-primary">
                <i class="ph-bold ph-user-plus"></i> + Crear Usuario
            </button>
            <a href="sudoadmin" class="sa-btn sa-btn-outline">
                <i class="ph-bold ph-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<!-- ALERTAS DE ÉXITO O ERROR DE SESIÓN -->
<?php if (isset($_SESSION['mensaje_gestor_exito'])): ?>
    <div class="sa-alert sa-alert-success">
        <i class="ph-bold ph-check-circle"></i>
        <?= htmlspecialchars($_SESSION['mensaje_gestor_exito']) ?>
        <?php unset($_SESSION['mensaje_gestor_exito']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_gestor_error'])): ?>
    <div class="sa-alert sa-alert-error">
        <i class="ph-bold ph-warning-circle"></i>
        <?= htmlspecialchars($_SESSION['mensaje_gestor_error']) ?>
        <?php unset($_SESSION['mensaje_gestor_error']); ?>
    </div>
<?php endif; ?>
<?php 
// Identificamos dinámicamente el ID que corresponde al Nivel Dios (0)
$idPrivilegioCero = null;
if (isset($privilegios)) {
    foreach ($privilegios as $p) {
        if ((int)$p['nivel_privilegio'] === 0) {
            $idPrivilegioCero = $p['privilegio_id'];
            break;
        }
    }
}
?>




<!-- NAVEGACIÓN INTERNA EN PESTAÑAS (TABS) PARA SECCIONAR RESPONSABILIDADES -->
<div class="sa-tabs-header glass-panel mb-2">
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
                                    <span class="<?= $usr['activo'] ? 'sa-badge-active' : 'sa-badge-inactive' ?>">
                                        <?= $usr['activo'] ? 'Activo' : 'Suspendido' ?>
                                    </span>
                                </td>
                                <td style="padding: 10px 12px; text-align: center;">
                                    <div style="display: flex; gap: 0.4rem; justify-content: center; flex-wrap: wrap;">
                                        <button type="button"
                                            class="sa-btn-action"
                                            title="Editar Credenciales"
                                            onclick="abrirModalEditarUsuario(<?= htmlspecialchars(json_encode([
                                                'id' => $usr['id'],
                                                'cedula' => $usr['cedula'],
                                                'nombre' => $usr['nombre_completo'],
                                                'email' => $usr['email'],
                                                'rol' => $usr['rol_nombre']
                                            ]), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="ph-bold ph-pencil-simple"></i> Editar
                                        </button>

                                        <!-- REVOCAR -->
                                        <form action="revocar-sesion" method="POST" style="margin:0;">
                                             <input type="hidden" name="usuario_id" value="<?= $usr['id'] ?>">
                                             <button type="button" class="sa-btn-action" title="Cerrar Sesión Remota" onclick="mostrarConfirmacionUsuarios(this.form, 'Revocar Sesión', '¿Expulsar a este usuario del sistema?', 'ph-power', 'var(--color-principal)')">
                                                 <i class="ph-bold ph-power"></i> Revocar
                                             </button>
                                         </form>

                                        <!-- SUSPENDER / RESTAURAR -->
                                        <form action="alternar-estado-usuario" method="POST" style="margin:0;">
                                             <input type="hidden" name="usuario_id" value="<?= $usr['id'] ?>">
                                             <input type="hidden" name="cedula" value="<?= htmlspecialchars($usr['cedula']) ?>">
                                             <input type="hidden" name="estado_actual" value="<?= $usr['activo'] ? '1' : '0' ?>">
                                             <button type="button" class="sa-btn-action" onclick="mostrarConfirmacionUsuarios(this.form, '<?= $usr['activo'] ? 'Suspender' : 'Restaurar' ?> Cuenta', '¿Confirma que desea <?= $usr['activo'] ? 'SUSPENDER' : 'RESTAURAR' ?> al usuario <?= htmlspecialchars($usr['nombre_completo'], ENT_QUOTES) ?>?', '<?= $usr['activo'] ? 'ph-user-minus' : 'ph-user-check' ?>', 'var(--color-secundario)')">
                                                 <i class="ph-bold <?= $usr['activo'] ? 'ph-user-minus' : 'ph-user-check' ?>"></i> <?= $usr['activo'] ? 'Suspender' : 'Restaurar' ?>
                                             </button>
                                         </form>

                                        <!-- ELIMINAR Y LIBERAR CREDENCIALES -->
                                        <form action="eliminar-usuario" method="POST" style="margin:0;">
                                             <input type="hidden" name="usuario_id" value="<?= $usr['id'] ?>">
                                             <button type="button" class="sa-btn-action sa-btn-action-dark" title="Eliminar Usuario y Liberar Credenciales" onclick="mostrarConfirmacionUsuarios(this.form, 'Eliminar y Archivar Usuario', '¿Está seguro de ELIMINAR permanentemente a <?= htmlspecialchars($usr['nombre_completo'], ENT_QUOTES) ?>? Sus credenciales (Cédula y Email) se liberarán de inmediato.', 'ph-trash', 'var(--color-principal)')">
                                                 <i class="ph-bold ph-trash"></i> Eliminar
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

        <!-- ===== TOOLBAR SUPERIOR ===== -->
        <div class="rbac-toolbar">
            <div class="rbac-toolbar-info">
                <h4>
                    <i class="ph-bold ph-shield-check"></i>
                    Matriz Granular de Permisos (RBAC)
                </h4>
                <p>Asigne permisos dinámicos por tipo de acción para controlar el comportamiento del sistema.</p>
            </div>

            <div class="rbac-toolbar-actions">
                <form action="crear-nivel-privilegio" method="POST" style="margin: 0;">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <button type="button" class="rbac-btn rbac-btn-outline"
                            onclick="mostrarConfirmacionUsuarios(this.form, 'Extender Privilegios', '¿Crear un nuevo nivel jerárquico superior en la base de datos?', 'ph-sort-ascending', 'var(--color-terciario)')">
                        <i class="ph-bold ph-plus"></i> Añadir Nivel
                    </button>
                </form>
            </div>
        </div>
        <!-- ===== LISTA DE NIVELES ===== -->
        <form action="guardar-matriz-rbac" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div style="display: flex; flex-direction: column; gap: 0.6rem; max-height: 600px; overflow-y: auto; padding-right: 5px;">

                <?php foreach ($privilegios as $priv): $nivel = $priv['nivel_privilegio']; if ($nivel === 0) continue; ?>

                    <details class="rbac-level">
                        <summary class="rbac-level-summary">
                            <div class="rbac-level-title">
                                <i class="ph-bold ph-shield-star"></i>
                                Configurar Permisos del Nivel <?= htmlspecialchars($nivel) ?>
                            </div>

                            <div class="rbac-level-controls">
                                <?php if ($nivel > 3): ?>
                                    <button type="button" class="rbac-level-delete"
                                            title="Eliminar Nivel <?= $nivel ?>"
                                            onclick="event.preventDefault(); event.stopPropagation(); confirmarEliminacionNivel(<?= $nivel ?>);">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                <?php endif; ?>
                                <i class="ph-bold ph-caret-down rbac-level-caret"></i>
                            </div>
                        </summary>

                        <div style="padding: 0; overflow-x: auto; border-top: 1px solid #e2e8f0;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                                <thead style="background: rgba(244,247,251,0.5);">
                                    <tr>
                                        <th style="padding: 8px 16px; color: #121a3e; font-weight: 800;">Módulo Objetivo</th>
                                        <?php foreach ($accionesDisponibles as $accion): ?>
                                            <th style="padding: 8px 12px; text-align: center; color: #121a3e; font-weight: 800; text-transform: capitalize;">
                                                <?= str_replace('_', ' ', $accion) ?>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($modulosInstalados as $indice => $moduloNombre):
                                        $permisosNivelModulo = $matrizRBAC[$nivel][$moduloNombre] ?? [];
                                    ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9; background: <?= $indice % 2 === 0 ? '#ffffff' : '#f8fafc' ?>;">
                                            <td style="padding: 8px 16px; font-weight: 600; color: var(--texto-titulos);">
                                                <i class="ph-bold ph-plugs-connected" style="color: #64748b; margin-right: 4px;"></i>
                                                <?= htmlspecialchars($moduloNombre) ?>
                                            </td>
                                            <?php foreach ($accionesDisponibles as $accion): ?>
                                                <td style="padding: 8px 12px; text-align: center;">
                                                    <input type="hidden" name="matrix[<?= $nivel ?>][<?= htmlspecialchars($moduloNombre) ?>][<?= $accion ?>]" value="0">
                                                    <input type="checkbox" name="matrix[<?= $nivel ?>][<?= htmlspecialchars($moduloNombre) ?>][<?= $accion ?>]" value="1" <?= !empty($permisosNivelModulo[$accion]) ? 'checked' : '' ?> style="width: 16px; height: 16px; cursor: pointer;">
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </details>

                <?php endforeach; ?>
            </div>

            <div class="rbac-form-footer">
                <button type="submit" class="rbac-btn rbac-btn-solid">
                    <i class="ph-bold ph-floppy-disk"></i> Guardar Matriz RBAC
                </button>
            </div>
        </form>

        <!-- ===== SECCIÓN ROLES ===== -->
        <div class="rbac-roles-section">
            <div class="rbac-roles-header">
                <h4>
                    <i class="ph-bold ph-pencil-line"></i>
                    Editar Denominación y Jerarquía de Roles
                </h4>
                <button type="button" class="rbac-btn rbac-btn-solid" onclick="toggleModalCrearRol(true)">
                    <i class="ph-bold ph-shield-plus"></i> Crear Nuevo Rol
                </button>
            </div>

            <div class="rbac-roles-grid">
                <?php foreach ($roles as $rItem):
                    $esRolSupremo = (isset($idPrivilegioCero) && $rItem['privilegio_id'] == $idPrivilegioCero);
                    if ($esRolSupremo):
                ?>
                    <!-- TARJETA INMUTABLE PARA EL ROL SUPREMO -->
                    <div class="rbac-role-card-locked">
                        <div class="rbac-role-row" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="text" value="<?= htmlspecialchars($rItem['nombre']) ?>" class="sa-filter-input"
                                   style="flex: 1; min-width: 0; background: #e2e8f0; cursor: not-allowed; color: #64748b;"
                                   readonly title="El rol base no puede ser alterado">
                            <span class="rbac-level-badge">Nivel 0</span>
                        </div>
                        <div style="display: flex; margin-top: 0.5rem;">
                            <button type="button" class="rbac-btn"
                                    style="width: 100%; justify-content: center; background: #cbd5e1; color: #64748b; cursor: not-allowed;"
                                    disabled>
                                <i class="ph-bold ph-lock-key"></i> Rol de Sistema (Inalterable)
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- FORMULARIO NORMAL PARA LOS DEMÁS ROLES -->
                    <form action="actualizar-rol" method="POST" class="rbac-role-card">
                        <input type="hidden" name="rol_id" value="<?= $rItem['id'] ?>">
                        <input type="hidden" name="nombre_anterior" value="<?= htmlspecialchars($rItem['nombre']) ?>">

                        <div class="rbac-role-row">
                            <input type="text" name="nuevo_nombre" value="<?= htmlspecialchars($rItem['nombre']) ?>" class="sa-filter-input" required>

                            <select name="privilegio_id" class="sa-filter-input" required title="Cambiar nivel jerárquico">
                                <?php foreach ($privilegios as $priv):
                                    if ((int)$priv['nivel_privilegio'] === 0) continue;
                                    $isSelected = (isset($rItem['privilegio_id']) && $rItem['privilegio_id'] == $priv['privilegio_id']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $priv['privilegio_id'] ?>" <?= $isSelected ?>>Nivel <?= $priv['nivel_privilegio'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="rbac-role-actions">
                            <button type="submit" class="rbac-btn rbac-btn-outline">
                                <i class="ph-bold ph-check"></i> Actualizar
                            </button>
                            <button type="button" class="rbac-btn rbac-btn-danger-outline"
                                    onclick="mostrarConfirmacionUsuarios(document.getElementById('form-del-rol-<?= $rItem['id'] ?>'), 'Eliminar Rol', '¿Seguro que desea eliminar el rol <?= htmlspecialchars($rItem['nombre'], ENT_QUOTES) ?>?', 'ph-trash', 'var(--color-principal)')">
                                <i class="ph-bold ph-trash"></i> Eliminar
                            </button>
                        </div>
                    </form>
                    <form id="form-del-rol-<?= $rItem['id'] ?>" action="eliminar-rol" method="POST" style="display:none;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="hidden" name="rol_id" value="<?= $rItem['id'] ?>">
                    </form>
                <?php endif; ?>
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
                    <div class="gestor-teacher-card teacher-card-item">
                        <div class="gestor-teacher-avatar">
                            <?= strtoupper(substr($profe['nombre_completo'], 0, 1)) ?>
                        </div>
                        <div class="gestor-teacher-info">
                            <h4 class="gestor-teacher-name"><?= htmlspecialchars($profe['nombre_completo']) ?></h4>
                            <span class="gestor-teacher-cedula"><?= htmlspecialchars($profe['cedula']) ?></span>
                        </div>
                        <a href="gestor-usuarios?cedula=<?= urlencode($profe['cedula']) ?>&tab=tab-comunidad"
                            title="Buscar usuario docente"
                            class="gestor-teacher-remove"
                            style="color: var(--color-terciario); text-decoration: none;">
                            <i class="ph-bold ph-arrow-circle-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- BARRAS Y CONTROLES DE PAGINACIÓN DOCENTES -->
        <?php if (!empty($profesores)): ?>
        <div id="teacherPaginationContainer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem; flex-wrap: wrap; gap: 0.75rem; border-top: 1px solid #e2e8f0; padding-top: 0.75rem;">
            <span id="teacherPaginationInfo" style="font-size: 0.82rem; color: #64748b; font-weight: 600;">Mostrando registros</span>
            <div style="display: flex; gap: 0.4rem; align-items: center;">
                <button id="btnPrevTeacherPage" onclick="changeTeacherPage(-1)" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.8rem; border-color: #cbd5e1; color: #334155;">&lt; Anterior</button>
                <span id="teacherPageNum" style="font-size: 0.85rem; font-weight: 700; color: var(--color-principal); padding: 0 6px;">1</span>
                <button id="btnNextTeacherPage" onclick="changeTeacherPage(1)" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.8rem; border-color: #cbd5e1; color: #334155;">Siguiente &gt;</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL PARA CREAR NUEVO USUARIO -->
<div id="modalCrearUsuario" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 12px; padding: 1.75rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1px solid rgba(226, 232, 240, 0.8);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--texto-titulos, #0f172a); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-user-plus" style="color: var(--color-secundario, #2563eb);"></i> Registrar Nuevo Usuario
            </h3>
            <button type="button" onclick="toggleModalCrearUsuario(false)" style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">✕</button>
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

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button type="button" onclick="toggleModalCrearUsuario(false)" class="sa-btn sa-btn-cancel" style="min-width: 110px;">Cancelar</button>
                <button type="submit" class="sa-btn sa-btn-primary" style="min-width: 140px;">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PARA EDITAR USUARIO -->

<div id="modalEditarUsuario"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: var(--radius-md); padding: 1.75rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--color-principal); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-user-plus" style="color: var(--color-secundario);"></i>  Editar Usuario
            </h3>

            <button type="button"
                    onclick="toggleModalEditarUsuario(false)"
                    style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">
                ✕
            </button>
        </div>

        <form action="procesar-edicion-usuario"
              method="POST"
              style="display: flex; flex-direction: column; gap: 0.85rem;">

            <input type="hidden" name="usuario_id" id="editarUsuarioId">
            <input type="hidden" name="cedula_original" id="editarCedulaOriginal">

            <div>
                <label>Cédula de Identidad</label>
                <input type="text"
                       name="cedula"
                       id="editarCedula"
                       class="sa-filter-input"
                       required
                       style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label>Nombre Completo</label>
                <input type="text"
                       name="nombre"
                       id="editarNombre"
                       class="sa-filter-input"
                       required
                       style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label>Correo Electrónico</label>
                <input type="email"
                       name="email"
                       id="editarEmail"
                       class="sa-filter-input"
                       required
                       style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label>Rol</label>
                <select name="id_rol" id="editarRol" class="sa-filter-input" required style="width: 100%; box-sizing: border-box;">
                    <?php foreach ($roles as $rol): 
                        $esRolCero = (isset($idPrivilegioCero) && $rol['privilegio_id'] == $idPrivilegioCero) ? 'true' : 'false';
                    ?>
                        <option value="<?= $rol['id'] ?>" 
                                data-nombre="<?= htmlspecialchars($rol['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                data-es-cero="<?= $esRolCero ?>">
                            <?= htmlspecialchars($rol['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Nueva Contraseña</label>
                <input type="password"
                       name="password"
                       class="sa-filter-input"
                       placeholder="Dejar vacío para conservarla"
                       style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label>Confirmar Contraseña</label>
                <input type="password"
                       name="password_confirm"
                       class="sa-filter-input"
                       placeholder="Repetir solo si desea cambiarla"
                       style="width: 100%; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button type="button"
                        onclick="toggleModalEditarUsuario(false)"
                        class="sa-btn sa-btn-cancel" style="min-width: 110px;">Cancelar</button>

                <button type="submit"
                        class="sa-btn sa-btn-primary"
                        style="min-width: 140px;">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PARA CREAR NUEVO ROL -->
<div id="modalCrearRol" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; border-radius: var(--radius-md); padding: 1.75rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1px solid rgba(80, 89, 132, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--color-principal); display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-shield-plus" style="color: var(--color-secundario);"></i> Crear Nuevo Rol
            </h3>
            <button type="button" onclick="toggleModalCrearRol(false)" style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">✕</button>
        </div>

        <form action="crear-rol" method="POST" style="display: flex; flex-direction: column; gap: 0.85rem;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Nombre del Rol</label>
                <input type="text" name="nuevo_rol_nombre" class="sa-filter-input" placeholder="Ej: Coordinador Académico" required style="width: 100%; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--texto-titulos); display: block; margin-bottom: 4px;">Nivel de Privilegio Base</label>
                <select name="nuevo_privilegio_id" class="sa-filter-input" required style="width: 100%; box-sizing: border-box;">
                    <option value="">Seleccione un nivel...</option>
                    <?php foreach ($privilegios as $priv): 
                        if ((int)$priv['nivel_privilegio'] === 0) continue;
                    ?>
                        <option value="<?= $priv['privilegio_id'] ?>">Nivel <?= $priv['nivel_privilegio'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button type="button" onclick="toggleModalCrearRol(false)" class="sa-btn sa-btn-cancel" style="min-width: 110px;">Cancelar</button>
                <button type="submit" class="sa-btn sa-btn-primary" style="min-width: 120px;">Crear Rol</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL DE CONFIRMACIÓN UNIVERSAL -->
<div id="modalConfirmacionUsuarios" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius-md); width: 90%; max-width: 400px; padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); text-align: center; border: 1px solid rgba(80, 89, 132, 0.2);">
        <div id="modalConfirmIcon" style="font-size: 3.5rem; margin-bottom: 1rem;"></div>
        <h3 id="modalConfirmTitle" style="margin: 0 0 0.5rem 0; font-size: 1.2rem; color: var(--texto-titulos);"></h3>
        <p id="modalConfirmMessage" style="color: var(--texto-silenciado); font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5;"></p>
        <div style="display: flex; justify-content: center; gap: 0.75rem;">
            <button type="button" class="sa-btn sa-btn-cancel" onclick="document.getElementById('modalConfirmacionUsuarios').style.display='none'" style="min-width: 110px;">Cancelar</button>
            <button type="button" id="modalConfirmBtn" class="sa-btn sa-btn-dark" style="min-width: 110px;">Confirmar</button>
        </div>
    </div>
</div>
<!-- SCRIPTS LOCALES JS DE NAVEGACIÓN & FILTROS -->
 <form id="formEliminarNivel" action="eliminar-nivel-privilegio" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <input type="hidden" name="nivel" id="inputEliminarNivel" value="">
</form>
<script>
function switchUserTab(tabId, btnElement) {
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

    if (tabId === 'tab-docentes') {
        applyTeacherPagination();
    }

    sessionStorage.setItem('gestorUsuariosTab', tabId);
}

function toggleModalCrearUsuario(show) {
    const modal = document.getElementById('modalCrearUsuario');
    if (modal) {
        modal.style.display = show ? 'flex' : 'none';
    }
}

let currentUserPage = 1;
const itemsPerPage = <?= (int)($paginacionUsuarios ?? 15) ?>;

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

let currentTeacherPage = 1;
const teachersPerPage = <?= (int)($paginacionDocentes ?? 15) ?>;

function applyTeacherPagination() {
    const cards = Array.from(document.querySelectorAll('.teacher-card-item'));
    if (!cards.length) return;

    cards.forEach(c => c.style.display = 'none');

    const totalMatches = cards.length;
    const totalPages = Math.ceil(totalMatches / teachersPerPage) || 1;

    if (currentTeacherPage > totalPages) currentTeacherPage = totalPages;
    if (currentTeacherPage < 1) currentTeacherPage = 1;

    const startIdx = (currentTeacherPage - 1) * teachersPerPage;
    const endIdx = startIdx + teachersPerPage;

    const visibleCards = cards.slice(startIdx, endIdx);
    visibleCards.forEach(c => c.style.display = '');

    const infoSpan = document.getElementById('teacherPaginationInfo');
    const pageNumSpan = document.getElementById('teacherPageNum');
    const btnPrev = document.getElementById('btnPrevTeacherPage');
    const btnNext = document.getElementById('btnNextTeacherPage');

    if (infoSpan) {
        const displayStart = startIdx + 1;
        const displayEnd = Math.min(endIdx, totalMatches);
        infoSpan.textContent = `Mostrando ${displayStart}-${displayEnd} de ${totalMatches} docentes registrados`;
    }

    if (pageNumSpan) {
        pageNumSpan.textContent = `Página ${currentTeacherPage} de ${totalPages}`;
    }

    if (btnPrev) {
        btnPrev.disabled = (currentTeacherPage <= 1);
        btnPrev.style.opacity = (currentTeacherPage <= 1) ? '0.5' : '1';
        btnPrev.style.cursor = (currentTeacherPage <= 1) ? 'not-allowed' : 'pointer';
    }

    if (btnNext) {
        btnNext.disabled = (currentTeacherPage >= totalPages);
        btnNext.style.opacity = (currentTeacherPage >= totalPages) ? '0.5' : '1';
        btnNext.style.cursor = (currentTeacherPage >= totalPages) ? 'not-allowed' : 'pointer';
    }
}

function changeTeacherPage(dir) {
    currentTeacherPage += dir;
    applyTeacherPagination();
}
function toggleModalEditarUsuario(show) {
    const modal = document.getElementById('modalEditarUsuario');

    if (modal) {
        modal.style.display = show ? 'flex' : 'none';
    }
}
function toggleModalCrearRol(show) {
    const modal = document.getElementById('modalCrearRol');
    if (modal) {
        modal.style.display = show ? 'flex' : 'none';
    }
}

function abrirModalEditarUsuario(usuario) {
    document.getElementById('editarUsuarioId').value = usuario.id;
    document.getElementById('editarCedulaOriginal').value = usuario.cedula;
    document.getElementById('editarCedula').value = usuario.cedula;
    document.getElementById('editarNombre').value = usuario.nombre;
    document.getElementById('editarEmail').value = usuario.email;

    const selectorRol = document.getElementById('editarRol');

    Array.from(selectorRol.options).forEach(opcion => {
        opcion.selected = opcion.dataset.nombre === usuario.rol;
    });

    toggleModalEditarUsuario(true);
}
document.addEventListener('DOMContentLoaded', () => {
    const parametros = new URLSearchParams(window.location.search);
    const tabDesdeUrl = parametros.get('tab');
    const tabGuardada = sessionStorage.getItem('gestorUsuariosTab');
    const tabId = tabDesdeUrl || tabGuardada || 'tab-comunidad';
    const boton = document.querySelector(
        `.sa-tab-btn[onclick*="'${tabId}'"]`
    );

    switchUserTab(tabId, boton);
    applyPaginationAndFilter();
    applyTeacherPagination();
});
function mostrarConfirmacionUsuarios(form, titulo, mensaje, icono, color) {
    document.getElementById('modalConfirmTitle').textContent = titulo;
    document.getElementById('modalConfirmMessage').textContent = mensaje;
    document.getElementById('modalConfirmIcon').innerHTML = `<i class="ph-bold ${icono}" style="color: ${color};"></i>`;
    
    const btnConfirm = document.getElementById('modalConfirmBtn');
    btnConfirm.style.background = color;
    btnConfirm.onclick = function() { form.submit(); };
    
    document.getElementById('modalConfirmacionUsuarios').style.display = 'flex';
}
function confirmarEliminacionNivel(nivel) {
    document.getElementById('inputEliminarNivel').value = nivel;
    const form = document.getElementById('formEliminarNivel');
    mostrarConfirmacionUsuarios(form, 'Eliminar Nivel', `¿Seguro que deseas eliminar el nivel de privilegio ${nivel}? Esta acción fallará por seguridad si aún existen roles asignados a esta jerarquía.`, 'ph-trash', 'var(--color-principal)');
}
function toggleModalInvitarProfesor(show) {
    const modal = document.getElementById('modalInvitarProfesor');
    if (modal) {
        modal.style.display = show ? 'flex' : 'none';
    }
}
</script>

<!-- MODAL: INVITAR PROFESOR POR CORREO -->
<div id="modalInvitarProfesor" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(18, 26, 62, 0.6); backdrop-filter: blur(4px); z-index: 9999; justify-content: center; align-items: center; padding: 1rem;">
    <div class="glass-panel" style="background: #ffffff; width: 100%; max-width: 500px; border-radius: var(--radius-md); padding: 1.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); border: 1px solid rgba(80,89,132,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
            <h3 style="margin: 0; font-size: 1.1rem; color: #121a3e; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-envelope-simple-open" style="color: #7090cb;"></i> Invitar Nuevo Docente / Profesor
            </h3>
            <button type="button" onclick="toggleModalInvitarProfesor(false)" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 0; margin-bottom: 1.25rem;">
            Emita un token de invitación firmado enviado al correo institucional del docente para que defina su propia contraseña y active su cuenta.
        </p>

        <form action="invitar-profesor" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 4px;">Cédula de Identidad (*):</label>
                <input type="text" name="cedula" required class="sa-filter-input" style="width: 100%;" placeholder="Ej: V-12345678">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 4px;">Nombre Completo (*):</label>
                <input type="text" name="nombre" required class="sa-filter-input" style="width: 100%;" placeholder="Ej: Prof. María Pérez">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 4px;">Correo Institucional (*):</label>
                <input type="email" name="email" required class="sa-filter-input" style="width: 100%;" placeholder="docente@upttmbi.edu.ve">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <button type="button" onclick="toggleModalInvitarProfesor(false)" class="sa-btn sa-btn-cancel" style="min-width: 110px;">Cancelar</button>
                <button type="submit" class="sa-btn sa-btn-dark" style="min-width: 150px;">
                    <i class="ph-bold ph-paper-plane-tilt"></i> Emitir Invitación
                </button>
            </div>
        </form>
    </div>
</div>