<?php
// Vista de Configuración del Módulo

$configData = $controller->configuracion();
?>
<style>
.vc-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
}
.vc-title {
    color: #121a3e;
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.vc-subtitle {
    color: #64748b;
    margin: 0;
    font-size: 0.95rem;
}
.vc-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 2rem 2rem 2rem;
}
.vc-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.vc-card-title {
    color: #1e293b;
    font-size: 1.2rem;
    margin: 0 0 1.5rem 0;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.vc-form-group {
    margin-bottom: 1.5rem;
}
.vc-form-group label {
    display: block;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
}
.vc-form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 1rem;
    color: #1e293b;
    transition: border-color 0.2s;
}
.vc-form-control:focus {
    border-color: #7090cb;
    outline: none;
}
.vc-switch-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.vc-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
.vc-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.vc-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .4s;
    border-radius: 24px;
}
.vc-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .vc-slider {
    background-color: #10b981;
}
input:checked + .vc-slider:before {
    transform: translateX(26px);
}
.vc-help-text {
    font-size: 0.85rem;
    color: #64748b;
    margin-top: 5px;
    display: block;
}
.vc-btn-submit {
    background: #505984;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.vc-btn-submit:hover {
    background: #3c456a;
}
</style>

<?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['flash_success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?php echo addslashes($_SESSION['flash_success']); ?>',
                    confirmButtonColor: '#10b981'
                });
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash_error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo addslashes($_SESSION['flash_error']); ?>',
                    confirmButtonColor: '#e11d48'
                });
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>
        });
    </script>
<?php endif; ?>

<div class="vc-header">
    <h2 class="vc-title"><i class="ph-bold ph-gear"></i> Configuración del Módulo</h2>
    <p class="vc-subtitle">Ajusta los parámetros operativos de Vinculación Empresarial y Bancos de Proyectos.</p>
</div>

<div class="vc-container">
    <form action="?ruta=guardar-configuracion" method="POST">
        
        <div class="vc-card">
            <h3 class="vc-card-title"><i class="ph-bold ph-clock-countdown"></i> Ciclo de Recepción</h3>
            
            <div class="vc-form-group">
                <label>Recepción de Propuestas Empresariales</label>
                <div class="vc-switch-wrap">
                    <label class="vc-switch">
                        <input type="checkbox" name="recepcion_activa" value="1" <?php echo !empty($configData['recepcion_activa']) ? 'checked' : ''; ?>>
                        <span class="vc-slider"></span>
                    </label>
                    <span style="font-weight: bold; color: <?php echo !empty($configData['recepcion_activa']) ? '#10b981' : '#ef4444'; ?>;">
                        <?php echo !empty($configData['recepcion_activa']) ? 'ABIERTA' : 'CERRADA'; ?>
                    </span>
                </div>
                <span class="vc-help-text">Apaga este interruptor cuando culmine el período académico y ya no desees que las empresas envíen nuevas postulaciones de requerimientos tecnológicos.</span>
            </div>
        </div>

        <div class="vc-card">
            <h3 class="vc-card-title"><i class="ph-bold ph-list-numbers"></i> Paginación y Visualización</h3>
            
            <div style="display:flex; gap: 2rem;">
                <div class="vc-form-group" style="flex:1;">
                    <label>Paginación de Gestión Interna</label>
                    <input type="number" class="vc-form-control" name="paginacion_gestion" value="<?php echo htmlspecialchars($configData['paginacion_gestion'] ?? 8); ?>" min="1" max="100" required>
                    <span class="vc-help-text">Número de filas visibles por página en "Gestión de Solicitudes".</span>
                </div>
                <div class="vc-form-group" style="flex:1;">
                    <label>Paginación en la Cartelera</label>
                    <input type="number" class="vc-form-control" name="paginacion_cartelera" value="<?php echo htmlspecialchars($configData['paginacion_cartelera'] ?? 12); ?>" min="1" max="100" required>
                    <span class="vc-help-text">Número de proyectos visibles por página en la Cartelera Pública de Estudiantes.</span>
                </div>
            </div>
        </div>

        <div class="vc-card">
            <h3 class="vc-card-title"><i class="ph-bold ph-megaphone"></i> Textos Públicos</h3>
            
            <div class="vc-form-group">
                <label>Mensaje Destacado (Cintilla Informativa)</label>
                <input type="text" class="vc-form-control" name="mensaje_marquee" value="<?php echo htmlspecialchars($configData['mensaje_marquee'] ?? 'Plataforma Oficial para la Vinculación de Estudiantes con Proyectos Reales de la Industria'); ?>" required>
                <span class="vc-help-text">Texto dinámico que corre de derecha a izquierda en la sección principal del Landing (Conócenos).</span>
            </div>
        </div>

        <div class="vc-card" id="formBuilderContainer">
            <h3 class="vc-card-title">
                <span><i class="ph-bold ph-textbox"></i> Campos del Formulario (Postula tu Requerimiento)</span>
                <button type="button" id="btn-add-field" class="vc-btn-submit" style="margin-left: auto; padding: 0.4rem 1rem; font-size: 0.9rem;">
                    <i class="ph-bold ph-plus"></i> Añadir Campo
                </button>
            </h3>
            
            <?php
            require_once __DIR__ . '/../services/FormularioPropuestaService.php';
            require_once __DIR__ . '/../../Autenticacion/services/PerfilPSTService.php';
            $camposActuales = FormularioPropuestaService::getCampos();
            $tiposValidos = FormularioPropuestaService::TIPOS;
            $trayectosActuales = array_values(PerfilPSTService::catalogoTrayectosDetallado());
            ?>
            <input type="hidden" name="campos_formulario_json" id="campos_formulario_json" value="">
            
            <div id="fb-list" style="display:flex; flex-direction:column; gap:1rem;"></div>
            <span class="vc-help-text" style="margin-top: 1rem;">Arrastre desde la cabecera del campo para reordenar. Los campos marcados como "Sistema" no se pueden eliminar.</span>
        </div>

        <div class="vc-card" id="trayectosBuilderContainer">
            <h3 class="vc-card-title" style="flex-wrap: wrap; gap: 0.75rem;">
                <span><i class="ph-bold ph-graduation-cap"></i> Trayectos y Niveles Académicos (Asignación Docente y Proyectos)</span>
                <button type="button" id="btn-add-trayecto" class="vc-btn-submit" style="margin-left: auto; padding: 0.4rem 1rem; font-size: 0.9rem;">
                    <i class="ph-bold ph-plus"></i> Añadir Trayecto / Nivel
                </button>
            </h3>

            <p style="font-size: 0.88rem; color: #475569; margin: 0 0 0.85rem 0;">
                Configure los trayectos y niveles académicos disponibles cuando los docentes o investigadores se asignan trayectos desde su <strong>Perfil</strong>, en la acreditación PST y en la asignación de proyectos. Puede agregar opciones como <strong>Trayecto V, Especialidad, Maestría o Doctorado</strong>:
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; align-items: center;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #505984;">Atajos rápidos:</span>
                <button type="button" class="btn-quick-trayecto" data-nombre="Trayecto V" data-desc="Quinto año / Prosecución e innovación tecnológica" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#121a3e; border-radius:20px; padding:4px 12px; font-size:0.8rem; font-weight:600; cursor:pointer;">+ Trayecto V</button>
                <button type="button" class="btn-quick-trayecto" data-nombre="Especialidad" data-desc="Postgrado / Especialización técnica y profesional" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#121a3e; border-radius:20px; padding:4px 12px; font-size:0.8rem; font-weight:600; cursor:pointer;">+ Especialidad</button>
                <button type="button" class="btn-quick-trayecto" data-nombre="Maestría" data-desc="Postgrado / Investigación avanzada de maestría" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#121a3e; border-radius:20px; padding:4px 12px; font-size:0.8rem; font-weight:600; cursor:pointer;">+ Maestría</button>
                <button type="button" class="btn-quick-trayecto" data-nombre="Doctorado" data-desc="Postgrado / Tesis doctoral y generación de conocimiento" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#121a3e; border-radius:20px; padding:4px 12px; font-size:0.8rem; font-weight:600; cursor:pointer;">+ Doctorado</button>
            </div>

            <input type="hidden" name="catalogo_trayectos_json" id="catalogo_trayectos_json" value="">
            <div id="tb-list" style="display:flex; flex-direction:column; gap:0.85rem;"></div>
            <span class="vc-help-text" style="margin-top: 0.85rem;">Los Trayectos I al IV son base del sistema. Cualquier nivel adicional creado aquí aparecerá automáticamente en el Perfil Docente/Investigador, Acreditación PST y Gestión de Proyectos.</span>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
            <button type="submit" class="vc-btn-submit" id="btn-save-all"><i class="ph-bold ph-floppy-disk"></i> Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const initialFields = <?php echo json_encode($camposActuales); ?>;
    const tipos = <?php echo json_encode($tiposValidos); ?>;
    const fbList = document.getElementById('fb-list');
    const jsonInput = document.getElementById('campos_formulario_json');
    const form = fbList.closest('form');
    
    let fields = JSON.parse(JSON.stringify(initialFields));
    let draggedIndex = null;

    function renderFields() {
        fbList.innerHTML = '';
        fields.forEach((field, index) => {
            const isSistema = field.sistema;
            
            const item = document.createElement('div');
            item.className = 'fb-item';
            item.style.cssText = 'border:1px solid #cbd5e1; border-radius:6px; background:#f8fafc; padding:1rem; position:relative;';
            item.draggable = true;
            
            // Reorder Handlers
            item.addEventListener('dragstart', e => {
                draggedIndex = index;
                e.dataTransfer.effectAllowed = 'move';
                setTimeout(() => item.style.opacity = '0.5', 0);
            });
            item.addEventListener('dragend', () => item.style.opacity = '1');
            item.addEventListener('dragover', e => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                item.style.borderTop = '2px solid #7090cb';
            });
            item.addEventListener('dragleave', () => item.style.borderTop = '');
            item.addEventListener('drop', e => {
                e.preventDefault();
                item.style.borderTop = '';
                if (draggedIndex === null || draggedIndex === index) return;
                const moved = fields.splice(draggedIndex, 1)[0];
                fields.splice(index, 0, moved);
                renderFields();
            });

            // Header (Drag handle + Delete)
            const header = document.createElement('div');
            header.style.cssText = 'display:flex; justify-content:space-between; margin-bottom:1rem; cursor:move;';
            const badgeStr = isSistema ? '<span style="background:#121a3e; color:#fff; padding:2px 6px; border-radius:4px; font-size:0.75rem;">SISTEMA</span>' : '';
            header.innerHTML = `<strong><i class="ph-bold ph-dots-six-vertical"></i> Campo ${index + 1} ${badgeStr}</strong>`;
            
            if (!isSistema) {
                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.innerHTML = '<i class="ph-bold ph-trash"></i>';
                delBtn.style.cssText = 'background:none; border:none; color:#e11d48; cursor:pointer; font-size:1.1rem;';
                delBtn.onclick = () => { fields.splice(index, 1); renderFields(); };
                header.appendChild(delBtn);
            }
            item.appendChild(header);

            // Grid for properties
            const grid = document.createElement('div');
            grid.style.cssText = 'display:grid; grid-template-columns: 1fr 1fr; gap:1rem;';
            
            // Etiqueta
            const divEtiqueta = document.createElement('div');
            divEtiqueta.innerHTML = '<label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">Etiqueta (Título)</label>';
            const inpEtiqueta = document.createElement('input');
            inpEtiqueta.type = 'text';
            inpEtiqueta.className = 'vc-form-control';
            inpEtiqueta.value = field.etiqueta;
            inpEtiqueta.oninput = e => field.etiqueta = e.target.value;
            divEtiqueta.appendChild(inpEtiqueta);
            grid.appendChild(divEtiqueta);

            // Tipo
            const divTipo = document.createElement('div');
            divTipo.innerHTML = '<label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">Tipo de Campo</label>';
            const selTipo = document.createElement('select');
            selTipo.className = 'vc-form-control';
            selTipo.disabled = isSistema;
            for (const [k, v] of Object.entries(tipos)) {
                const opt = document.createElement('option');
                opt.value = k;
                opt.textContent = v;
                if (field.tipo === k) opt.selected = true;
                selTipo.appendChild(opt);
            }
            selTipo.onchange = e => field.tipo = e.target.value;
            divTipo.appendChild(selTipo);
            grid.appendChild(divTipo);

            // Placeholder
            const divPh = document.createElement('div');
            divPh.innerHTML = '<label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">Placeholder / Ejemplo</label>';
            const inpPh = document.createElement('input');
            inpPh.type = 'text';
            inpPh.className = 'vc-form-control';
            inpPh.value = field.placeholder || '';
            inpPh.oninput = e => field.placeholder = e.target.value;
            divPh.appendChild(inpPh);
            grid.appendChild(divPh);

            // Requerido y Ancho
            const divOpts = document.createElement('div');
            divOpts.style.cssText = 'display:flex; gap:1rem; align-items:flex-end;';
            
            const lblReq = document.createElement('label');
            lblReq.style.cssText = 'display:flex; align-items:center; gap:6px; font-size:0.9rem; font-weight:600; cursor:pointer;';
            const chkReq = document.createElement('input');
            chkReq.type = 'checkbox';
            chkReq.checked = field.requerido;
            chkReq.onchange = e => field.requerido = e.target.checked;
            lblReq.appendChild(chkReq);
            lblReq.appendChild(document.createTextNode('¿Obligatorio?'));
            divOpts.appendChild(lblReq);

            const divAncho = document.createElement('div');
            divAncho.innerHTML = '<label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">Ancho</label>';
            const selAncho = document.createElement('select');
            selAncho.className = 'vc-form-control';
            selAncho.style.padding = '0.3rem';
            selAncho.innerHTML = '<option value="completo">Completo</option><option value="medio">Medio</option>';
            selAncho.value = field.ancho || 'completo';
            selAncho.onchange = e => field.ancho = e.target.value;
            divAncho.appendChild(selAncho);
            divOpts.appendChild(divAncho);

            grid.appendChild(divOpts);

            item.appendChild(grid);
            fbList.appendChild(item);
        });
    }

    document.getElementById('btn-add-field').addEventListener('click', () => {
        fields.push({
            clave: '',
            tipo: 'texto',
            etiqueta: 'Nuevo Campo',
            placeholder: '',
            requerido: false,
            sistema: false,
            ancho: 'completo'
        });
        renderFields();
        setTimeout(() => fbList.lastElementChild.scrollIntoView({behavior:'smooth'}), 100);
    });

    // --- GESTOR DE TRAYECTOS Y NIVELES ACADÉMICOS ---
    const initialTrayectos = <?php echo json_encode($trayectosActuales); ?>;
    const tbList = document.getElementById('tb-list');
    const trayectosJsonInput = document.getElementById('catalogo_trayectos_json');
    let trayectos = JSON.parse(JSON.stringify(initialTrayectos));

    function nextTrayectoNumero() {
        let maxN = 4;
        trayectos.forEach(t => {
            const n = parseInt(t.numero, 10) || 0;
            if (n > maxN) maxN = n;
        });
        return maxN + 1;
    }

    function renderTrayectos() {
        if (!tbList) return;
        tbList.innerHTML = '';
        trayectos.forEach((tr, idx) => {
            const isSistema = !!tr.sistema || (parseInt(tr.numero, 10) >= 1 && parseInt(tr.numero, 10) <= 4);
            const row = document.createElement('div');
            row.style.cssText = 'border:1px solid #cbd5e1; border-radius:6px; background:#f8fafc; padding:0.85rem 1rem;';

            const head = document.createElement('div');
            head.style.cssText = 'display:flex; justify-content:space-between; align-items:center; margin-bottom:0.65rem;';
            const badge = isSistema
                ? '<span style="background:#121a3e; color:#fff; padding:2px 8px; border-radius:4px; font-size:0.72rem; margin-left:6px;">SISTEMA</span>'
                : '<span style="background:#505984; color:#fff; padding:2px 8px; border-radius:4px; font-size:0.72rem; margin-left:6px;">PERSONALIZADO</span>';
            head.innerHTML = `<strong><i class="ph-bold ph-graduation-cap"></i> Nivel #${tr.numero || (idx + 1)} ${badge}</strong>`;

            if (!isSistema) {
                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.title = 'Eliminar este trayecto/nivel';
                delBtn.innerHTML = '<i class="ph-bold ph-trash"></i> Eliminar';
                delBtn.style.cssText = 'background:#fff1f2; border:1px solid #fecdd3; color:#e11d48; border-radius:6px; padding:3px 10px; cursor:pointer; font-size:0.8rem; font-weight:600;';
                delBtn.onclick = () => { trayectos.splice(idx, 1); renderTrayectos(); };
                head.appendChild(delBtn);
            }
            row.appendChild(head);

            const grid = document.createElement('div');
            grid.style.cssText = 'display:grid; grid-template-columns: 1fr 1.6fr; gap:0.85rem;';

            const colNom = document.createElement('div');
            colNom.innerHTML = '<label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:4px;">Nombre del Trayecto / Especialidad / Postgrado</label>';
            const inpNom = document.createElement('input');
            inpNom.type = 'text';
            inpNom.className = 'vc-form-control';
            inpNom.placeholder = 'Ej: Trayecto V, Especialidad, Maestría, Doctorado';
            inpNom.value = tr.nombre || '';
            inpNom.oninput = e => tr.nombre = e.target.value;
            colNom.appendChild(inpNom);

            const colDesc = document.createElement('div');
            colDesc.innerHTML = '<label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:4px;">Descripción Académica</label>';
            const inpDesc = document.createElement('input');
            inpDesc.type = 'text';
            inpDesc.className = 'vc-form-control';
            inpDesc.placeholder = 'Ej: Postgrado / Investigación avanzada';
            inpDesc.value = tr.descripcion || '';
            inpDesc.oninput = e => tr.descripcion = e.target.value;
            colDesc.appendChild(inpDesc);

            grid.appendChild(colNom);
            grid.appendChild(colDesc);
            row.appendChild(grid);
            tbList.appendChild(row);
        });
    }

    function addTrayectoItem(nombreDefault = '', descDefault = '') {
        if (nombreDefault) {
            const yaExiste = trayectos.some(t => (t.nombre || '').trim().toLowerCase() === nombreDefault.trim().toLowerCase());
            if (yaExiste) return;
        }
        trayectos.push({
            numero: nextTrayectoNumero(),
            nombre: nombreDefault || 'Nuevo Trayecto / Nivel',
            descripcion: descDefault || 'Nivel académico o postgrado habilitado',
            sistema: false
        });
        renderTrayectos();
        setTimeout(() => tbList.lastElementChild?.scrollIntoView({behavior:'smooth', block:'nearest'}), 80);
    }

    document.getElementById('btn-add-trayecto')?.addEventListener('click', () => addTrayectoItem('', ''));
    document.querySelectorAll('.btn-quick-trayecto').forEach(btn => {
        btn.addEventListener('click', () => {
            addTrayectoItem(btn.getAttribute('data-nombre') || '', btn.getAttribute('data-desc') || '');
        });
    });

    form.addEventListener('submit', () => {
        jsonInput.value = JSON.stringify(fields);
        if (trayectosJsonInput) {
            trayectosJsonInput.value = JSON.stringify(trayectos);
        }
    });

    renderFields();
    renderTrayectos();
});
</script>
