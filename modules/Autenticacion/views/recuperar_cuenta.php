<div class="recovery-split-container">
    
    <div class="recovery-form-side">
        <div class="recovery-form-box">
            <h2>RECUPERAR CUENTA</h2>
            <p class="recovery-form-instruction">
                Seleccione su método de validación. Enviaremos un token de seguridad para restaurar su acceso.
            </p>

            <?php if (isset($error) && $error): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <?php
            require_once CORE_PATH . 'Security/CaptchaService.php';
            $segDataRec = CaptchaService::generarCamposSeguridad();
            ?>
            <form action="procesar-recuperacion" method="POST" class="recovery-flat-form">
                <!-- SEGURIDAD 1.1: HONEYPOT INVISIBLE PARA BOTS -->
                <input type="text" name="website_url_hp" style="display:none !important; opacity:0; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
                <!-- SEGURIDAD 1.2: TIMESTAMP DE TIEMPO HUMANO -->
                <input type="hidden" name="_form_ts" value="<?= $segDataRec['timestamp'] ?>">

                <div class="recovery-flat-group">
                    <label for="metodo_recuperacion">Método de Recuperación:</label>
                    <select id="metodo_recuperacion" name="metodo_recuperacion" class="recovery-flat-select" onchange="actualizarMetodo()">
                        <option value="cedula">Por Cédula de Identidad</option>
                        <option value="correo">Por Correo Electrónico</option>
                    </select>
                </div>

                <div class="recovery-flat-group" id="grupo_cedula">
                    <label for="recup_cedula_num">Cédula del Usuario:</label>
                    <div style="display: flex; gap: 0.5rem; align-items: stretch;">
                        <select id="recup_cedula_tipo" class="recovery-flat-select" style="width: 80px; flex-shrink: 0; cursor: pointer; font-weight: 700;">
                            <option value="V-">V-</option>
                            <option value="E-">E-</option>
                        </select>
                        <input type="text" inputmode="numeric" id="recup_cedula_num" class="recovery-flat-input" placeholder="Ej: 12345678" maxlength="9" oninput="this.value = this.value.replace(/\D/g, '');" style="flex: 1;" required autocomplete="off">
                    </div>
                </div>

                <div class="recovery-flat-group" id="grupo_correo" style="display: none;">
                    <label for="recup_email">Correo Electrónico:</label>
                    <input type="email" id="recup_email" class="recovery-flat-input" placeholder="ejemplo@correo.com" autocomplete="email">
                </div>

                <input type="hidden" id="dato_recuperacion" name="dato_recuperacion">

                <!-- SEGURIDAD 2: CAPTCHA VISUAL DISTORSIONADO GD -->
                <div class="recovery-flat-group" style="margin-top: 0.5rem;">
                    <label for="captcha_ans">Verificación Anti-Bot:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <img src="?ruta=captcha-imagen&t=<?= time() ?>" id="imgCaptchaRec" alt="Captcha" style="border-radius: 6px; border: 1px solid #cbd5e1; height: 36px; cursor: pointer;" title="Haz clic para recargar el captcha" onclick="this.src='?ruta=captcha-imagen&t='+Date.now()">
                        <input type="text" id="captcha_ans" name="captcha_ans" class="recovery-flat-input" placeholder="Respuesta" required style="width: 110px; text-align: center; font-weight: 700;" autocomplete="off">
                    </div>
                </div>

                <button type="submit" class="recovery-btn-submit">Enviar código</button>

                <div class="recovery-flat-footer">
                    <a href="login">¿Recordó su contraseña? Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>

    <div class="recovery-visual-side">
        <div class="recovery-visual-overlay"></div>
        <div class="recovery-visual-content">
            <h1 class="recovery-main-title">RECUPERACIÓN DE ACCESO</h1>
            <blockquote class="recovery-quote">
                "Garantizando la integridad, confidencialidad y el acceso seguro a los entornos y sistemas de nuestro ecosistema científico."
            </blockquote>
        </div>
    </div>

</div>

<script>
function actualizarMetodo() {
    const metodo = document.getElementById('metodo_recuperacion').value;
    const grupoCedula = document.getElementById('grupo_cedula');
    const grupoCorreo = document.getElementById('grupo_correo');
    const inputCedNum = document.getElementById('recup_cedula_num');
    const inputEmail = document.getElementById('recup_email');

    if (metodo === 'cedula') {
        grupoCedula.style.display = 'block';
        grupoCorreo.style.display = 'none';
        inputCedNum.required = true;
        inputEmail.required = false;
        inputCedNum.focus();
    } else {
        grupoCedula.style.display = 'none';
        grupoCorreo.style.display = 'block';
        inputCedNum.required = false;
        inputEmail.required = true;
        inputEmail.focus();
    }
}

document.querySelector('.recovery-flat-form')?.addEventListener('submit', function() {
    const metodo = document.getElementById('metodo_recuperacion').value;
    const hiddenDato = document.getElementById('dato_recuperacion');
    if (metodo === 'cedula') {
        const tipo = document.getElementById('recup_cedula_tipo').value;
        const num = document.getElementById('recup_cedula_num').value.trim().replace(/\D/g, '');
        hiddenDato.value = num ? (tipo + num) : '';
    } else {
        hiddenDato.value = document.getElementById('recup_email').value.trim();
    }
});
</script>