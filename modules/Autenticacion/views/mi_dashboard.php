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
            <?php if ($esProfesor): ?>
            <button type="button" class="db-tab-btn <?= ($activeTab === 'vinculacion') ? 'active' : '' ?>" data-tab="tab-vinculacion" id="btn-tab-vinculacion">
                <i class="ph-bold ph-git-branch"></i> Especialidad y Trayectos (CIIDI)
            </button>
            <?php endif; ?>
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
                        <?php if ($esProfesor): ?>
                        <?php
                            $misDimensionesIds = $misDimensionesIds ?? [];
                            $dimensionesPorLinea = $dimensionesPorLinea ?? [];
                            $misLineasDetalle = [];
                            foreach ($lineasDisponibles as $linItem) {
                                $idLin = (int)$linItem['id'];
                                if (in_array($idLin, $misLineasIds, true)) {
                                    $dimsDeEstaLinea = [];
                                    foreach (($dimensionesPorLinea[$idLin] ?? []) as $dItem) {
                                        if (in_array((int)$dItem['id'], $misDimensionesIds, true)) {
                                            $dimsDeEstaLinea[] = $dItem['nombre'];
                                        }
                                    }
                                    $misLineasDetalle[] = [
                                        'nombre'   => $linItem['nombre'],
                                        'carrera'  => $linItem['carrera_nombre'] ?? 'General / Institucional',
                                        'dims'     => $dimsDeEstaLinea,
                                    ];
                                }
                            }
                        ?>
                        <div class="db-data-item" style="align-items:flex-start;">
                            <span class="db-data-label"><i class="ph-bold ph-git-branch"></i> Líneas y Dimensiones de Especialidad</span>
                            <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end; max-width:65%;">
                                <?php if (!empty($misLineasDetalle)): ?>
                                    <div style="display:flex; flex-direction:column; gap:6px; width:100%; align-items:flex-end;">
                                        <?php foreach ($misLineasDetalle as $mld): ?>
                                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; text-align:right; width:100%;">
                                                <div style="font-size:0.7rem; font-weight:700; color:#7090cb; text-transform:uppercase; letter-spacing:0.03em;">
                                                    <i class="ph-bold ph-graduation-cap"></i> <?= htmlspecialchars($mld['carrera']) ?>
                                                </div>
                                                <div style="font-size:0.86rem; font-weight:700; color:#121a3e;">
                                                    <?= htmlspecialchars($mld['nombre']) ?>
                                                </div>
                                                <?php if (!empty($mld['dims'])): ?>
                                                    <div style="display:flex; flex-wrap:wrap; gap:4px; justify-content:flex-end; margin-top:4px;">
                                                        <?php foreach ($mld['dims'] as $dNom): ?>
                                                            <span style="background:rgba(80,89,132,0.1); color:#505984; border:1px solid rgba(80,89,132,0.22); border-radius:999px; padding:1px 8px; font-size:0.72rem; font-weight:600;">
                                                                <i class="ph-bold ph-tag" style="font-size:0.65rem;"></i> <?= htmlspecialchars($dNom) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="db-data-value" style="color:#64748b; font-style:italic;">Sin líneas ni dimensiones asignadas aún</span>
                                <?php endif; ?>
                                <button type="button" class="db-btn-verify" onclick="document.getElementById('btn-tab-vinculacion')?.click();" style="cursor:pointer;">
                                    <i class="ph-bold ph-sliders-horizontal"></i> Asignar Líneas, Dimensiones y Trayectos
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
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

        <?php if ($esProfesor): ?>
        <?php
            // Agrupar líneas de investigación por Carrera (PNF)
            $carrerasDisponibles = $carrerasDisponibles ?? [];
            $dimensionesPorLinea = $dimensionesPorLinea ?? [];
            $misDimensionesIds   = $misDimensionesIds ?? [];

            $gruposPorCarrera = [];
            foreach ($carrerasDisponibles as $carRow) {
                $cid = (int)$carRow['id'];
                $gruposPorCarrera[$cid] = [
                    'id'          => $cid,
                    'nombre'      => $carRow['nombre'],
                    'descripcion' => $carRow['descripcion'] ?? '',
                    'lineas'      => [],
                ];
            }
            foreach ($lineasDisponibles as $linRow) {
                $cid = !empty($linRow['id_carrera']) ? (int)$linRow['id_carrera'] : 0;
                if (!isset($gruposPorCarrera[$cid])) {
                    $gruposPorCarrera[$cid] = [
                        'id'          => $cid,
                        'nombre'      => $linRow['carrera_nombre'] ?? 'General / Institucional',
                        'descripcion' => '',
                        'lineas'      => [],
                    ];
                }
                $gruposPorCarrera[$cid]['lineas'][] = $linRow;
            }
            // Filtrar grupos vacíos salvo que no haya ninguna línea
            $gruposConLineas = array_filter($gruposPorCarrera, fn($g) => !empty($g['lineas']));
            if (empty($gruposConLineas)) {
                $gruposConLineas = $gruposPorCarrera;
            }
        ?>
        <!-- PESTAÑA: VINCULACIÓN CIIDI -->
        <div id="tab-vinculacion" class="db-tab-pane <?= ($activeTab === 'vinculacion') ? 'active' : '' ?>">
            <div class="db-card">
                <div class="db-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
                    <h3 style="margin:0;"><i class="ph-bold ph-git-branch"></i> Líneas de Investigación y Dimensiones Operativas por Carrera</h3>
                    <span style="background:rgba(80,89,132,0.1); color:#505984; border:1px solid rgba(80,89,132,0.22); border-radius:999px; padding:4px 12px; font-size:0.78rem; font-weight:700;">
                        <i class="ph-bold ph-check-circle"></i>
                        <span id="counter-lineas-sel"><?= count($misLineasIds) ?></span> Línea(s) ·
                        <span id="counter-dims-sel"><?= count($misDimensionesIds) ?></span> Dimensión(es)
                    </span>
                </div>
                <form action="perfil" method="POST" class="db-form" id="form-especialidad-docente">
                    <?= CSRF::campoOculto() ?>
                    <input type="hidden" name="accion" value="vincular_lineas_docente">
                    <p style="margin-bottom: 1rem; color: #4b5563; line-height: 1.5;">
                        Selecciona las <strong>líneas de investigación</strong> y las <strong>dimensiones operativas específicas</strong> en las que eres especialista o tutor, organizadas por <strong>Carrera / PNF</strong>. Al marcar cualquier dimensión operativa, su línea de investigación principal quedará vinculada automáticamente.
                    </p>

                    <!-- BARRA DE FILTRO POR CARRERA / PNF -->
                    <?php if (count($gruposConLineas) > 0): ?>
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:10px 14px; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <span style="font-size:0.76rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.04em; display:inline-flex; align-items:center; gap:5px; margin-right:4px;">
                                <i class="ph-bold ph-graduation-cap" style="color:#505984; font-size:1rem;"></i> Filtrar por Carrera:
                            </span>
                            <button type="button" class="perfil-carrera-filter-btn active" data-carrera-filter="all" onclick="filtrarCarreraEspecialidad('all', this)"
                                    style="background:#121a3e; color:#ffffff; border:1px solid #121a3e; border-radius:999px; padding:5px 13px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s;">
                                Todas las Carreras (<?= count($lineasDisponibles) ?>)
                            </button>
                            <?php foreach ($gruposConLineas as $cidKey => $gCar): ?>
                                <button type="button" class="perfil-carrera-filter-btn" data-carrera-filter="<?= (int)$cidKey ?>" onclick="filtrarCarreraEspecialidad('<?= (int)$cidKey ?>', this)"
                                        style="background:#ffffff; color:#334155; border:1px solid #cbd5e1; border-radius:999px; padding:5px 13px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s;">
                                    <i class="ph-bold ph-buildings"></i> <?= htmlspecialchars($gCar['nombre']) ?> (<?= count($gCar['lineas']) ?>)
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- BLOQUES AGRUPADOS POR CARRERA -->
                    <?php if (empty($lineasDisponibles)): ?>
                        <p>No hay líneas de investigación registradas en el sistema.</p>
                    <?php else: ?>
                        <div id="contenedor-carreras-especialidad" style="display:flex; flex-direction:column; gap:1.5rem; margin-bottom:1.5rem;">
                            <?php foreach ($gruposConLineas as $cidKey => $grupoCar): ?>
                                <section class="bloque-carrera-especialidad" data-carrera-id="<?= (int)$cidKey ?>" style="border:1px solid #dbe3f0; border-radius:14px; overflow:hidden; background:#ffffff; box-shadow:0 4px 14px rgba(18,26,62,0.04);">
                                    <!-- Cabecera de la Carrera -->
                                    <div style="background:linear-gradient(135deg, #121a3e 0%, #505984 100%); color:#ffffff; padding:12px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <span style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.16); display:inline-flex; align-items:center; justify-content:center; font-size:1.05rem;">
                                                <i class="ph-bold ph-graduation-cap"></i>
                                            </span>
                                            <div>
                                                <h4 style="margin:0; font-size:0.98rem; font-weight:800; color:#ffffff; letter-spacing:0.01em;">
                                                    <?= htmlspecialchars($grupoCar['nombre']) ?>
                                                </h4>
                                                <?php if (!empty($grupoCar['descripcion'])): ?>
                                                    <span style="font-size:0.75rem; color:rgba(255,255,255,0.78); display:block;">
                                                        <?= htmlspecialchars(mb_substr((string)$grupoCar['descripcion'], 0, 110)) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <span style="background:rgba(255,255,255,0.18); color:#ffffff; border:1px solid rgba(255,255,255,0.28); border-radius:999px; padding:3px 11px; font-size:0.74rem; font-weight:700;">
                                            <?= count($grupoCar['lineas']) ?> Línea(s) de Investigación
                                        </span>
                                    </div>

                                    <!-- Grid de Líneas y sus Dimensiones dentro de la Carrera -->
                                    <div style="padding:16px; display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:1.1rem; background:#f8fafc;">
                                        <?php foreach ($grupoCar['lineas'] as $linea):
                                            $idLin = (int)$linea['id'];
                                            $estaMarcada = in_array($idLin, $misLineasIds, true);
                                            $dimsLinea = $dimensionesPorLinea[$idLin] ?? [];
                                            $dimsMarcadasCount = 0;
                                            foreach ($dimsLinea as $dCheck) {
                                                if (in_array((int)$dCheck['id'], $misDimensionesIds, true)) {
                                                    $dimsMarcadasCount++;
                                                }
                                            }
                                        ?>
                                            <div class="tarjeta-linea-esp" id="card-linea-<?= $idLin ?>" data-linea-id="<?= $idLin ?>"
                                                 style="display:flex; flex-direction:column; justify-content:space-between; border:1.5px solid <?= $estaMarcada ? '#505984' : '#e2e8f0' ?>; border-radius:12px; background:<?= $estaMarcada ? '#f4f7ff' : '#ffffff' ?>; transition:all 0.2s; overflow:hidden; box-shadow:0 2px 6px rgba(15,23,42,0.03);">
                                                
                                                <!-- Cabecera seleccionable de la Línea -->
                                                <label style="display:flex; align-items:flex-start; gap:0.75rem; padding:1rem 1rem 0.85rem 1rem; cursor:pointer; margin:0;">
                                                    <input type="checkbox" name="lineas[]" value="<?= $idLin ?>"
                                                           class="chk-linea-esp" data-linea-id="<?= $idLin ?>"
                                                           <?= $estaMarcada ? 'checked' : '' ?>
                                                           onchange="onToggleLineaEspecialidad(<?= $idLin ?>, this.checked)"
                                                           style="margin-top:0.2rem; width:18px; height:18px; accent-color:#505984; cursor:pointer; flex-shrink:0;">
                                                    <div style="flex:1; min-width:0;">
                                                        <div style="display:flex; align-items:center; justify-content:space-between; gap:6px; flex-wrap:wrap; margin-bottom:4px;">
                                                            <span style="font-size:0.68rem; font-weight:800; color:#505984; background:rgba(80,89,132,0.1); padding:2px 8px; border-radius:999px; text-transform:uppercase;">
                                                                <?= htmlspecialchars($grupoCar['nombre']) ?>
                                                            </span>
                                                            <span id="badge-dim-count-<?= $idLin ?>" style="font-size:0.7rem; font-weight:700; color:<?= $dimsMarcadasCount > 0 ? '#1e293b' : '#64748b' ?>; background:<?= $dimsMarcadasCount > 0 ? '#dbeafe' : '#f1f5f9' ?>; padding:2px 8px; border-radius:999px;">
                                                                <?= $dimsMarcadasCount ?> / <?= count($dimsLinea) ?> dim.
                                                            </span>
                                                        </div>
                                                        <strong style="display:block; color:#121a3e; font-size:0.95rem; line-height:1.3; margin-bottom:0.25rem;">
                                                            <?= htmlspecialchars($linea['nombre']) ?>
                                                        </strong>
                                                        <span style="font-size:0.81rem; color:#64748b; line-height:1.4; display:block;">
                                                            <?= htmlspecialchars(mb_substr((string)$linea['descripcion'], 0, 120)) ?><?= mb_strlen((string)$linea['descripcion']) > 120 ? '...' : '' ?>
                                                        </span>
                                                    </div>
                                                </label>

                                                <!-- Sub-panel de Dimensiones Operativas de la Línea -->
                                                <div style="border-top:1px dashed #cbd5e1; background:rgba(248,250,252,0.85); padding:0.75rem 1rem;">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:0.5rem;">
                                                        <span style="font-size:0.73rem; font-weight:800; color:#334155; text-transform:uppercase; letter-spacing:0.03em; display:inline-flex; align-items:center; gap:4px;">
                                                            <i class="ph-bold ph-tag" style="color:#505984;"></i> Dimensiones Operativas (<?= count($dimsLinea) ?>)
                                                        </span>
                                                        <?php if (!empty($dimsLinea)): ?>
                                                        <div style="display:inline-flex; gap:6px; font-size:0.72rem;">
                                                            <button type="button" onclick="seleccionarDimensionesDeLinea(<?= $idLin ?>, true)"
                                                                    style="background:none; border:none; color:#505984; font-weight:700; cursor:pointer; padding:0; text-decoration:underline;">
                                                                Todas
                                                            </button>
                                                            <span style="color:#cbd5e1;">|</span>
                                                            <button type="button" onclick="seleccionarDimensionesDeLinea(<?= $idLin ?>, false)"
                                                                    style="background:none; border:none; color:#64748b; font-weight:600; cursor:pointer; padding:0; text-decoration:underline;">
                                                                Ninguna
                                                            </button>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php if (empty($dimsLinea)): ?>
                                                        <div style="font-size:0.78rem; color:#94a3b8; font-style:italic;">
                                                            Esta línea aún no posee dimensiones operativas registradas.
                                                        </div>
                                                    <?php else: ?>
                                                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                                                            <?php foreach ($dimsLinea as $dim):
                                                                $idDim = (int)$dim['id'];
                                                                $dimMarcada = in_array($idDim, $misDimensionesIds, true);
                                                            ?>
                                                                <label class="chip-dim-esp" id="chip-dim-<?= $idDim ?>"
                                                                       title="<?= htmlspecialchars($dim['descripcion'] ?: $dim['nombre']) ?>"
                                                                       style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; font-size:0.77rem; font-weight:600; cursor:pointer; transition:all 0.18s; user-select:none; border:1px solid <?= $dimMarcada ? '#505984' : '#cbd5e1' ?>; background:<?= $dimMarcada ? '#505984' : '#ffffff' ?>; color:<?= $dimMarcada ? '#ffffff' : '#334155' ?>;">
                                                                    <input type="checkbox" name="dimensiones[]" value="<?= $idDim ?>"
                                                                           class="chk-dim-esp" data-linea-id="<?= $idLin ?>" data-dim-id="<?= $idDim ?>"
                                                                           <?= $dimMarcada ? 'checked' : '' ?>
                                                                           onchange="onToggleDimensionEspecialidad(<?= $idLin ?>, <?= $idDim ?>, this.checked)"
                                                                           style="display:none;">
                                                                    <i class="ph-bold <?= $dimMarcada ? 'ph-check-circle' : 'ph-circle' ?>" id="icon-dim-<?= $idDim ?>" style="font-size:0.82rem;"></i>
                                                                    <span><?= htmlspecialchars($dim['nombre']) ?></span>
                                                                </label>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 16px; margin-bottom: 1.25rem;">
                        <input type="hidden" name="es_investigador" value="0">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #121a3e; font-size: 0.92rem; margin: 0;">
                            <input type="checkbox" name="es_investigador" value="1" <?= (!empty($usuarioActual['es_investigador']) || !empty($misLineasIds)) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #505984; cursor: pointer;">
                            <span>Mostrar mi ficha en el Directorio Público de Investigadores y en el panel de Profesores Afiliados de cada línea</span>
                        </label>
                    </div>

                    <div class="db-form-actions">
                        <button type="submit" class="db-btn-submit">
                            <i class="ph-bold ph-floppy-disk"></i> Guardar Líneas y Dimensiones de Especialidad
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECCIÓN: ACREDITACIÓN DOCENTE PST -->
            <div class="db-card" style="margin-top: 1.5rem;">
                <div class="db-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                    <h3 style="margin: 0;"><i class="ph-bold ph-chalkboard-teacher"></i> Asignación de Trayectos y Niveles Académicos (PST / Postgrado)</h3>
                    <?php if ((int)($usuarioActual['nivel_privilegio'] ?? 999) <= 1): ?>
                        <a href="vinculacion-config#trayectosBuilderContainer" class="db-btn-verify" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="ph-bold ph-plus-circle"></i> Configurar / Añadir Trayectos (+)
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($solicitudPstPendiente)): ?>
                    <div style="background: #fef9c3; border-left: 4px solid #facc15; padding: 14px 18px; border-radius: 8px; margin-bottom: 1.25rem; color: #854d0e;">
                        <div style="display:flex; align-items:center; gap:8px; font-weight:700; margin-bottom:4px;">
                            <i class="ph-bold ph-hourglass-medium" style="font-size:1.2rem;"></i> Solicitud de Acreditación en Revisión
                        </div>
                        <p style="margin: 0; font-size: 0.88rem; line-height: 1.4;">
                            Has solicitado acreditarte para: 
                            <strong><?= !empty($solicitudPstPendiente['trayectos']) ? implode(', ', array_map(fn($t) => PerfilPSTService::nombreTrayecto($t), $solicitudPstPendiente['trayectos'])) : 'Sin trayectos' ?></strong>.
                            Esta solicitud fue enviada el <strong><?= htmlspecialchars(date('d/m/Y H:i', strtotime($solicitudPstPendiente['fecha_solicitud']))) ?></strong> y se encuentra en espera de validación por parte del <strong>Comité o Super Administrador</strong>.
                        </p>
                    </div>
                <?php elseif ($esPstActual): ?>
                    <div style="background: #f0fdf4; border-left: 4px solid #22c55e; padding: 14px 18px; border-radius: 8px; margin-bottom: 1.25rem; color: #166534;">
                        <div style="display:flex; align-items:center; gap:8px; font-weight:700; margin-bottom:4px;">
                            <i class="ph-bold ph-check-circle" style="font-size:1.2rem;"></i> Acreditación Activa
                        </div>
                        <p style="margin: 0; font-size: 0.88rem; line-height: 1.4;">
                            Actualmente estás formalmente acreditado(a) como Docente PST para: 
                            <strong><?= !empty($trayectosPstActuales) ? implode(', ', array_map(fn($t) => PerfilPSTService::nombreTrayecto($t), $trayectosPstActuales)) : 'PST General' ?></strong>.
                            Los estudiantes de estos trayectos pueden seleccionarte como su tutor de proyecto.
                        </p>
                    </div>
                <?php elseif (!empty($ultimaSolicitudPst) && $ultimaSolicitudPst['estado'] === 'rechazado'): ?>
                    <div style="background: #fff1f2; border-left: 4px solid #e11d48; padding: 14px 18px; border-radius: 8px; margin-bottom: 1.25rem; color: #9f1239;">
                        <div style="display:flex; align-items:center; gap:8px; font-weight:700; margin-bottom:4px;">
                            <i class="ph-bold ph-warning-circle" style="font-size:1.2rem;"></i> Solicitud Previa No Aprobada
                        </div>
                        <p style="margin: 0; font-size: 0.88rem; line-height: 1.4;">
                            Tu solicitud anterior no fue aprobada<?= !empty($ultimaSolicitudPst['motivo']) ? ': <em>' . htmlspecialchars($ultimaSolicitudPst['motivo']) . '</em>' : '.' ?>. Puedes actualizar tus datos y enviar una nueva solicitud a continuación.
                        </p>
                    </div>
                <?php else: ?>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px 18px; border-radius: 8px; margin-bottom: 1.25rem; color: #475569;">
                        <p style="margin: 0; font-size: 0.88rem; line-height: 1.4;">
                            Si eres profesor(a) del eje de Proyecto Sociotecnológico (PST), marca la casilla y selecciona los trayectos que impartes. Al enviar el formulario se generará una <strong>solicitud de acreditación</strong> para ser validada por el Comité / Super Administrador.
                        </p>
                    </div>
                <?php endif; ?>

                <form action="perfil" method="POST" class="db-form">
                    <?= CSRF::campoOculto() ?>
                    <input type="hidden" name="accion" value="solicitar_acreditacion_pst">
                    <?php
                    $pstIdPrefijo = 'perfil_doc';
                    $pstEsProfesor = $esPstActual || (!empty($solicitudPstPendiente['es_profesor_pst']));
                    $pstSeleccion = !empty($solicitudPstPendiente['trayectos']) ? $solicitudPstPendiente['trayectos'] : $trayectosPstActuales;
                    include __DIR__ . '/partials/selector_pst.php';
                    ?>
                    <div class="db-form-actions" style="margin-top: 1rem;">
                        <button type="submit" class="db-btn-submit" style="display:inline-flex; align-items:center; gap:6px;">
                            <i class="ph-bold ph-paper-plane-tilt"></i> <?= !empty($solicitudPstPendiente) ? 'Actualizar Solicitud de Acreditación' : 'Enviar Solicitud de Acreditación PST' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

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

<!-- SCRIPT DE INTERACCIÓN DE PESTAÑAS Y VINCULACIÓN DE ESPECIALIDAD -->
<script>
function filtrarCarreraEspecialidad(carreraId, btnEl) {
    const buttons = document.querySelectorAll('.perfil-carrera-filter-btn');
    buttons.forEach(b => {
        b.classList.remove('active');
        b.style.background = '#ffffff';
        b.style.color = '#334155';
        b.style.borderColor = '#cbd5e1';
    });
    if (btnEl) {
        btnEl.classList.add('active');
        btnEl.style.background = '#121a3e';
        btnEl.style.color = '#ffffff';
        btnEl.style.borderColor = '#121a3e';
    }

    const bloques = document.querySelectorAll('.bloque-carrera-especialidad');
    bloques.forEach(sec => {
        const secId = sec.getAttribute('data-carrera-id');
        if (String(carreraId) === 'all' || String(secId) === String(carreraId)) {
            sec.style.display = '';
        } else {
            sec.style.display = 'none';
        }
    });
}

function actualizarEstiloChipDimension(dimId, checked) {
    const chip = document.getElementById('chip-dim-' + dimId);
    const icon = document.getElementById('icon-dim-' + dimId);
    if (chip) {
        chip.style.borderColor = checked ? '#505984' : '#cbd5e1';
        chip.style.background  = checked ? '#505984' : '#ffffff';
        chip.style.color       = checked ? '#ffffff' : '#334155';
    }
    if (icon) {
        icon.className = 'ph-bold ' + (checked ? 'ph-check-circle' : 'ph-circle');
    }
}

function actualizarEstiloTarjetaLinea(lineaId, checked) {
    const card = document.getElementById('card-linea-' + lineaId);
    if (card) {
        card.style.borderColor = checked ? '#505984' : '#e2e8f0';
        card.style.background  = checked ? '#f4f7ff' : '#ffffff';
    }
}

function actualizarContadoresEspecialidad(lineaId) {
    if (lineaId) {
        const dimsAll = document.querySelectorAll('.chk-dim-esp[data-linea-id="' + lineaId + '"]');
        const dimsSel = document.querySelectorAll('.chk-dim-esp[data-linea-id="' + lineaId + '"]:checked');
        const badge = document.getElementById('badge-dim-count-' + lineaId);
        if (badge) {
            badge.textContent = dimsSel.length + ' / ' + dimsAll.length + ' dim.';
            badge.style.color = dimsSel.length > 0 ? '#1e293b' : '#64748b';
            badge.style.background = dimsSel.length > 0 ? '#dbeafe' : '#f1f5f9';
        }
    }
    const totalLineas = document.querySelectorAll('.chk-linea-esp:checked').length;
    const totalDims   = document.querySelectorAll('.chk-dim-esp:checked').length;
    const cLin = document.getElementById('counter-lineas-sel');
    const cDim = document.getElementById('counter-dims-sel');
    if (cLin) cLin.textContent = totalLineas;
    if (cDim) cDim.textContent = totalDims;
}

function onToggleLineaEspecialidad(lineaId, checked) {
    actualizarEstiloTarjetaLinea(lineaId, checked);
    if (!checked) {
        // Si desmarca la línea padre, desmarcar sus dimensiones operativas hijas
        const dims = document.querySelectorAll('.chk-dim-esp[data-linea-id="' + lineaId + '"]');
        dims.forEach(chk => {
            chk.checked = false;
            actualizarEstiloChipDimension(chk.getAttribute('data-dim-id'), false);
        });
    }
    actualizarContadoresEspecialidad(lineaId);
}

function onToggleDimensionEspecialidad(lineaId, dimId, checked) {
    actualizarEstiloChipDimension(dimId, checked);
    if (checked) {
        // Si marca una dimensión operativa, marcar automáticamente su línea de investigación padre
        const chkLinea = document.querySelector('.chk-linea-esp[data-linea-id="' + lineaId + '"]');
        if (chkLinea && !chkLinea.checked) {
            chkLinea.checked = true;
            actualizarEstiloTarjetaLinea(lineaId, true);
        }
    }
    actualizarContadoresEspecialidad(lineaId);
}

function seleccionarDimensionesDeLinea(lineaId, marcarTodas) {
    const dims = document.querySelectorAll('.chk-dim-esp[data-linea-id="' + lineaId + '"]');
    dims.forEach(chk => {
        chk.checked = marcarTodas;
        actualizarEstiloChipDimension(chk.getAttribute('data-dim-id'), marcarTodas);
    });
    if (marcarTodas && dims.length > 0) {
        const chkLinea = document.querySelector('.chk-linea-esp[data-linea-id="' + lineaId + '"]');
        if (chkLinea && !chkLinea.checked) {
            chkLinea.checked = true;
            actualizarEstiloTarjetaLinea(lineaId, true);
        }
    }
    actualizarContadoresEspecialidad(lineaId);
}

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