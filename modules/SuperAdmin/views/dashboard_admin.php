<div class="welcome-banner admin-banner gradient">
    <h1>Centro de Mando - Sudoadmin</h1>
    <p>Supervisión global de la arquitectura. Monitoreo de recursos, usuarios y mantenimiento del servidor.</p>
</div>

<?php if (isset($_SESSION['mensaje_admin_exito'])): ?>
    <div class="mensaje-exito">
        <i class="ph-bold ph-check-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_exito']) ?>
    </div>
    <?php unset($_SESSION['mensaje_admin_exito']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['mensaje_admin_error'])): ?>
    <div class="mensaje-error">
        <i class="ph-bold ph-warning-circle" style="font-size: 1.2rem;"></i>
        <?= htmlspecialchars($_SESSION['mensaje_admin_error']) ?>
    </div>
    <?php unset($_SESSION['mensaje_admin_error']); ?>
<?php endif; ?>

<h3 class="admin-section-title">Monitor de Usuarios</h3>
<div class="metric-grid-v2">
    <div class="metric-card-v2 success">
        <div>
            <h4>Usuarios Activos</h4>
            <div class="metric-value"><?= $stats['usuarios_activos'] ?> <span class="metric-sub"><?= $stats['usuarios_online'] ?> Online</span></div>
        </div>
    </div>
    <div class="metric-card-v2 warning">
        <div>
            <h4>Pendientes de Aprobación</h4>
            <div class="metric-value"><?= $stats['empresas_pendientes'] ?> <span class="metric-sub">Empresas</span></div>
        </div>
    </div>
    <div class="metric-card-v2 danger">
        <div>
            <h4>Cuentas Deshabilitadas</h4>
            <div class="metric-value"><?= $stats['usuarios_bloqueados'] ?> <span class="metric-sub">Bloqueados</span></div>
        </div>
    </div>
    <div class="metric-card-v2">
        <div>
            <h4>Tractores (Docentes)</h4>
            <div class="metric-value"><?= $stats['docentes'] ?> <span class="metric-sub">Registrados</span></div>
        </div>
    </div>
</div>

<h3 class="admin-section-title">Herramientas de Administración</h3>
<div class="actions-container">
    
    <details class="action-accordion">
        <summary>Exportar Base de Datos (PostgreSQL)</summary>
        <div class="action-content">
            <!-- Volcado Completo -->
            <a href="generar-backup" class="btn btn-secondary" style="text-decoration: none; display: block; margin-bottom: 0.5rem;">
                <i class="ph-bold ph-database"></i> Volcado Completo (Dump .sql)
            </a>
            
            <!-- Volcado de Solo Esquema -->
            <a href="generar-backup-esquema" class="btn btn-secondary" style="text-decoration: none; display: block; margin-bottom: 0.5rem;">
                <i class="ph-bold ph-file-code"></i> Exportar solo Esquema (Sin datos)
            </a>
            
            <!-- Volcado de Tabla Específica -->
            <form action="generar-backup-tabla" method="POST" style="display: flex; gap: 0.5rem; margin-top: 0.5rem; width: 100%; margin-bottom: 0;">
                <select name="nombre_tabla" class="login-flat-input" required style="margin-bottom: 0; flex-grow: 1; padding: 0.5rem; cursor: pointer;">
                    <option value="" disabled selected>Seleccione la tabla a exportar...</option>
                    <?php foreach ($tablas as $tabla): ?>
                        <option value="<?= htmlspecialchars($tabla) ?>">
                            <?= htmlspecialchars(ucfirst($tabla)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary" style="white-space: nowrap;">
                    <i class="ph-bold ph-table"></i> Exportar Tabla
                </button>
            </form>
        </div>
    </details>

    <?php
    // Leemos el estado actual
    $archivo_mant = __DIR__ . '/../../../storage/maintenance.json';
    $dataMant = file_exists($archivo_mant) ? json_decode(file_get_contents($archivo_mant), true) : ['activo' => false];
    $mantenimientoActivo = $dataMant['activo'] ?? false;
    ?>
    <details class="action-accordion">
        <summary>Modo Mantenimiento</summary>
        <div class="action-content">
            <p style="width: 100%; font-size: 0.9rem; color: var(--texto-silenciado); margin-bottom: 1rem;">Cierra el acceso a los estudiantes y muestra una pantalla de "Mantenimiento Programado". Los administradores podrán seguir navegando.</p>
            
            <form action="alternar-mantenimiento" method="POST" style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%; margin: 0;">
                
                <?php if ($mantenimientoActivo == false): ?>
                    <!-- Solo mostramos el input si vamos a ACTIVAR el mantenimiento -->
                    <input type="text" name="mensaje" class="login-flat-input" placeholder="Mensaje para los usuarios (Opcional)..." style="padding: 0.6rem;">
                <?php endif; ?>
                
                
                <button type="submit" class="btn <?= $mantenimientoActivo ? 'btn-secondary' : 'btn-danger' ?>" style="width: 100%; justify-content: center;">
                    <i class="<?= $mantenimientoActivo ? 'ph-bold ph-power' : 'ph-bold ph-warning-circle' ?>"></i> 
                    <?= $mantenimientoActivo ? 'Restaurar Sistema (Abrir)' : 'Activar Mantenimiento (Cerrar)' ?>
                </button>
            </form>
        </div>
    </details>
    <details class="action-accordion">
        <summary style="color: #dc2626;"> Restaurar Copia de Seguridad</summary>
        <div class="action-content">
            <p style="width: 100%; font-size: 0.9rem; color: var(--texto-silenciado); margin-bottom: 1rem;">
                Cargue un archivo <strong>.sql</strong> generado previamente por el sistema. <br>
                <span style="color: #dc2626; font-weight: bold;">¡Advertencia: Esta acción sobreescribirá y reemplazará todos los datos actuales!</span>
            </p>
            
            <form action="restaurar-backup" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%; margin: 0;">
                <input type="file" name="backup_file" accept=".sql" class="login-flat-input" style="padding: 0.6rem;" required>
                
                <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center;" onclick="return confirm('¿ESTÁ COMPLETAMENTE SEGURO? Esta acción es irreversible y reemplazará la base de datos actual.');">
                    <i class="ph-bold ph-warning-circle"></i> Ejecutar Restauración
                </button>
            </form>
        </div>
    </details>

</div>