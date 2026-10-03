<?php
// modules/Autenticacion/views/mi_dashboard.php
// Vista principal del perfil de usuario - CIIDI UPTTMBI
?>

<div class="db-wrapper">

    <!-- CABECERA INSTITUCIONAL DEL PERFIL -->
    <header class="db-account-header">
        <div class="db-profile-meta">
            <div class="db-avatar-container" title="Inicial de <?= htmlspecialchars($usuarioActual['nombre_completo']) ?>">
                <?= htmlspecialchars($usuarioActual['iniciales'] ?? 'U') ?>
            </div>
            <div class="db-user-details">
                <h1><?= htmlspecialchars($usuarioActual['nombre_completo']) ?></h1>
                <div class="db-user-badges">
                    <span class="db-badge-role">
                        <i class="ph-bold ph-shield-star"></i> <?= htmlspecialchars($usuarioActual['nombre_rol']) ?>
                    </span>
                    <span class="db-badge-id">
                        <i class="ph-bold ph-identification-card"></i> <?= htmlspecialchars($usuarioActual['cedula']) ?>
                    </span>
                    <?php if (!empty($usuarioActual['email_verified'])): ?>
                        <span class="db-badge-role" style="background-color: rgba(var(--color-terciario-rgb), 0.3);">
                            <i class="ph-bold ph-check-circle"></i> Correo Verificado
                        </span>
                    <?php else: ?>
                        <span class="db-badge-role" style="background-color: rgba(var(--color-secundario-rgb), 0.25);">
                            <i class="ph-bold ph-clock"></i> Verificación Pendiente
                        </span>
                    <?php endif; ?>
                </div>
                <p class="db-institution">PNF en Informática — UPTTMBI Sede La Beatriz</p>
            </div>
        </div>

        <div class="db-header-actions">
            <a href="cerrar-sesion" class="db-btn-logout" title="Cerrar sesión de forma segura">
                <i class="ph-bold ph-sign-out"></i> Salir
            </a>
        </div>
    </header>

    <!-- NOTIFICACIONES Y ALERTAS FLASH -->
    <?php if (!empty($mensajeExito)): ?>
        <div class="db-alert db-alert-success" role="alert">
            <i class="ph-bold ph-check-circle"></i>
            <div><?= htmlspecialchars($mensajeExito) ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensajeError)): ?>
        <div class="db-alert db-alert-error" role="alert">
            <i class="ph-bold ph-warning-circle"></i>
            <div><?= htmlspecialchars($mensajeError) ?></div>
        </div>
    <?php endif; ?>

    <!-- SISTEMA DE PESTAÑAS -->
    <div class="db-tabs-container">
        
        <!-- NAVEGACIÓN DE PESTAÑAS -->
        <nav class="db-tabs-nav" aria-label="Pestañas del perfil">
            <button type="button" class="db-tab-btn <?= ($activeTab === 'resumen') ? 'active' : '' ?>" data-tab="tab-resumen">
                <i class="ph-bold ph-user-circle"></i> Resumen de Cuenta
            </button>
            <button type="button" class="db-tab-btn <?= ($activeTab === 'editar') ? 'active' : '' ?>" data-tab="tab-editar">
                <i class="ph-bold ph-pencil-simple-line"></i> Modificar Mis Datos
            </button>
            <button type="button" class="db-tab-btn <?= ($activeTab === 'seguridad') ? 'active' : '' ?>" data-tab="tab-seguridad">
                <i class="ph-bold ph-shield-check"></i> Seguridad y Acceso
            </button>
        </nav>

        <!-- PESTAÑA 1: RESUMEN GENERAL -->
        <div id="tab-resumen" class="db-tab-pane <?= ($activeTab === 'resumen') ? 'active' : '' ?>">
            <div class="db-grid-two-col">
                
                <!-- Tarjeta 1: Ficha Institucional -->
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="ph-bold ph-address-book"></i> Ficha Personal e Institucional</h3>
                    </div>
                    <div class="db-data-list">
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-user"></i> Nombre Registrado</span>
                            <span class="db-data-value strong"><?= htmlspecialchars($usuarioActual['nombre_completo']) ?></span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-identification-card"></i> Cédula de Identidad</span>
                            <span class="db-data-value"><?= htmlspecialchars($usuarioActual['cedula']) ?></span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-envelope-simple"></i> Correo Electrónico</span>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                                <span class="db-data-value"><?= htmlspecialchars($usuarioActual['email'] ?: 'No registrado') ?></span>
                                <?php if (empty($usuarioActual['email_verified']) && !empty($usuarioActual['email'])): ?>
                                    <form action="perfil" method="POST" style="margin: 0;">
                                        <?= CSRF::campoOculto() ?>
                                        <input type="hidden" name="accion" value="enviar_verificacion_email">
                                        <button type="submit" class="db-btn-verify" title="Enviar enlace de activación a tu correo">
                                            <i class="ph-bold ph-paper-plane-tilt"></i> Verificar Correo
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-shield-check"></i> Rol en la Plataforma</span>
                            <span class="db-data-value strong"><?= htmlspecialchars($usuarioActual['nombre_rol']) ?> (Nivel <?= (int)$usuarioActual['nivel_privilegio'] ?>)</span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-buildings"></i> Dependencia Académica</span>
                            <span class="db-data-value">PNF en Informática — UPTTMBI</span>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Actividad y Estado del Sistema -->
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="ph-bold ph-activity"></i> Estado de la Cuenta y Accesos</h3>
                    </div>
                    <div class="db-data-list">
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-check-square-offset"></i> Estado del Usuario</span>
                            <span class="db-data-value strong">
                                <?= !empty($usuarioActual['activo']) ? 'Cuenta Activa' : 'Cuenta Suspendida' ?>
                            </span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-envelope-open"></i> Estado de Verificación</span>
                            <span class="db-data-value">
                                <?= !empty($usuarioActual['email_verified']) ? 'Correo Verificado' : 'Verificación Pendiente' ?>
                            </span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-arrow-counter-clockwise"></i> Total de Accesos</span>
                            <span class="db-data-value"><?= htmlspecialchars((string)($usuarioActual['conteo_accesos'] ?? '1')) ?> inicios de sesión</span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-calendar-blank"></i> Registro Inicial</span>
                            <span class="db-data-value"><?= htmlspecialchars($usuarioActual['fecha_inicial'] ?? 'Registro del Sistema') ?></span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-clock"></i> Última Actividad</span>
                            <span class="db-data-value"><?= htmlspecialchars($usuarioActual['ultima_actividad'] ?? 'Sesión en curso') ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- PESTAÑA 2: MODIFICAR DATOS PERSONALES -->
        <div id="tab-editar" class="db-tab-pane <?= ($activeTab === 'editar') ? 'active' : '' ?>">
            <div class="db-card">
                <div class="db-card-header">
                    <h3><i class="ph-bold ph-pencil-simple"></i> Datos Personales y de Cuenta</h3>
                </div>

                <form action="perfil" method="POST" class="db-form">
                    <?= CSRF::campoOculto() ?>
                    <input type="hidden" name="accion" value="actualizar_datos">

                    <?php if ($esSuperAdmin): ?>
                        <!-- CAMPOS EDITABLES PARA SUPERADMINISTRADOR -->
                        <div class="db-form-row">
                            <div class="db-form-group">
                                <label for="nombre_completo">
                                    <i class="ph-bold ph-user"></i> Nombre Completo *
                                </label>
                                <input 
                                    type="text" 
                                    id="nombre_completo" 
                                    name="nombre_completo" 
                                    value="<?= htmlspecialchars($usuarioActual['nombre_completo']) ?>" 
                                    required 
                                    maxlength="150"
                                    placeholder="Ej: Administrador Principal"
                                >
                                <span class="db-form-hint">Como superusuario puedes actualizar tu nombre en el sistema.</span>
                            </div>

                            <div class="db-form-group">
                                <label for="cedula">
                                    <i class="ph-bold ph-identification-card"></i> Cédula de Identidad *
                                </label>
                                <input 
                                    type="text" 
                                    id="cedula" 
                                    name="cedula" 
                                    value="<?= htmlspecialchars($usuarioActual['cedula']) ?>" 
                                    required 
                                    maxlength="20"
                                    placeholder="Ej: V-12345678"
                                >
                                <span class="db-form-hint">Privilegio exclusivo de superusuario: rectificación de cédula.</span>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- CAMPOS PROTEGIDOS PARA USUARIOS REGULARES -->
                        <div class="db-form-row">
                            <div class="db-form-group">
                                <label for="nombre_readonly">
                                    <i class="ph-bold ph-lock-key"></i> Nombre Completo Registrado
                                </label>
                                <input 
                                    type="text" 
                                    id="nombre_readonly" 
                                    value="<?= htmlspecialchars($usuarioActual['nombre_completo']) ?>" 
                                    readonly 
                                    disabled
                                >
                            </div>

                            <div class="db-form-group">
                                <label for="cedula_readonly_user">
                                    <i class="ph-bold ph-lock-key"></i> Cédula de Identidad (Bloqueado)
                                </label>
                                <input 
                                    type="text" 
                                    id="cedula_readonly_user" 
                                    value="<?= htmlspecialchars($usuarioActual['cedula']) ?>" 
                                    readonly 
                                    disabled
                                >
                                <span class="db-form-hint">Dato institucional inalterable. Para correcciones de cédula acude a la Coordinación Académica.</span>
                            </div>
                        </div>

                        <!-- AVISO DE IDENTIDAD PROTEGIDA -->
                        <div class="db-request-box">
                            <p>
                                <strong><i class="ph-bold ph-shield-check"></i> Protección de identidad institucional:</strong><br>
                                Tu nombre y cédula de identidad están protegidos por normativa institucional para garantizar la validez legal de tus proyectos, actas y constancias académicas. Si requieres rectificar tu nombre o cédula, debes solicitarlo formalmente a la Coordinación Académica.
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- CAMPO DE CORREO ELECTRÓNICO (EDITABLE PARA TODOS LOS USUARIOS) -->
                    <div class="db-form-group" style="margin-top: 0.5rem;">
                        <label for="email">
                            <i class="ph-bold ph-envelope"></i> Correo Electrónico Institucional / Personal *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= htmlspecialchars($usuarioActual['email']) ?>" 
                            required 
                            maxlength="100"
                            placeholder="ejemplo@upttmbi.edu.ve"
                        >
                        <span class="db-form-hint">
                            Puedes actualizar tu correo electrónico. Si lo modificas, el sistema enviará un enlace de verificación a tu nueva dirección.
                        </span>
                    </div>

                    <div class="db-form-actions">
                        <button type="submit" class="db-btn-submit">
                            <i class="ph-bold ph-floppy-disk"></i> Guardar Cambios
                        </button>
                    </div>
                </form>

                <!-- SECCIÓN DE VERIFICACIÓN DE CORREO PENDIENTE -->
                <?php if (empty($usuarioActual['email_verified']) && !empty($usuarioActual['email'])): ?>
                    <div class="db-request-box" style="margin-top: 1.2rem; border-left-color: var(--color-terciario);">
                        <p>
                            <strong><i class="ph-bold ph-warning"></i> Tu correo electrónico aún no ha sido verificado:</strong><br>
                            Verifica tu cuenta para recibir notificaciones formales de proyectos, tutorías y alertas de seguridad.
                        </p>
                        <form action="perfil" method="POST" style="margin: 0;">
                            <?= CSRF::campoOculto() ?>
                            <input type="hidden" name="accion" value="enviar_verificacion_email">
                            <button type="submit" class="db-btn-verify">
                                <i class="ph-bold ph-paper-plane-tilt"></i> Reenviar Enlace de Verificación al Correo
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- PESTAÑA 3: SEGURIDAD Y ACCESO -->
        <div id="tab-seguridad" class="db-tab-pane <?= ($activeTab === 'seguridad') ? 'active' : '' ?>">
            <div class="db-grid-two-col">

                <!-- Formulario de Cambio de Clave -->
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="ph-bold ph-key"></i> Cambiar Contraseña</h3>
                    </div>

                    <form action="perfil" method="POST" class="db-form">
                        <?= CSRF::campoOculto() ?>
                        <input type="hidden" name="accion" value="cambiar_clave">

                        <div class="db-form-group">
                            <label for="clave_actual">
                                <i class="ph-bold ph-shield-check"></i> Contraseña Actual *
                            </label>
                            <input 
                                type="password" 
                                id="clave_actual" 
                                name="clave_actual" 
                                required 
                                autocomplete="current-password"
                                placeholder="Ingresa tu contraseña vigente"
                            >
                        </div>

                        <div class="db-form-group">
                            <label for="clave_nueva">
                                <i class="ph-bold ph-lock"></i> Nueva Contraseña *
                            </label>
                            <input 
                                type="password" 
                                id="clave_nueva" 
                                name="clave_nueva" 
                                required 
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Mínimo 8 caracteres"
                            >
                            <span class="db-form-hint">Mínimo 8 caracteres. Recomendamos usar mayúsculas, minúsculas y números.</span>
                        </div>

                        <div class="db-form-group">
                            <label for="clave_confirmar">
                                <i class="ph-bold ph-lock-key"></i> Confirmar Nueva Contraseña *
                            </label>
                            <input 
                                type="password" 
                                id="clave_confirmar" 
                                name="clave_confirmar" 
                                required 
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Repite la nueva contraseña"
                            >
                        </div>

                        <div class="db-form-actions">
                            <button type="submit" class="db-btn-submit">
                                <i class="ph-bold ph-arrows-clockwise"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tarjeta de Sesión Activa y Parámetros de Seguridad -->
                <div class="db-card">
                    <div class="db-card-header">
                        <h3><i class="ph-bold ph-shield-warning"></i> Entorno de Seguridad y Sesión</h3>
                    </div>

                    <div class="db-data-list">
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-globe"></i> Dirección IP Actual</span>
                            <span class="db-data-value strong"><?= htmlspecialchars($ipActual) ?></span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-device-mobile-camera"></i> Entorno de Navegación</span>
                            <span class="db-data-value"><?= htmlspecialchars($dispositivo) ?></span>
                        </div>
                        <div class="db-data-item">
                            <span class="db-data-label"><i class="ph-bold ph-hourglass-high"></i> Inactividad de Sesión</span>
                            <span class="db-data-value">Cierre automático tras <?= (int)$timeoutMinutos ?> minutos</span>
                        </div>
                    </div>

                    <!-- Bitácora de Actividad Personal Reciente -->
                    <div style="margin-top: 1.4rem; padding-top: 1rem; border-top: 1px solid var(--blanco);">
                        <h4 style="margin: 0 0 0.65rem 0; font-size: 0.92rem; color: var(--color-secundario); display: flex; align-items: center; gap: 0.45rem;">
                            <i class="ph-bold ph-list-dashes"></i> Mis Últimos Eventos Registrados
                        </h4>

                        <?php if (!empty($actividadReciente)): ?>
                            <table class="db-audit-table">
                                <thead>
                                    <tr>
                                        <th>Fecha / Hora</th>
                                        <th>Acción</th>
                                        <th>Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($actividadReciente as $evento): ?>
                                        <tr>
                                            <td style="white-space: nowrap; font-size: 0.78rem; color: var(--texto-silenciado);">
                                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($evento['fecha_hora'] ?? 'now'))) ?>
                                            </td>
                                            <td>
                                                <span class="db-audit-badge">
                                                    <?= htmlspecialchars($evento['accion'] ?? 'Evento') ?>
                                                </span>
                                            </td>
                                            <td style="font-size: 0.8rem;">
                                                <?= htmlspecialchars($evento['detalles'] ?? '-') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p style="font-size: 0.85rem; color: var(--texto-silenciado); margin: 0.4rem 0 0 0;">
                                No se registran incidentes ni eventos recientes en tu cuenta.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- SCRIPT DE INTERACCIÓN DE PESTAÑAS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.db-tab-btn');
    const tabPanes = document.querySelectorAll('.db-tab-pane');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Quitar clase activa a todos los botones y paneles
            tabButtons.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));

            // Activar botón y panel seleccionados
            this.classList.add('active');
            const pane = document.getElementById(targetTab);
            if (pane) {
                pane.classList.add('active');
            }
        });
    });
});
</script>