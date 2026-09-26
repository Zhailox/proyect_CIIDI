<div class="recovery-split-container">
    <div class="recovery-form-side">
        <div class="recovery-form-box">
            <h2>CÓDIGO DE VERIFICACIÓN</h2>
            <p class="recovery-form-instruction">
                Hemos enviado un código a su correo electrónico. Ingréselo junto a su nueva contraseña.
            </p>

            <?php if (isset($error) && $error): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['exito_recuperar'])): ?>
                <div style="background: #d1fae5; color: #065f46; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    <?= htmlspecialchars($_SESSION['exito_recuperar']) ?>
                    <?php unset($_SESSION['exito_recuperar']); ?>
                </div>
            <?php endif; ?>

            <form action="procesar-codigo" method="POST" class="recovery-flat-form">
                <input type="hidden" name="uid" value="<?= htmlspecialchars($uid) ?>">
                
                <div class="recovery-flat-group">
                    <label for="codigo">Código de 6 dígitos:</label>
                    <input type="text" id="codigo" name="codigo" class="recovery-flat-input" placeholder="123456" required autocomplete="off" maxlength="6" pattern="\d{6}">
                </div>

                <div class="recovery-flat-group">
                    <label for="password">Nueva Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="recovery-flat-input" placeholder="Min. 8 caracteres" required autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="recovery-flat-group">
                    <label for="password_conf">Confirmar Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password_conf" name="password_conf" class="recovery-flat-input" placeholder="Repetir contraseña" required autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_conf', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="recovery-btn-submit">Restablecer Contraseña</button>
                <div class="recovery-flat-footer">
                    <a href="login">Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
    <div class="recovery-visual-side">
        <div class="recovery-visual-overlay"></div>
        <div class="recovery-visual-content">
            <h1 class="recovery-main-title">RESTABLECER ACCESO</h1>
            <blockquote class="recovery-quote">
                "La seguridad de su cuenta es nuestra máxima prioridad."
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
