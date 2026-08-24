<div class="recovery-split-container">
    
    <div class="recovery-form-side">
        <div class="recovery-form-box">
            <h2>RECUPERAR CUENTA</h2>
            <p class="recovery-form-instruction">
                Seleccione su método de validación. Enviaremos un token de seguridad para restaurar su acceso.
            </p>

            <form action="#" method="POST" class="recovery-flat-form">
                
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

                <button type="submit" class="recovery-btn-submit">Enviar Token</button>

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