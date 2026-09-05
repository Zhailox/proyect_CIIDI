document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador-autores');
    const resultadosBox = document.getElementById('resultados-autores');
    const seleccionadosBox = document.getElementById('autores-seleccionados');
    const hiddenInputsBox = document.getElementById('autores-hidden-inputs');
    const errorMsg = document.getElementById('error-autores');
    
    // Elementos del Modal
    const modalAutor = document.getElementById('modal-autor');
    const inputNombre = document.getElementById('modal-autor-nombre');
    const inputCedula = document.getElementById('modal-autor-cedula');
    
    const listaAutores = window.DATA_AUTORES || []; 
    let autoresSeleccionados = new Set();

    if (window.AUTORES_SELECCIONADOS && window.AUTORES_SELECCIONADOS.length) {
    window.AUTORES_SELECCIONADOS.forEach(function(id) {
        autoresSeleccionados.add(parseInt(id, 10));
    });
}
    
    // NUEVO: Memoria temporal para los autores que creamos al vuelo
    let autoresNuevosMap = {}; 
    
    buscador.addEventListener('input', function() {
        const rawQuery = this.value.trim();
        const query = rawQuery.toLowerCase();
        
        resultadosBox.innerHTML = '';
        
        if (query.length < 2) {
            resultadosBox.style.display = 'none';
            return;
        }

        let filtrados = listaAutores.filter(a => 
            a.nombre_completo.toLowerCase().includes(query) || 
            (a.cedula && a.cedula.toLowerCase().includes(query))
        );

        if (filtrados.length > 0) {
            filtrados.forEach(autor => {
                if(autoresSeleccionados.has(autor.id)) return; 

                let item = document.createElement('div');
                item.className = 'autocomplete-item';
                item.innerHTML = `<strong>${autor.nombre_completo}</strong> <span class="text-muted" style="font-size:0.8rem;">(${autor.cedula || 'Sin cédula'})</span>`;
                
                item.onclick = function() {
                    agregarAutor(autor.id, autor.nombre_completo);
                    buscador.value = '';
                    resultadosBox.style.display = 'none';
                };
                resultadosBox.appendChild(item);
            });
        } else {
            resultadosBox.innerHTML = `<div class="autocomplete-item text-secondary">
                <i class="ph-bold ph-plus"></i> Registrar a "${rawQuery}" como nuevo autor
            </div>`;
            resultadosBox.firstChild.onclick = function() {
                abrirModalAutor(rawQuery);
            };
        }
        
        resultadosBox.style.display = 'block';
    });

    document.addEventListener('click', function(e) {
        if (!buscador.contains(e.target) && !resultadosBox.contains(e.target)) {
            resultadosBox.style.display = 'none';
        }
    });

    function agregarAutor(id, nombre) {
        autoresSeleccionados.add(id);
        errorMsg.style.display = 'none'; 
        renderChips();
    }

    function renderChips() {
        seleccionadosBox.innerHTML = '';
        hiddenInputsBox.innerHTML = ''; // Limpiamos para redibujar

        autoresSeleccionados.forEach(id => {
            const autorInfo = listaAutores.find(a => a.id === id) || { nombre_completo: 'Nuevo Autor' };
            
            // Pintamos la pastilla visual
            let chip = document.createElement('div');
            chip.className = 'chip-autor';
            chip.innerHTML = `${autorInfo.nombre_completo} <span class="chip-close" onclick="removerAutor('${id}')">&times;</span>`;
            seleccionadosBox.appendChild(chip);

            // Recreamos el input oculto correspondiente
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            
            // MAGIA: Si el ID es un texto que empieza con 'nuevo_', sabemos que es temporal
            if (String(id).startsWith('nuevo_')) {
                hiddenInput.name = 'autores_nuevos[]';
                hiddenInput.value = JSON.stringify(autoresNuevosMap[id]);
            } else {
                hiddenInput.name = 'autores[]';
                hiddenInput.value = id;
            }
            
            hiddenInputsBox.appendChild(hiddenInput);
        });
    }

    window.removerAutor = function(id) {
        autoresSeleccionados.delete(id); 
        autoresSeleccionados.delete(parseInt(id));
        
        // Si borramos un autor temporal, también limpiamos su memoria
        if (String(id).startsWith('nuevo_')) {
            delete autoresNuevosMap[id];
        }
        
        renderChips();
    };

    // --- LÓGICA DEL MODAL ---
    // --- LÓGICA DEL MODAL ---
    window.abrirModalAutor = function(nombreOriginal) {
        inputNombre.value = nombreOriginal; 
        inputCedula.value = '';
        document.getElementById('modal-autor-nacionalidad').value = 'V-'; // Reseteamos el select por defecto
        modalAutor.style.display = 'flex';
        
        buscador.value = '';
        resultadosBox.style.display = 'none';
    };

    window.cerrarModalAutor = function() {
        modalAutor.style.display = 'none';
    };

    window.confirmarModalAutor = function() {
        const nombre = inputNombre.value.trim();
        const nacionalidad = document.getElementById('modal-autor-nacionalidad').value;
        const cedulaNum = inputCedula.value.trim();

        if (nombre === '') {
            alert("El nombre del autor es obligatorio.");
            return;
        }
    
        
        // Unimos el "V-" o "E-" con los números que escribió el usuario
        const cedulaCompleta = nacionalidad + cedulaNum;
        
        const pseudoId = 'nuevo_' + Date.now();
        
        // 1. Lo guardamos en la lista general con la cédula armada
        listaAutores.push({id: pseudoId, nombre_completo: nombre, cedula: cedulaCompleta});
        
        // 2. Lo guardamos en el diccionario de nuevos autores
        autoresNuevosMap[pseudoId] = {nombre: nombre, cedula: cedulaCompleta};

        // 3. Añadimos el ID a los seleccionados y redibujamos
        agregarAutor(pseudoId, nombre);
        cerrarModalAutor();
    };
    renderChips();
});

// --- VALIDACIÓN MAESTRA DEL FORMULARIO ---
window.validarFormulario = function() {
    let esValido = true;

    // 1. Validar Autores
    const hiddenInputsBox = document.getElementById('autores-hidden-inputs');
    const errorAutores = document.getElementById('error-autores');
    
    if (hiddenInputsBox.children.length === 0) {
        errorAutores.style.display = 'block';
        esValido = false; 
    } else {
        errorAutores.style.display = 'none';
    }

    // 2. Validar Categorías
    const checkboxesCat = document.querySelectorAll('input[name="categorias[]"]:checked');
    const errorCategorias = document.getElementById('error-categorias');
    const boxCategorias = document.getElementById('box-categorias');
    
    // Verificamos si los elementos existen (para que el script no falle si en un futuro los quitas)
    if (boxCategorias && errorCategorias) {
        if (checkboxesCat.length === 0) {
            errorCategorias.style.display = 'block';
            boxCategorias.style.borderColor = '#dc2626'; // Borde rojo de alerta
            esValido = false;
        } else {
            errorCategorias.style.display = 'none';
            boxCategorias.style.borderColor = 'rgba(0,0,0,0.1)'; // Borde normal
        }
    }

    // El formulario solo se envía si todo está correcto
    return esValido; 
};