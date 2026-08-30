<?php
require_once __DIR__ . '/../models/PropuestaEmpresaModel.php';
$modelo_pe = new PropuestaEmpresaModel();
$roles_profesor = ['Profesor', 'Super Administrador', 'Comite'];
$esProfesor = isset($_SESSION['rol_nombre']) && in_array($_SESSION['rol_nombre'], $roles_profesor);

if ($esProfesor) {
    $propuestas = $modelo_pe->getTodas();
} else {
    $propuestas = $modelo_pe->getAceptadas();
}
?>
    <style>
        .ve-btn-filtro {
            background-color: #ffffff;
            color: #505984;
            border: 1px solid #cbd5e1;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .ve-btn-filtro:hover {
            border-color: #7090cb;
            color: #121a3e;
            background-color: #f8fafc;
        }
        .ve-btn-filtro.active {
            background-color: #121a3e;
            color: #ffffff;
            border-color: #121a3e;
            box-shadow: 0 4px 6px -1px rgba(18, 26, 62, 0.2);
        }
        
        /* Badges de Estado */
        .badge-estado {
            padding: 4px 10px; 
            border-radius: 12px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.5px;
        }
        .badge-pendiente { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-aceptada { background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .badge-rechazada { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        .propuesta-card {
            transition: all 0.2s ease;
        }
        .propuesta-card:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .ve-btn-aceptar { background-color: #10b981; color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 6px; cursor: pointer; transition: 0.3s; font-weight: bold; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); display: inline-flex; align-items: center; gap: 5px; }
        .ve-btn-aceptar:hover { background-color: #059669; }
        
        .ve-btn-rechazar { background-color: white; color: #ef4444; border: 1px solid #ef4444; padding: 0.7rem 1.5rem; border-radius: 6px; cursor: pointer; transition: 0.3s; font-weight: bold; display: inline-flex; align-items: center; gap: 5px;}
        .ve-btn-rechazar:hover { background-color: #fef2f2; }
    </style>
    
    <!-- HEADER CORPORATIVO -->
    <div style="background: #ffffff; border-radius: 10px; padding: 20px 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.03); margin-bottom: 25px; border-left: 5px solid #121a3e;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="margin: 0; color: #121a3e; font-size: 1.4rem; display: flex; align-items: center; gap: 10px; font-weight: 800;">
                    <i class="ph-fill ph-briefcase" style="color: #505984;"></i> Banco de Proyectos (PST)
                </h1>
                <p style="margin: 6px 0 0 0; color: #64748b; font-size: 0.95rem;">
                    <?php echo $esProfesor ? "Administración centralizada de propuestas corporativas para asignación académica." : "Explora oportunidades y postula tu equipo a los proyectos disponibles."; ?>
                </p>
            </div>
            
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <!-- Buscador -->
                <div style="position: relative; width: 100%; max-width: 300px;">
                    <input type="text" id="searchInput" placeholder="Buscar empresa o área..." style="padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: 6px; border: 1px solid #cbd5e1; width: 100%; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02); outline: none; transition: 0.2s; font-size: 0.9rem;" onkeyup="aplicarFiltros()" onfocus="this.style.borderColor='#7090cb'; this.style.boxShadow='0 0 0 3px rgba(112, 144, 203, 0.15)';">
                    <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem;"></i>
                </div>
                
                <!-- Filtro Trayecto -->
                <div style="position: relative;">
                    <select id="trayectoFilter" onchange="aplicarFiltros()" style="appearance: none; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.6rem 2.2rem 0.6rem 1rem; font-size: 0.9rem; color: #121a3e; outline: none; cursor: pointer; transition: 0.2s; font-weight: 600;" onfocus="this.style.borderColor='#7090cb';">
                        <option value="">Todos los Trayectos</option>
                        <option value="Trayecto I">Trayecto I</option>
                        <option value="Trayecto II">Trayecto II</option>
                        <option value="Trayecto III">Trayecto III</option>
                        <option value="Trayecto IV">Trayecto IV</option>
                        <option value="Postgrado / Maestría">Postgrado / Maestría</option>
                        <option value="Sin Asignar">Sin Asignar</option>
                    </select>
                    <i class="ph-bold ph-caret-down" style="position: absolute; right: 0.8rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b;"></i>
                </div>
                
                <?php if($esProfesor): ?>
                <!-- Filtros de Estado -->
                <div style="display: flex; gap: 8px; background: #f1f5f9; padding: 4px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <button class="ve-btn-filtro active" style="padding: 0.4rem 1rem; border:none; box-shadow:none;" data-filter="todas" onclick="setFiltroEstado('todas', this)">Todas</button>
                    <button class="ve-btn-filtro" style="padding: 0.4rem 1rem; border:none; box-shadow:none;" data-filter="pendiente" onclick="setFiltroEstado('pendiente', this)"><i class="ph-fill ph-clock"></i> Pendientes</button>
                    <button class="ve-btn-filtro" style="padding: 0.4rem 1rem; border:none; box-shadow:none;" data-filter="aceptada" onclick="setFiltroEstado('aceptada', this)"><i class="ph-fill ph-check-circle"></i> Aceptadas</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TABLA DE DATOS -->
    <div style="background: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="overflow-x: auto; width: 100%;">
            <?php if(empty($propuestas)): ?>
                <div style="text-align:center; padding: 40px 20px; color: #64748b;">
                    <i class="ph-thin ph-folder-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                    <p style="font-size: 1.1rem;">No hay propuestas disponibles en este momento.</p>
                </div>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <?php if($esProfesor): ?>
                            <th style="padding: 15px 20px; color: #505984; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="ph-bold ph-activity" style="margin-right: 5px;"></i> Estado</th>
                            <?php endif; ?>
                            <th style="padding: 15px 20px; color: #505984; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="ph-bold ph-buildings" style="margin-right: 5px;"></i> Empresa</th>
                            <th style="padding: 15px 20px; color: #505984; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="ph-bold ph-target" style="margin-right: 5px;"></i> Área Requerida</th>
                            <th style="padding: 15px 20px; color: #505984; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="ph-bold ph-graduation-cap" style="margin-right: 5px;"></i> Trayecto Acad.</th>
                            <th style="padding: 15px 20px; color: #505984; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><i class="ph-bold ph-calendar-blank" style="margin-right: 5px;"></i> Recepción</th>
                            <th style="padding: 15px 20px; text-align: right;"></th>
                        </tr>
                    </thead>
                    <tbody id="tablaPropuestas">
                        <?php foreach ($propuestas as $p): ?>
                            <tr class="propuesta-card" data-estado="<?php echo htmlspecialchars($p['estado']); ?>" data-trayecto="<?php echo htmlspecialchars($p['nivel_trayecto'] ?? 'Sin Asignar'); ?>" style="border-bottom: 1px solid #f1f5f9;">
                                <?php if($esProfesor): ?>
                                <td style="padding: 15px 20px;">
                                    <?php 
                                        $icon = 'ph-clock';
                                        if($p['estado'] == 'aceptada') $icon = 'ph-check-circle';
                                        if($p['estado'] == 'rechazada') $icon = 'ph-x-circle';
                                    ?>
                                    <span class="badge-estado badge-<?php echo htmlspecialchars($p['estado']); ?>">
                                        <i class="ph-fill <?php echo $icon; ?>"></i> <?php echo htmlspecialchars($p['estado']); ?>
                                    </span>
                                </td>
                                <?php endif; ?>
                                <td style="padding: 15px 20px; color: #121a3e; font-weight: 700; font-size: 0.95rem;" class="texto-busqueda">
                                    <?php echo htmlspecialchars($p['nombre_empresa']); ?>
                                </td>
                                <td style="padding: 15px 20px; color: #475569;" class="texto-busqueda">
                                    <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-size: 0.85rem; border: 1px solid #e2e8f0;"><?php echo htmlspecialchars($p['area_afectada']); ?></span>
                                </td>
                                <td style="padding: 15px 20px; color: #64748b; font-weight: 500; font-size: 0.9rem;">
                                    <?php echo htmlspecialchars($p['nivel_trayecto'] ?? 'Sin Asignar'); ?>
                                </td>
                                <td style="padding: 15px 20px; color: #94a3b8; font-size: 0.85rem; font-weight: 500;">
                                    <?php echo date('d M, Y', strtotime($p['fecha_creacion'])); ?>
                                </td>
                                <td style="padding: 15px 20px; text-align: right;">
                                    <button class="ve-btn-filtro" style="padding: 6px 12px; font-size: 0.85rem; display: inline-flex; justify-content: center;" onclick="abrirModal(
                                        '<?php echo addslashes(htmlspecialchars($p['nombre_empresa'])); ?>', 
                                        '<?php echo addslashes(htmlspecialchars($p['area_afectada'])); ?>', 
                                        '<?php echo addslashes(htmlspecialchars($p['descripcion_problema'])); ?>', 
                                        '<?php echo addslashes(htmlspecialchars($p['nivel_trayecto'] ?? 'Pendiente')); ?>',
                                        '<?php echo addslashes(htmlspecialchars($p['correo_contacto'])); ?>',
                                        '<?php echo addslashes(htmlspecialchars($p['telefono_contacto'])); ?>',
                                        <?php echo $p['id']; ?>,
                                        '<?php echo $p['estado']; ?>'
                                    )">
                                        <i class="ph-bold ph-eye" style="font-size: 1.1rem; color: #7090cb;"></i> <?php echo $esProfesor ? 'Revisar' : 'Postular'; ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="ve-modal-overlay" id="proyectoModal">
    <div class="ve-modal-content">
        <span class="ve-modal-close" onclick="cerrarModal()">&times;</span>
        <div class="ve-modal-body">
            <h2 id="modalTitulo">Cargando...</h2>
            
            <div class="ve-meta">
                <strong id="modalEmpresa" style="color: var(--ve-principal);">Empresa</strong> 
                <br>
                Requisito Académico: <span id="modalTrayecto" style="font-weight: bold;">Trayecto X</span>
                <br>
                Contacto: <span id="modalContacto"></span>
            </div>
            
            <h4>Descripción de la Problemática</h4>
            <p id="modalDescripcion">Cargando detalles...</p>
            
            <?php if ($esProfesor): ?>
                <div id="profesorAcciones" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ccc;">
                    <h4>Opciones de Profesor:</h4>
                    <form action="?ruta=procesar-propuesta" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <input type="hidden" name="id_propuesta" id="modalIdPropuesta" value="">
                        <select name="nivel_trayecto" class="ve-input" style="flex: 1; min-width: 200px;">
                            <option value="Trayecto I (T1)">Trayecto I (T1)</option>
                            <option value="Trayecto II (T2)">Trayecto II (T2)</option>
                            <option value="Trayecto III (T3)">Trayecto III (T3)</option>
                            <option value="Trayecto IV (T4)">Trayecto IV (T4)</option>
                            <option value="Postgrado / Maestría">Postgrado / Maestría</option>
                        </select>
                        <button type="submit" name="accion" value="aceptar" class="ve-btn-aceptar">Aceptar</button>
                        <button type="submit" name="accion" value="rechazar" class="ve-btn-rechazar">Rechazar</button>
                    </form>
                </div>
                
                <div id="profesorMensajeEstado" style="display: none; margin-top: 20px; padding: 15px; background: #eef2f9; border-left: 4px solid var(--ve-principal); border-radius: 4px;">
                    <strong style="color: var(--ve-principal);">Estado actual:</strong> <span id="textoEstadoFinal"></span>
                </div>
            <?php endif; ?>

            <a href="#" class="ve-btn-postular" id="btnPostular">Postular a mi Equipo para este PST</a>
        </div>
    </div>
</div>

<script>
    function abrirModal(empresa, titulo, descripcion, trayecto, correo, telefono, id, estado) {
        document.getElementById('modalEmpresa').innerText = empresa;
        document.getElementById('modalTitulo').innerText = titulo;
        document.getElementById('modalDescripcion').innerText = descripcion;
        document.getElementById('modalTrayecto').innerText = trayecto;
        document.getElementById('modalContacto').innerText = correo + ' | ' + telefono;
        
        <?php if ($esProfesor): ?>
            document.getElementById('modalIdPropuesta').value = id;
            if (estado !== 'pendiente') {
                document.getElementById('profesorAcciones').style.display = 'none';
                document.getElementById('profesorMensajeEstado').style.display = 'block';
                document.getElementById('textoEstadoFinal').innerText = 'Esta propuesta ya ha sido ' + estado.toUpperCase();
            } else {
                document.getElementById('profesorAcciones').style.display = 'block';
                document.getElementById('profesorMensajeEstado').style.display = 'none';
            }
        <?php endif; ?>
        
        document.getElementById('proyectoModal').classList.add('active');
    }

    function cerrarModal() {
        document.getElementById('proyectoModal').classList.remove('active');
    }

    document.getElementById('proyectoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });

    let filtroEstadoGlobal = 'todas';

    function setFiltroEstado(estado, btnElement) {
        document.querySelectorAll('.ve-btn-filtro').forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');
        filtroEstadoGlobal = estado;
        aplicarFiltros();
    }

    function aplicarFiltros() {
        const query = document.getElementById('searchInput') ? document.getElementById('searchInput').value.toLowerCase() : '';
        const trayectoElegido = document.getElementById('trayectoFilter') ? document.getElementById('trayectoFilter').value : '';
        const filas = document.querySelectorAll('.propuesta-card');

        filas.forEach(fila => {
            const estado = fila.getAttribute('data-estado');
            const trayectoFila = fila.getAttribute('data-trayecto');
            
            let matchEstado = (filtroEstadoGlobal === 'todas' || estado === filtroEstadoGlobal);
            let matchTrayecto = (trayectoElegido === '' || trayectoFila.includes(trayectoElegido));
            
            let matchTexto = true;
            if (query !== '') {
                const columnasTexto = fila.querySelectorAll('.texto-busqueda');
                let filaTexto = '';
                columnasTexto.forEach(col => filaTexto += col.innerText.toLowerCase() + ' ');
                matchTexto = filaTexto.includes(query);
            }

            if (matchEstado && matchTexto && matchTrayecto) {
                fila.style.display = ''; // Usar comportamiento default (table-row)
            } else {
                fila.style.display = 'none';
            }
        });
    }
</script>