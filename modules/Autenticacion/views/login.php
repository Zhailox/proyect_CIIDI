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
                    <label for="cedula">Cédula:</label>
                    <input type="number" id="cedula" name="cedula" class="login-flat-input" required autocomplete="off">
                </div>

                <div class="login-flat-group">
                    <label for="password">Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="login-flat-input" required>
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