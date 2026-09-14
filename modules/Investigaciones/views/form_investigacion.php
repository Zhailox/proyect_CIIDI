<?php
// modules/Investigaciones/views/form_investigacion.php
// Variables: $investigacion (array), $lineas (array), $dimensiones (array - opcional)
$esEdicion = !empty($investigacion['id']);
?>
<div class="inv-wrapper" style="max-width: 1200px; margin: 0 auto;">

    <div style="margin-bottom: 2rem;">
        <a href="?ruta=mis-investigaciones" style="color:var(--inv-muted); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; font-weight:600; font-size:0.9rem; margin-bottom:1rem; transition:color 0.2s;" onmouseover="this.style.color='var(--inv-primary)';" onmouseout="this.style.color='var(--inv-muted)';"><i class="ph-bold ph-arrow-left"></i> Volver a Mis Proyectos</a>
        
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--inv-dark); margin-bottom: 0.5rem; display:flex; align-items:center; gap:0.8rem;">
            <div style="width:50px; height:50px; border-radius:12px; background: linear-gradient(135deg, #0EA5E9 0%, #0369A1 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:1.6rem; box-shadow:0 10px 20px rgba(14, 165, 233, 0.3);">
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
            <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-info" style="color:var(--inv-primary);"></i> 1. Información Principal</h3>
            
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Título del Proyecto <span style="color:var(--inv-danger);">*</span></label>
                <div style="position:relative;">
                    <i class="ph-bold ph-text-aa" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($investigacion['titulo'] ?? '') ?>" placeholder="Ej: Sistema de control de inventario basado en RFID" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Línea de Investigación <span style="color:var(--inv-danger);">*</span></label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-git-branch" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <select name="id_linea" required style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                            <option value="">Seleccione una línea</option>
                            <?php foreach ($lineas as $l): ?>
                                <option value="<?= $l['id'] ?>" <?= (($investigacion['id_linea']??0) == $l['id']) ? 'selected' : '' ?>><?= htmlspecialchars($l['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); pointer-events:none;"></i>
                    </div>
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Estado Inicial</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-pulse" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <select name="estado" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
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
                <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-article" style="color:var(--inv-primary);"></i> 2. Planteamiento y Objetivos</h3>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Planteamiento del Problema / Resumen <span style="color:var(--inv-danger);">*</span></label>
                    <textarea name="planteamiento_problema" required rows="4" placeholder="Describe brevemente el problema a resolver..." style="width:100%; padding:1rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';"><?= htmlspecialchars($investigacion['planteamiento_problema'] ?? '') ?></textarea>
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Objetivo General / Meta</label>
                    <textarea name="objetivo_general" rows="2" placeholder="Objetivo general del proyecto..." style="width:100%; padding:1rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1rem; outline:none; font-family:inherit; resize:vertical; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';"><?= htmlspecialchars($investigacion['objetivo_general'] ?? '') ?></textarea>
                </div>
            </div>

            <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04); display:flex; flex-direction:column;">
                <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-image" style="color:var(--inv-primary);"></i> 3. Recursos Multimedia</h3>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Imagen de Portada (Archivo Local)</label>
                    <label style="display:block; border:2px dashed var(--inv-border); border-radius:12px; padding:2rem; text-align:center; cursor:pointer; background:#F8FAFC; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--inv-primary)';" onmouseout="this.style.borderColor='var(--inv-border)';">
                        <i class="ph-fill ph-upload-simple" style="font-size:2.5rem; color:var(--inv-muted); margin-bottom:1rem; display:block;"></i>
                        <strong style="color:var(--inv-primary);">Seleccionar Imagen</strong>
                        <div style="font-size:0.85rem; color:var(--inv-muted); margin-top:0.5rem;">JPG, PNG, WebP</div>
                        <input type="file" name="imagen" accept="image/*" style="display:none;" onchange="document.getElementById('file-name').textContent = this.files[0].name;">
                        <div id="file-name" style="margin-top:1rem; font-weight:600; color:var(--inv-dark);"></div>
                    </label>
                    <?php if (!empty($investigacion['imagen']) && strpos($investigacion['imagen'], 'http') !== 0): ?>
                        <div style="margin-top:1rem; padding:1rem; background:rgba(14, 165, 233, 0.1); border-radius:8px; display:flex; align-items:center; gap:1rem;">
                            <img src="<?= htmlspecialchars($investigacion['imagen']) ?>" alt="Actual" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            <span style="font-weight:600; color:#0369A1; font-size:0.9rem;">Imagen local cargada</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">O Enlace Web (URL Alternativa)</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-link" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <input type="url" name="imagen_url" placeholder="https://ejemplo.com/imagen.jpg" value="<?= (strpos($investigacion['imagen'] ?? '', 'http') === 0) ? htmlspecialchars($investigacion['imagen']) : '' ?>" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                    </div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN 3: METADATOS TÉCNICOS -->
        <div style="background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(80, 89, 132, 0.15); border-radius: 16px; padding: 2.5rem; box-shadow: 0 10px 30px rgba(18, 26, 62, 0.04);">
            <h3 style="font-weight:800; color:var(--inv-dark); font-size:1.3rem; margin-bottom:2rem; padding-bottom:1rem; border-bottom:2px solid #F1F5F9; display:flex; align-items:center; gap:0.5rem;"><i class="ph-fill ph-sliders" style="color:var(--inv-primary);"></i> 4. Parámetros Académicos</h3>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Trayecto Académico Objetivo</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-graduation-cap" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <select name="trayecto" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; appearance:none; cursor:pointer;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                            <option value="t1" <?= (($investigacion['trayecto'] ?? '') == 't1') ? 'selected' : '' ?>>Trayecto I (T1)</option>
                            <option value="t2" <?= (($investigacion['trayecto'] ?? '') == 't2') ? 'selected' : '' ?>>Trayecto II (T2)</option>
                            <option value="t3" <?= (($investigacion['trayecto'] ?? '') == 't3') ? 'selected' : '' ?>>Trayecto III (T3)</option>
                            <option value="t4" <?= (($investigacion['trayecto'] ?? '') == 't4') ? 'selected' : '' ?>>Trayecto IV (T4)</option>
                            <option value="maestria" <?= (($investigacion['trayecto'] ?? '') == 'maestria') ? 'selected' : '' ?>>Maestría</option>
                        </select>
                        <i class="ph-bold ph-caret-down" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted); pointer-events:none;"></i>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-weight:600; color:var(--inv-dark); margin-bottom:0.5rem;">Cupos Disponibles</label>
                    <div style="position:relative;">
                        <i class="ph-bold ph-users-three" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--inv-muted);"></i>
                        <input type="number" name="cupos_disponibles" min="1" max="10" value="<?= $investigacion['cupos_disponibles'] ?? 3 ?>" style="width:100%; padding:1rem 1rem 1rem 2.8rem; border:1px solid var(--inv-border); border-radius:12px; font-size:1.05rem; outline:none; transition:box-shadow 0.2s;" onfocus="this.style.boxShadow='0 0 0 4px rgba(14,165,233,0.15)'; this.style.borderColor='var(--inv-primary)';" onblur="this.style.boxShadow='none'; this.style.borderColor='var(--inv-border)';">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; padding-top:1rem; padding-bottom:3rem; gap:1rem;">
            <a href="?ruta=mis-investigaciones" style="background: white; color: var(--inv-dark); border: 1px solid var(--inv-border); padding: 1rem 2rem; border-radius: 50px; font-weight: 700; font-size: 1.15rem; text-decoration:none; transition: background 0.2s;" onmouseover="this.style.background='#F1F5F9';" onmouseout="this.style.background='white';">Cancelar</a>
            
            <button type="submit" style="
                background: linear-gradient(135deg, #0EA5E9 0%, #0369A1 100%); 
                color: white; 
                border: none; 
                padding: 1rem 2.5rem; 
                border-radius: 50px; 
                font-weight: 700; 
                font-size: 1.15rem; 
                display:flex; 
                align-items:center; 
                gap: 0.8rem;
                box-shadow: 0 10px 25px rgba(14, 165, 233, 0.4);
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
            " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(14, 165, 233, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(14, 165, 233, 0.4)';">
                <i class="ph-bold ph-floppy-disk" style="font-size: 1.4rem;"></i> <?= $esEdicion ? 'Guardar Cambios' : 'Crear Proyecto' ?>
            </button>
        </div>
    </form>
</div>

<script>
function mostrarModalSistema(tipo, titulo, mensaje, isConfirm = false, onConfirm = null) {
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
    
    let icon = tipo === 'success' ? '<i class="ph-bold ph-check-circle" style="color: #16a34a;"></i>' : '<i class="ph-bold ph-warning-circle" style="color: #dc2626;"></i>';
    let btnHtml = isConfirm 
        ? `<button type="button" class="btn btn-secondary" onclick="this.closest('div').parentElement.parentElement.remove()" style="margin-right:0.5rem; background:white; color:var(--inv-dark); border:1px solid var(--inv-border); padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer;">Cancelar</button>
           <button type="button" class="btn btn-primary" id="btn-confirm-modal" style="background:var(--color-danger, #dc2626); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:600; cursor:pointer; box-shadow:0 4px 10px rgba(220, 38, 38, 0.3);">Sí, eliminar</button>`
        : `<button type="button" class="btn btn-primary w-100 justify-center" onclick="this.closest('div').parentElement.parentElement.remove()" style="background:var(--color-secundario, #0b1a30); color:white; border:none; padding:0.8rem 1.5rem; border-radius:50px; font-weight:700; cursor:pointer; width:100%;">Entendido</button>`;

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