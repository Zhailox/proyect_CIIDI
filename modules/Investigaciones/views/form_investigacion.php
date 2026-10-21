<?php
// modules/Investigaciones/views/form_investigacion.php
// Variables: $investigacion (array), $lineas (array), $dimensiones (array - opcional)
$esEdicion = !empty($investigacion['id']);
?>
<div class="inv-wrapper" style="max-width: 1200px; margin: 0 auto;">

    <div style="margin-bottom: 2rem;">
        <a href="?ruta=mis-investigaciones" style="color:var(--inv-muted); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; font-weight:600; font-size:0.9rem; margin-bottom:1rem; transition:color 0.2s;" onmouseover="this.style.color='var(--inv-primary)';" onmouseout="this.style.color='var(--inv-muted)';"><i class="ph-bold ph-arrow-left"></i> Volver a Mis Proyectos</a>
        
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--inv-dark); margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.8rem;">
            <div style="width:50px; height:50px; border-radius:12px; background: #505984; color:white; display:flex; align-items:center; justify-content:center; font-size:1.6rem; box-shadow:0 10px 20px rgba(80, 89, 132, 0.3);">
                <i class="ph-fill <?= $esEdicion ? 'ph-pencil-simple' : 'ph-plus' ?>"></i>
            </div>
            <?= $esEdicion ? 'Editar Investigación' : 'Crear Investigación' ?>
        </h1>
        <p style="color:var(--inv-muted); font-size:1.05rem;">
            <?= $esEdicion ? 'Actualiza los datos del proyecto y la disponibilidad de cupos.' : 'Registra un nuevo proyecto en el departamento para buscar tesistas.' ?>
        </p>
    </div>

    

    <form action="?ruta=<?= $esEdicion ? 'actualizar-investigacion' : 'guardar-investigacion' ?>" method="POST" enctype="multipart/form-data" novalidate style="display:flex; flex-direction:column; gap:2rem;">
        
        <?php if ($esEdicion): ?>
            <input type="hidden" name="id" value="<?= $investigacion['id'] ?>">
        <?php endif; ?>

        <!-- SECCIÓN 1: PRINCIPAL -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <!-- Sección 1: icono azul #0369A1 -->
            <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#E0F2FE;">
                    <i class="ph-fill ph-info" style="color:#0369A1; font-size:1.2rem;"></i>
                </span>
                1. Información Principal
            </h3>
            
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Título del Proyecto <span style="color:var(--inv-danger);">*</span></label>
                <div style="position:relative;">
                    <i class="ph-bold ph-text-aa" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($investigacion['titulo'] ?? '') ?>" placeholder="Ej: Sistema de control de inventario basado en RFID" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                </div>
            </div>

            <!-- Líneas y Dimensiones (Pills) -->
            <div style="margin-bottom:2rem;">
                <style>
                .cb-pill input[type="checkbox"] { display: none; }
                .cb-pill-text {
                    display: inline-flex; align-items: center; gap: 0.4rem;
                    padding: 0.6rem 1.2rem; background: #F1F5F9; color: #475569;
                    border: 1px solid var(--inv-border); border-radius: 50px;
                    font-size: 0.95rem; font-weight: 600; cursor: pointer;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); user-select: none;
                }
                .cb-pill input[type="checkbox"]:checked + .cb-pill-text {
                    background: var(--inv-primary-light); color: var(--inv-primary);
                    border-color: var(--inv-primary); box-shadow: 0 4px 12px rgba(80,89,132,0.15);
                }
                .cb-pill:hover .cb-pill-text { border-color: var(--inv-primary); transform: translateY(-1px); }
                </style>

                <!-- Líneas de Investigación -->
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.8rem;">Líneas de Investigación <span style="color:var(--inv-danger);">*</span></label>
                    <div style="display:flex; flex-wrap:wrap; gap:0.6rem;">
                        <?php 
                        $selectedLineas = $investigacion['lineas_array'] ?? (!empty($investigacion['id_linea']) ? [$investigacion['id_linea']] : []);
                        foreach ($lineas as $l): ?>
                            <label class="cb-pill">
                                <input type="checkbox" name="id_linea[]" value="<?= $l['id'] ?>" class="linea-cb" <?= (in_array($l['id'], $selectedLineas)) ? 'checked' : '' ?>>
                                <span class="cb-pill-text"><i class="ph-bold ph-git-branch"></i> <?= htmlspecialchars($l['nombre']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Dimensiones Operativas -->
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.8rem;">Dimensiones Operativas</label>
                    <div id="dimensiones-container" style="display:flex; flex-wrap:wrap; gap:0.6rem; min-height:45px; padding:1.2rem; border:1px dashed var(--inv-border); border-radius:12px; background:#F8FAFC;">
                        <?php 
                        $selectedDims = $investigacion['dimensiones_array'] ?? (!empty($investigacion['id_dimension']) ? [$investigacion['id_dimension']] : []);
                        if (!empty($todasDimensiones)): ?>
                            <?php foreach ($todasDimensiones as $d): ?>
                                <label class="cb-pill dim-pill" data-linea="<?= $d['id_linea'] ?>" style="display:none;">
                                    <input type="checkbox" name="id_dimension[]" value="<?= $d['id'] ?>" <?= (in_array($d['id'], $selectedDims)) ? 'checked' : '' ?>>
                                    <span class="cb-pill-text"><i class="ph-bold ph-circles-three"></i> <?= htmlspecialchars($d['nombre']) ?></span>
                                </label>
                            <?php endforeach; ?>
                            <span id="no-dims-msg" style="color:var(--inv-muted); font-size:0.9rem;">Seleccione primero una línea de investigación.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
                <!-- Responsable del Proyecto -->
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">
                        Investigador / Docente Responsable <span style="color:var(--inv-danger);">*</span>
                    </label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-user-circle-gear" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); font-size:1.2rem;"></i>
                        <select name="id_profesor" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                            <?php 
                            $respActual = (int)($investigacion['id_profesor'] ?? ($usuario_actual['id'] ?? 0));
                            if (!empty($responsablesDisponibles)):
                                foreach ($responsablesDisponibles as $resp): ?>
                                    <option value="<?= $resp['id'] ?>" <?= ($respActual === (int)$resp['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($resp['nombre_completo']) ?> (<?= htmlspecialchars($resp['rol_nombre']) ?><?= !empty($resp['es_investigador']) ? ' · Investigador' : '' ?>)
                                    </option>
                                <?php endforeach;
                            else: ?>
                                <option value="<?= (int)($usuario_actual['id'] ?? 0) ?>"><?= htmlspecialchars($usuario_actual['nombre_completo'] ?? 'Usuario Actual') ?></option>
                            <?php endif; ?>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); pointer-events:none;"></i>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Estado del Proyecto</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-pulse" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <select name="estado" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                            <?php $estados = ['Abierta', 'En Desarrollo', 'Cerrada', 'Finalizada']; ?>
                            <?php foreach ($estados as $est): ?>
                                <option value="<?= $est ?>" <?= (($investigacion['estado']??'Abierta') == $est) ? 'selected' : '' ?>><?= $est ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); pointer-events:none;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: DESCRIPCIÓN Y MULTIMEDIA -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:2rem;">
            
            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:var(--inv-primary-light);">
                        <i class="ph-fill ph-article" style="color:var(--inv-primary); font-size:1.2rem;"></i>
                    </span>
                    2. Planteamiento y Objetivos
                </h3>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Planteamiento del Problema / Resumen <span style="color:var(--inv-danger);">*</span></label>
                    <textarea name="planteamiento_problema" required rows="4" placeholder="Describe brevemente el problema a resolver..." style="width:100%; padding:1rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';"><?= htmlspecialchars($investigacion['planteamiento_problema'] ?? '') ?></textarea>
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Objetivo General / Meta</label>
                    <textarea name="objetivo_general" rows="2" placeholder="Objetivo general del proyecto..." style="width:100%; padding:1rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';"><?= htmlspecialchars($investigacion['objetivo_general'] ?? '') ?></textarea>
                </div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:var(--inv-primary-light);">
                        <i class="ph-fill ph-image" style="color:var(--inv-primary); font-size:1.2rem;"></i>
                    </span>
                    3. Recursos Multimedia
                </h3>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Imagen de Portada (Archivo Local)</label>
                    <?php $tiene_img = !empty($investigacion['imagen']) && strpos($investigacion['imagen'], 'http') !== 0; ?>
                    <label style="display:block; border:2px dashed var(--inv-border); border-radius:12px; padding:2rem; text-align:center; cursor:pointer; background:#F8FAFC; transition:all 0.2s; position:relative; overflow:hidden; min-height: 200px; display:flex; align-items:center; justify-content:center;" onmouseover="this.style.borderColor='var(--inv-primary)';" onmouseout="this.style.borderColor='var(--inv-border)';">
                        
                        <!-- Imagen previsualizada -->
                        <img id="img-preview" src="<?= $tiene_img ? htmlspecialchars($investigacion['imagen']) : '' ?>" style="display:<?= $tiene_img ? 'block' : 'none' ?>; width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0; z-index:1; opacity:0.3;">
                        
                        <!-- Contenido -->
                        <div id="upload-content" style="position:relative; z-index:2; <?= $tiene_img ? 'background:rgba(255,255,255,0.85); padding:1rem 2rem; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);' : '' ?>">
                            <i class="ph-fill ph-upload-simple" style="font-size:2.5rem; color:var(--inv-muted); margin-bottom:1rem; display:block;"></i>
                            <strong style="color:var(--inv-primary);">Seleccionar Imagen</strong>
                            <div style="font-size:0.82rem; color:var(--inv-muted); margin-top:0.5rem;">.jpg &bull; .jpeg &bull; .png &bull; .webp</div>
                            <div style="font-size:0.75rem; color:var(--inv-muted); margin-top:0.5rem; font-style:italic;">El sistema verifica el contenido real (MIME) del archivo.</div>
                            <div id="file-name" style="margin-top:1rem; font-weight:600; color:var(--inv-dark);"></div>
                        </div>

                        <input type="file" id="imagen-input" name="imagen" accept="image/jpeg,image/png,image/webp" style="display:none;">
                    </label>
                </div>

                <script>
                    document.getElementById('imagen-input').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            document.getElementById('file-name').textContent = file.name;
                            const reader = new FileReader();
                            reader.onload = function(evt) {
                                const preview = document.getElementById('img-preview');
                                preview.src = evt.target.result;
                                preview.style.display = 'block';
                                
                                const content = document.getElementById('upload-content');
                                content.style.background = 'rgba(255,255,255,0.85)';
                                content.style.padding = '1rem 2rem';
                                content.style.borderRadius = '12px';
                                content.style.boxShadow = '0 4px 15px rgba(0,0,0,0.05)';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>

                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">O Enlace Web (URL Alternativa)</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-link" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <input type="url" name="imagen_url" placeholder="https://ejemplo.com/imagen.jpg" value="<?= (strpos($investigacion['imagen'] ?? '', 'http') === 0) ? htmlspecialchars($investigacion['imagen']) : '' ?>" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                    </div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN 4: METADATOS TÉCNICOS -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:var(--inv-primary-light);">
                    <i class="ph-fill ph-sliders" style="color:var(--inv-primary); font-size:1.2rem;"></i>
                </span>
                4. Parámetros Académicos
            </h3>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Trayecto / Nivel Académico Objetivo</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-graduation-cap" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <?php
                        $catTrayectosInv = [];
                        $perfilPstPath = __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';
                        if (file_exists($perfilPstPath)) {
                            require_once $perfilPstPath;
                            $catTrayectosInv = PerfilPSTService::catalogoTrayectosDetallado();
                        }
                        $valTrayActual = (string)($investigacion['trayecto'] ?? 't4');
                        ?>
                        <select name="trayecto" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                            <?php if (!empty($catTrayectosInv)): ?>
                                <?php foreach ($catTrayectosInv as $numT => $infoT): 
                                    $codInv = (string)($infoT['codigo_inv'] ?? ('t' . $numT));
                                ?>
                                    <option value="<?= htmlspecialchars($codInv) ?>" <?= ($valTrayActual === $codInv || $valTrayActual === (string)$infoT['nombre']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($infoT['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="t1" <?= ($valTrayActual == 't1') ? 'selected' : '' ?>>Trayecto I (T1)</option>
                                <option value="t2" <?= ($valTrayActual == 't2') ? 'selected' : '' ?>>Trayecto II (T2)</option>
                                <option value="t3" <?= ($valTrayActual == 't3') ? 'selected' : '' ?>>Trayecto III (T3)</option>
                                <option value="t4" <?= ($valTrayActual == 't4') ? 'selected' : '' ?>>Trayecto IV (T4)</option>
                                <option value="maestria" <?= ($valTrayActual == 'maestria') ? 'selected' : '' ?>>Maestría</option>
                            <?php endif; ?>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); pointer-events:none;"></i>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Cupos Disponibles</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-users-three" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <input type="number" name="cupos_disponibles" min="1" max="10" value="<?= $investigacion['cupos_disponibles'] ?? 3 ?>" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(80,89,132,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; padding-top:1rem; padding-bottom:3rem; gap:1rem;">
            <a href="?ruta=mis-investigaciones" style="background: white; color: var(--inv-dark); border: 1px solid var(--inv-border); padding: 1rem 2rem; border-radius: 50px; font-weight: 700; font-size: 1.15rem; text-decoration:none; transition: background 0.2s;" onmouseover="this.style.background='#F1F5F9';" onmouseout="this.style.background='white';">Cancelar</a>
            
            <button type="submit" style="
                background: #505984; 
                color: white; 
                border: none; 
                padding: 1rem 2.5rem; 
                border-radius: 50px; 
                font-weight: 700; 
                font-size: 1.15rem; 
                display:flex; 
                align-items:center; 
                gap: 0.8rem;
                box-shadow: 0 4px 15px rgba(80, 89, 132, 0.25);
                cursor: pointer;
                transition: all 0.2s;"
                onmouseover="this.style.background='#3C456A'; this.style.transform='translateY(-2px)';"
                onmouseout="this.style.background='#505984'; this.style.transform='translateY(0)';">
                <i class="ph-bold ph-floppy-disk" style="font-size: 1.4rem;"></i> <?= $esEdicion ? 'Guardar Cambios' : 'Crear Proyecto' ?>
            </button>
        </div>
    </form>
</div>

<script>
// Filtrar dimensiones basado en las líneas seleccionadas (UX mejorada con Pills)
document.addEventListener('DOMContentLoaded', () => {
    const lineaCbs = document.querySelectorAll('.linea-cb');
    const dimPills = document.querySelectorAll('.dim-pill');
    const noDimsMsg = document.getElementById('no-dims-msg');

    function updateDimensiones() {
        const selectedLineas = Array.from(lineaCbs)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        let visibleCount = 0;

        dimPills.forEach(pill => {
            const idLinea = pill.getAttribute('data-linea');
            if (selectedLineas.includes(idLinea)) {
                pill.style.display = 'inline-block';
                visibleCount++;
            } else {
                pill.style.display = 'none';
                pill.querySelector('input').checked = false;
            }
        });

        if (noDimsMsg) {
            if (selectedLineas.length === 0) {
                noDimsMsg.textContent = 'Seleccione al menos una línea de investigación.';
                noDimsMsg.style.display = 'block';
            } else if (visibleCount === 0) {
                noDimsMsg.textContent = 'No hay dimensiones configuradas para las líneas seleccionadas.';
                noDimsMsg.style.display = 'block';
            } else {
                noDimsMsg.style.display = 'none';
            }
        }
    }

    lineaCbs.forEach(cb => cb.addEventListener('change', updateDimensiones));
    // Inicializar estado al cargar (importante para edición)
    updateDimensiones();
});

function mostrarModalSistema(tipo, titulo, mensaje, isConfirm = false, onConfirm = null) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
    
    let icon = tipo === 'success' ? '<i class="ph-bold ph-check-circle" style="color: #16a34a;"></i>' : '<i class="ph-bold ph-warning-circle" style="color: #dc2626;"></i>';
    let btnHtml = isConfirm 
        ? `<button type="button" class="btn btn-secondary" onclick="this.closest('div').parentElement.parentElement.remove()" style="margin-right:0.5rem; background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer;">Cancelar</button>
           <button type="button" class="btn btn-primary" id="btn-confirm-modal" style="background:var(--inv-danger); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer; box-shadow:0 4px 10px rgba(239, 68, 68, 0.3);">Sí, eliminar</button>`
        : `<button type="button" class="btn btn-primary w-100 justify-center" onclick="this.closest('div').parentElement.parentElement.remove()" style="background:var(--inv-primary); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:700; cursor:pointer; width:100%;">Entendido</button>`;

    if (isConfirm) {
        icon = '<i class="ph-bold ph-trash" style="color: #dc2626;"></i>';
    }

    overlay.innerHTML = `
        <div style="background: white; padding: 2.5rem 2rem; border-radius: 16px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="font-size: 3.5rem; margin-bottom: 1rem;">${icon}</div>
            <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size:1.4rem; font-weight:800;">${titulo}</h3>
            <p style="color: #475569; font-size: 0.95rem; margin-bottom: 2rem; line-height:1.5;">${mensaje}</p>
            <div style="display:flex; justify-content:center;">${btnHtml}</div>
        </div>
    `;
    
    document.body.appendChild(overlay);

    if (isConfirm && onConfirm) {
        document.getElementById('btn-confirm-modal').addEventListener('click', () => {
            overlay.remove();
            onConfirm();
        });
    }
}

function confirmarEliminacion(idInvestigacion) {
    mostrarModalSistema(
        'warning', 
        'Eliminar Proyecto', 
        '¿Está seguro de eliminar esta investigación? Se borrará el proyecto y todas sus postulaciones permanentemente.', 
        true, 
        () => document.getElementById('form-delete-' + idInvestigacion).submit()
    );
}
</script>
<script>
<?php if (isset($_SESSION['flash_success'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        mostrarModalSistema('success', 'Operación Exitosa', '<?= htmlspecialchars($_SESSION['flash_success']) ?>');
    });
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    document.addEventListener("DOMContentLoaded", function() {
        mostrarModalSistema('error', 'Ocurrió un Problema', '<?= htmlspecialchars($_SESSION['flash_error']) ?>');
    });
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
</script>
