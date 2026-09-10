<style>
/* Estilos para el Timeline (Stepper) */
.ve-stepper { display: flex; justify-content: space-between; position: relative; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #cbd5e1; }
.ve-step { flex: 1; text-align: center; position: relative; z-index: 1; }
.ve-step::before { content: ''; position: absolute; top: 15px; left: -50%; width: 100%; height: 4px; background: #e2e8f0; z-index: -1; }
.ve-step:first-child::before { display: none; }
.ve-step-icon { width: 35px; height: 35px; border-radius: 50%; background: #e2e8f0; color: #94a3b8; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem auto; font-size: 1.2rem; border: 4px solid white; transition: 0.3s; }
.ve-step-text { font-size: 0.85rem; font-weight: 600; color: #64748b; }

/* Estados del Step */
.ve-step.active .ve-step-icon { background: #121a3e; color: white; }
.ve-step.active .ve-step-text { color: #121a3e; }
.ve-step.active ~ .ve-step::before { background: #e2e8f0; } /* La línea anterior a este step */
.ve-step.active::before { background: #121a3e; }

.ve-step.rejected .ve-step-icon { background: #ef4444; color: white; }
.ve-step.rejected .ve-step-text { color: #ef4444; }
.ve-step.rejected::before { background: #ef4444; }
</style>

<div class="ve-wrapper" style="min-height: 80vh; background: #f4f7fb; padding: 3rem 1rem;">
    <div class="ve-container" style="max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="ph-bold ph-magnifying-glass" style="font-size: 3rem; color: #121a3e;"></i>
            <h1 style="color: #121a3e; margin-top: 0.5rem;">Portal de Transparencia</h1>
            <p style="color: #64748B;">Rastrea el estatus de tus requerimientos tecnológicos en tiempo real.</p>
        </div>

        <!-- TABS DE BÚSQUEDA -->
        <div class="ve-tabs" style="display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
            <button class="ve-tab-btn active" onclick="switchTab('codigo')" style="flex:1; padding: 0.8rem; background: none; border: none; font-weight: bold; color: #121a3e; border-bottom: 3px solid #121a3e; cursor: pointer;">
                <i class="ph-bold ph-barcode"></i> Tengo mi Código
            </button>
            <button class="ve-tab-btn" onclick="switchTab('rif')" style="flex:1; padding: 0.8rem; background: none; border: none; font-weight: bold; color: #94A3B8; cursor: pointer;">
                <i class="ph-bold ph-buildings"></i> Olvidé mi Código
            </button>
        </div>

        <!-- FORMULARIO 1: POR CÓDIGO -->
        <div id="form-codigo" class="ve-search-form">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Código de Seguimiento</label>
                <input type="text" id="input_codigo" placeholder="Ej. CIIDI-2026-X7B9K" style="width: 100%; padding: 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; text-transform: uppercase;">
            </div>
            <button onclick="buscarPropuesta('codigo')" style="width: 100%; padding: 1rem; background: #505984; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Rastrear Proyecto
            </button>
        </div>

        <!-- FORMULARIO 2: POR RIF/CORREO (Oculto) -->
        <div id="form-rif" class="ve-search-form" style="display: none;">
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">RIF o Nombre de la Organización</label>
                    <input type="text" id="input_rif" placeholder="Ej. J-12345 o 'Comunidad San Luis'" style="width: 100%; padding: 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Correo de Contacto</label>
                    <input type="email" id="input_correo" placeholder="correo@empresa.com" style="width: 100%; padding: 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
            </div>
            <button onclick="buscarPropuesta('rif')" style="width: 100%; padding: 1rem; background: #505984; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Recuperar y Buscar
            </button>
        </div>

        <!-- RESULTADOS (Se llenan con JS) -->
        <div id="resultados-container" style="margin-top: 3rem; display: none;">
            <h3 style="color: #121a3e; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">Reporte de Estatus</h3>
            <div id="resultados-lista"></div>
        </div>

    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.ve-search-form').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.ve-tab-btn').forEach(btn => {
        btn.style.color = '#94A3B8';
        btn.style.borderBottom = 'none';
    });
    document.getElementById('form-' + tabId).style.display = 'block';
    let activeBtn = event.currentTarget;
    activeBtn.style.color = '#121a3e';
    activeBtn.style.borderBottom = '3px solid #121a3e';
}

async function buscarPropuesta(tipo) {
    let payload = { tipo: tipo };
    if (tipo === 'codigo') {
        payload.codigo = document.getElementById('input_codigo').value;
    } else {
        payload.rif = document.getElementById('input_rif').value;
        payload.correo = document.getElementById('input_correo').value;
    }

    try {
        const response = await fetch('index.php?ruta=api-transparencia', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        const contenedor = document.getElementById('resultados-container');
        const lista = document.getElementById('resultados-lista');
        
        contenedor.style.display = 'block';
        lista.innerHTML = '';

        if (!data.success) {
            lista.innerHTML = `<div style="padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 6px;"><i class="ph-bold ph-warning"></i> ${data.message}</div>`;
            return;
        }

        data.data.forEach(prop => {
            // Lógica del Stepper
            let s1 = 'active', s2 = '', s3 = '', s4 = '';
            let s2_text = 'Aprobación Comité', s2_icon = 'ph-users-three';
            let s2_class = '';
            
            if (prop.estado === 'rechazada') {
                s2_class = 'rejected';
                s2_text = 'No Viable';
                s2_icon = 'ph-x-circle';
            } else if (prop.estado === 'aceptada') {
                s2 = 'active';
                s2_icon = 'ph-check-circle';
                
                // Chequear el estado de la oferta académica
                if (prop.estado_oferta === 'En Desarrollo' || prop.estado_oferta === 'Finalizada') {
                    s3 = 'active';
                }
                if (prop.estado_oferta === 'Finalizada') {
                    s4 = 'active';
                }
            }

            // Etiqueta Superior
            let badgeColor = prop.estado === 'aceptada' ? '#10b981' : (prop.estado === 'rechazada' ? '#ef4444' : '#f59e0b');
            let txtEstadoGlobal = prop.estado === 'pendiente' ? 'EN REVISIÓN' : (prop.estado === 'rechazada' ? 'RECHAZADA' : 'OFERTA ACTIVA');
            if (prop.estado_oferta === 'En Desarrollo') txtEstadoGlobal = 'EN DESARROLLO';
            if (prop.estado_oferta === 'Finalizada') txtEstadoGlobal = 'FINALIZADO';

            let alertaRechazo = '';
            if (prop.estado === 'rechazada') {
                alertaRechazo = `
                    <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 8px; margin-top: 1.5rem;">
                        <h5 style="color: #991b1b; margin: 0 0 0.5rem 0;"><i class="ph-bold ph-warning-circle"></i> Retroalimentación del Comité</h5>
                        <p style="color: #7f1d1d; font-size: 0.9rem; margin: 0; font-style: italic;">
                            "${prop.motivo_rechazo || 'La propuesta no se ajusta a los lineamientos académicos actuales.'}"
                        </p>
                    </div>
                `;
            }

            lista.innerHTML += `
                <div style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; background: #fafafa;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <span style="font-size: 0.85rem; font-family: monospace; background: #e2e8f0; padding: 0.3rem 0.6rem; border-radius: 20px; color: #475569; font-weight: bold;">
                                <i class="ph-bold ph-barcode"></i> ${prop.codigo_seguimiento || 'S/C'}
                            </span>
                            <h4 style="margin: 0.5rem 0; color: #121a3e; font-size: 1.3rem;">Requerimiento: ${prop.area_afectada || 'General'}</h4>
                            <p style="font-size: 0.9rem; color: #64748B; margin-bottom: 0;">Organización: <strong>${prop.nombre_empresa}</strong></p>
                        </div>
                        <div style="background: ${badgeColor}; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; font-size: 0.85rem; letter-spacing: 1px;">
                            ${txtEstadoGlobal}
                        </div>
                    </div>
                    
                    ${alertaRechazo}

                    <!-- STEPPER TIMELINE -->
                    <div class="ve-stepper">
                        <div class="ve-step ${s1}">
                            <div class="ve-step-icon"><i class="ph-bold ph-envelope-simple"></i></div>
                            <div class="ve-step-text">Recepción</div>
                        </div>
                        <div class="ve-step ${s2} ${s2_class}">
                            <div class="ve-step-icon"><i class="ph-bold ${s2_icon}"></i></div>
                            <div class="ve-step-text">${s2_text}</div>
                        </div>
                        <div class="ve-step ${s3}">
                            <div class="ve-step-icon"><i class="ph-bold ph-code"></i></div>
                            <div class="ve-step-text">Equipo Asignado</div>
                        </div>
                        <div class="ve-step ${s4}">
                            <div class="ve-step-icon"><i class="ph-bold ph-flag-checkered"></i></div>
                            <div class="ve-step-text">Solución Entregada</div>
                        </div>
                    </div>
                </div>
            `;
        });
    } catch (e) {
        alert("Ocurrió un problema de conexión local. Verifique que la API responda JSON.");
    }
}
</script>
