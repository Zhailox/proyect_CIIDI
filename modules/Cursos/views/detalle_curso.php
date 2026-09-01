<?php
// modules/Cursos/views/detalle_curso.php
// Variables disponibles: $curso, $usuario_actual

$img = !empty($curso['imagen_portada'])
    ? htmlspecialchars($curso['imagen_portada'])
    : 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?auto=format&fit=crop&q=80&w=1200';

$fecha = date('d \d\e M, Y', strtotime($curso['fecha_creacion']));
$nota = number_format((float)$curso['nota_minima_aprobacion'], 1);
?>

<div class="cur-detalle-wrapper">

    <!-- Breadcrumb -->
    <nav class="cur-breadcrumb cur-detalle-breadcrumb">
        <a href="?ruta=cursos">
            <i class="ph-fill ph-graduation-cap"></i> Catálogo de Cursos
        </a>
        <i class="ph-bold ph-caret-right"></i>
        <span>Detalles del Curso</span>
    </nav>

    <!-- Header / Portada del Curso -->
    <header class="cur-detalle-header" style="background-image: linear-gradient(rgba(18,26,62,0.85), rgba(18,26,62,0.95)), url('<?= $img ?>');">
        <div class="cur-detalle-header-inner">
            <div class="cur-detalle-badge">
                <i class="ph-fill ph-star"></i> Curso Oficial
            </div>
            <h1 class="cur-detalle-titulo"><?= htmlspecialchars($curso['titulo']) ?></h1>
            <p class="cur-detalle-subtitle">Aprende con los mejores profesionales y potencia tus habilidades en esta área.</p>
            
            <div class="cur-detalle-meta">
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-chalkboard-teacher"></i>
                    <div>
                        <span>Docente</span>
                        <strong><?= htmlspecialchars($curso['nombre_docente'] ?? 'No asignado') ?></strong>
                    </div>
                </div>
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-calendar-blank"></i>
                    <div>
                        <span>Publicado el</span>
                        <strong><?= $fecha ?></strong>
                    </div>
                </div>
                <div class="cur-detalle-meta-item">
                    <i class="ph-fill ph-medal"></i>
                    <div>
                        <span>Nota Mínima</span>
                        <strong><?= $nota ?> / 100</strong>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="cur-detalle-layout">
        
        <!-- Columna Izquierda: Descripción e Info -->
        <main class="cur-detalle-main">
            <section class="cur-detalle-section">
                <h2><i class="ph-fill ph-info"></i> Acerca de este curso</h2>
                <div class="cur-detalle-content">
                    <?php if (!empty($curso['descripcion'])): ?>
                        <p><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
                    <?php else: ?>
                        <p class="cur-text-muted">Este curso aún no tiene una descripción detallada provista por el docente.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="cur-detalle-section">
                <h2><i class="ph-fill ph-list-checks"></i> Lo que aprenderás</h2>
                <ul class="cur-detalle-list">
                    <li><i class="ph-bold ph-check-circle"></i> Dominar los conceptos fundamentales del tema.</li>
                    <li><i class="ph-bold ph-check-circle"></i> Aplicar técnicas prácticas en entornos reales.</li>
                    <li><i class="ph-bold ph-check-circle"></i> Desarrollar proyectos guiados por el docente.</li>
                    <li><i class="ph-bold ph-check-circle"></i> Obtener las bases para certificaciones avanzadas.</li>
                </ul>
            </section>
        </main>

        <!-- Columna Derecha: Sidebar de Acción -->
        <aside class="cur-detalle-sidebar">
            <div class="cur-detalle-card">
                <div class="cur-detalle-card-img" style="background-image: url('<?= $img ?>');"></div>
                <div class="cur-detalle-card-body">
                    <h3>¡Inscríbete ahora!</h3>
                    <p>Accede a todo el material, foros y asesorías directas con el docente.</p>
                    
                    <button class="btn cur-btn-enroll" onclick="alert('Funcionalidad de inscripción en desarrollo.')">
                        <i class="ph-bold ph-student"></i> Solicitar Inscripción
                    </button>
                    
                    <div class="cur-detalle-features">
                        <div class="feature-item"><i class="ph-fill ph-monitor-play"></i> 100% Online</div>
                        <div class="feature-item"><i class="ph-fill ph-clock"></i> Acceso 24/7</div>
                        <div class="feature-item"><i class="ph-fill ph-certificate"></i> Certificado de aprobación</div>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</div>
