<div class="recovery-split-container">
    <div class="recovery-form-side">
        <div class="recovery-form-box">
            <h2>ACTIVACIÓN DE CUENTA DOCENTE</h2>
            <p class="recovery-form-instruction">
                Estimado(a) Profesor(a) <strong><?= htmlspecialchars($profesor['nombre_completo'] ?? 'Docente') ?></strong> (C.I: <?= htmlspecialchars($profesor['cedula'] ?? '') ?>).
                Por favor, defina su nueva contraseña para activar su cuenta institucional.
            </p>

            <?php if (!empty($error)): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; color: #b91c1c; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="procesar-completar-registro" method="POST" class="recovery-flat-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                
                <div class="recovery-flat-group">
                    <label for="password">Definir Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="recovery-flat-input" placeholder="Mínimo 8 caracteres (1 mayúscula, 1 número, 1 símbolo)" required autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="recovery-flat-group">
                    <label for="password_confirm">Confirmar Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password_confirm" name="password_confirm" class="recovery-flat-input" placeholder="Repita la contraseña" required autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirm', this)" title="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="recovery-btn-submit" style="background-color: #121a3e;">Activar Mi Cuenta Docente</button>

                <div class="recovery-flat-footer">
                    <a href="login">Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>

    <div class="recovery-visual-side" style="background: linear-gradient(135deg, #121a3e 0%, #505984 100%);">
        <div class="recovery-visual-overlay"></div>
        <div class="recovery-visual-content">
            <h1 class="recovery-main-title">PLANTEL DOCENTE CIIDI</h1>
            <blockquote class="recovery-quote">
                "Portal institucional de docentes e investigadores de la Universidad Politécnica Territorial Mario Briceño Iragorry."
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
