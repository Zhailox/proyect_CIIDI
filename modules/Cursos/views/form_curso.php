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
$f_img     = htmlspecialchars($curso['imagen_portada']  ?? '');
$f_estado  = $curso['estado']                           ?? 'borrador';
$f_docente = $curso['id_docente']                       ?? '';

// Metadatos JSON
$m_moodle   = htmlspecialchars($meta['url_moodle']        ?? '');
$m_modal    = $meta['modalidad']    ?? 'Virtual';
$m_nivel    = $meta['nivel']        ?? 'Básico';
$m_duracion = htmlspecialchars($meta['duracion'] ?? '');
$m_cupo     = (int)($meta['cupo_maximo'] ?? 0);
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

        <!-- SECCIÓN 1: PRINCIPAL -->
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
                            $nombre_docente_actual = $usuario_actual['nombre'];
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

        <!-- SECCIÓN 2: DESCRIPCIÓN Y PORTADA -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:2rem;">
            
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-article" style="color:var(--cur-primary);"></i> 2. Descripción General</h3>
                <label for="descripcion" style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Resumen del Curso <span style="color:var(--cur-danger);">*</span></label>
                <textarea id="descripcion" name="descripcion" required rows="7" placeholder="Escribe un resumen atractivo para los estudiantes..." style="width:100%; padding:1rem; border:1px solid var(--cur-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--cur-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--cur-border)';"><?= $f_desc ?></textarea>
            </div>

            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--cur-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-image" style="color:var(--cur-primary);"></i> 3. Imagen de Portada</h3>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--cur-dark); margin-bottom:0.5rem;">Imagen de Portada (Máx <?= $img_max_mb ?>MB · Solo PNG)</label>
                    <label id="label-portada" style="display:block; border:2px dashed var(--cur-border); border-radius:12px; padding:2rem; text-align:center; cursor:pointer; background:#F8FAFC; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--cur-primary)';" onmouseout="this.style.borderColor='var(--cur-border)';">
                        <i class="ph-fill ph-upload-simple" style="font-size:2.5rem; color:var(--cur-muted); margin-bottom:1rem; display:block;"></i>
                        <strong style="color:var(--cur-primary);">Seleccionar Imagen PNG</strong>
                        <div style="font-size:0.85rem; color:var(--cur-muted); margin-top:0.5rem;">Solo se aceptan archivos <strong>.png</strong></div>
                        <input type="file" name="imagen_portada_file" id="portada" accept=".png" style="display:none;" onchange="validarArchivoImagen(this)">
                        <div id="file-name" style="margin-top:1rem; font-weight:600; color:var(--cur-dark);"></div>
                    </label>
                    <div id="file-error" style="display:none; margin-top:0.8rem; padding:0.8rem 1rem; background:#FEE2E2; border-radius:8px; color:#991B1B; font-size:0.9rem; font-weight:500;"></div>
                    <?php if ($f_img): ?>
                        <div style="margin-top:1rem; padding:1rem; background:rgba(80, 89, 132, 0.08); border-radius:8px; display:flex; align-items:center; gap:1rem;">
                            <img src="<?= $f_img ?>" alt="Actual" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            <span style="font-weight:600; color:#3d456a; font-size:0.9rem;">El curso ya cuenta con una imagen. Sube otra PNG si deseas reemplazarla.</span>
                        </div>
                    <?php endif; ?>
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
/**
 * Valida que el archivo seleccionado:
 * 1. Tenga extensión .png (case-insensitive)
 * 2. Sea realmente una imagen PNG verificando el magic byte (firma PNG: 89 50 4E 47)
 */
function validarArchivoImagen(input) {
    const file = input.files[0];
    const errDiv = document.getElementById('file-error');
    const nameDiv = document.getElementById('file-name');
    const btnSubmit = document.getElementById('btn-submit');

    errDiv.style.display = 'none';
    errDiv.textContent = '';
    nameDiv.textContent = '';

    if (!file) return;

    // 1. Verificar extensión
    if (!file.name.toLowerCase().endsWith('.png')) {
        errDiv.textContent = '⚠ Solo se permiten archivos con extensión .png. El archivo seleccionado tiene una extensión diferente.';
        errDiv.style.display = 'block';
        input.value = '';
        btnSubmit.disabled = false;
        return;
    }

    // 2. Verificar firma PNG real (magic bytes: 0x89 0x50 0x4E 0x47)
    const reader = new FileReader();
    reader.onloadend = function(e) {
        const arr = new Uint8Array(e.target.result);
        // PNG magic bytes: 137 80 78 71 (decimal)
        const isPNG = arr[0] === 0x89 && arr[1] === 0x50 && arr[2] === 0x4E && arr[3] === 0x47;
        if (!isPNG) {
            errDiv.textContent = '⚠ El archivo no es una imagen PNG válida. Parece ser un archivo disfrazado con extensión .png.';
            errDiv.style.display = 'block';
            input.value = '';
            nameDiv.textContent = '';
        } else {
            nameDiv.textContent = '✓ ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            nameDiv.style.color = '#166534';
        }
    };
    reader.readAsArrayBuffer(file.slice(0, 4));

    nameDiv.textContent = file.name;
}

// Prevenir envío del formulario si hay error de archivo
document.getElementById('form-curso').addEventListener('submit', function(e) {
    const errDiv = document.getElementById('file-error');
    if (errDiv.style.display === 'block' && errDiv.textContent.trim() !== '') {
        e.preventDefault();
        errDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>