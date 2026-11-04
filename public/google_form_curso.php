<?php
// google_form_curso.php
// Formulario de Inscripción estilo Google Forms específico por curso,
// conectado al colector oficial de Google Forms y con el nombre del curso integrado.

$cursoId   = (int)($_GET['curso_id'] ?? 1);
$titulo    = trim((string)($_GET['titulo'] ?? 'Curso de Formación CIIDI'));
$docente   = trim((string)($_GET['docente'] ?? 'Docente Investigador CIIDI'));
$modalidad = trim((string)($_GET['modalidad'] ?? 'Virtual'));
$duracion  = trim((string)($_GET['duracion'] ?? '40 horas'));
$nivel     = trim((string)($_GET['nivel'] ?? 'Básico'));

// Si tenemos SyncEngine disponible y solo pasaron curso_id, enriquecer datos desde el catálogo
if (file_exists(__DIR__ . '/includes/SyncEngine.php')) {
    require_once __DIR__ . '/includes/SyncEngine.php';
    try {
        $eng = new SyncEngine();
        $data = $eng->obtenerDatosPortal(false);
        foreach (($data['cursos'] ?? []) as $c) {
            if ((int)($c['id'] ?? 0) === $cursoId) {
                $titulo    = $c['titulo'] ?: $titulo;
                $docente   = $c['nombre_docente'] ?: $docente;
                $modalidad = $c['modalidad'] ?: $modalidad;
                $duracion  = $c['duracion'] ?: $duracion;
                $nivel     = $c['nivel'] ?: $nivel;
                break;
            }
        }
    } catch (Throwable $e) {
        // Ignorar y usar parámetros GET
    }
}

// Paleta oficial CIIDI (#505984 / #7090cb) con distintivo específico por curso
$paletas = [
    1 => ['accent' => '#505984', 'bg' => '#f4f7fb', 'badge' => 'FORMULARIO DE CURSO #01 · METODOLOGÍA I+D'],
    2 => ['accent' => '#3c456a', 'bg' => '#f4f7fb', 'badge' => 'FORMULARIO DE CURSO #02 · IA Y CIENCIA DE DATOS'],
    3 => ['accent' => '#505984', 'bg' => '#f4f7fb', 'badge' => 'FORMULARIO DE CURSO #03 · REDACCIÓN CIENTÍFICA Y APA 7'],
];
$tema = $paletas[$cursoId] ?? [
    'accent' => '#505984',
    'bg'     => '#f4f7fb',
    'badge'  => 'FORMULARIO OFICIAL DEL CURSO #' . str_pad((string)$cursoId, 2, '0', STR_PAD_LEFT),
];

// Pregunta específica según el curso para evidenciar que cada curso tiene su propio formulario
if ($cursoId === 1 || stripos($titulo, 'Metodolog') !== false) {
    $preguntaCursoLabel = 'Área o Línea de Investigación donde proyecta aplicar la Metodología';
    $preguntaCursoOpciones = [
        'Desarrollo de Software y Sistemas Inteligentes',
        'Soberanía Tecnológica y Gestión Pública',
        'Innovación Educativa y Entornos Virtuales',
        'Telecomunicaciones, Redes y Ciberseguridad',
    ];
} elseif ($cursoId === 2 || stripos($titulo, 'Inteligencia Artificial') !== false) {
    $preguntaCursoLabel = 'Nivel de experiencia previa en Python y Ciencia de Datos';
    $preguntaCursoOpciones = [
        'Inicial (conocimientos básicos de lógica y programación)',
        'Intermedio (manejo de estructuras de datos y scripts en Python)',
        'Avanzado (uso de pandas, scikit-learn o modelos de IA)',
    ];
} elseif ($cursoId === 3 || stripos($titulo, 'Normas APA') !== false) {
    $preguntaCursoLabel = 'Tipo de producción académica que desarrolla actualmente';
    $preguntaCursoOpciones = [
        'Artículo científico arbitrado / Ponencia',
        'Proyecto Socio-Tecnológico (PST) / Trabajo Especial de Grado',
        'Tesis de Postgrado (Especialización, Maestría o Doctorado)',
        'Actualización docente e identidad investigativa (ORCID)',
    ];
} else {
    $preguntaCursoLabel = 'Perfil del participante para este curso';
    $preguntaCursoOpciones = [
        'Estudiante de Pregrado / PNF',
        'Docente / Investigador Universitario',
        'Profesional o Investigador Externo',
    ];
}

$modPreselect = 'Virtual';
if (stripos($modalidad, 'Presencial') !== false) {
    $modPreselect = 'Presencial';
} elseif (stripos($modalidad, 'Híbrido') !== false || stripos($modalidad, 'Mixto') !== false) {
    $modPreselect = 'Mixto';
}

$googleRawPrefill = 'https://docs.google.com/forms/d/e/1FAIpQLSean-NUF1PEU6ci8MiNIQp13OewWCofp7O7V3MV8k_CA1CHNQ/viewform?usp=pp_url'
    . '&entry.2098787602=' . rawurlencode($modPreselect)
    . '&entry.69221431=' . rawurlencode('Curso: ' . $titulo . ' | Docente: ' . $docente);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción: <?= htmlspecialchars($titulo) ?> — Google Forms CIIDI</title>
    <link rel="icon" type="image/webp" href="assets/img/SinfondoCIIDI.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Google+Sans:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gf-accent: <?= $tema['accent'] ?>;
            --gf-bg: <?= $tema['bg'] ?>;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: var(--gf-bg);
            color: #202124;
            padding: 1.25rem 0.85rem 2.5rem;
            line-height: 1.5;
        }
        .gf-wrap {
            max-width: 680px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .gf-card {
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            padding: 1.5rem 1.6rem;
            box-shadow: 0 1px 3px rgba(60, 64, 67, 0.08);
            position: relative;
            overflow: hidden;
        }
        .gf-card-header {
            border-top: 10px solid var(--gf-accent);
            padding-top: 1.35rem;
        }
        .gf-badge-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.65rem;
        }
        .gf-course-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--gf-bg);
            color: var(--gf-accent);
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            padding: 0.28rem 0.7rem;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, 0.07);
        }
        .gf-title {
            font-family: 'Google Sans', 'Roboto', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #202124;
            line-height: 1.25;
            margin-bottom: 0.5rem;
        }
        .gf-subtitle {
            font-size: 0.92rem;
            color: #5f6368;
            margin-bottom: 0.9rem;
        }
        .gf-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.65rem;
            background: #f8f9fa;
            border: 1px solid #e8eaed;
            border-left: 4px solid var(--gf-accent);
            border-radius: 6px;
            padding: 0.75rem 0.95rem;
            font-size: 0.83rem;
            margin-bottom: 0.85rem;
        }
        .gf-meta-grid span {
            color: #5f6368;
            display: block;
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
        }
        .gf-meta-grid strong {
            color: #202124;
            font-weight: 700;
        }
        .gf-req-note {
            border-top: 1px solid #dadce0;
            padding-top: 0.65rem;
            font-size: 0.82rem;
            color: #d93025;
        }
        .gf-q-title {
            font-size: 1rem;
            font-weight: 500;
            color: #202124;
            margin-bottom: 0.85rem;
        }
        .gf-q-title .req {
            color: #d93025;
            margin-left: 0.2rem;
        }
        .gf-input {
            width: 100%;
            max-width: 420px;
            border: none;
            border-bottom: 1px solid #dadce0;
            padding: 0.45rem 0;
            font-size: 0.95rem;
            font-family: inherit;
            color: #202124;
            outline: none;
            transition: border-color 0.2s;
            background: transparent;
        }
        .gf-input.full {
            max-width: 100%;
        }
        .gf-input:focus {
            border-bottom: 2px solid var(--gf-accent);
        }
        .gf-input-locked {
            background: #f1f3f4;
            border: 1px solid #dadce0;
            border-radius: 6px;
            padding: 0.6rem 0.85rem;
            font-weight: 700;
            color: var(--gf-accent);
            max-width: 100%;
        }
        .gf-radio-list {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .gf-radio-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.93rem;
            color: #202124;
            cursor: pointer;
        }
        .gf-radio-item input[type="radio"] {
            accent-color: var(--gf-accent);
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .gf-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.25rem;
        }
        .gf-btn-submit {
            background-color: var(--gf-accent);
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 0.65rem 1.5rem;
            font-size: 0.92rem;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(60,64,67,0.3);
            transition: opacity 0.2s;
        }
        .gf-btn-submit:hover { opacity: 0.92; }
        .gf-btn-clear {
            background: transparent;
            color: var(--gf-accent);
            border: none;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            padding: 0.4rem 0.6rem;
            border-radius: 4px;
        }
        .gf-btn-clear:hover { background: rgba(0,0,0,0.04); }
        .gf-footer {
            text-align: center;
            font-size: 0.78rem;
            color: #5f6368;
            margin-top: 0.8rem;
        }
        .gf-brand-footer {
            font-family: 'Google Sans', 'Roboto', sans-serif;
            font-size: 1.15rem;
            color: #5f6368;
            margin-top: 0.35rem;
        }
        @media (max-width: 540px) {
            body { padding: 0.65rem 0.5rem 1.8rem; }
            .gf-card { padding: 1.15rem 1.1rem; }
            .gf-title { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

    <!-- Iframe oculto para enviar la respuesta real a Google Forms sin salir de la vista -->
    <iframe name="hidden_gf_target" id="hidden_gf_target" style="display:none;"></iframe>

    <div class="gf-wrap" id="gf-form-container">
        <!-- Encabezado con el Nombre del Curso -->
        <div class="gf-card gf-card-header">
            <div class="gf-badge-row">
                <span class="gf-course-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    <?= htmlspecialchars($tema['badge']) ?>
                </span>
                <span style="font-size:0.75rem; color:#5f6368; font-weight:500;">Google Forms · CIIDI UPTTMBI</span>
            </div>

            <h1 class="gf-title"><?= htmlspecialchars($titulo) ?></h1>
            <p class="gf-subtitle">
                Formulario de inscripción específico para el curso <strong><?= htmlspecialchars($titulo) ?></strong>. Completa tus datos para formalizar tu registro académico.
            </p>

            <div class="gf-meta-grid">
                <div>
                    <span>Curso Seleccionado</span>
                    <strong><?= htmlspecialchars($titulo) ?></strong>
                </div>
                <div>
                    <span>Facilitador / Docente</span>
                    <strong><?= htmlspecialchars($docente) ?></strong>
                </div>
                <div>
                    <span>Modalidad y Duración</span>
                    <strong><?= htmlspecialchars($modalidad) ?> · <?= htmlspecialchars($duracion) ?></strong>
                </div>
            </div>

            <div class="gf-req-note">* Indica que la pregunta es obligatoria</div>
        </div>

        <form id="gf-main-form"
              action="https://docs.google.com/forms/d/e/1FAIpQLSean-NUF1PEU6ci8MiNIQp13OewWCofp7O7V3MV8k_CA1CHNQ/formResponse"
              method="POST"
              target="hidden_gf_target"
              onsubmit="return procesarEnvioGoogleForm(event)"
              style="display:flex; flex-direction:column; gap:0.85rem;">

            <!-- Campo de Curso (Fijo con el nombre exacto del curso) -->
            <div class="gf-card">
                <div class="gf-q-title">Curso en el que se inscribe <span class="req">*</span></div>
                <input type="text" class="gf-input gf-input-locked" value="<?= htmlspecialchars($titulo) ?> (Facilitador: <?= htmlspecialchars($docente) ?>)" readonly>
            </div>

            <!-- Nombre (entry.1147669610) -->
            <div class="gf-card">
                <div class="gf-q-title">Nombre <span class="req">*</span></div>
                <input type="text" name="entry.1147669610" id="gf-nombre" class="gf-input" placeholder="Tu respuesta" required>
            </div>

            <!-- Apellido (entry.994644810) -->
            <div class="gf-card">
                <div class="gf-q-title">Apellido <span class="req">*</span></div>
                <input type="text" name="entry.994644810" id="gf-apellido" class="gf-input" placeholder="Tu respuesta" required>
            </div>

            <!-- Correo electrónico y Teléfono -->
            <div class="gf-card">
                <div class="gf-q-title">Correo electrónico institucional o personal <span class="req">*</span></div>
                <input type="email" id="gf-email" class="gf-input" placeholder="ejemplo@upttmbi.edu.ve" required>
            </div>

            <!-- Pregunta específica de este curso -->
            <div class="gf-card">
                <div class="gf-q-title"><?= htmlspecialchars($preguntaCursoLabel) ?> <span class="req">*</span></div>
                <div class="gf-radio-list">
                    <?php foreach ($preguntaCursoOpciones as $idx => $op): ?>
                    <label class="gf-radio-item">
                        <input type="radio" name="pregunta_especifica_curso" value="<?= htmlspecialchars($op) ?>" <?= $idx === 0 ? 'checked' : '' ?> required>
                        <span><?= htmlspecialchars($op) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Modalidad del curso (entry.2098787602) -->
            <div class="gf-card">
                <div class="gf-q-title">Modalidad del curso <span class="req">*</span></div>
                <div class="gf-radio-list">
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.2098787602" value="Virtual" <?= $modPreselect === 'Virtual' ? 'checked' : '' ?> required>
                        <span>Virtual</span>
                    </label>
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.2098787602" value="Presencial" <?= $modPreselect === 'Presencial' ? 'checked' : '' ?>>
                        <span>Presencial</span>
                    </label>
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.2098787602" value="Mixto" <?= $modPreselect === 'Mixto' ? 'checked' : '' ?>>
                        <span>Mixto / Híbrido</span>
                    </label>
                </div>
            </div>

            <!-- Horario preferido (entry.1364269381) -->
            <div class="gf-card">
                <div class="gf-q-title">Horario preferido <span class="req">*</span></div>
                <div class="gf-radio-list">
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.1364269381" value="Manana (8:00 am - 12:00 pm)" checked required>
                        <span>Mañana (8:00 am - 12:00 pm)</span>
                    </label>
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.1364269381" value="Tarde (1:00 pm - 5:00 pm)">
                        <span>Tarde (1:00 pm - 5:00 pm)</span>
                    </label>
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.1364269381" value="Noche (7:00 pm - 10:00 pm)">
                        <span>Noche (7:00 pm - 10:00 pm)</span>
                    </label>
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.1364269381" value="Fines de semana">
                        <span>Fines de semana</span>
                    </label>
                    <label class="gf-radio-item">
                        <input type="radio" name="entry.1364269381" value="A conveniencia">
                        <span>A conveniencia</span>
                    </label>
                </div>
            </div>

            <!-- Comentarios o preguntas (entry.69221431) -->
            <div class="gf-card">
                <div class="gf-q-title">Comentarios o preguntas adicionales</div>
                <input type="text" id="gf-comentarios-vis" class="gf-input full" placeholder="Tu respuesta">
                <input type="hidden" name="entry.69221431" id="gf-comentarios-real" value="">
            </div>

            <div class="gf-actions">
                <button type="submit" class="gf-btn-submit">Enviar inscripción al curso</button>
                <button type="reset" class="gf-btn-clear">Borrar formulario</button>
            </div>
        </form>

        <div class="gf-footer">
            Formulario vinculado al curso <strong><?= htmlspecialchars($titulo) ?></strong> · CIIDI UPTTMBI
            <div class="gf-brand-footer"><strong>Google</strong> Formularios</div>
        </div>
    </div>

    <!-- Vista de confirmación de envío -->
    <div class="gf-wrap" id="gf-confirm-container" style="display:none;">
        <div class="gf-card gf-card-header">
            <h1 class="gf-title"><?= htmlspecialchars($titulo) ?></h1>
            <p style="font-size:0.98rem; color:#202124; margin:0.85rem 0 1.2rem;">
                Se ha registrado tu solicitud de inscripción para el curso <strong><?= htmlspecialchars($titulo) ?></strong> (Facilitador: <?= htmlspecialchars($docente) ?>).
            </p>
            <a href="javascript:void(0)" onclick="reiniciarFormulario()" style="color:var(--gf-accent); font-size:0.9rem; text-decoration:underline; font-weight:500;">
                Enviar otra respuesta para este curso
            </a>
        </div>
    </div>

    <script>
    function procesarEnvioGoogleForm(e) {
        const cursoTitulo = <?= json_encode($titulo, JSON_UNESCAPED_UNICODE) ?>;
        const docente = <?= json_encode($docente, JSON_UNESCAPED_UNICODE) ?>;
        const email = document.getElementById('gf-email').value.trim();
        const espEl = document.querySelector('input[name="pregunta_especifica_curso"]:checked');
        const espVal = espEl ? espEl.value : '';
        const comentVis = document.getElementById('gf-comentarios-vis').value.trim();

        // Empaquetar el nombre del curso, correo y respuesta específica en entry.69221431 para que quede registrado en la hoja de Google Forms
        document.getElementById('gf-comentarios-real').value =
            '[Curso: ' + cursoTitulo + ' | Docente: ' + docente + '] ' +
            'Correo: ' + email + ' | Perfil/Área: ' + espVal +
            (comentVis ? (' | Comentario: ' + comentVis) : '');

        setTimeout(function() {
            document.getElementById('gf-form-container').style.display = 'none';
            document.getElementById('gf-confirm-container').style.display = 'flex';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 450);

        return true;
    }

    function reiniciarFormulario() {
        document.getElementById('gf-main-form').reset();
        document.getElementById('gf-confirm-container').style.display = 'none';
        document.getElementById('gf-form-container').style.display = 'flex';
    }
    </script>
</body>
</html>
