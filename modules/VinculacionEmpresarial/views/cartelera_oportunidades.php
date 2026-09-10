<?php
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';
$modelo = new PropuestaEmpresaModel();
$oportunidades = $modelo->getAceptadas(); // Esto ya trae las propuestas con estado 'aceptada'
?>
<style>
.cartelera-hero { background: linear-gradient(135deg, #121a3e 0%, #1e2a5c 100%); color: white; padding: 4rem 2rem; text-align: center; border-radius: 0 0 2rem 2rem; margin-bottom: 3rem; }
.cartelera-hero h1 { font-size: 2.5rem; margin-bottom: 1rem; font-weight: 800; }
.cartelera-hero p { font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto; line-height: 1.6; }

.cartelera-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; padding: 0 2rem 4rem; max-width: 1200px; margin: 0 auto; }
.cartelera-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; flex-direction: column; }
.cartelera-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.cartelera-header { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; }
.cartelera-company { display: flex; align-items: center; gap: 0.8rem; }
.cartelera-company-icon { width: 45px; height: 45px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #121a3e; border: 1px solid #e2e8f0; }
.cartelera-company-info h4 { margin: 0; font-size: 1.1rem; color: #121a3e; }
.cartelera-company-info span { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.cartelera-badge { background: #d1fae5; color: #059669; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }

.cartelera-body { padding: 1.5rem; flex-grow: 1; }
.cartelera-title { font-size: 1.2rem; color: #1e293b; margin: 0 0 1rem 0; font-weight: 700; line-height: 1.4; }
.cartelera-desc { font-size: 0.95rem; color: #475569; line-height: 1.6; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

.cartelera-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.cartelera-tag { background: #f1f5f9; color: #475569; padding: 0.3rem 0.8rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 4px; }

.cartelera-footer { padding: 1.5rem; border-top: 1px solid #f1f5f9; background: #f8fafc; }
.cartelera-btn { display: block; width: 100%; text-align: center; background: #121a3e; color: white; padding: 0.8rem; border-radius: 8px; font-weight: bold; text-decoration: none; transition: background 0.2s; border: none; cursor: pointer; }
.cartelera-btn:hover { background: #1e2a5c; }
</style>

<div class="cartelera-hero">
    <h1><i class="ph-bold ph-rocket-launch" style="color: #38bdf8;"></i> Cartelera de Oportunidades PST</h1>
    <p>Aplica tus conocimientos resolviendo problemas reales del sector socioproductivo. Conecta con empresas y desarrolla tu Proyecto Socio-Tecnológico con impacto verdadero.</p>
</div>

<div class="cartelera-grid">
    <?php if (empty($oportunidades)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 16px; border: 2px dashed #cbd5e1;">
            <i class="ph-fill ph-empty" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
            <h3 style="color: #475569;">No hay oportunidades empresariales en este momento</h3>
            <p style="color: #64748b;">Mantente atento, pronto las empresas publicarán nuevos retos tecnológicos.</p>
        </div>
    <?php else: ?>
        <?php foreach ($oportunidades as $op): ?>
        <div class="cartelera-card">
            <div class="cartelera-header">
                <div class="cartelera-company">
                    <div class="cartelera-company-icon">
                        <i class="ph-bold ph-buildings"></i>
                    </div>
                    <div class="cartelera-company-info">
                        <h4><?= htmlspecialchars($op['nombre_empresa']) ?></h4>
                        <span>Socio Productivo</span>
                    </div>
                </div>
                <div class="cartelera-badge">OFERTA ACTIVA</div>
            </div>
            
            <div class="cartelera-body">
                <h3 class="cartelera-title">Requerimiento: <?= htmlspecialchars($op['area_afectada']) ?></h3>
                <p class="cartelera-desc">
                    <?= htmlspecialchars($op['descripcion_problema']) ?>
                </p>
                
                <div class="cartelera-tags">
                    <span class="cartelera-tag"><i class="ph-bold ph-student"></i> Nivel: <?= htmlspecialchars($op['nivel_trayecto'] ?? 'Trayecto III') ?></span>
                    <span class="cartelera-tag"><i class="ph-bold ph-users"></i> Equipos de 3-4</span>
                    <span class="cartelera-tag" style="background:#e0f2fe; color:#0369a1;"><i class="ph-bold ph-calendar"></i> <?= date('M Y', strtotime($op['fecha_creacion'])) ?></span>
                </div>
            </div>
            
            <div class="cartelera-footer">
                <button class="cartelera-btn" onclick="postularProyecto(<?= $op['id'] ?>, '<?= htmlspecialchars(addslashes($op['nombre_empresa'])) ?>')">
                    Aplicar a este Reto
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function postularProyecto(idInvestigacion, empresa) {
    Swal.fire({
        title: 'Postulación PST',
        html: `¿Deseas postular a tu equipo para resolver el requerimiento de <b>${empresa}</b>?<br><br>
               Escribe un breve mensaje de motivación para el Comité de Proyectos:`,
        input: 'textarea',
        inputPlaceholder: 'Ej: Nos interesa porque tenemos experiencia en BD...',
        inputAttributes: {
            'aria-label': 'Escribe tu mensaje de motivación'
        },
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#121a3e',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, enviar solicitud',
        cancelButtonText: 'Cancelar',
        preConfirm: (motivacion) => {
            if (!motivacion) {
                Swal.showValidationMessage('Debes escribir un mensaje de motivación');
            }
            return motivacion;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario oculto y enviarlo por POST
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
            inputMotivacion.value = result.value;

            form.appendChild(inputId);
            form.appendChild(inputMotivacion);
            document.body.appendChild(form);
            form.submit();
        }
    })
}
</script>
