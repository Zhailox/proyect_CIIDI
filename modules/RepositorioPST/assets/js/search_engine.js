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

// Cambia de modo (Estándar vs IA)
function toggleSearchModeVisual() {
    const modeInputSidebar = document.getElementById('searchModeInput');
    const modeInputTop = document.getElementById('searchModeInputTop');
    const slider = document.getElementById('sliderVisual');
    
    const currentMode = modeInputTop.value;
    const targetMode = (currentMode === 'B') ? 'A' : 'B';
    
    if (modeInputSidebar) modeInputSidebar.value = targetMode;
    if (modeInputTop) modeInputTop.value = targetMode;
    
    if (targetMode === 'B') {
        slider.classList.add('checked');
    } else {
        slider.classList.remove('checked');
    }
    
    updateUIForMode(targetMode === 'B');
    
    // Auto submit to refresh results with the new search mode
    submitFilterForm();
}

// Actualiza los estilos visuales del buscador según el modo
function updateUIForMode(isIA) {
    const searchBarContainer = document.getElementById('searchBarContainer');
    const searchInput = document.getElementById('searchQueryInput');
    const labelA = document.getElementById('labelModeA');
    const labelB = document.getElementById('labelModeB');
    const wrapper = document.querySelector('.search-view-wrapper');
    
    if (isIA) {
        if (wrapper) wrapper.classList.add('ia-mode-active');
        if (searchBarContainer) searchBarContainer.classList.add('ia-mode-container');
        if (searchInput) searchInput.placeholder = "Describe tu propuesta o componentes de investigación (Búsqueda Semántica IA)...";
        if (labelA) labelA.classList.remove('active-label');
        if (labelB) labelB.classList.add('active-label');
    } else {
        if (wrapper) wrapper.classList.remove('ia-mode-active');
        if (searchBarContainer) searchBarContainer.classList.remove('ia-mode-container');
        if (searchInput) searchInput.placeholder = "Buscar por títulos, palabras clave o resumen abstract...";
        if (labelA) labelA.classList.add('active-label');
        if (labelB) labelB.classList.remove('active-label');
    }
}

// Envía el formulario unificando la búsqueda y los filtros
function submitFilterForm() {
    const filterForm = document.getElementById('searchFilterForm');
    const queryInput = document.getElementById('searchQueryInput');
    const queryHidden = document.getElementById('searchQueryHidden');
    
    if (filterForm) {
        // Copiar query del top form al hidden del sidebar form para enviar todo junto
        if (queryInput && queryHidden) {
            queryHidden.value = queryInput.value;
        }
        
        const modeInput = document.getElementById('searchModeInput');
        if (modeInput && modeInput.value === 'B') {
            const banner = document.getElementById('simulationBanner');
            if (banner) banner.classList.add('active');
            setTimeout(() => {
                filterForm.submit();
            }, 1000);
        } else {
            filterForm.submit();
        }
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
    
    // Inicializar visualmente el modo
    const modeInputTop = document.getElementById('searchModeInputTop');
    if (modeInputTop) {
        updateUIForMode(modeInputTop.value === 'B');
    }
});
