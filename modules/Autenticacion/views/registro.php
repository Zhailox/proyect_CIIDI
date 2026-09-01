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

            <form action="procesar-registro" method="POST" class="login-flat-form">
                
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
                        <input type="password" id="password" name="password" class="login-flat-input" required>
                    </div>

                    <div class="login-flat-group">
                        <label for="password_confirm">Confirmar Clave:</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="login-flat-input" required>
                    </div>
                </div>

                <button type="submit" class="login-btn-submit" style="margin-top: 1rem;">Registrar Usuario</button>

                <div class="login-flat-footer">
                    <a href="login">¿Ya tienes una cuenta? Iniciar Sesión</a>
                </div>
            </form>
            
        </div>
    </div>
</div>