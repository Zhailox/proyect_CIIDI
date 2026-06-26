<div class="ve-kanban-wrapper">
    
    <div class="ve-kanban-header">
        <h1>Banco de Proyectos Socio Tecnológicos (PST)</h1>
        <p>Filtrado por nivel de complejidad arquitectónica para asignación académica.</p>
    </div>

    <div class="ve-kanban-board">
        
        <div class="ve-kanban-col" data-nivel="1">
            <div class="ve-kanban-col-header">
                <h3>Trayecto I</h3>
                <span>Soporte / Hardware</span>
            </div>
            
            <div class="ve-kanban-card" onclick="abrirModal('Escuela Bolivariana La Beatriz', 'Reactivación de Laboratorio', 'Los 15 equipos del laboratorio de computación están inoperativos por problemas de SO, virus y falta de mantenimiento físico. Necesitamos una reactivación total de la red local.', 'Trayecto I (T1)')">
                <span class="ve-card-badge">Comunidad Educativa</span>
                <h4>Reactivación de Laboratorio</h4>
                <p>Los 15 equipos del laboratorio de computación están inoperativos por problemas de SO, virus y falta de mantenimiento físico...</p>
                <div class="ve-kanban-card-footer">
                    <span>📍 La Beatriz</span>
                    <span>Hace 2 días</span>
                </div>
            </div>
        </div>

        <div class="ve-kanban-col" data-nivel="2">
            <div class="ve-kanban-col-header">
                <h3>Trayecto II</h3>
                <span>Sistemas Web Mínimos</span>
            </div>
            
            <div class="ve-kanban-card" onclick="abrirModal('Bodega El Carmen', 'Control de Stock y Ventas', 'Se requiere un pequeño sistema web local para dejar de usar el cuaderno. Solo necesitamos registrar entradas/salidas de productos, ver el stock actual y emitir un reporte básico al cierre del día.', 'Trayecto II (T2)')">
                <span class="ve-card-badge">Pequeño Comercio</span>
                <h4>Control de Stock y Ventas</h4>
                <p>Se requiere un pequeño sistema web local para dejar de usar el cuaderno. Solo necesitamos registrar entradas/salidas de productos...</p>
                <div class="ve-kanban-card-footer">
                    <span>📍 Centro de Valera</span>
                    <span>Hace 5 hrs</span>
                </div>
            </div>
        </div>

        <div class="ve-kanban-col" data-nivel="3">
            <div class="ve-kanban-col-header">
                <h3>Trayecto III</h3>
                <span>Sistemas Intermedios</span>
            </div>
            
            <div class="ve-kanban-card" onclick="abrirModal('Clínica San Antonio', 'Gestor de Historias Médicas', 'Requerimos modernizar la gestión de pacientes. El sistema debe manejar roles (médico, enfermera, admin), encriptación de datos sensibles, agenda de citas y módulos de facturación.', 'Trayecto III (T3)')">
                <span class="ve-card-badge">Sector Salud</span>
                <h4>Gestor de Historias Médicas</h4>
                <p>Requerimos modernizar la gestión de pacientes. El sistema debe manejar roles (médico, enfermera, admin), encriptación de datos sensibles...</p>
                <div class="ve-kanban-card-footer">
                    <span>📍 Las Acacias</span>
                    <span>Hace 1 sem</span>
                </div>
            </div>
        </div>

        <div class="ve-kanban-col" data-nivel="4">
            <div class="ve-kanban-col-header">
                <h3>Trayecto IV</h3>
                <span>Arquitecturas Complejas</span>
            </div>
            
            <div class="ve-kanban-card" onclick="abrirModal('Lácteos Los Andes', 'Intranet Operativa Local', 'Migración de la base de datos central a una nueva intranet corporativa. Requiere control estricto de sesiones y manejo de altos volúmenes de datos.', 'Trayecto IV (T4)')">
                <span class="ve-card-badge">Industria</span>
                <h4>Intranet Operativa Local</h4>
                <p>Migración de la base de datos central a una nueva intranet corporativa. Requiere control estricto de sesiones...</p>
                <div class="ve-kanban-card-footer">
                    <span>📍 Zona Industrial</span>
                    <span>Ayer</span>
                </div>
            </div>
        </div>

        <div class="ve-kanban-col" data-nivel="5">
            <div class="ve-kanban-col-header">
                <h3>Maestría</h3>
                <span>Sistemas Expertos e IA</span>
            </div>
            
            <div class="ve-kanban-card" onclick="abrirModal('Venvidrio', 'Sistema Predictivo de Fallas', 'Se requiere un sistema experto capaz de analizar los logs históricos de las máquinas de moldeo para predecir paradas críticas mediante algoritmos o heurísticas complejas, integrándose con una API heredada y modelos predictivos.', 'Postgrado / Maestría')">
                <span class="ve-card-badge">Industria Pesada</span>
                <h4>Sistema Predictivo de Fallas</h4>
                <p>Se requiere un sistema experto capaz de analizar los logs históricos de las máquinas de moldeo para predecir paradas críticas...</p>
                <div class="ve-kanban-card-footer">
                    <span>📍 Valera</span>
                    <span>Hace 3 días</span>
                </div>
            </div>
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
            </div>
            
            <h4>Descripción de la Problemática</h4>
            <p id="modalDescripcion">Cargando detalles...</p>
            
            <a href="#" class="ve-btn-postular">Postular a mi Equipo para este PST</a>
        </div>
    </div>
</div>

<script>
    function abrirModal(empresa, titulo, descripcion, trayecto) {
        document.getElementById('modalEmpresa').innerText = empresa;
        document.getElementById('modalTitulo').innerText = titulo;
        document.getElementById('modalDescripcion').innerText = descripcion;
        document.getElementById('modalTrayecto').innerText = trayecto;
        
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
</script>