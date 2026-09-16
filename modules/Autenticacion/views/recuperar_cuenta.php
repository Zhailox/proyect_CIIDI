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

            <form action="procesar-recuperacion" method="POST" class="recovery-flat-form">
                
                <div class="recovery-flat-group">
                    <label for="metodo_recuperacion">Método de Recuperación:</label>
                    <select id="metodo_recuperacion" name="metodo_recuperacion" class="recovery-flat-select" onchange="actualizarMetodo()">
                        <option value="cedula">Por Cédula de Identidad</option>
                        <option value="correo">Por Correo Electrónico</option>
                    </select>
                </div>

                <div class="recovery-flat-group">
                    <label id="label_dinamico" for="dato_recuperacion">Cédula del Usuario:</label>
                    <input type="number" id="dato_recuperacion" name="dato_recuperacion" class="recovery-flat-input" placeholder="EJ: V-12345678" required autocomplete="off">
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
    const label = document.getElementById('label_dinamico');
    const input = document.getElementById('dato_recuperacion');
    
    if (metodo === 'cedula') {
        label.innerText = 'Cédula del Usuario:';
        input.type = 'number';
        input.placeholder = 'EJ: 12345678';
    } else {
        label.innerText = 'Correo Electrónico:';
        input.type = 'email';
        input.placeholder = 'ejemplo@correo.com';
    }
    input.value = '';
    input.focus();
}
</script>