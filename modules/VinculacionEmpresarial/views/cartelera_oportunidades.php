<?php // Logica movida al Controlador ?>

<style>
/* Forzar la paleta azul para los botones y elementos Kanban de esta vista */
.post-wrapper {
    padding: 0 1.5rem;
    margin: 0 auto;
    max-width: 1800px;
    --color-principal: #121a3e;
}
.post-kanban-board {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;
    overflow-x: hidden !important;
    gap: 1.5rem !important;
}
.post-kanban-col {
    min-width: 0 !important; /* allows grid to squeeze it */
}
/* Estandarizar tarjetas */
.post-card {
    height: 380px !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    position: relative;
    overflow: hidden;
}
/* Contenedor de la descripción con fading */
.post-card-desc-container {
    flex-grow: 1;
    overflow: hidden;
    position: relative;
    background: #f8fafc; 
    padding: 12px; 
    border-radius: 6px; 
    border-left: 4px solid #505984; 
    margin-bottom: 15px;
}
.post-card-desc-container::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 40px;
    background: linear-gradient(transparent, #f8fafc);
}
.post-btn-apply {
    background-color: transparent !important;
    color: #121a3e !important;
    border: 2px solid #121a3e !important;
    border-radius: 4px !important;
    z-index: 2;
    position: relative;
}
.post-btn-apply:hover {
    background-color: #121a3e !important;
    color: #ffffff !important;
}
</style>

<div class="post-wrapper">

    <header class="post-hero">
        <div class="post-hero-content">
            <h1 style="text-transform: uppercase;">¿Preparado para transformar el sector productivo?</h1>
            <p>Aplica tus conocimientos académicos resolviendo retos tecnológicos de empresas reales. Conecta con la industria, adquiere experiencia profesional y desarrolla un Proyecto Socio-Tecnológico de alto impacto.</p>
        </div>
    </header>

    <?php if (empty($oportunidades)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 16px; border: 2px dashed #cbd5e1; max-width: 1200px; margin: 0 auto;">
            <i class="ph-fill ph-empty" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
            <h3 style="color: #475569;">No hay oportunidades empresariales en este momento</h3>
            <p style="color: #64748b;">Mantente atento, pronto las empresas publicarán nuevos retos tecnológicos.</p>
        </div>
    <?php else: ?>

    <div class="post-filter-container" style="display: flex; flex-direction: column; align-items: center; gap: 15px; margin-bottom: 2rem;">
        <div class="post-filter-pill-bar" id="lineaFilters" style="display: flex; flex-wrap: wrap; justify-content: center; width: fit-content; max-width: 100%; margin: 0 auto;">
            <button class="post-filter-btn active" onclick="filtrarLinea('Todas', this)">TODAS LAS LÍNEAS</button>
            <?php foreach ($lineasUnicas as $linea): ?>
                <button class="post-filter-btn" onclick="filtrarLinea('<?= htmlspecialchars($linea, ENT_QUOTES) ?>', this)"><?= mb_strtoupper(htmlspecialchars($linea)) ?></button>
            <?php endforeach; ?>
        </div>
        <div class="post-filter-pill-bar" id="cuposFilters" style="display: flex; flex-wrap: wrap; justify-content: center; width: fit-content; max-width: 100%; margin: 0 auto; transform: scale(0.95);">
            <button class="post-filter-btn active" onclick="filtrarCupos('Todos', this)">CUALQUIER INTEGRANTE</button>
            <?php foreach ($cuposUnicos as $c): ?>
                <button class="post-filter-btn" onclick="filtrarCupos('<?= $c ?>', this)"><?= $c ?> INTEGRANTE<?= $c > 1 ? 'S' : '' ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="post-kanban-board">
        
        <?php foreach ($oportunidadesPorTrayecto as $trayecto => $ops): 
            $dataNivel = getAttrNivel($trayecto);
            
            // Compensamos colores faltantes en Investigaciones.css
            $extraStyleCol = '';
            $extraStyleText = '';
            if ($dataNivel === 't1') {
                $extraStyleCol = 'border-top-color: #7090cb;';
                $extraStyleText = 'color: #7090cb;';
            } elseif ($dataNivel === 't2') {
                $extraStyleCol = 'border-top-color: #505984;';
                $extraStyleText = 'color: #505984;';
            }
        ?>
        <div class="post-kanban-col" data-nivel="<?= $dataNivel ?>" style="<?= $extraStyleCol ?>">
            <div class="post-col-header">
                <h3 style="<?= $extraStyleText ?>"><?= mb_strtoupper(htmlspecialchars($trayecto)) ?></h3>
                <span class="col-count">Oportunidades PST: <span class="count-value"><?= count($ops) ?></span></span>
            </div>
            
            <?php foreach ($ops as $op): 
                $lineaSegura = htmlspecialchars($op['linea_investigacion'] ?: 'Sin Línea Asignada', ENT_QUOTES);
                $cupos = $op['cupos_disponibles'] ?? 3;
                $empresaSegura = htmlspecialchars(addslashes($op['nombre_empresa']));
                $problemaLimpio = htmlspecialchars(addslashes(preg_replace('/\s+/', ' ', trim($op['descripcion_problema']))));
                $areaLimpia = mb_strtoupper(htmlspecialchars(addslashes($op['area_afectada'])));
            ?>
            <div class="post-card" data-linea="<?= $lineaSegura ?>" data-cupos="<?= $cupos ?>" onclick="verDetalles(<?= $op['id_investigacion'] ?>, '<?= $empresaSegura ?>', <?= $cupos ?>, '<?= $lineaSegura ?>', '<?= $areaLimpia ?>', '<?= $problemaLimpio ?>')">
                <span class="post-card-linea">LÍNEA: <?= mb_strtoupper(htmlspecialchars($op['linea_investigacion'] ?: 'N/A')) ?></span>
                
                <h4 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 0.5rem;"><i class="ph-bold ph-lightbulb"></i> Área: <?= mb_strtoupper(htmlspecialchars($op['area_afectada'])) ?></h4>
                
                <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                    <strong><i class="ph-bold ph-buildings" style="margin-right:4px;"></i> Empresa:</strong> <?= htmlspecialchars($op['nombre_empresa']) ?>
                </div>

                <div class="post-card-desc-container">
                    <strong style="color: #475569; font-size: 0.8rem; display: block; margin-bottom: 5px; text-transform: uppercase;"><i class="ph-bold ph-warning-circle"></i> Problemática a Resolver:</strong>
                    <p style="margin: 0; color: #334155; font-size: 0.9rem; line-height: 1.5;">
                        <?= nl2br(htmlspecialchars($op['descripcion_problema'])) ?>
                    </p>
                </div>
                
                <div class="post-card-footer">
                    <span class="post-vacancies" style="color: var(--color-principal);">Máx. Integrantes: <?= $cupos ?></span>
                    <button class="post-btn-apply" onclick="event.stopPropagation(); verDetalles(<?= $op['id_investigacion'] ?>, '<?= $empresaSegura ?>', <?= $cupos ?>, '<?= $lineaSegura ?>', '<?= $areaLimpia ?>', '<?= $problemaLimpio ?>')">Ver y Postular</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['flash_success'])): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '<?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES) ?>',
        confirmButtonColor: '#121a3e'
    });
</script>
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Atención',
        text: '<?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES) ?>',
        confirmButtonColor: '#121a3e'
    });
</script>
<?php unset($_SESSION['flash_error']); endif; ?>

<script>
let currentLinea = 'Todas';
let currentCupos = 'Todos';

const itemsPerPage = 3;
let colPages = {};

function filtrarLinea(linea, btn) {
    const botones = document.querySelectorAll('#lineaFilters .post-filter-btn');
    botones.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentLinea = linea;
    aplicarFiltrosGlobales();
}

function filtrarCupos(cupos, btn) {
    const botones = document.querySelectorAll('#cuposFilters .post-filter-btn');
    botones.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentCupos = cupos;
    aplicarFiltrosGlobales();
}

function aplicarFiltrosGlobales() {
    const columnas = document.querySelectorAll('.post-kanban-col');
    
    columnas.forEach(col => {
        const nivel = col.getAttribute('data-nivel');
        let tarjetasVisibles = [];
        const tarjetas = col.querySelectorAll('.post-card');
        
        tarjetas.forEach(tarjeta => {
            const lineaTarjeta = tarjeta.getAttribute('data-linea');
            const cuposTarjeta = tarjeta.getAttribute('data-cupos');
            
            let matchLinea = (currentLinea === 'Todas' || lineaTarjeta === currentLinea);
            let matchCupos = (currentCupos === 'Todos' || cuposTarjeta === currentCupos);
            
            if (matchLinea && matchCupos) {
                tarjeta.classList.add('visible-card');
                tarjetasVisibles.push(tarjeta);
            } else {
                tarjeta.classList.remove('visible-card');
                tarjeta.style.display = 'none';
            }
        });
        
        if (tarjetasVisibles.length === 0) {
            col.style.display = 'none';
        } else {
            col.style.display = 'flex';
            col.querySelector('.count-value').textContent = tarjetasVisibles.length;
            
            colPages[nivel] = 1;
            renderPage(col, nivel, tarjetasVisibles);
        }
    });
}

function renderPage(col, nivel, tarjetasVisibles) {
    const page = colPages[nivel];
    const totalPages = Math.ceil(tarjetasVisibles.length / itemsPerPage);
    
    tarjetasVisibles.forEach(t => t.style.display = 'none');
    
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    for (let i = start; i < end && i < tarjetasVisibles.length; i++) {
        tarjetasVisibles[i].style.display = 'flex';
    }
    
    let paginationContainer = col.querySelector('.pagination-controls');
    if (!paginationContainer) {
        paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination-controls';
        paginationContainer.style = 'display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 15px; border-top: 1px solid #e2e8f0;';
        col.appendChild(paginationContainer);
    }
    
    paginationContainer.innerHTML = '';
    
    if (totalPages > 1) {
        const btnPrev = document.createElement('button');
        btnPrev.innerHTML = '<i class="ph-bold ph-caret-left"></i>';
        btnPrev.style = `border: none; background: ${page === 1 ? '#e2e8f0' : '#121a3e'}; color: ${page === 1 ? '#94a3b8' : 'white'}; border-radius: 4px; padding: 6px 12px; cursor: ${page === 1 ? 'not-allowed' : 'pointer'};`;
        btnPrev.disabled = (page === 1);
        btnPrev.onclick = () => { if(page > 1) { colPages[nivel]--; renderPage(col, nivel, tarjetasVisibles); } };
        
        const spanInfo = document.createElement('span');
        spanInfo.textContent = `Pág. ${page} de ${totalPages}`;
        spanInfo.style = 'font-size: 0.85rem; color: #64748b; font-weight: 600;';
        
        const btnNext = document.createElement('button');
        btnNext.innerHTML = '<i class="ph-bold ph-caret-right"></i>';
        btnNext.style = `border: none; background: ${page === totalPages ? '#e2e8f0' : '#121a3e'}; color: ${page === totalPages ? '#94a3b8' : 'white'}; border-radius: 4px; padding: 6px 12px; cursor: ${page === totalPages ? 'not-allowed' : 'pointer'};`;
        btnNext.disabled = (page === totalPages);
        btnNext.onclick = () => { if(page < totalPages) { colPages[nivel]++; renderPage(col, nivel, tarjetasVisibles); } };
        
        paginationContainer.appendChild(btnPrev);
        paginationContainer.appendChild(spanInfo);
        paginationContainer.appendChild(btnNext);
    }
}

function verDetalles(idInvestigacion, empresa, maxCupos, linea, area, problema) {
    Swal.fire({
        title: 'Detalles del Requerimiento',
        width: '700px',
        html: `
            <div style="text-align: left; font-size: 0.95rem; color: #334155;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                    <div><strong>Línea de Investigación:</strong><br> ${linea}</div>
                    <div><strong>Área Afectada:</strong><br> ${area}</div>
                    <div><strong>Empresa:</strong><br> ${empresa}</div>
                    <div><strong>Máx. Integrantes Permitidos:</strong><br> ${maxCupos}</div>
                </div>
                <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border-left: 4px solid #505984;">
                    <strong style="color: #475569; display: block; margin-bottom: 10px; text-transform: uppercase;"><i class="ph-bold ph-warning-circle"></i> Problemática a Resolver:</strong>
                    <p style="margin: 0; line-height: 1.6;">${problema}</p>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#121a3e',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Postular a mi equipo',
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            postularProyecto(idInvestigacion, empresa, maxCupos);
        }
    });
}

function postularProyecto(idInvestigacion, empresa, maxCupos) {
    let currentUser = {
        nombre: '<?= addslashes($userData['nombre_completo'] ?? '') ?>',
        cedula: '<?= addslashes($userData['cedula'] ?? '') ?>',
        email: '<?= addslashes($userData['email'] ?? '') ?>',
        telefono: '<?= addslashes($userData['telefono'] ?? '') ?>'
    };

    let maxCompañeros = maxCupos - 1; 
    let compañerosCount = 0;

    Swal.fire({
        title: 'Postular a Reto PST',
        width: '650px',
        html: `
            <div style="text-align:left; font-size:0.9rem;">
                <p style="margin-bottom:15px; color:#475569;">Aplicando al requerimiento de <b>${empresa}</b>. (Cupo Máximo: ${maxCupos} estudiantes)</p>
                
                <h4 style="margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:5px; color:#1e293b;">Datos del Líder del Proyecto</h4>
                <div style="display:flex; gap:10px; margin-bottom:10px;">
                    <div style="flex:1;">
                        <label style="display:block; font-size:0.8rem; color:#64748b; margin-bottom:2px;">Nombre Completo</label>
                        <input type="text" id="lider_nombre" style="width:100%; border:1px solid #cbd5e1; padding:8px; border-radius:6px; background:#fff; color:#0f172a;" value="${currentUser.nombre}">
                    </div>
                    <div style="width:140px;">
                        <label style="display:block; font-size:0.8rem; color:#64748b; margin-bottom:2px;">Cédula</label>
                        <input type="text" id="lider_cedula" style="width:100%; border:1px solid #cbd5e1; padding:8px; border-radius:6px; background:#fff; color:#0f172a;" value="${currentUser.cedula}">
                    </div>
                </div>
                <div style="display:flex; gap:10px; margin-bottom:15px;">
                    <div style="flex:1;">
                        <label style="display:block; font-size:0.8rem; color:#64748b; margin-bottom:2px;">Correo Electrónico</label>
                        <input type="email" id="lider_email" style="width:100%; border:1px solid #cbd5e1; padding:8px; border-radius:6px; background:#fff; color:#0f172a;" value="${currentUser.email}">
                    </div>
                    <div style="width:140px;">
                        <label style="display:block; font-size:0.8rem; color:#64748b; margin-bottom:2px;">Teléfono</label>
                        <input type="text" id="lider_telefono" style="width:100%; border:1px solid #cbd5e1; padding:8px; border-radius:6px; background:#fff; color:#0f172a;" value="${currentUser.telefono}">
                    </div>
                </div>
                
                <h4 style="margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:5px; color:#1e293b;">Razones por las cuales postulan (Opcional)</h4>
                <textarea id="motivacion" class="swal2-textarea" style="margin:0 0 15px 0; width:100%; font-size:0.9rem; padding:10px; border-radius:6px; border:1px solid #cbd5e1;" placeholder="¿Por qué tu equipo es ideal para este reto?" rows="2"></textarea>

                <h4 style="margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:5px; color:#1e293b; display:flex; justify-content:space-between; align-items:center;">
                    <span>Compañeros de Equipo (Opcional)</span>
                    <button type="button" id="btnAddCompañero" style="background:#121a3e; color:white; border:none; padding:4px 10px; border-radius:6px; cursor:pointer; font-size:0.8rem; box-shadow:0 2px 4px rgba(0,0,0,0.1);">+ Agregar Compañero</button>
                </h4>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0; margin-bottom:10px;">Puedes agregar hasta ${maxCompañeros} compañeros más.</p>
                <div id="compañerosContainer"></div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#121a3e',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Enviar Postulación Oficial',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            const btnAdd = document.getElementById('btnAddCompañero');
            const container = document.getElementById('compañerosContainer');
            
            if (maxCompañeros <= 0) {
                btnAdd.style.display = 'none';
            }
            
            btnAdd.addEventListener('click', () => {
                if (compañerosCount >= maxCompañeros) {
                    Swal.showValidationMessage('Se ha alcanzado el límite de integrantes establecido por el profesor.');
                    return;
                }
                Swal.resetValidationMessage();
                compañerosCount++;
                
                const div = document.createElement('div');
                div.style = "background:#f8fafc; padding:12px; border-radius:8px; margin-bottom:12px; border:1px dashed #cbd5e1; position:relative;";
                div.innerHTML = `
                    <button type="button" class="btn-remove" style="position:absolute; top:5px; right:10px; background:transparent; border:none; color:#a9a8a6; cursor:pointer; font-weight:bold; font-size:1rem;" title="Eliminar Compañero">✖</button>
                    <div style="display:flex; gap:10px; margin-bottom:10px; padding-right:20px;">
                        <div style="flex:1;">
                            <label style="display:block; font-size:0.75rem; color:#64748b; margin-bottom:2px;">Nombre Completo</label>
                            <input type="text" class="comp-nombre" style="width:100%; border:1px solid #cbd5e1; padding:6px; border-radius:4px; background:#fff;" required>
                        </div>
                        <div style="width:120px;">
                            <label style="display:block; font-size:0.75rem; color:#64748b; margin-bottom:2px;">Cédula</label>
                            <input type="text" class="comp-cedula" style="width:100%; border:1px solid #cbd5e1; padding:6px; border-radius:4px; background:#fff;" required>
                        </div>
                    </div>
                    <div style="padding-right:20px;">
                        <label style="display:block; font-size:0.75rem; color:#64748b; margin-bottom:2px;">Teléfono de contacto</label>
                        <input type="text" class="comp-telefono" style="width:100%; border:1px solid #cbd5e1; padding:6px; border-radius:4px; background:#fff;" required>
                    </div>
                `;
                
                div.querySelector('.btn-remove').addEventListener('click', () => {
                    div.remove();
                    compañerosCount--;
                    Swal.resetValidationMessage(); // Limpia errores si el usuario eliminó una tarjeta incompleta
                });

                container.appendChild(div);
            });
        },
        preConfirm: () => {
            // Ya no es obligatorio el mensaje
            const motivacion = document.getElementById('motivacion').value.trim();
            
            // Recoger compañeros
            let compañeros = [];
            let isValid = true;
            document.querySelectorAll('#compañerosContainer > div').forEach(div => {
                const nombre = div.querySelector('.comp-nombre').value.trim();
                const cedula = div.querySelector('.comp-cedula').value.trim();
                const telefono = div.querySelector('.comp-telefono').value.trim();
                
                if (!nombre || !cedula || !telefono) {
                    isValid = false;
                } else {
                    compañeros.push({ nombre, cedula, telefono });
                }
            });
            
            if (!isValid) {
                Swal.showValidationMessage('Debes completar todos los datos de los compañeros agregados.');
                return false;
            }
            
            return { motivacion, compañeros };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '?ruta=postular-oportunidad';

            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id_investigacion';
            inputId.value = idInvestigacion;

            const inputMotivacion = document.createElement('input');
            inputMotivacion.type = 'hidden';
            inputMotivacion.name = 'motivacion';
            inputMotivacion.value = result.value.motivacion;
            
            const inputEquipo = document.createElement('input');
            inputEquipo.type = 'hidden';
            inputEquipo.name = 'equipo_extra';
            inputEquipo.value = JSON.stringify(result.value.compañeros);

            form.appendChild(inputId);
            form.appendChild(inputMotivacion);
            form.appendChild(inputEquipo);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Inicializar la paginación al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    aplicarFiltrosGlobales();
});
</script>
