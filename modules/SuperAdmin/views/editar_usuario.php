<div class="welcome-banner admin-banner gradient">
    <h1>Modificar Usuario</h1>
    <p>Actualización de credenciales maestras y niveles de acceso.</p>
</div>

<div class="admin-card-panel" style="max-width: 700px; margin: 2rem auto;">
    
    <?php if (isset($error) && $error !== null): ?>
        <div class="gestor-error-msg" style="margin-bottom: 1.5rem;">
            <i class="ph-fill ph-warning-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="procesar-edicion-usuario" method="POST" class="login-flat-form">
        <input type="hidden" name="usuario_id" value="<?= $usuarioEditar['id'] ?>">
        <input type="hidden" name="cedula_original" value="<?= htmlspecialchars($usuarioEditar['cedula']) ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div class="login-flat-group">
                <label>Cédula de Identidad:</label>
                <input type="text" name="cedula" class="login-flat-input" value="<?= htmlspecialchars($usuarioEditar['cedula']) ?>" required>
            </div>
            <div class="login-flat-group">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre" class="login-flat-input" value="<?= htmlspecialchars($usuarioEditar['nombre_completo']) ?>" required>
            </div>
        </div>

        <div class="login-flat-group" style="margin-bottom: 1rem;">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" class="login-flat-input" value="<?= htmlspecialchars($usuarioEditar['email']) ?>" required>
        </div>
        <div style="background: rgba(0,0,0,0.02); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05); margin-bottom: 2rem;">
            <h4 style="margin-top: 0; color: var(--texto-titulos); font-size: 0.95rem; margin-bottom: 1rem;">Cambio de Contraseña (Opcional)</h4>
            <p style="font-size: 0.85rem; color: var(--gris); margin-bottom: 1rem;">Deje estos campos en blanco si no desea modificar la contraseña del usuario.</p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="login-flat-group" style="margin: 0;">
                    <label>Nueva Contraseña:</label>
                    <input type="password" name="password" class="login-flat-input" placeholder="••••••••">
                </div>
                <div class="login-flat-group" style="margin: 0;">
                    <label>Confirmar Contraseña:</label>
                    <input type="password" name="password_confirm" class="login-flat-input" placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="login-flat-group" style="margin-bottom: 2rem;">
            <label>Asignación de Rol:</label>
            <select name="id_rol" class="login-flat-input" style="cursor: pointer;" required>
                <?php foreach ($rolesDisponibles as $rol): ?>
                    <option value="<?= $rol['id'] ?>" <?= $usuarioEditar['rol_nombre'] === $rol['nombre'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($rol['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="gestor-usuarios?cedula=<?= urlencode($usuarioEditar['cedula']) ?>" class="btn btn-outline" style="border-color: var(--gris); color: var(--gris); text-decoration: none;">Cancelar</a>
            <button type="submit" class="btn btn-solid" style="background-color: var(--color-secundario);">Guardar Cambios</button>
        </div>
    </form>
</div>