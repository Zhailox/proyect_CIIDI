<?php
// modules/Cursos/views/form_curso.php
// Variables: $curso, $meta, $docentes, $modo, $titulo_form, $error, $csrf_token, $config_vista

$es_editar  = ($modo === 'editar');
$accion_url = $es_editar ? '?ruta=cursos-procesar-editar' : '?ruta=cursos-procesar-crear';
$cfg        = $config_vista ?? [];
$img_max_mb = $cfg['imagenes']['max_size_mb'] ?? 5;
$lazy_load  = !empty($cfg['imagenes']['lazy_load']) ? 'lazy' : 'eager';

// Campos de la tabla cursos
$f_id      = $curso['id']       ?? '';
$f_titulo  = htmlspecialchars($curso['titulo']          ?? '');
$f_desc    = htmlspecialchars($curso['descripcion']     ?? '');
$f_img_raw = $curso['imagen_portada'] ?? '';
// DB stores 'public/uploads/cursos/x.webp' but web root IS public/, so strip 'public/'
if (!function_exists('curImgUrl')) {
    function curImgUrl(string $raw): string {
        if (empty($raw)) return '';
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) return $raw;
        return ltrim(preg_replace('#^public/#', '', $raw), '/');
    }
}
$f_img     = !empty($f_img_raw) ? htmlspecialchars(curImgUrl($f_img_raw)) : '';
$f_estado  = $curso['estado']                           ?? 'borrador';
$f_docente = $curso['id_docente']                       ?? '';

// Metadatos y adicionales DB
$m_moodle   = htmlspecialchars($curso['url_moodle'] ?? '');
$m_modal    = $curso['modalidad']    ?? 'Virtual';
$m_nivel    = $curso['nivel']        ?? 'Básico';
$m_duracion = htmlspecialchars($curso['duracion'] ?? '');
$m_cupo     = (int)($curso['cupo_maximo'] ?? 0);

$f_f_inicio = $curso['fecha_inicio'] ?? '';
$f_f_fin = $curso['fecha_fin'] ?? '';
$f_vpreview = htmlspecialchars($curso['url_video_preview'] ?? '');
$f_est_insc = $curso['estado_inscripcion'] ?? 'Abierta';
?>

<div class="cur-wrapper" style="max-width: 1200px;">
    
    <div style="margin-bottom: 2rem;">
        <a href="?ruta=cursos-gestion" style="color:var(--cur-muted); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; font-weight:600; font-size:0.9rem; margin-bottom:1rem; transition:color 0.2s;" onmouseover="this.style.color='var(--cur-primary)';" onmouseout="this.style.color='var(--cur-muted)';"><i class="ph-bold ph-arrow-left"></i> Volver a la Gestión</a>
        
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--cur-dark); margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.8rem;">
            <div style="width:50px; height:50px; border-radius:12px; background: linear-gradient(135deg, rgba(80,89,132,0.95) 0%, rgba(112,144,203,0.9) 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:1.6rem; box-shadow:0 10px 20px rgba(80,89,132,0.3);">
                <i class="ph-fill <?= $es_editar ? 'ph-pencil-simple' : 'ph-plus' ?>"></i>
            </div>
            <?= htmlspecialchars($titulo_form) ?>
        </h1>
        <p style="color:var(--cur-muted); font-size:1.05rem;">
            <?= $es_editar ? 'Modifica los datos del curso y guarda los cambios.' : 'Completa todos los campos para registrar un nuevo curso en el ecosistema.' ?>
        </p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="cur-flash cur-flash-error" style="border-radius:12px; margin-bottom:2rem;"><i class="ph-fill ph-warning-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= $accion_url ?>" id="form-curso" enctype="multipart/form-data" novalidate style="display:flex; flex-direction:column; gap:2rem;">
        <?= $csrf_token ?? '' ?>
        <?php if ($es_editar): ?>
            <input type="hidden" name="id" value="<?= (int)$f_id ?>">
        <?php endif; ?>

        <!-- SECCIÁN 1: PRINCIPAL -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-info" style="color:var(--cur-primary);"></i> 1. Información Principal</h3>
            
            <div style="margin-bottom:1.5rem;">
                <label for="titulo" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Título del Curso <span style="color:var(--cur-danger);">*</span></label>
                <div style="position:relative;">
                    <i class="ph-bold ph-text-aa" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                    <input type="text" id="titulo" name="titulo" value="<?= $f_titulo ?>" placeholder="Ej: Fundamentos de Inteligencia Artificial" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                <?php $nivel_usuario = (int)($usuario_actual['nivel'] ?? -1); ?>
                <?php if ($nivel_usuario >= 3): ?>
                <div>
                    <label for="id_docente" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Docente Responsable <span style="color:var(--cur-danger);">*</span></label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-chalkboard-teacher" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="id_docente" name="id_docente" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="">— Seleccionar docente —</option>
                            <?php foreach ($docentes as $doc): ?>
                            <option value="<?= (int)$doc['id'] ?>" <?= ($f_docente == $doc['id']) ? 'selected' : '' ?>><?= htmlspecialchars($doc['nombre_completo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>
                <?php else: ?>
                <div>
                    <label style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Docente Responsable</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-chalkboard-teacher" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <?php
                            $nombre_docente_actual = $usuario_actual['nombre'] . ' ' . $usuario_actual['apellido'];
                            if ($es_editar) {
                                foreach ($docentes as $doc) {
                                    if ($doc['id'] == $f_docente) {
                                        $nombre_docente_actual = $doc['nombre_completo'];
                                        break;
                                    }
                                }
                            }
                        ?>
                        <input type="text" value="<?= htmlspecialchars($nombre_docente_actual) ?>" readonly style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; background:#F8FAFC; color:var(--cur-muted); outline:none; cursor:not-allowed;">
                    </div>
                </div>
                <?php endif; ?>

                <div>
                    <label for="estado" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Estado Inicial</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-toggle-right" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="estado" name="estado" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="borrador" <?= $f_estado === 'borrador' ? 'selected' : '' ?>>Borrador (Oculto)</option>
                            <option value="publicado" <?= $f_estado === 'publicado' ? 'selected' : '' ?>>Publicado (Visible en catálogo)</option>
                            <option value="archivado" <?= $f_estado === 'archivado' ? 'selected' : '' ?>>Archivado</option>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÁN 2: DESCRIPCIÁN Y PORTADA -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:2rem;">
            
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-article" style="color:var(--cur-primary);"></i> 2. Descripción General</h3>
                <label for="descripcion" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Resumen del Curso <span style="color:var(--cur-danger);">*</span></label>
                <textarea id="descripcion" name="descripcion" required rows="7" placeholder="Escribe un resumen atractivo para los estudiantes..." style="width:100%; padding:1rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';"><?= $f_desc ?></textarea>
            </div>

            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-image" style="color:var(--cur-primary);"></i> 3. Imagen de Portada</h3>

                <?php if ($f_img): ?>
                <!-- Imagen actual (sólo en edición) -->
                <div style="margin-bottom:1.5rem; padding:1.2rem; background:#EFF6FF; border-radius:12px; border:1px solid #BFDBFE; display:flex; align-items:center; gap:1.2rem; flex-wrap:wrap;">
                    <img src="<?= $f_img ?>" alt="Imagen actual" style="width:80px; height:80px; object-fit:cover; border-radius:10px; border:2px solid #BFDBFE; flex-shrink:0;" onerror="this.style.display='none'">
                    <div>
                        <div style="font-weight:700; color:#1D4ED8; font-size:0.9rem; margin-bottom:0.3rem;"><i class="ph-fill ph-check-circle"></i> Imagen actual guardada</div>
                        <div style="font-size:0.82rem; color:#3B82F6;">Sube un nuevo archivo o pega una URL para reemplazarla. Si no cambias nada, se conserva la actual.</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Pestañas: Archivo vs URL -->
                <div style="display:flex; gap:0.5rem; margin-bottom:1.2rem;">
                    <button type="button" id="tab-archivo" onclick="cambiarTab('archivo')" style="padding:0.6rem 1.2rem; border-radius:50px; border:2px solid var(--cur-primary); background:var(--cur-primary); color:white; font-weight:600; cursor:pointer; font-size:0.9rem; transition:all 0.2s;">
                        <i class="ph-bold ph-upload-simple"></i> Subir archivo
                    </button>
                    <button type="button" id="tab-url" onclick="cambiarTab('url')" style="padding:0.6rem 1.2rem; border-radius:50px; border:2px solid var(--cur-border); background:white; color:var(--cur-muted); font-weight:600; cursor:pointer; font-size:0.9rem; transition:all 0.2s;">
                        <i class="ph-bold ph-link"></i> Pegar URL
                    </button>
                </div>

                <!-- Panel: Subir archivo -->
                <div id="panel-archivo" style="margin-bottom:1rem;">
                    <label id="label-portada" style="display:block; border:2px dashed var(--cur-border); border-radius:12px; padding:2rem; text-align:center; cursor:pointer; background:#F8FAFC; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--cur-primary)';" onmouseout="this.style.borderColor='var(--cur-border)';">
                        <i class="ph-fill ph-upload-simple" style="font-size:2.5rem; color:var(--cur-muted); margin-bottom:0.8rem; display:block;" id="upload-icon"></i>
                        <img id="img-preview" src="" style="display:none; max-width:100%; max-height:200px; border-radius:8px; margin:0 auto 1rem;" />
                        <strong style="color:var(--cur-primary);">Seleccionar Imagen</strong>
                        <div style="font-size:0.85rem; color:var(--cur-muted); margin-top:0.4rem;">Formatos: <strong>.jpg, .png, .webp, .gif</strong> — Máx <?= $img_max_mb ?>MB</div>
                        <input type="file" name="imagen_portada_file" id="portada" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;" onchange="validarArchivoImagen(this)">
                        <div id="file-name" style="margin-top:0.8rem; font-weight:600; color:#166534;"></div>
                    </label>
                    <div id="file-error" style="display:none; margin-top:0.8rem; padding:0.8rem 1rem; background:#FEE2E2; border-radius:8px; color:#991B1B; font-size:0.9rem; font-weight:500;"></div>
                </div>

                <!-- Panel: URL de imagen -->
                <div id="panel-url" style="display:none; margin-bottom:1rem;">
                    <label style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">URL de la imagen <span style="color:var(--cur-muted); font-weight:400; font-size:0.85rem;">(pega el enlace directo a la imagen)</span></label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-link" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="url" name="imagen_portada_url" id="imagen_url" value="<?= htmlspecialchars($f_img_raw ?? '') ?>" placeholder="https://ejemplo.com/imagen.jpg"
                            style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1rem; outline:none; box-sizing:border-box;"
                            onfocus="this.style.borderColor='var(--cur-primary)';" onblur="this.style.borderColor='var(--cur-border)';"
                            oninput="previsualizarUrl(this.value)">
                    </div>
                    <div id="url-preview-wrap" style="display:none; margin-top:1rem; text-align:center;">
                        <img id="url-preview-img" src="" alt="Vista previa" style="max-width:100%; max-height:200px; border-radius:10px; border:2px solid #E5E7EB;">
                    </div>
                </div>

            </div>
        </div>

        <!-- SECCIÁN 3: METADATOS TÁCNICOS -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-sliders" style="color:var(--cur-primary);"></i> 4. Parámetros Académicos</h3>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                
                <div>
                    <label for="url_moodle" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">URL de Moodle</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-link" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="url" id="url_moodle" name="url_moodle" value="<?= $m_moodle ?>" placeholder="Enlace al curso virtual..." style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="modalidad" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Modalidad</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-desktop" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="modalidad" name="modalidad" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="Virtual" <?= $m_modal === 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                            <option value="Presencial" <?= $m_modal === 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                            <option value="Híbrido" <?= $m_modal === 'Híbrido' ? 'selected' : '' ?>>Híbrido / Semipresencial</option>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>

                <div>
                    <label for="nivel" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Nivel de Dificultad</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-stairs" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="nivel" name="nivel" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="Básico" <?= $m_nivel === 'Básico' ? 'selected' : '' ?>>Básico</option>
                            <option value="Intermedio" <?= $m_nivel === 'Intermedio' ? 'selected' : '' ?>>Intermedio</option>
                            <option value="Avanzado" <?= $m_nivel === 'Avanzado' ? 'selected' : '' ?>>Avanzado</option>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>

                <div>
                    <label for="duracion" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Duración (Horas/Semanas)</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-clock" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="text" id="duracion" name="duracion" value="<?= $m_duracion ?>" placeholder="Ej: 40 horas académicas" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="cupo_maximo" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Cupo Máximo</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-users-three" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="number" id="cupo_maximo" name="cupo_maximo" value="<?= $m_cupo ?>" min="0" placeholder="0 = Ilimitado" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                

                <div>
                    <label for="fecha_inicio" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Fecha de Inicio</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-calendar" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= $f_f_inicio ?>" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="fecha_fin" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Fecha de Fin</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-calendar-check" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?= $f_f_fin ?>" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="estado_inscripcion" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Estado de Inscripción</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-door-open" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="estado_inscripcion" name="estado_inscripcion" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="Próximamente" <?= $f_est_insc === 'Próximamente' ? 'selected' : '' ?>>Próximamente</option>
                            <option value="Abierta" <?= $f_est_insc === 'Abierta' ? 'selected' : '' ?>>Inscripción Abierta</option>
                            <option value="Cerrada" <?= $f_est_insc === 'Cerrada' ? 'selected' : '' ?>>Inscripción Cerrada</option>
                            <option value="En Curso" <?= $f_est_insc === 'En Curso' ? 'selected' : '' ?>>En Curso</option>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="url_video_preview" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Video Preview (YouTube URL)</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-video" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="url" id="url_video_preview" name="url_video_preview" value="<?= $f_vpreview ?>" placeholder="Ej: https://youtube.com/watch?v=..." style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; padding-top:1rem; padding-bottom:3rem; gap:1rem;">
            <a href="?ruta=cursos-gestion" style="background: white; color: var(--cur-dark); border: 1px solid var(--cur-border); padding: 1rem 2rem; border-radius: 50px; font-weight: 700; font-size: 1.15rem; text-decoration:none; transition: background 0.2s;" onmouseover="this.style.background='#F1F5F9';" onmouseout="this.style.background='white';">Cancelar</a>
            
            <button type="submit" id="btn-submit" style="
                background: linear-gradient(135deg, rgba(80,89,132,0.95) 0%, rgba(112,144,203,0.9) 100%); 
                color: white; 
                border: none; 
                padding: 1rem 2.5rem; 
                border-radius: 50px; 
                font-weight: 700; 
                font-size: 1.15rem; 
                display:flex; 
                align-items:center; 
                gap: 0.8rem;
                box-shadow: 0 10px 25px rgba(80, 89, 132, 0.35);
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
            " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(80, 89, 132, 0.45)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(80, 89, 132, 0.35)';">
                <i class="ph-bold ph-floppy-disk" style="font-size: 1.4rem;"></i> <?= $es_editar ? 'Guardar Cambios' : 'Crear Curso' ?>
            </button>
        </div>
    </form>
</div>

<script>
const MAX_MB = <?= $img_max_mb ?>;

// ── Tab switcher: Archivo / URL ──────────────────────────────────────────────
function cambiarTab(tab) {
    const panelArchivo = document.getElementById('panel-archivo');
    const panelUrl     = document.getElementById('panel-url');
    const tabArchivo   = document.getElementById('tab-archivo');
    const tabUrl       = document.getElementById('tab-url');
    const inputFile    = document.getElementById('portada');
    const inputUrl     = document.getElementById('imagen_url');

    if (tab === 'archivo') {
        panelArchivo.style.display = 'block';
        panelUrl.style.display     = 'none';
        tabArchivo.style.background = 'var(--cur-primary)';
        tabArchivo.style.color      = 'white';
        tabArchivo.style.borderColor = 'var(--cur-primary)';
        tabUrl.style.background = 'white';
        tabUrl.style.color = 'var(--cur-muted)';
        tabUrl.style.borderColor = 'var(--cur-border)';
        // Disable URL input so file takes priority
        if (inputUrl) inputUrl.disabled = true;
        if (inputFile) inputFile.disabled = false;
    } else {
        panelArchivo.style.display = 'none';
        panelUrl.style.display     = 'block';
        tabUrl.style.background = 'var(--cur-primary)';
        tabUrl.style.color      = 'white';
        tabUrl.style.borderColor = 'var(--cur-primary)';
        tabArchivo.style.background = 'white';
        tabArchivo.style.color = 'var(--cur-muted)';
        tabArchivo.style.borderColor = 'var(--cur-border)';
        // Disable file input so URL takes priority
        if (inputFile) { inputFile.value = ''; inputFile.disabled = true; }
        if (inputUrl) inputUrl.disabled = false;
        // Show preview if URL already has value
        if (inputUrl && inputUrl.value) previsualizarUrl(inputUrl.value);
    }
}

// ── URL image preview ─────────────────────────────────────────────────────────
function previsualizarUrl(url) {
    const wrap = document.getElementById('url-preview-wrap');
    const img  = document.getElementById('url-preview-img');
    if (!url || !url.startsWith('http')) {
        wrap.style.display = 'none';
        return;
    }
    img.src = url;
    img.onerror = () => { wrap.style.display = 'none'; };
    img.onload  = () => { wrap.style.display = 'block'; };
}

// ── File validation ───────────────────────────────────────────────────────────
function validarArchivoImagen(input) {
    const file = input.files[0];
    const errDiv    = document.getElementById('file-error');
    const nameDiv   = document.getElementById('file-name');
    const previewImg = document.getElementById('img-preview');
    const uploadIcon = document.getElementById('upload-icon');

    errDiv.style.display = 'none';
    errDiv.textContent = '';
    nameDiv.textContent = '';

    if (!file) {
        previewImg.style.display = 'none';
        uploadIcon.style.display = 'block';
        return;
    }

    const allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    if (!allowed.includes(file.type.toLowerCase())) {
        errDiv.textContent = '⚠ Solo se permiten archivos de imagen válidos (.jpg, .png, .webp, .gif).';
        errDiv.style.display = 'block';
        input.value = '';
        previewImg.style.display = 'none';
        uploadIcon.style.display = 'block';
        return;
    }

    const sizeMB = file.size / (1024 * 1024);
    if (sizeMB > MAX_MB) {
        errDiv.textContent = '⚠ El archivo excede el tamaño máximo permitido de ' + MAX_MB + 'MB (Tamaño actual: ' + sizeMB.toFixed(1) + 'MB).';
        errDiv.style.display = 'block';
        input.value = '';
        previewImg.style.display = 'none';
        uploadIcon.style.display = 'block';
        return;
    }

    const objectUrl = URL.createObjectURL(file);
    previewImg.src = objectUrl;
    previewImg.style.display = 'block';
    uploadIcon.style.display = 'none';
    nameDiv.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
    nameDiv.style.color = '#166534';
}

// ── Submit guard ──────────────────────────────────────────────────────────────
document.getElementById('form-curso').addEventListener('submit', function(e) {
    const errDiv = document.getElementById('file-error');
    if (errDiv && errDiv.style.display === 'block' && errDiv.textContent.trim() !== '') {
        e.preventDefault();
        errDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Init: default tab = archivo
cambiarTab('archivo');
</script>






