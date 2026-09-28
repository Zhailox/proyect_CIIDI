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
                    <i class="ph-bold ph-warning-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php
            require_once CORE_PATH . 'Security/CaptchaService.php';
            $segDataDoc = CaptchaService::generarCamposSeguridad();
            ?>
            <form action="procesar-completar-registro" method="POST" class="recovery-flat-form">
                <!-- SEGURIDAD 1.1: HONEYPOT INVISIBLE PARA BOTS -->
                <input type="text" name="website_url_hp" style="display:none !important; opacity:0; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
                <!-- SEGURIDAD 1.2: TIMESTAMP DE TIEMPO HUMANO -->
                <input type="hidden" name="_form_ts" value="<?= $segDataDoc['timestamp'] ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                
                <div class="recovery-flat-group">
                    <label for="password">Definir Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" class="recovery-flat-input" placeholder="Mínimo 8 caracteres" required autocomplete="new-password" oninput="evaluarFortalezaClave(this.value)">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="recovery-flat-group">
                    <label for="password_confirm">Confirmar Contraseña:</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password_confirm" name="password_confirm" class="recovery-flat-input" placeholder="Repita la contraseña" required autocomplete="new-password" oninput="validarCoincidenciaClave()">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirm', this)" title="Mostrar / Ocultar Contraseña">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- MEDIDOR DE FORTALEZA (ENTROPY METER) Y REQUISITOS -->
                <div class="strength-meter-container" style="margin-bottom: 1.25rem;">
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
                            <i class="ph-bold ph-x-circle"></i> 1 símbolo o especial (+, @, #, $, etc.)
                        </li>
                    </ul>
                </div>

                <!-- SEGURIDAD 2: CAPTCHA VISUAL DISTORSIONADO GD -->
                <div class="recovery-flat-group" style="margin-bottom: 1.25rem;">
                    <label for="captcha_ans">Verificación Anti-Bot:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <img src="?ruta=captcha-imagen&t=<?= time() ?>" id="imgCaptchaDoc" alt="Captcha" style="border-radius: 6px; border: 1px solid #cbd5e1; height: 36px; cursor: pointer;" title="Haz clic para recargar el captcha" onclick="this.src='?ruta=captcha-imagen&t='+Date.now()">
                        <input type="text" id="captcha_ans" name="captcha_ans" class="recovery-flat-input" placeholder="Respuesta" required style="width: 110px; text-align: center; font-weight: 700;" autocomplete="off">
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

function evaluarFortalezaClave(val) {
    const bar = document.getElementById('strengthBarFill');
    const label = document.getElementById('strengthLabel');
    if (!bar || !label) return;
    
    // Reglas regex flexibles (incluye + y todos los caracteres especiales no alfanuméricos)
    const hasLength = val.length >= 8;
    const hasUpper = /[A-Z]/.test(val);
    const hasNumber = /[0-9]/.test(val);
    const hasSpecial = /[^a-zA-Z0-9\s]/.test(val);

    actualizarReglaItem('ruleLength', hasLength);
    actualizarReglaItem('ruleUpper', hasUpper);
    actualizarReglaItem('ruleNumber', hasNumber);
    actualizarReglaItem('ruleSpecial', hasSpecial);

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
        bar.style.backgroundColor = '#ef4444';
        label.textContent = 'Débil';
        label.style.color = '#ef4444';
    } else if (score === 2) {
        bar.style.width = '50%';
        bar.style.backgroundColor = '#f59e0b';
        label.textContent = 'Aceptable';
        label.style.color = '#d97706';
    } else if (score === 3) {
        bar.style.width = '75%';
        bar.style.backgroundColor = '#3b82f6';
        label.textContent = 'Segura';
        label.style.color = '#2563eb';
    } else if (score === 4) {
        bar.style.width = '100%';
        bar.style.backgroundColor = '#10b981';
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
