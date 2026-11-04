<div class="login-split-container">
    
    <div class="login-visual-side">
        <div class="login-visual-overlay"></div>
        <div class="login-visual-content">
            <h1 class="login-main-title">SISTEMA UPTTMBI</h1>
            <blockquote class="login-quote">
                "Plataforma institucional para la gestión, visibilidad e impacto de la producción científica y tecnológica de nuestra comunidad."
            </blockquote>
            <button type="button" class="login-btn-more">Saber Más</button>
        </div>
    </div>

    <div class="login-form-side">
        <div class="login-form-box">
            
            <p class="login-form-instruction">
                Para acceder al sistema usted debe <strong>introducir sus credenciales</strong> primero.
            </p>
            <?php if (isset($error) && !empty($error)): ?>
                <div class="login-error-message">
                    <i class="ph-bold ph-warning-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($exito) && !empty($exito)): ?>
                <div style="background: rgba(16, 185, 129, 0.12); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                    <i class="ph-bold ph-check-circle" style="font-size: 1.2rem; flex-shrink: 0;"></i>
                    <span><?= htmlspecialchars($exito) ?></span>
                </div>
            <?php endif; ?>

            <?php
            require_once CORE_PATH . 'Security/CaptchaService.php';
            $segData = CaptchaService::generarCamposSeguridad();
            ?>
            <form action="procesar-login" method="POST" class="login-flat-form">
                <!-- SEGURIDAD 1.1: HONEYPOT INVISIBLE PARA BOTS -->
                <input type="text" name="website_url_hp" style="display:none !important; opacity:0; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
                <!-- SEGURIDAD 1.2: TIMESTAMP DE TIEMPO HUMANO -->
                <input type="hidden" name="_form_ts" value="<?= $segData['timestamp'] ?>">

                <div class="login-flat-group">
                    <label for="cedula_num">Cédula de Identidad:</label>
                    <div style="display: flex; gap: 0.5rem; align-items: stretch;">
                        <select id="cedula_tipo" name="cedula_tipo" class="login-flat-input" style="width: 80px; flex-shrink: 0; cursor: pointer; font-weight: 700;" onchange="actualizarCedulaLogin()">
                            <option value="V-">V-</option>
                            <option value="E-">E-</option>
                        </select>
                        <input type="text" inputmode="numeric" id="cedula_num" name="cedula_num" class="login-flat-input" placeholder="Ej: 12345678" required autocomplete="username" maxlength="9" oninput="this.value = this.value.replace(/\D/g, ''); actualizarCedulaLogin();" style="flex: 1;">
                        <input type="hidden" id="cedula" name="cedula">
                    </div>
                </div>

                <div class="login-flat-group">
                    <label for="password">Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="login-flat-input" required autocomplete="current-password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- SEGURIDAD 2: CAPTCHA VISUAL DISTORSIONADO GD -->
                <div class="login-flat-group">
                    <label for="captcha_ans">Verificación Anti-Bot:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <img src="?ruta=captcha-imagen&t=<?= time() ?>" id="imgCaptcha" alt="Captcha" style="border-radius: 6px; border: 1px solid #cbd5e1; height: 36px; cursor: pointer;" title="Haz clic para recargar el captcha" onclick="this.src='?ruta=captcha-imagen&t='+Date.now()">
                        <input type="text" id="captcha_ans" name="captcha_ans" class="login-flat-input" placeholder="Respuesta" required style="width: 110px; text-align: center; font-weight: 700;" autocomplete="off">
                    </div>
                </div>

                <button type="submit" class="login-btn-submit">Entrar al Sistema</button>

                <div class="login-flat-footer">
                    <a href="recuperar-cuenta">¿Olvidó su Contraseña?</a>
                    <span>|</span>
                    <a href="registro" style="font-weight: 700; color: var(--color-secundario);">Crear Cuenta</a>
                </div>
            </form>
            

            
        </div>
    </div>

</div>

<script>
function actualizarCedulaLogin() {
    const tipoEl = document.getElementById('cedula_tipo');
    const numEl = document.getElementById('cedula_num');
    const hiddenEl = document.getElementById('cedula');
    if (!tipoEl || !numEl || !hiddenEl) return;
    const num = numEl.value.trim().replace(/\D/g, '');
    hiddenEl.value = num ? (tipoEl.value + num) : '';
}

document.querySelector('.login-flat-form')?.addEventListener('submit', function() {
    actualizarCedulaLogin();
});

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