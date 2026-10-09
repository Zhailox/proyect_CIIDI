document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador-autores');
    const resultadosBox = document.getElementById('resultados-autores');
    const seleccionadosBox = document.getElementById('autores-seleccionados');
    const hiddenInputsBox = document.getElementById('autores-hidden-inputs');
    const errorMsg = document.getElementById('error-autores');
    
    // Elementos del Modal
    const modalAutor = document.getElementById('modal-autor');
    const inputNombre = document.getElementById('modal-autor-nombre');
    const inputOrcid = document.getElementById('modal-autor-orcid'); // ORCID ahora manda
    const inputBiografia = document.getElementById('modal-autor-biografia');
    const inputWeb = document.getElementById('modal-autor-web'); // Nueva Web
    
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
            (a.orcid && a.orcid.toLowerCase().includes(query))
        );

        if (filtrados.length > 0) {
            filtrados.forEach(autor => {
                if(autoresSeleccionados.has(autor.id)) return; 

                let item = document.createElement('div');
                item.className = 'autocomplete-item';
                let subTexto = autor.orcid ? `<i class="ph-fill ph-identification-badge" style="color:var(--color-terciario);"></i> ORCID: ${autor.orcid}` : 'Sin ORCID registrado';
                item.innerHTML = `<strong>${autor.nombre_completo}</strong> <span class="text-muted" style="font-size:0.8rem; display: flex; align-items: center; gap: 4px;">${subTexto}</span>`;
                
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

    window.abrirModalAutor = function(nombreOriginal) {
        inputNombre.value = nombreOriginal; 
        if (inputOrcid) inputOrcid.value = '';
        if (inputBiografia) inputBiografia.value = '';
        if (inputWeb) inputWeb.value = '';
        
        // NUEVO: Limpiamos el ORCID para que no se quede pegado el del autor anterior
        const orcidInput = document.getElementById('modal-autor-orcid');
        if (document.getElementById('modal-autor-biografia')) document.getElementById('modal-autor-biografia').value = '';
        if (orcidInput) orcidInput.value = '';

        modalAutor.style.display = 'flex';
        
        buscador.value = '';
        resultadosBox.style.display = 'none';
    };

    window.cerrarModalAutor = function() {
        modalAutor.style.display = 'none';
    };

    window.confirmarModalAutor = function() {
        const nombre = inputNombre.value.trim();
        const orcidInput = document.getElementById('modal-autor-orcid');
        const orcidVal = orcidInput ? orcidInput.value.trim() : '';
        const bioInput = document.getElementById('modal-autor-biografia');
        const biografiaVal = bioInput ? bioInput.value.trim() : '';
        const inputWeb = document.getElementById('modal-autor-web');
        const webVal = inputWeb ? inputWeb.value.trim() : '';

        if (nombre === '' || orcidVal === '') {
            alert("El nombre y el ORCID son obligatorios para registrar al investigador.");
            return;
        }
        const orcidRegex = /^(https?:\/\/orcid\.org\/)?\d{4}-\d{4}-\d{4}-\d{3}[0-9X]$/i;
        if (!orcidRegex.test(orcidVal)) {
            alert("El ORCID no es válido. Debe tener el formato 0000-0000-0000-0000 o ser un enlace a orcid.org.");
            return;
        }
    
    
        const pseudoId = 'nuevo_' + Date.now();
        
        // 1. Lo guardamos en la lista general con la cédula armada (o nula)
        listaAutores.push({id: pseudoId, nombre_completo: nombre, orcid: orcidVal, biografia: biografiaVal, pagina_web: webVal});
        autoresNuevosMap[pseudoId] = {nombre: nombre, orcid: orcidVal, biografia: biografiaVal, pagina_web: webVal};

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