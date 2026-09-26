<div class="recovery-split-container">
    <div class="recovery-form-side">
        <div class="recovery-form-box">
            <h2>RESTABLECER CONTRASEÑA</h2>
            <p class="recovery-form-instruction">
                Hola, <strong><?= htmlspecialchars($nombreUsuario ?? 'Usuario') ?></strong>. Ingrese su nueva contraseña de acceso.
            </p>

            <?php if (isset($error) && $error): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; color: #b91c1c; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="procesar-restablecer-clave" method="POST" class="recovery-flat-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                
                <div class="recovery-flat-group">
                    <label for="password">Nueva Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="recovery-flat-input" placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="recovery-flat-group">
                    <label for="password_confirm">Confirmar Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password_confirm" name="password_confirm" class="recovery-flat-input" placeholder="Repita la contraseña" required autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirm', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="recovery-btn-submit">Guardar Nueva Contraseña</button>

                <div class="recovery-flat-footer">
                    <a href="login">Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>

    <div class="recovery-visual-side">
        <div class="recovery-visual-overlay"></div>
        <div class="recovery-visual-content">
            <h1 class="recovery-main-title">SEGURIDAD UPTTMBI</h1>
            <blockquote class="recovery-quote">
                "Restablecimiento de credenciales de forma segura mediante enlace firmado criptográficamente."
            </blockquote>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ph-bold ph-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'ph-bold ph-eye';
    }
}
</script>
