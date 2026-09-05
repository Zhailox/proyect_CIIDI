<?php require_once __DIR__ . '/../services/ConfigService.php'; ?>
<div class="main-content">
    <div class="pst-container">

        <!-- HERO SECTION INTERACTIVO CON CANVAS Y BÚSQUEDA RÁPIDA -->
        <section class="pst-modern-hero">
            <canvas id="pstHeroCanvas"></canvas>
            <div class="pst-hero-overlay"></div>
            <div class="pst-hero-inner">
                <h1 class="pst-hero-title">Proyectos Socio-Tecnológicos</h1>
                <p class="pst-hero-desc">Explora el conocimiento académico y las soluciones tecnológicas desarrolladas por nuestra comunidad universitaria.</p>
                <span class="pst-hero-badge"><i class="ph ph-sparkles"></i> Repositorio Institucional de Investigaciones</span>
            </div>
        </section>

        <!-- CARRUSELES POR LÍNEA DE INVESTIGACIÓN CON BOTÓN SOLICITAR CARGAR MÁS -->
        <section class="pst-carousels-section">
            <!-- Carrusel: Por Líneas de Investigación -->
            <div class="pst-carousel-block">
                <div class="pst-carousel-header">
                    <h3><i class="ph ph-compass"></i> Proyectos Recientes por Línea</h3>
                    <div class="pst-carousel-controls">
                        <button type="button" class="pst-carousel-btn" onclick="scrollCarousel('carouselProyectos', -1)"><i class="ph ph-caret-left"></i></button>
                        <button type="button" class="pst-carousel-btn" onclick="scrollCarousel('carouselProyectos', 1)"><i class="ph ph-caret-right"></i></button>
                    </div>
                </div>
                <div class="pst-carousel-track-wrapper">
                    <div class="pst-carousel-track" id="carouselProyectos">
                        <?php if (!empty($documentos)): ?>
                            <?php foreach (array_slice($documentos, 0, 8) as $docSlide): ?>
                                <div class="pst-project-slide">
                                    <div class="pst-slide-tags">
                                        <span class="pst-badge-soft" style="background: rgba(0, 123, 255, 0.1); color: var(--color-terciario); font-weight: 700;">
                                            <?= htmlspecialchars($docSlide['linea_nombre'] ?? 'Línea General') ?>
                                        </span>
                                        <span class="pst-badge-soft" style="background: rgba(112, 144, 203, 0.15); color: var(--color-secundario); font-weight: 700;">
                                            <?= $docSlide['anio_publicacion'] ?>
                                        </span>
                                    </div>
                                    <h4><?= htmlspecialchars($docSlide['titulo'] ?? '') ?></h4>
                                    <p class="pst-slide-community"><i class="ph ph-buildings"></i> <?= htmlspecialchars($docSlide['comunidad_beneficiada'] ?? 'Comunidad no especificada') ?></p>
                                    <div class="pst-slide-footer">
                                        <a href="?ruta=detalles-pst&id=<?= $docSlide['id'] ?>" class="btn-outline-repo" style="font-size: 0.75rem; text-decoration: none;">Ver Ficha</a>
                                        <button type="button" class="btn-outline-repo" style="font-size: 0.75rem; cursor: pointer;" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($docSlide['titulo'])) ?>, <?= htmlspecialchars(json_encode($docSlide['autores_nombres'] ?? 'Autores Varios')) ?>, <?= $docSlide['anio_publicacion'] ?>)"><i class="ph ph-quotes"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: var(--texto-silenciado); padding: 1rem;">No hay proyectos para mostrar en el carrusel.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layout de Cuadrícula con Sidebar a la derecha -->
        <div class="pst-layout-grid">
            
            <!-- Columna Izquierda: Tabla de Resultados Espaciada -->
            <div class="pst-main-column">
                
                <!-- Listado de Investigaciones Indexadas (Tabla Espaciada que respira) -->
                <section class="pst-table-wrapper">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                        <h3 class="pst-table-title" style="margin-bottom: 0;">Banco de Proyectos</h3>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="pst-table pst-table-spaced">
                            <thead>
                                <tr>
                                    <th style="width: 65%;">TÍTULO DEL PROYECTO</th>
                                    <th style="width: 20%;">LÍNEA / NIVEL</th>
                                    <th style="width: 7%;">AÑO</th>
                                    <th style="text-align: center; width: 8%;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="pstTableBody">
                                <?php if (empty($documentos)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2.5rem; color: var(--texto-silenciado);">
                                            No se encontraron proyectos con los filtros de catalogación seleccionados.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($documentos as $doc): ?>
                                        <tr>
                                            <td class="pst-td-title">
                                                <strong><?= htmlspecialchars($doc['titulo'] ?? '') ?></strong>
                                            </td>
                                            <td>
                                                <span class="pst-badge-soft badge-edu" style="display: block; margin-bottom: 0.2rem; text-align: center;">
                                                    <?= htmlspecialchars($doc['linea_nombre'] ?? 'General') ?>
                                                </span>
                                                <small style="color: var(--color-secundario); font-weight: 700; display: block; font-size: 0.7rem; text-align: center;">
                                                    <?= htmlspecialchars($doc['nivel_academico'] ?? 'Pregrado') ?><?= (($doc['nivel_academico'] ?? 'Pregrado') === 'Pregrado' && !empty($doc['trayecto'])) ? ' • ' . htmlspecialchars($doc['trayecto']) : '' ?>
                                                </small>
                                            </td>
                                            <td><strong><?= $doc['anio_publicacion'] ?></strong></td>
                                            <td style="text-align: center;">
                                                <div style="display: flex; gap: 0.35rem; justify-content: center;">
                                                    <a href="?ruta=detalles-pst&id=<?= $doc['id'] ?>" class="btn-outline-repo" style="text-decoration: none; padding: 0.35rem 0.6rem; font-size: 0.75rem;" title="Ver Ficha">
                                                        Ver
                                                    </a>
                                                    <button type="button" class="btn-outline-repo" style="padding: 0.35rem 0.6rem; font-size: 0.75rem; cursor: pointer;" title="Generar Cita" onclick="abrirModalCita(<?= htmlspecialchars(json_encode($doc['titulo'])) ?>, <?= htmlspecialchars(json_encode($doc['autores_nombres'] ?? 'Autores Varios')) ?>, <?= $doc['anio_publicacion'] ?>)">
                                                        <i class="ph ph-quotes"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- Columna Derecha: Sidebar Panel de Filtrado -->
            <aside class="pst-sidebar-column">
                <form id="pstFilterForm" method="GET" action="" class="pst-sidebar-panel">
                    <input type="hidden" name="ruta" value="repositorio">
                    <input type="hidden" name="anio" id="filterAnioInput" value="<?= htmlspecialchars($filtros['anio'] ?? '') ?>">
                    
                    <h3><i class="ph ph-funnel"></i> Filtrar Recursos</h3>
                    
                    <!-- Mostrar carrera como informativa (fija) -->
                    <div class="filter-group">
                        <label>Programa Académico</label>
                        <select disabled>
                            <option>PNF en Informática</option>
                        </select>
                    </div>

                    <!-- Filtro por Tiempo: Histograma por Año -->
                    <div class="filter-group">
                        <label><i class="ph ph-chart-bar"></i> Distribución por Año</label>
                        <div class="year-histogram-container">
                            <?php 
                            $maxCount = !empty($anioCounts) ? max($anioCounts) : 1;
                            if ($maxCount <= 0) $maxCount = 1;
                            foreach ($anioCounts as $year => $count): 
                                $percent = ($count / $maxCount) * 100;
                                $isActive = (isset($filtros['anio']) && (int)$filtros['anio'] === $year);
                            ?>
                                <div class="histogram-col <?= $isActive ? 'active' : '' ?>" 
                                     data-year="<?= $year ?>" 
                                     onclick="selectYear(<?= $year ?>)"
                                     title="<?= $year ?>: <?= $count ?> proyectos">
                                    <div class="histogram-bar-wrapper">
                                        <div class="histogram-bar" style="height: <?= max(5, $percent) ?>%;">
                                            <span class="histogram-tooltip"><?= $count ?></span>
                                        </div>
                                    </div>
                                    <span class="histogram-year-label"><?= substr((string)$year, 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($filtros['anio'])): ?>
                            <button type="button" class="btn-reset-year" onclick="selectYear('')">
                                <i class="ph ph-x-circle"></i> Quitar año (<?= $filtros['anio'] ?>)
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Selector de Nivel Académico -->
                    <div class="filter-group">
                        <label for="nivel_academico">Nivel Académico</label>
                        <select name="nivel_academico" id="nivel_academico_filter" onchange="this.form.submit()">
                            <option value="">Todos los Niveles</option>
                            <option value="Pregrado" <?= (($filtros['nivel_academico'] ?? '') === 'Pregrado') ? 'selected' : '' ?>>Pregrado</option>
                            <option value="Especialización" <?= (($filtros['nivel_academico'] ?? '') === 'Especialización') ? 'selected' : '' ?>>Especialización</option>
                            <option value="Maestría" <?= (($filtros['nivel_academico'] ?? '') === 'Maestría') ? 'selected' : '' ?>>Maestría</option>
                            <option value="Doctorado" <?= (($filtros['nivel_academico'] ?? '') === 'Doctorado') ? 'selected' : '' ?>>Doctorado</option>
                        </select>
                    </div>

                    <!-- Selector de Trayecto -->
                    <div class="filter-group">
                        <label for="trayecto">Trayecto del PNF (Pregrado)</label>
                        <select name="trayecto" id="trayecto_filter" onchange="this.form.submit()">
                            <option value="">Todos los Trayectos</option>
                            <option value="Trayecto I" <?= (($filtros['trayecto'] ?? '') === 'Trayecto I') ? 'selected' : '' ?>>Trayecto I</option>
                            <option value="Trayecto II" <?= (($filtros['trayecto'] ?? '') === 'Trayecto II') ? 'selected' : '' ?>>Trayecto II</option>
                            <option value="Trayecto III" <?= (($filtros['trayecto'] ?? '') === 'Trayecto III') ? 'selected' : '' ?>>Trayecto III</option>
                            <option value="Trayecto IV" <?= (($filtros['trayecto'] ?? '') === 'Trayecto IV') ? 'selected' : '' ?>>Trayecto IV</option>
                        </select>
                    </div>

                    <!-- Selector de Línea de Investigación -->
                    <div class="filter-group">
                        <label for="linea_id">Línea de Investigación</label>
                        <select name="linea_id" id="linea_id">
                            <option value="">Todas las Líneas</option>
                            <?php foreach ($lineas as $linea): ?>
                                <option value="<?= $linea['id'] ?>" <?= ($filtros['linea_id'] == $linea['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($linea['nombre'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Selector de Dimensión Operativa Dependiente -->
                    <div class="filter-group">
                        <label for="dimension_id">Dimensión Operativa</label>
                        <select name="dimension_id" id="dimension_id" disabled>
                            <option value="">Todas las Dimensiones</option>
                        </select>
                    </div>

                    <a href="?ruta=repositorio" class="btn-clear-filters">Limpiar Filtros</a>
                </form>
            </aside>

        </div>

    </div>
</div>

<script>
// ANIMACIÓN DE HERO CANVAS (RED DE NODOS / CONSTELACIÓN DIGITAL DE CONOCIMIENTO)
(function initPstHeroCanvas() {
    const canvas = document.getElementById('pstHeroCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];

    function resize() {
        width = canvas.width = canvas.parentElement.offsetWidth;
        height = canvas.height = canvas.parentElement.offsetHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.6;
            this.vy = (Math.random() - 0.5) * 0.6;
            this.radius = Math.random() * 2 + 1;
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(112, 144, 203, 0.7)';
            ctx.fill();
        }
    }

    const numParticles = Math.min(Math.floor(width / 20), 45);
    for (let i = 0; i < numParticles; i++) {
        particles.push(new Particle());
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);
        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 110) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(112, 144, 203, ${1 - dist / 110})`;
                    ctx.lineWidth = 0.6;
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(animate);
    }
    animate();
})();

// CONTROL DE SCROLL HORIZONTAL EN CARRUSELES
function scrollCarousel(elementId, direction) {
    const track = document.getElementById(elementId);
    if (!track) return;
    const scrollAmount = track.offsetWidth * 0.75;
    track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}

// FILTRAR POR TRAYECTO DESDE CARRUSEL
function filtrarPorTrayecto(trayectoNombre) {
    const filterSelect = document.getElementById('trayecto_filter');
    if (filterSelect) {
        filterSelect.value = trayectoNombre;
        filterSelect.form.submit();
    }
}

// LAZY LOADING / INFINITE SCROLL AUTOMÁTICO EN EL CARRUSEL Y EN LA TABLA
let currentPagePst = <?= json_encode((int)($pagination['current_page'] ?? 1)) ?>;
let totalPagesPst = <?= json_encode((int)($pagination['total_pages'] ?? 1)) ?>;
let isFetchingPst = false;

function lazyLoadMasProyectos() {
    if (isFetchingPst || currentPagePst >= totalPagesPst) return;
    
    isFetchingPst = true;
    const nextPage = currentPagePst + 1;
    const currentUrlParams = new URLSearchParams(window.location.search);
    currentUrlParams.set('page', nextPage);
    
    fetch('?' + currentUrlParams.toString())
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // 1. Agregar filas a la Tabla Principal
            const newRows = doc.querySelectorAll('#pstTableBody tr');
            const tableBody = document.getElementById('pstTableBody');
            if (newRows && newRows.length > 0 && tableBody) {
                newRows.forEach(row => {
                    tableBody.appendChild(row.cloneNode(true));
                });
            }

            // 2. Agregar tarjetas al Carrusel de Proyectos
            const newSlides = doc.querySelectorAll('#carouselProyectos .pst-project-slide');
            const carouselTrack = document.getElementById('carouselProyectos');
            if (newSlides && newSlides.length > 0 && carouselTrack) {
                newSlides.forEach(slide => {
                    carouselTrack.appendChild(slide.cloneNode(true));
                });
            }

            currentPagePst = nextPage;
            isFetchingPst = false;
        })
        .catch(err => {
            console.error('Error al realizar Lazy Loading:', err);
            isFetchingPst = false;
        });
}

// Escuchar Scroll Horizontal en el Carrusel para disparar Lazy Loading al llegar al final
document.addEventListener('DOMContentLoaded', () => {
    const carouselTrack = document.getElementById('carouselProyectos');
    if (carouselTrack) {
        carouselTrack.addEventListener('scroll', () => {
            if (carouselTrack.scrollLeft + carouselTrack.clientWidth >= carouselTrack.scrollWidth - 150) {
                lazyLoadMasProyectos();
            }
        });
    }

    // Escuchar Scroll Vertical de la ventana para la Tabla
    window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 400) {
            lazyLoadMasProyectos();
        }
    });
});

// Función para seleccionar/deseleccionar un año en el histograma del sidebar
function selectYear(year) {
    const input = document.getElementById('filterAnioInput');
    if (input) {
        input.value = year;
        document.getElementById('pstFilterForm').submit();
    }
}
// JSON con todas las dimensiones operativas del sistema
const todasDimensiones = <?= json_encode($dimensiones) ?>;
const activeDimensionId = <?= json_encode($filtros['dimension_id'] ?? '') ?>;

// Función para actualizar dinámicamente las opciones del selector de dimensión
function updateDimensionOptions(selectedLineaId) {
    const dimSelect = document.getElementById('dimension_id');
    
    // Resetear opciones
    dimSelect.innerHTML = '<option value="">Todas las Dimensiones</option>';
    
    if (!selectedLineaId) {
        dimSelect.disabled = true;
        return;
    }
    
    dimSelect.disabled = false;
    
    // Filtrar dimensiones que pertenecen a la línea seleccionada
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

document.addEventListener('DOMContentLoaded', () => {
    const lineaSelect = document.getElementById('linea_id');
    
    // Inicializar el selector al cargar
    updateDimensionOptions(lineaSelect.value);
    
    // Escuchar el cambio en el selector de Línea
    lineaSelect.addEventListener('change', (e) => {
        updateDimensionOptions(e.target.value);
        e.target.form.submit(); // Submit automático
    });
    
    // Escuchar el cambio en el selector de Dimensión
    const dimSelect = document.getElementById('dimension_id');
    if (dimSelect) {
        dimSelect.addEventListener('change', () => {
            dimSelect.form.submit();
        });
    }
});

// MODAL DE GENERACIÓN DE CITAS ACADÉMICAS DINÁMICAS
const configuracionesCitas = <?= json_encode(ConfigService::get('citas.estilos', [])) ?>;

function abrirModalCita(titulo, autores, anio) {
    const listEl = document.getElementById('listaCitasDinamicas');
    if (!listEl) return;
    
    listEl.innerHTML = '';
    
    const mockData = {
        '{autores}': autores || 'Autores Varios',
        '{anio}': anio || 's.f.',
        '{titulo}': titulo || 'Proyecto Socio-Tecnológico',
        '{carrera}': 'PNF en Informática'
    };
    
    let count = 0;
    for (const [slug, item] of Object.entries(configuracionesCitas)) {
        if (!item.activo) continue;
        count++;
        
        let textoCita = item.plantilla || '';
        for (const [k, v] of Object.entries(mockData)) {
            textoCita = textoCita.replaceAll(k, v);
        }
        
        const boxId = 'cita_txt_' + slug;
        const cardHtml = `
            <div style="margin-bottom: 0.85rem;">
                <strong style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--color-secundario); text-transform: uppercase; margin-bottom: 0.25rem;">
                    <span>${item.nombre || slug}</span>
                    <button type="button" onclick="copiarCitaText('${boxId}', this)" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 3px; cursor: pointer; font-weight: 700;">
                        <i class="ph ph-copy"></i> Copiar
                    </button>
                </strong>
                <div id="${boxId}" style="background: #fafbfe; border: 1px solid rgba(169, 168, 166, 0.15); padding: 0.5rem 0.65rem; border-radius: 4px; font-size: 0.8rem; color: var(--texto-normal); font-family: monospace; line-height: 1.4;">${textoCita}</div>
            </div>
        `;
        listEl.insertAdjacentHTML('beforeend', cardHtml);
    }
    
    if (count === 0) {
        listEl.innerHTML = '<p style="font-size: 0.8rem; color: var(--texto-silenciado);">No hay formatos de cita activos en la configuración.</p>';
    }
    
    document.getElementById('modalCitasContainer').style.display = 'flex';
}

function cerrarModalCitas() {
    document.getElementById('modalCitasContainer').style.display = 'none';
}

function copiarCitaText(elementId, btn) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const text = el.textContent;

    const mostrarExito = () => {
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="ph ph-check"></i> ¡Copiado!';
        setTimeout(() => { btn.innerHTML = origText; }, 2000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(mostrarExito).catch(() => {
            fallbackCopiarTexto(text, mostrarExito);
        });
    } else {
        fallbackCopiarTexto(text, mostrarExito);
    }
}

function fallbackCopiarTexto(text, onSuccess) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        const successful = document.execCommand('copy');
        if (successful && typeof onSuccess === 'function') {
            onSuccess();
        }
    } catch (err) {
        console.error('Error al copiar texto via fallback: ', err);
    }
    document.body.removeChild(textArea);
}
</script>

<!-- Modal Citas Académicas -->
<div id="modalCitasContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 34, 68, 0.7); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card, #ffffff); border: 1px solid rgba(169, 168, 166, 0.2); border-radius: 8px; width: 90%; max-width: 540px; padding: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(169, 168, 166, 0.2); padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--texto-titulos); margin: 0; display: flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-quotes" style="color: var(--color-terciario);"></i> Formato de Cita Académica
            </h3>
            <button type="button" onclick="cerrarModalCitas()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--texto-silenciado);">&times;</button>
        </div>
        
        <div id="listaCitasDinamicas" style="max-height: 380px; overflow-y: auto;">
            <!-- Contenido inyectado dinámicamente -->
        </div>

        <div style="text-align: right; border-top: 1px solid rgba(169, 168, 166, 0.15); padding-top: 0.5rem;">
            <button type="button" onclick="cerrarModalCitas()" class="btn-clear-filters" style="display: inline-block; width: auto; padding: 0.35rem 1rem;">Cerrar</button>
        </div>
    </div>
</div>