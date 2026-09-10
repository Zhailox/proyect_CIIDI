<div class="welcome-banner admin-banner gradient">
    <h1>Gestión de Usuarios</h1>
    <p>Administración de credenciales, asignación de roles docentes y monitoreo de la comunidad PNF Informática.</p>
</div>

<h3 class="admin-section-title gestor-mt-title">Buscador General</h3>

<div class="admin-card-panel">
    <form action="gestor-usuarios" method="GET" class="gestor-search-form">
        <input type="text" name="cedula" class="login-flat-input gestor-search-input" placeholder="Ej: V-12345678" value="<?= htmlspecialchars($cedulaBusqueda) ?>" required>
        <button type="submit" class="btn btn-solid btn-search-cedula">
            <i class="ph-bold ph-magnifying-glass"></i> Buscar Cédula
        </button>
    </form>

    <?php if ($mensajeError): ?>
        <div class="gestor-error-msg">
            <i class="ph-fill ph-warning-circle"></i> <?= $mensajeError ?>
        </div>
    <?php endif; ?>

    <?php if ($usuarioEncontrado): ?>
        <div class="gestor-result-box">
            <h4 class="gestor-result-title">Resultado de Búsqueda:</h4>
            
            <div class="gestor-user-card">
                <div class="gestor-user-details">
                    <h2 class="gestor-user-name"><?= htmlspecialchars($usuarioEncontrado['nombre_completo']) ?></h2>
                    <p class="gestor-user-meta">C.I: <?= htmlspecialchars($usuarioEncontrado['cedula']) ?> | Correo: <?= htmlspecialchars($usuarioEncontrado['email']) ?></p>
                    
                    <span class="gestor-badge-role">
                        Rol: <?= htmlspecialchars($usuarioEncontrado['rol_nombre']) ?> (Nivel <?= $usuarioEncontrado['nivel_privilegio'] ?>)
                    </span>
                    
                    <span class="gestor-badge-status <?= $usuarioEncontrado['activo'] ? 'status-active' : 'status-suspended' ?>">
                        Estado: <?= $usuarioEncontrado['activo'] ? 'Activo' : 'Suspendido' ?>
                    </span>
                </div>
                
                <div class="gestor-user-actions">
                    <?php if ($usuarioEncontrado['id'] === $_SESSION['usuario_id']): ?>
                        <span class="gestor-badge-role" style="background: rgba(255,255,255,0.1); color: var(--gris); border: 1px solid var(--gris);">
                            <i class="ph-bold ph-lock-key"></i> Esta es tu cuenta
                        </span>
                    <?php else: ?>
                        <a href="editar-usuario?cedula=<?= htmlspecialchars($usuarioEncontrado['cedula']) ?>" class="btn btn-outline" style="border-color: var(--color-terciario); color: var(--color-terciario); text-decoration: none; text-align: center;">
                            <i class="ph-bold ph-pencil-simple"></i> Editar Datos
                        </a>

                        <form action="alternar-estado-usuario" method="POST" style="margin: 0;">
                            <input type="hidden" name="usuario_id" value="<?= $usuarioEncontrado['id'] ?>">
                            <input type="hidden" name="cedula" value="<?= htmlspecialchars($usuarioEncontrado['cedula']) ?>">
                            <input type="hidden" name="estado_actual" value="<?= $usuarioEncontrado['activo'] ? '1' : '0' ?>">
                            
                            <button type="submit" class="btn btn-status-toggle <?= $usuarioEncontrado['activo'] ? 'toggle-suspend' : 'toggle-restore' ?>" style="width: 100%;">
                                <?= $usuarioEncontrado['activo'] ? 'Suspender Acceso' : 'Restaurar Acceso' ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['mensaje_gestor_exito'])): ?>
    <div style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_gestor_exito']) ?>
    </div>
    <?php unset($_SESSION['mensaje_gestor_exito']); ?>
<?php endif; ?>

<!-- 1. MATRIZ DE PERMISOS / RBAC DINÁMICO & GESTOR DE ROLES -->
<h3 class="admin-section-title">Matriz de Permisos / RBAC Dinámico & Edición de Roles</h3>

<div class="admin-card-panel mb-2" style="background: #ffffff; border-radius: 12px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.08);">
    <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 1.25rem;">
        Asigne permisos dinámicos por acción (Crear, Editar, Eliminar, Auditar) y personalice las denominaciones de cada rol del sistema.
    </p>

    <form action="guardar-matriz-rbac" method="POST">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; color: #334155;">Rol del Sistema</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; color: #334155;">Crear (POST)</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; color: #334155;">Editar (UPDATE)</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; color: #334155;">Eliminar (DELETE)</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; color: #334155;">Auditar (LOGS)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($roles as $rItem): 
                        $rolNombre = $rItem['nombre'];
                        $permisosRol = $matrizRBAC[$rolNombre] ?? ['crear' => true, 'editar' => false, 'eliminar' => false, 'auditar' => false];
                    ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem; font-weight: 600; color: #1e293b;">
                                <i class="ph-bold ph-shield" style="color: var(--color-principal); margin-right: 6px;"></i>
                                <?= htmlspecialchars($rolNombre) ?>
                            </td>
                            <?php foreach (['crear', 'editar', 'eliminar', 'auditar'] as $accion): ?>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
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
            <button type="submit" class="btn btn-solid" style="background: var(--color-secundario, #002244); color: white;">
                <i class="ph-bold ph-floppy-disk"></i> Guardar Matriz RBAC
            </button>
        </div>
    </form>

    <!-- RENOMBRADO SEGURO DE ROLES EN CASCADA -->
    <div style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.25rem;">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem;">
            <i class="ph-bold ph-pencil-line"></i> Renombrar Denominación de Roles (Sincronización en Cascadas)
        </h4>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            <?php foreach ($roles as $rItem): ?>
                <form action="actualizar-rol" method="POST" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.85rem; border-radius: 8px; display: flex; gap: 0.5rem; align-items: center;">
                    <input type="hidden" name="rol_id" value="<?= $rItem['id'] ?>">
                    <input type="hidden" name="nombre_anterior" value="<?= htmlspecialchars($rItem['nombre']) ?>">
                    
                    <input type="text" name="nuevo_nombre" value="<?= htmlspecialchars($rItem['nombre']) ?>" style="flex: 1; padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-weight: 600; font-size: 0.85rem;" required>
                    
                    <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; border-color: var(--color-secundario); color: var(--color-secundario); cursor: pointer;" title="Guardar Nuevo Nombre de Rol">
                        <i class="ph-bold ph-check"></i> Renombrar
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- 2. GESTOR DE SESIONES ACTIVAS (KILL SESSION) -->
<h3 class="admin-section-title">Comunidad de Usuarios & Gestor de Sesiones Activas</h3>

<div class="admin-card-panel" style="background: #ffffff; border-radius: 12px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.08); margin-bottom: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; background: #f8fafc; text-align: left;">
                    <th style="padding: 0.75rem 1rem;">Usuario / Cédula</th>
                    <th style="padding: 0.75rem 1rem;">Rol</th>
                    <th style="padding: 0.75rem 1rem;">Última Actividad</th>
                    <th style="padding: 0.75rem 1rem;">Estado Cuenta</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Acciones de Seguridad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todosLosUsuarios as $usr): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem 1rem;">
                            <strong style="color: #0f172a; display: block;"><?= htmlspecialchars($usr['nombre_completo']) ?></strong>
                            <span style="color: #64748b; font-size: 0.78rem;"><?= htmlspecialchars($usr['cedula']) ?> | <?= htmlspecialchars($usr['email']) ?></span>
                        </td>
                        <td style="padding: 0.75rem 1rem;">
                            <span class="gestor-badge-role" style="font-size: 0.75rem; padding: 3px 8px;">
                                <?= htmlspecialchars($usr['rol_nombre']) ?>
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #475569; font-size: 0.82rem;">
                            <?= !empty($usr['ultima_actividad']) ? date('d/m/Y H:i', strtotime($usr['ultima_actividad'])) : 'Sin registro' ?>
                        </td>
                        <td style="padding: 0.75rem 1rem;">
                            <span class="gestor-badge-status <?= $usr['activo'] ? 'status-active' : 'status-suspended' ?>" style="font-size: 0.75rem; padding: 3px 8px;">
                                <?= $usr['activo'] ? 'Activo' : 'Suspendido' ?>
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <a href="editar-usuario?cedula=<?= htmlspecialchars($usr['cedula']) ?>" class="btn btn-outline" title="Editar Credenciales / Forzar Contraseña" style="padding: 4px 8px; font-size: 0.8rem; border-color: #cbd5e1; color: #334155; text-decoration: none;">
                                    <i class="ph-bold ph-pencil-simple"></i> Editar
                                </a>

                                <?php if ((int)$usr['id'] !== (int)$_SESSION['usuario_id']): ?>
                                    <form action="revocar-sesion" method="POST" style="margin:0;">
                                        <input type="hidden" name="usuario_id" value="<?= $usr['id'] ?>">
                                        <button type="submit" class="btn" title="Revocar Sesión Remota (Kill Session)" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 8px; font-size: 0.8rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                            <i class="ph-bold ph-power"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<h3 class="admin-section-title">Plantel Docente</h3>

<div class="gestor-teachers-grid">
    <?php if (empty($profesores)): ?>
        <p class="gestor-empty-msg">No hay profesores registrados en el sistema.</p>
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