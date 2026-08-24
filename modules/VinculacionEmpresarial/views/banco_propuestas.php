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
            background-color: var(--ve-blanco-puro, #ffffff);
            color: var(--ve-secundario, #505984);
            border: 1px solid #dcdde1;
            padding: 0.6rem 1.4rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .ve-btn-filtro:hover {
            border-color: var(--ve-terciario, #7090CB);
            background-color: #f8f9fa;
        }
        .ve-btn-filtro.active {
            background-color: var(--ve-principal, #121A3E);
            color: #ffffff;
            border-color: var(--ve-principal, #121A3E);
            box-shadow: 0 4px 10px rgba(18, 26, 62, 0.25);
        }
        
        /* Badges usando estrictamente la paleta del sistema */
        .badge-pendiente { background-color: #eef2f9; color: var(--ve-secundario, #505984); border: 1px solid var(--ve-terciario, #7090CB); }
        .badge-aceptada { background-color: var(--ve-principal, #121A3E); color: #ffffff; border: 1px solid var(--ve-principal, #121A3E); }
        .badge-rechazada { background-color: var(--ve-gris, #A9A8A6); color: #ffffff; border: 1px solid var(--ve-gris, #A9A8A6); }
        
        .ve-btn-aceptar { background-color: var(--ve-principal); color: var(--ve-blanco-puro); border: none; padding: 0.7rem 1.5rem; border-radius: 6px; cursor: pointer; transition: 0.3s; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .ve-btn-aceptar:hover { background-color: var(--ve-secundario); transform: translateY(-2px); }
        
        .ve-btn-rechazar { background-color: transparent; color: var(--ve-secundario); border: 2px solid var(--ve-secundario); padding: 0.6rem 1.4rem; border-radius: 6px; cursor: pointer; transition: 0.3s; font-weight: bold; }
        .ve-btn-rechazar:hover { background-color: var(--ve-secundario); color: var(--ve-blanco-puro); }
    </style>
    
    <div class="ve-kanban-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1>Banco de Proyectos Socio Tecnológicos (PST)</h1>
            <p><?php echo $esProfesor ? "Administración de propuestas recibidas" : "Filtrado por nivel de complejidad arquitectónica para asignación académica."; ?></p>
        </div>
        
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            
            <div style="position: relative; width: 100%; max-width: 350px;">
                <input type="text" id="searchInput" placeholder="Buscar por empresa o área..." style="padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: 50px; border: 1px solid #dcdde1; width: 100%; min-width: 250px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); outline: none; transition: 0.2s;" onkeyup="aplicarFiltros()" onfocus="this.style.borderColor='var(--ve-terciario, #7090CB)'; this.style.boxShadow='0 0 0 3px rgba(112, 144, 203, 0.1)';" onblur="this.style.borderColor='#dcdde1'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.02)';">
                <svg style="position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%); color: #999;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            
            <div style="position: relative;">
                <select id="trayectoFilter" onchange="aplicarFiltros()" style="appearance: none; background: #f8f9fa; border: 1px solid #dcdde1; border-radius: 6px; padding: 0.6rem 2rem 0.6rem 0.8rem; font-size: 0.85rem; color: var(--ve-secundario, #505984); outline: none; cursor: pointer; transition: 0.2s; font-weight: 500;" onfocus="this.style.borderColor='var(--ve-terciario, #7090CB)'; this.style.boxShadow='0 0 0 3px rgba(112, 144, 203, 0.1)';" onblur="this.style.borderColor='#dcdde1'; this.style.boxShadow='none';">
                    <option value="">Todos los Trayectos</option>
                    <option value="Trayecto I">Trayecto I</option>
                    <option value="Trayecto II">Trayecto II</option>
                    <option value="Trayecto III">Trayecto III</option>
                    <option value="Trayecto IV">Trayecto IV</option>
                    <option value="Postgrado / Maestría">Postgrado / Maestría</option>
                    <option value="Sin Asignar">Sin Asignar</option>
                </select>
                <span style="position: absolute; right: 0.8rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ve-secundario, #505984); font-size: 0.7rem;">▼</span>
            </div>
            
            <?php if($esProfesor): ?>
            <div class="ve-filtros" style="display: flex; gap: 10px;">
                <button class="ve-btn-filtro active" data-filter="todas" onclick="setFiltroEstado('todas', this)">Todas</button>
                <button class="ve-btn-filtro" data-filter="pendiente" onclick="setFiltroEstado('pendiente', this)">Pendientes</button>
                <button class="ve-btn-filtro" data-filter="aceptada" onclick="setFiltroEstado('aceptada', this)">Aceptadas</button>
                <button class="ve-btn-filtro" data-filter="rechazada" onclick="setFiltroEstado('rechazada', this)">Rechazadas</button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="ve-table-responsive" style="overflow-x: auto; width: 100%; margin-top: 5px;">
        <?php if(empty($propuestas)): ?>
            <p style="text-align:center; padding: 20px;">No hay propuestas disponibles en este momento.</p>
        <?php else: ?>
            <table class="ve-table" style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden;">
                <thead style="background: var(--ve-principal, #121A3E); color: #fff;">
                    <tr>
                        <?php if($esProfesor): ?>
                        <th style="padding: 14px; text-align: left;">Estado</th>
                        <?php endif; ?>
                        <th style="padding: 14px; text-align: left;">Empresa</th>
                        <th style="padding: 14px; text-align: left;">Área Afectada</th>
                        <th style="padding: 14px; text-align: left;">Nivel / Trayecto</th>
                        <th style="padding: 14px; text-align: left;">Fecha</th>
                        <th style="padding: 14px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPropuestas">
                    <?php foreach ($propuestas as $p): ?>
                        <tr class="propuesta-card" data-estado="<?php echo htmlspecialchars($p['estado']); ?>" data-trayecto="<?php echo htmlspecialchars($p['nivel_trayecto'] ?? 'Sin Asignar'); ?>" style="border-bottom: 1px solid #eee;">
                            <?php if($esProfesor): ?>
                            <td style="padding: 12px;">
                                <span class="badge-<?php echo htmlspecialchars($p['estado']); ?>" style="padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; display: inline-block;">
                                    <?php echo strtoupper(htmlspecialchars($p['estado'])); ?>
                                </span>
                            </td>
                            <?php endif; ?>
                            <td style="padding: 12px; font-weight: bold; color: var(--ve-principal);" class="texto-busqueda"><?php echo htmlspecialchars($p['nombre_empresa']); ?></td>
                            <td style="padding: 12px; color: var(--ve-secundario);" class="texto-busqueda"><?php echo htmlspecialchars($p['area_afectada']); ?></td>
                            <td style="padding: 12px; color: #555;"><?php echo htmlspecialchars($p['nivel_trayecto'] ?? 'Sin Asignar'); ?></td>
                            <td style="padding: 12px; color: #777; font-size: 0.9rem;"><?php echo date('d/m/Y', strtotime($p['fecha_creacion'])); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <button class="ve-btn-filtro" style="padding: 5px 12px; font-size: 0.85rem;" onclick="abrirModal(
                                    '<?php echo addslashes(htmlspecialchars($p['nombre_empresa'])); ?>', 
                                    '<?php echo addslashes(htmlspecialchars($p['area_afectada'])); ?>', 
                                    '<?php echo addslashes(htmlspecialchars($p['descripcion_problema'])); ?>', 
                                    '<?php echo addslashes(htmlspecialchars($p['nivel_trayecto'] ?? 'Pendiente')); ?>',
                                    '<?php echo addslashes(htmlspecialchars($p['correo_contacto'])); ?>',
                                    '<?php echo addslashes(htmlspecialchars($p['telefono_contacto'])); ?>',
                                    <?php echo $p['id']; ?>,
                                    '<?php echo $p['estado']; ?>'
                                )">
                                    <?php echo $esProfesor ? 'Ver Detalle' : 'Ver Detalles para Postulación'; ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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