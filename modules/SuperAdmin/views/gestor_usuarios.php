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