// modules/RepositorioPST/assets/js/search_engine.js

let todasDimensiones = [];
let activeDimensionId = '';
let nivelesConTrayectoList = ['Pregrado'];

// Inicialización de Dimensiones y Filtros Dependientes
function initDimensionSelector(dims, activeDim, nivelesTrayecto) {
    todasDimensiones = dims || [];
    activeDimensionId = activeDim || '';
    if (Array.isArray(nivelesTrayecto) && nivelesTrayecto.length > 0) {
        nivelesConTrayectoList = nivelesTrayecto;
    }
    
    const lineaSelect = document.getElementById('linea_id');
    if (lineaSelect) {
        updateDimensionOptions(lineaSelect.value);
        
        lineaSelect.addEventListener('change', (e) => {
            activeDimensionId = '';
            updateDimensionOptions(e.target.value);
            submitFilterForm();
        });
    }

    const dimSelect = document.getElementById('dimension_id');
    if (dimSelect) {
        dimSelect.addEventListener('change', () => {
            submitFilterForm();
        });
    }

    const nivelSelect = document.getElementById('nivel_academico');
    const trayectoSelect = document.getElementById('trayecto');
    if (nivelSelect) {
        updateTrayectoState(nivelSelect.value);
        nivelSelect.addEventListener('change', (e) => {
            updateTrayectoState(e.target.value);
            submitFilterForm();
        });
    }

    if (trayectoSelect) {
        trayectoSelect.addEventListener('change', () => {
            submitFilterForm();
        });
    }

    const ordenSelect = document.getElementById('ordenSelect');
    if (ordenSelect) {
        ordenSelect.addEventListener('change', () => {
            submitFilterForm();
        });
    }
}

// Habilita o deshabilita el selector de trayecto según el nivel académico seleccionado
function updateTrayectoState(nivelVal) {
    const trayectoSelect = document.getElementById('trayecto');
    if (!trayectoSelect) return;
    if (!nivelVal || nivelesConTrayectoList.includes(nivelVal)) {
        trayectoSelect.disabled = false;
    } else {
        trayectoSelect.value = '';
        trayectoSelect.disabled = true;
    }
}

// Filtra y actualiza las opciones del selector de dimensión dependiente
function updateDimensionOptions(selectedLineaId) {
    const dimSelect = document.getElementById('dimension_id');
    if (!dimSelect) return;
    
    dimSelect.innerHTML = '<option value="">Todas las dimensiones</option>';
    
    if (!selectedLineaId) {
        dimSelect.disabled = true;
        return;
    }
    
    dimSelect.disabled = false;
    const filtered = todasDimensiones.filter(d => d.id_linea == selectedLineaId);
    
    filtered.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.nombre;
        if (d.id == activeDimensionId) {
            opt.selected = true;
        }
        dimSelect.appendChild(opt);
    });
}

// Selecciona o desactiva un año del histograma y envía el formulario
function selectYear(year) {
    const yearInput = document.getElementById('searchYearInput');
    if (yearInput) {
        yearInput.value = (year !== '' && String(yearInput.value) === String(year)) ? '' : year;
        submitFilterForm();
    }
}

// Envía el formulario unificando la búsqueda y los filtros
function submitFilterForm() {
    const filterForm = document.getElementById('searchFilterForm');
    const queryInput = document.getElementById('searchQueryInput');
    const queryHidden = document.getElementById('searchQueryHidden');
    
    if (filterForm) {
        if (queryInput && queryHidden) {
            queryHidden.value = queryInput.value.trim();
        }
        filterForm.submit();
    }
}

// Escuchar el envío del formulario del buscador principal
document.addEventListener('DOMContentLoaded', () => {
    const searchBarForm = document.getElementById('searchBarForm');
    if (searchBarForm) {
        searchBarForm.addEventListener('submit', (e) => {
            e.preventDefault();
            submitFilterForm();
        });
    }
});
