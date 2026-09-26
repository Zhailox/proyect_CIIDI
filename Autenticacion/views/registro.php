<div class="login-split-container">
    
    <div class="login-visual-side">
        <div class="login-visual-overlay"></div>
        <div class=\"login-visual-content\">
            <h1 class="login-main-title">ÚNETE AL ECOSISTEMA</h1>
            <blockquote class="login-quote">
                "Forma parte de la red de investigadores y desarrolladores sociotecnológicos del estado Trujillo."
            </blockquote>
        </div>
    </div>

    <div class="login-form-side">
        <div class="login-form-box">
            
            <h2 style="color: var(--color-principal); margin-bottom: 0.5rem; font-size: 1.8rem; font-weight: 800;">Crear Cuenta</h2>
            <p class="login-form-instruction" style="margin-bottom: 1.5rem;">
                Complete sus datos para registrarse como estudiante del PNF en Informática.
            </p>

            <?php if (isset($error) && $error !== null): ?>
                <div style="background-color: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; color: #ef4444; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; font-size: 0.9rem; font-weight: 600;">
                    <i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <?php
            require_once CORE_PATH . 'Security/CaptchaService.php';
            $segDataReg = CaptchaService::generarCamposSeguridad();
            ?>
            <form action="procesar-registro" method="POST" class="login-flat-form">
                <!-- SEGURIDAD 1.1: HONEYPOT INVISIBLE PARA BOTS -->
                <input type="text" name="website_url_hp" style="display:none !important; opacity:0; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
                <!-- SEGURIDAD 1.2: TIMESTAMP DE TIEMPO HUMANO -->
                <input type="hidden" name="_form_ts" value="<?= $segDataReg['timestamp'] ?>">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="login-flat-group">
                        <label for="cedula">Cédula:</label>
                        <input type="text" id="cedula" name="cedula" class="login-flat-input" placeholder="V-12345678" required autocomplete="off">
                    </div>

                    <div class="login-flat-group">
                        <label for="nombre">Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" class="login-flat-input" placeholder="Ej. Juan Pérez" required autocomplete="name">
                    </div>
                </div>

                <div class="login-flat-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" class="login-flat-input" required autocomplete="email">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="login-flat-group">
                        <label for="password">Contraseña:</label>
                        <div class="password-field-wrapper">
                            <input type="password" id="password" name="password" class="login-flat-input" required oninput="evaluarFortalezaClave(this.value)">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                                <i class="ph-bold ph-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="login-flat-group">
                        <label for="password_confirm">Confirmar Clave:</label>
                        <div class="password-field-wrapper">
                            <input type="password" id="password_confirm" name="password_confirm" class="login-flat-input" required oninput="validarCoincidenciaClave()">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirm', this)" title="Mostrar / Ocultar Contraseña" aria-label="Mostrar / Ocultar Contraseña">
                                <i class="ph-bold ph-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MEDIDOR DE FORTALEZA (ENTROPY METER) Y REQUISITOS -->
                <div class="strength-meter-container">
                    <div class="strength-bar-track">
                        <div id="strengthBarFill" class="strength-bar-fill"></div>
                    </div>
                    <div class="strength-label-text">
                        <span>Seguridad de clave: <strong id="strengthLabel">Esperando contraseña...</strong></span>
                        <span id="matchStatusLabel" style="font-size: 0.72rem; font-weight: 700; color: #94a3b8;"></span>
                    </div>
                    
                    <ul class="password-rules-list">
                        <li id="ruleLength" class="rule-item invalid">
                            <i class="ph-bold ph-x-circle"></i> Mínimo 8 caracteres
                        </li>
                        <li id="ruleUpper" class="rule-item invalid">
                            <i class="ph-bold ph-x-circle"></i> Al menos 1 mayúscula
                        </li>
                        <li id="ruleNumber" class="rule-item invalid">
                            <i class="ph-bold ph-x-circle"></i> Al menos 1 número
                        </li>
                        <li id="ruleSpecial" class="rule-item invalid">
                            <i class="ph-bold ph-x-circle"></i> 1 especial (@$!%*?&)
                        </li>
                    </ul>
                </div>

                <!-- SEGURIDAD 2: CAPTCHA VISUAL DISTORSIONADO GD -->
                <div class="login-flat-group">
                    <label for="captcha_ans">Verificación Anti-Bot:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <img src="?ruta=captcha-imagen&t=<?= time() ?>" id="imgCaptchaReg" alt="Captcha" style="border-radius: 6px; border: 1px solid #cbd5e1; height: 36px; cursor: pointer;" title="Haz clic para recargar el captcha" onclick="this.src='?ruta=captcha-imagen&t='+Date.now()">
                        <input type="text" id="captcha_ans" name="captcha_ans" class="login-flat-input" placeholder="Respuesta" required style="width: 110px; text-align: center; font-weight: 700;" autocomplete="off">
                    </div>
                </div>

                <button type="submit" id="btnSubmitRegistro" class="login-btn-submit" style="margin-top: 0.5rem;">Registrar Usuario</button>

                <div class="login-flat-footer">
                    <a href="login">¿Ya tienes una cuenta? Iniciar Sesión</a>
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

function evaluarFortalezaClave(val) {
    const bar = document.getElementById('strengthBarFill');
    const label = document.getElementById('strengthLabel');
    
    // Reglas regex
    const hasLength = val.length >= 8;
    const hasUpper = /[A-Z]/.test(val);
    const hasNumber = /[0-9]/.test(val);
    const hasSpecial = /[@$!%*?&._\-\#\^\(\)\{\}\[\]]/.test(val);

    // Actualizar items de reglas
    actualizarReglaItem('ruleLength', hasLength);
    actualizarReglaItem('ruleUpper', hasUpper);
    actualizarReglaItem('ruleNumber', hasNumber);
    actualizarReglaItem('ruleSpecial', hasSpecial);

    // Calcular entropía/nivel (0 a 4)
    let score = 0;
    if (hasLength) score++;
    if (hasUpper) score++;
    if (hasNumber) score++;
    if (hasSpecial) score++;

    if (val.length === 0) {
        bar.style.width = '0%';
        bar.style.backgroundColor = '#e2e8f0';
        label.textContent = 'Esperando contraseña...';
        label.style.color = '#64748b';
    } else if (score <= 1) {
        bar.style.width = '25%';
        bar.style.backgroundColor = '#ef4444'; // Rojo (Débil)
        label.textContent = 'Débil';
        label.style.color = '#ef4444';
    } else if (score === 2) {
        bar.style.width = '50%';
        bar.style.backgroundColor = '#f59e0b'; // Naranja (Aceptable)
        label.textContent = 'Aceptable';
        label.style.color = '#d97706';
    } else if (score === 3) {
        bar.style.width = '75%';
        bar.style.backgroundColor = '#3b82f6'; // Azul (Segura)
        label.textContent = 'Segura';
        label.style.color = '#2563eb';
    } else if (score === 4) {
        bar.style.width = '100%';
        bar.style.backgroundColor = '#10b981'; // Verde (Excelente)
        label.textContent = 'Excelente';
        label.style.color = '#059669';
    }

    validarCoincidenciaClave();
}

function actualizarReglaItem(id, esValido) {
    const el = document.getElementById(id);
    if (!el) return;
    const icon = el.querySelector('i');
    
    if (esValido) {
        el.className = 'rule-item valid';
        if (icon) icon.className = 'ph-bold ph-check-circle';
    } else {
        el.className = 'rule-item invalid';
        if (icon) icon.className = 'ph-bold ph-x-circle';
    }
}

function validarCoincidenciaClave() {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    const matchLabel = document.getElementById('matchStatusLabel');
    
    if (!matchLabel) return;
    
    if (confirm.length === 0) {
        matchLabel.textContent = '';
    } else if (pass === confirm) {
        matchLabel.textContent = '✓ Las claves coinciden';
        matchLabel.style.color = '#10b981';
    } else {
        matchLabel.textContent = '✕ Las claves no coinciden';
        matchLabel.style.color = '#ef4444';
    }
}
</script>