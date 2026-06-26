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
            <form action="procesar-login" method="POST" class="login-flat-form">
                
                <div class="login-flat-group">
                    <label for="cedula">Cédula:</label>
                    <input type="number" id="cedula" name="cedula" class="login-flat-input" required autocomplete="off">
                </div>

                <div class="login-flat-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="login-flat-input" required>
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