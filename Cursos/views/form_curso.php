<?php
// modules/Cursos/views/form_curso.php
// Variables: $curso, $meta, $docentes, $modo, $titulo_form, $error, $csrf_token, $config_vista

$es_editar  = ($modo === 'editar');
$accion_url = $es_editar ? '?ruta=cursos-procesar-editar' : '?ruta=cursos-procesar-crear';
$cfg        = $config_vista ?? [];
$img_max_mb = $cfg['imagenes']['max_size_mb'] ?? 5;
$img_exts   = implode(', ', $cfg['imagenes']['extensiones_permitidas'] ?? ['jpg','jpeg','png','webp']);
$lazy_load  = !empty($cfg['imagenes']['lazy_load']) ? 'lazy' : 'eager';

// Campos de la tabla cursos
$f_id      = $curso['id']       ?? '';
$f_titulo  = htmlspecialchars($curso['titulo']          ?? '');
$f_desc    = htmlspecialchars($curso['descripcion']     ?? '');
$f_img     = htmlspecialchars($curso['imagen_portada']  ?? '');
$f_estado  = $curso['estado']                           ?? 'borrador';
$f_nota    = $curso['nota_minima_aprobacion']           ?? '70.00';
$f_docente = $curso['id_docente']                       ?? '';

// Metadatos JSON
$m_moodle   = htmlspecialchars($meta['url_moodle']        ?? '');
$m_video    = htmlspecialchars($meta['url_video_preview'] ?? '');
$m_modal    = $meta['modalidad']    ?? 'Virtual';
$m_nivel    = $meta['nivel']        ?? 'Básico';
$m_duracion = htmlspecialchars($meta['duracion'] ?? '');
$m_cupo     = (int)($meta['cupo_maximo'] ?? 0);
?>

<div class="cur-wrapper" style="max-width: 1200px;">
    
    <div style="margin-bottom: 2rem;">
        <a href="?ruta=cursos-gestion" style="color:var(--cur-muted); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; font-weight:600; font-size:0.9rem; margin-bottom:1rem; transition:color 0.2s;" onmouseover="this.style.color='var(--cur-primary)';" onmouseout="this.style.color='var(--cur-muted)';"><i class="ph-bold ph-arrow-left"></i> Volver a la Gestión</a>
        
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--cur-dark); margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.8rem;">
            <div style="width:50px; height:50px; border-radius:12px; background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:1.6rem; box-shadow:0 10px 20px rgba(37, 99, 235, 0.3);">
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

        <!-- SECCIÓN 1: PRINCIPAL -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-info" style="color:var(--cur-primary);"></i> 1. Información Principal</h3>
            
            <div style="margin-bottom:1.5rem;">
                <label for="titulo" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Título del Curso <span style="color:var(--cur-danger);">*</span></label>
                <div style="position:relative;">
                    <i class="ph-bold ph-text-aa" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                    <input type="text" id="titulo" name="titulo" value="<?= $f_titulo ?>" placeholder="Ej: Fundamentos de Inteligencia Artificial" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                <div>
                    <label for="id_docente" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Docente Responsable <span style="color:var(--cur-danger);">*</span></label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-chalkboard-teacher" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="id_docente" name="id_docente" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="">— Seleccionar docente —</option>
                            <?php foreach ($docentes as $doc): ?>
                            <option value="<?= (int)$doc['id'] ?>" <?= ($f_docente == $doc['id']) ? 'selected' : '' ?>><?= htmlspecialchars($doc['nombre_completo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>
                <div>
                    <label for="estado" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Estado Inicial</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-toggle-right" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="estado" name="estado" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                            <option value="borrador" <?= $f_estado === 'borrador' ? 'selected' : '' ?>>Borrador (Oculto)</option>
                            <option value="publicado" <?= $f_estado === 'publicado' ? 'selected' : '' ?>>Publicado (Visible en catálogo)</option>
                            <option value="archivado" <?= $f_estado === 'archivado' ? 'selected' : '' ?>>Archivado</option>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted); pointer-events:none;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: DESCRIPCIÓN Y MULTIMEDIA -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:2rem;">
            
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-article" style="color:var(--cur-primary);"></i> 2. Descripción General</h3>
                <label for="descripcion" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Resumen del Curso <span style="color:var(--cur-danger);">*</span></label>
                <textarea id="descripcion" name="descripcion" required rows="7" placeholder="Escribe un resumen atractivo para los estudiantes..." style="width:100%; padding:1rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';"><?= $f_desc ?></textarea>
            </div>

            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-image" style="color:var(--cur-primary);"></i> 3. Recursos Multimedia</h3>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Imagen de Portada (Máx <?= $img_max_mb ?>MB)</label>
                    <label style="display:block; border:2px dashed var(--cur-border); border-radius:12px; padding:2rem; text-align:center; cursor:pointer; background:#F8FAFC; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--cur-primary)';" onmouseout="this.style.borderColor='var(--cur-border)';">
                        <i class="ph-fill ph-upload-simple" style="font-size:2.5rem; color:var(--cur-muted); margin-bottom:1rem; display:block;"></i>
                        <strong style="color:var(--cur-primary);">Seleccionar Imagen</strong>
                        <div style="font-size:0.85rem; color:var(--cur-muted); margin-top:0.5rem;">Formatos: <?= $img_exts ?></div>
                        <input type="file" name="portada" id="portada" accept="image/*" style="display:none;" onchange="document.getElementById('file-name').textContent = this.files[0].name;">
                        <div id="file-name" style="margin-top:1rem; font-weight:600; color:var(--cur-dark);"></div>
                    </label>
                    <?php if ($f_img): ?>
                        <div style="margin-top:1rem; padding:1rem; background:rgba(16, 185, 129, 0.1); border-radius:8px; display:flex; align-items:center; gap:1rem;">
                            <img src="<?= $f_img ?>" alt="Actual" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            <span style="font-weight:600; color:#065F46; font-size:0.9rem;">El curso ya cuenta con una imagen. Sube otra si deseas reemplazarla.</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="url_video_preview" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Video Promocional (Opcional)</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-video-camera" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="url" id="url_video_preview" name="url_video_preview" value="<?= $m_video ?>" placeholder="https://youtube.com/..." style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN 3: METADATOS TÉCNICOS -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-sliders" style="color:var(--cur-primary);"></i> 4. Parámetros Académicos</h3>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                
                <div>
                    <label for="url_moodle" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">URL de Moodle</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-link" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="url" id="url_moodle" name="url_moodle" value="<?= $m_moodle ?>" placeholder="Enlace al curso virtual..." style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="modalidad" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Modalidad</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-desktop" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <select id="modalidad" name="modalidad" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
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
                        <select id="nivel" name="nivel" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
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
                        <input type="text" id="duracion" name="duracion" value="<?= $m_duracion ?>" placeholder="Ej: 40 horas académicas" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="cupo_maximo" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Cupo Máximo</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-users-three" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="number" id="cupo_maximo" name="cupo_maximo" value="<?= $m_cupo ?>" min="0" placeholder="0 = Ilimitado" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

                <div>
                    <label for="nota_minima_aprobacion" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Nota de Aprobación (%)</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-percent" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--cur-muted);"></i>
                        <input type="number" step="0.01" id="nota_minima_aprobacion" name="nota_minima_aprobacion" value="<?= htmlspecialchars((string)$f_nota) ?>" min="0" max="100" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(79,70,229,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';">
                    </div>
                </div>

            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; padding-top:1rem; padding-bottom:3rem; gap:1rem;">
            <a href="?ruta=cursos-gestion" style="background: white; color: var(--cur-dark); border: 1px solid var(--cur-border); padding: 1rem 2rem; border-radius: 50px; font-weight: 700; font-size: 1.15rem; text-decoration:none; transition: background 0.2s;" onmouseover="this.style.background='#F1F5F9';" onmouseout="this.style.background='white';">Cancelar</a>
            
            <button type="submit" style="
                background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); 
                color: white; 
                border: none; 
                padding: 1rem 2.5rem; 
                border-radius: 50px; 
                font-weight: 700; 
                font-size: 1.15rem; 
                display:flex; 
                align-items:center; 
                gap: 0.8rem;
                box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
            " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(37, 99, 235, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(37, 99, 235, 0.4)';">
                <i class="ph-bold ph-floppy-disk" style="font-size: 1.4rem;"></i> <?= $es_editar ? 'Guardar Cambios' : 'Crear Curso' ?>
            </button>
        </div>
    </form>
</div>