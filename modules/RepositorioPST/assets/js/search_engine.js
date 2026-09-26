// modules/RepositorioPST/assets/js/search_engine.js

let todasDimensiones = [];
let activeDimensionId = '';

// Inicialización de Dimensiones
function initDimensionSelector(dims, activeDim) {
    todasDimensiones = dims;
    activeDimensionId = activeDim;
    
    const lineaSelect = document.getElementById('linea_id');
    if (lineaSelect) {
        updateDimensionOptions(lineaSelect.value);
        
        lineaSelect.addEventListener('change', (e) => {
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

// Selecciona un año del histograma y envía el formulario
function selectYear(year) {
    const yearInput = document.getElementById('searchYearInput');
    if (yearInput) {
        yearInput.value = year;
        submitFilterForm();
    }
}

// Maneja el toggle de búsqueda semántica (IA)
function handleSemanticToggle(checkbox) {
    const isChecked = checkbox.checked;
    const hiddenInFilter = document.getElementById('searchUsarIaHidden');
    if (hiddenInFilter) {
        hiddenInFilter.value = isChecked ? '1' : '';
    }
    
    const label = document.querySelector('.semantic-toggle-label');
    if (label) {
        if (isChecked) {
            label.classList.add('active');
        } else {
            label.classList.remove('active');
        }
    }
    
    updateUIForMode(isChecked);
    
    // Si ya hay una consulta escrita, refrescamos automáticamente la búsqueda
    const queryInput = document.getElementById('searchQueryInput');
    if (queryInput && queryInput.value.trim() !== '') {
        submitFilterForm();
    }
}

// Actualiza los estilos visuales del buscador según el modo
function updateUIForMode(isIA) {
    const searchBarContainer = document.getElementById('searchBarContainer');
    const searchInput = document.getElementById('searchQueryInput');
    const iaHint = document.querySelector('.ia-hint');
    
    if (isIA) {
        if (searchBarContainer) searchBarContainer.classList.add('ia-mode-container');
        if (searchInput) searchInput.placeholder = "Describe tu propuesta o temática de investigación (Búsqueda Semántica con Redes Neuronales)...";
        if (iaHint) iaHint.classList.add('visible');
    } else {
        if (searchBarContainer) searchBarContainer.classList.remove('ia-mode-container');
        if (searchInput) searchInput.placeholder = "Buscar por títulos, palabras clave o resumen abstract...";
        if (iaHint) iaHint.classList.remove('visible');
    }
}

// Envía el formulario unificando la búsqueda y los filtros
function submitFilterForm() {
    const filterForm = document.getElementById('searchFilterForm');
    const queryInput = document.getElementById('searchQueryInput');
    const queryHidden = document.getElementById('searchQueryHidden');
    const usarIaCheckbox = document.getElementById('usarIaCheckbox');
    const usarIaHidden = document.getElementById('searchUsarIaHidden');
    
    if (filterForm) {
        if (queryInput && queryHidden) {
            queryHidden.value = queryInput.value.trim();
        }
        
        if (usarIaCheckbox && usarIaHidden) {
            usarIaHidden.value = usarIaCheckbox.checked ? '1' : '';
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
    
    const usarIaCheckbox = document.getElementById('usarIaCheckbox');
    if (usarIaCheckbox) {
        updateUIForMode(usarIaCheckbox.checked);
    }
});
