-- =============================================================================
-- MÓDULO INVESTIGACIONES — Schema + Seed Data
-- Base de datos: ciidi (PostgreSQL 18)
-- Creado: 2026-06-26
-- =============================================================================

-- Eliminamos tablas en orden inverso a dependencias si ya existen
DROP TABLE IF EXISTS inv_postulaciones CASCADE;
DROP TABLE IF EXISTS inv_vacantes      CASCADE;
DROP TABLE IF EXISTS inv_proyectos     CASCADE;
DROP TABLE IF EXISTS inv_anuncios      CASCADE;
DROP TABLE IF EXISTS inv_investigadores CASCADE;
DROP TABLE IF EXISTS inv_lineas        CASCADE;

-- -----------------------------------------------------------------------------
-- 1. LÍNEAS DE INVESTIGACIÓN INSTITUCIONALES
-- -----------------------------------------------------------------------------
CREATE TABLE inv_lineas (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    icono_ph    VARCHAR(60)  NOT NULL DEFAULT 'ph-flask',
    descripcion TEXT         NOT NULL,
    color_hex   VARCHAR(7)   NOT NULL DEFAULT '#121A3E'
);

INSERT INTO inv_lineas (nombre, icono_ph, descripcion, color_hex) VALUES
('Ingeniería de Software',  'ph-code',        'Arquitecturas modulares, metodologías ágiles, microkernels y optimización de código nativo para entornos de bajos recursos.', '#121A3E'),
('Gestión de Datos e IA',   'ph-database',    'Modelado relacional avanzado, bases de datos vectoriales y entrenamiento de modelos LLM locales para análisis predictivo.',   '#505984'),
('Redes y Ciberseguridad',  'ph-hard-drives', 'Auditorías de sistemas, hardening de servidores Linux (Debian), infraestructura local y protocolos de comunicación segura.',   '#2D3561'),
('Agroinformática y Hardware','ph-plant',     'Integración de microcontroladores, sensores IoT y automatización (MicroPython/C++) enfocada en el sector agrícola andino.',     '#1A8754');

-- -----------------------------------------------------------------------------
-- 2. INVESTIGADORES
-- -----------------------------------------------------------------------------
CREATE TABLE inv_investigadores (
    id               SERIAL PRIMARY KEY,
    nombre           VARCHAR(120) NOT NULL,
    grado_academico  VARCHAR(30)  NOT NULL, -- Dr., MSc., Ing., Prof.
    especialidad     VARCHAR(100) NOT NULL,
    sede             VARCHAR(80)  NOT NULL DEFAULT 'Sede Central',
    foto_url         TEXT,
    bio              TEXT,
    email            VARCHAR(120),
    habilidades      TEXT[]       NOT NULL DEFAULT '{}', -- Array de tags
    activo           BOOLEAN      NOT NULL DEFAULT TRUE,
    creado_en        TIMESTAMP    NOT NULL DEFAULT NOW()
);

INSERT INTO inv_investigadores (nombre, grado_academico, especialidad, sede, foto_url, bio, email, habilidades) VALUES
(
    'Lando Pérez',
    'Prof.',
    'Arquitectura de Software',
    'Núcleo Principal',
    'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=400&q=80',
    'Especialista en sistemas de bajo nivel y arquitecturas de microkernel. Pionero del proyecto OmegaCode en la UPTTMBI, editor TUI basado en Rust/libvaxis para laboratorios con recursos limitados.',
    'lperez@upttmbi.edu.ve',
    ARRAY['Rust', 'TUI', 'Microkernel', 'Bajo Nivel']
),
(
    'Josué García',
    'Ing.',
    'Inteligencia Artificial',
    'Sede Central',
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
    'Investigador líder en integración de Modelos de Lenguaje Grande (LLM) para entornos académicos. Creador de "Chavo", el copiloto IA de la plataforma CIIDI, basado en técnicas RAG.',
    'jgarcia@upttmbi.edu.ve',
    ARRAY['LLM', 'RAG', 'Python', 'IA Local']
),
(
    'Andru Martínez',
    'T.S.U.',
    'Desarrollo Web Full-Stack',
    'Núcleo Principal',
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
    'Desarrollador principal del Sistema CIIDI. Especialista en arquitectura PHP sin frameworks, diseño de BD relacionales y sistemas web de alto rendimiento. Co-autor del patrón Microkernel aplicado en la plataforma.',
    'amartinez@upttmbi.edu.ve',
    ARRAY['PHP', 'PostgreSQL', 'Microkernel', 'CSS']
),
(
    'Sofía Castillo',
    'Dra.',
    'Sistemas Embebidos',
    'Núcleo C',
    'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
    'Doctora en Ingeniería Electrónica. Lidera el programa de robótica agrícola integrando C++ y MicroPython en nodos embebidos para riego automatizado en la región andina de Mérida.',
    'scastillo@upttmbi.edu.ve',
    ARRAY['C++', 'MicroPython', 'IoT', 'Embebidos']
),
(
    'Elena Vargas',
    'Dra.',
    'Ciencia de Datos',
    'Sede Central',
    'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
    'Investigadora en análisis predictivo y modelos estadísticos aplicados a la deserción estudiantil universitaria. Promotora del uso del lenguaje R en proyectos de datos abiertos institucionales.',
    'evargas@upttmbi.edu.ve',
    ARRAY['R', 'Data Science', 'Estadística', 'Python']
),
(
    'Roberto Silva',
    'MSc.',
    'Redes y Telecomunicaciones',
    'Sede Central',
    'https://images.unsplash.com/photo-1496345875659-11f7dd282d1d?auto=format&fit=crop&w=400&q=80',
    'Magíster en Seguridad Informática. Responsable del hardening y monitoreo de la infraestructura de red de la UPTTMBI. Especialista en servidores Debian Trixie y protocolos de defensa perimetral.',
    'rsilva@upttmbi.edu.ve',
    ARRAY['Debian', 'Redes', 'Pentesting', 'Seguridad']
);

-- -----------------------------------------------------------------------------
-- 3. PROYECTOS DE I+D
-- -----------------------------------------------------------------------------
CREATE TABLE inv_proyectos (
    id               SERIAL PRIMARY KEY,
    titulo           VARCHAR(200) NOT NULL,
    resumen          TEXT         NOT NULL,
    imagen_url       TEXT,
    estado           VARCHAR(30)  NOT NULL DEFAULT 'activo', -- activo, pruebas, concluido
    linea_id         INTEGER      NOT NULL REFERENCES inv_lineas(id),
    investigador_id  INTEGER      NOT NULL REFERENCES inv_investigadores(id),
    fecha_inicio     DATE         NOT NULL DEFAULT CURRENT_DATE,
    destacado        BOOLEAN      NOT NULL DEFAULT FALSE
);

INSERT INTO inv_proyectos (titulo, resumen, imagen_url, estado, linea_id, investigador_id, fecha_inicio, destacado) VALUES
(
    'OmegaCode: Editor TUI para Hardware de Bajos Recursos',
    'Construcción de un editor de código nativo basado en Rust, implementando la librería libvaxis. El objetivo es proporcionar herramientas de desarrollo ultraligeras para los laboratorios de la UPTTMBI, permitiendo programar en máquinas con menos de 512MB de RAM sin perder productividad.',
    'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
    'activo',
    1, 1, '2025-09-01', TRUE
),
(
    'CIIDI Web: Sistema Híbrido de Repositorio Académico',
    'Estructuración de un sistema centralizado en PHP puro sin frameworks pesados para almacenar trabajos de grado, gestionar foros de discusión y conectar estudiantes del PNF con problemáticas reales de empresas de la región andina. Implementa un patrón Microkernel original.',
    'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=80',
    'activo',
    1, 3, '2025-03-01', TRUE
),
(
    'Chavo IA: Asistente Virtual para Tutorías y Foros',
    'Entrenamiento e integración de un LLM de tamaño reducido con técnica RAG (Retrieval-Augmented Generation). Analiza el contexto de salas de chat para extraer requerimientos automáticamente, generar actas de reunión y resumir conversaciones académicas prolongadas.',
    'https://images.unsplash.com/photo-1611162617474-5b21e879e113?auto=format&fit=crop&w=800&q=80',
    'activo',
    2, 2, '2025-11-15', TRUE
),
(
    'AgroBot: Sistema de Riego Automatizado con IoT',
    'Integración de sensores de humedad de suelo programados en MicroPython para el control automático de motores de riego en parcelas agrícolas experimentales de la UPTTMBI. Transmisión de datos vía MQTT a un dashboard web en tiempo real.',
    'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=800&q=80',
    'pruebas',
    4, 4, '2025-06-01', FALSE
),
(
    'SecureNet: Auditoría y Hardening de Infraestructura UPTTMBI',
    'Proyecto de revisión integral de la seguridad perimetral de la red universitaria. Incluye análisis de vulnerabilidades, implementación de firewall con nftables en Debian Trixie y configuración de sistemas de detección de intrusos (IDS/IPS) de código abierto.',
    'https://images.unsplash.com/photo-1563206767-5b18f218e8de?auto=format&fit=crop&w=800&q=80',
    'activo',
    3, 6, '2026-01-10', FALSE
),
(
    'DataMinds: Predicción de Deserción Estudiantil con ML',
    'Aplicación de modelos de regresión logística y Random Forest en R para identificar patrones de riesgo de abandono en estudiantes del PNF de Informática. Los resultados alimentan un sistema de alerta temprana para tutores académicos.',
    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
    'concluido',
    2, 5, '2024-09-01', FALSE
);

-- -----------------------------------------------------------------------------
-- 4. VACANTES (PLAZAS DE COLABORACIÓN)
-- -----------------------------------------------------------------------------
CREATE TABLE inv_vacantes (
    id               SERIAL PRIMARY KEY,
    proyecto_id      INTEGER      NOT NULL REFERENCES inv_proyectos(id),
    linea_id         INTEGER      NOT NULL REFERENCES inv_lineas(id),
    titulo_rol       VARCHAR(150) NOT NULL,
    descripcion      TEXT         NOT NULL,
    nivel_requerido  VARCHAR(20)  NOT NULL, -- t3, t4, postgrado
    cupo_total       SMALLINT     NOT NULL DEFAULT 1,
    cupo_disponible  SMALLINT     NOT NULL DEFAULT 1,
    activa           BOOLEAN      NOT NULL DEFAULT TRUE
);

INSERT INTO inv_vacantes (proyecto_id, linea_id, titulo_rol, descripcion, nivel_requerido, cupo_total, cupo_disponible) VALUES
-- Trayecto III
(2, 1, 'Desarrollador Frontend Web',          'Apoyo en la creación de interfaces administrativas basadas en layouts asimétricos para la gestión del directorio del CIIDI. Se requieren conocimientos básicos de HTML, CSS y JavaScript.',                                    't3', 1, 1),
(4, 4, 'Técnico de Sensores IoT',              'Ensamblado y calibración de nodos de sensores de humedad y temperatura. Programación básica en MicroPython. El estudiante aprenderá electrónica de campo en un entorno real.',                                               't3', 2, 2),
(6, 2, 'Asistente de Limpieza de Datos',       'Preprocesamiento de datasets históricos de rendimiento estudiantil. Uso de hojas de cálculo avanzadas y nociones de Python (pandas). Ideal para iniciarse en el mundo de la Ciencia de Datos.',                            't3', 1, 1),
-- Trayecto IV
(2, 1, 'Ingeniero Backend PHP',               'Diseño e implementación de nuevos módulos para el sistema CIIDI siguiendo el patrón Microkernel. Se requiere experiencia sólida en PHP orientado a objetos, PostgreSQL y arquitectura MVC.',                                   't4', 1, 1),
(3, 2, 'Optimizador de Consultas RAG',        'Depuración y mejora del pipeline de recuperación de información del copiloto Chavo. Trabajo con bases de datos vectoriales (pgvector) y APIs de LLMs locales (Ollama). Requiere Python avanzado.',                             't4', 2, 1),
(1, 1, 'Contribuidor Rust (libvaxis)',        'Desarrollo de nuevas funciones de UI para OmegaCode. Se requiere conocimiento de Rust y terminal UI. El candidato trabajará directamente con la librería libvaxis y el sistema de eventos del editor.',                         't4', 1, 1),
(4, 4, 'Ensamblador de Nodos Embebidos',      'Integración de sensores avanzados y módulos de comunicación LoRa en los nodos del AgroBot. Programación en C++ con Arduino IDE y MicroPython para la lógica de control de motores de riego de alta potencia.',              't4', 3, 2),
-- Postgrado
(5, 3, 'Analista de Hardening Linux',         'Auditoría avanzada en servidores Debian Trixie y configuración de políticas de defensa perimetral. Trabajo con nftables, Fail2Ban y Suricata IDS. Ideal para maestrantes en Seguridad Informática.',                         'postgrado', 1, 1),
(3, 2, 'Investigador LLM y Fine-Tuning',      'Diseño de estrategias de fine-tuning para modelos de lenguaje (Mistral/Llama) con datasets académicos institucionales. Publicación de resultados en congreso nacional. Colaboración directa con el Dr. García.',            'postgrado', 1, 1);

-- -----------------------------------------------------------------------------
-- 5. ANUNCIOS / CARTELERA
-- -----------------------------------------------------------------------------
CREATE TABLE inv_anuncios (
    id                SERIAL PRIMARY KEY,
    titulo            VARCHAR(200) NOT NULL,
    contenido         TEXT         NOT NULL,
    categoria         VARCHAR(50)  NOT NULL DEFAULT 'general', -- convocatoria, evento, resultado, general
    fecha_publicacion TIMESTAMP    NOT NULL DEFAULT NOW(),
    es_nuevo          BOOLEAN      NOT NULL DEFAULT TRUE,
    url_detalle       TEXT
);

INSERT INTO inv_anuncios (titulo, contenido, categoria, fecha_publicacion, es_nuevo, url_detalle) VALUES
(
    'Apertura de Convocatoria Q3 2026 — Línea Software Libre',
    'Se abre el proceso de recepción de anteproyectos para financiamiento de I+D en el área de Software Libre. Los equipos deben estar conformados por al menos un docente investigador y dos estudiantes del Trayecto IV. Fecha límite de postulación: 15 de agosto de 2026. Los proyectos aprobados recibirán dotación de equipos y horas de tutoría especializada.',
    'convocatoria',
    NOW() - INTERVAL '2 days',
    TRUE,
    '#'
),
(
    'Resultados del Hackathon UPTTMBI 2026',
    'El equipo conformado por Andru Martínez, Mikeyisito y Yuslen Zerpa se alzó con el primer lugar con el proyecto "PoliNet: Red Social Académica Descentralizada". Sus proyectos pasarán a la fase de incubación en nuestro laboratorio principal durante el período septiembre-diciembre 2026.',
    'resultado',
    NOW() - INTERVAL '8 days',
    FALSE,
    '#'
),
(
    'Taller: Introducción a Rust para Sistemas Embebidos',
    'El Prof. Lando Pérez dictará un taller intensivo de 12 horas sobre Rust aplicado a sistemas embebidos y programación de bajo nivel. El taller es gratuito para estudiantes del PNF de Informática. Cupos limitados a 20 participantes. Inscripciones abiertas en la Coordinación Académica.',
    'evento',
    NOW() - INTERVAL '3 days',
    TRUE,
    '#'
),
(
    'Publicación Indexada: Chavo IA en Congreso Internacional',
    'El artículo "RAG-Based Academic Assistant for LMS Integration" co-autorado por el Ing. Josué García fue aceptado en la revista IEEE Access (Factor de Impacto 3.9). Felicitaciones al equipo de investigación del área de IA por este logro que posiciona a la UPTTMBI en el ámbito internacional.',
    'resultado',
    NOW() - INTERVAL '15 days',
    FALSE,
    '#'
),
(
    'Convocatoria: Becas de Investigación Semestre 2026-II',
    'El CIIDI abre la convocatoria para becas de investigación del semestre 2026-II. Se otorgarán 5 becas parciales de 6 meses para estudiantes del Trayecto IV con índice académico superior a 16 puntos. Los interesados deben presentar una carta de intención ante la Dirección de Investigación.',
    'convocatoria',
    NOW() - INTERVAL '1 day',
    TRUE,
    '#'
);

-- -----------------------------------------------------------------------------
-- 6. POSTULACIONES DE ESTUDIANTES (para recibir datos del formulario)
-- -----------------------------------------------------------------------------
CREATE TABLE inv_postulaciones (
    id                SERIAL PRIMARY KEY,
    vacante_id        INTEGER      NOT NULL REFERENCES inv_vacantes(id),
    nombre_solicitante VARCHAR(150) NOT NULL,
    cedula            VARCHAR(15),
    email             VARCHAR(120) NOT NULL,
    motivacion        TEXT         NOT NULL,
    portfolio_url     TEXT,
    estado            VARCHAR(20)  NOT NULL DEFAULT 'pendiente', -- pendiente, revisado, aceptado, rechazado
    fecha             TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- Postulaciones de ejemplo (para que la tabla no esté vacía)
INSERT INTO inv_postulaciones (vacante_id, nombre_solicitante, cedula, email, motivacion, portfolio_url, estado) VALUES
(1, 'María González', 'V-28.541.032', 'mgonzalez@estudiante.upttmbi.edu.ve', 'Tengo sólidos conocimientos en HTML y CSS, y me apasiona el diseño de interfaces académicas. Quiero aportar al proyecto CIIDI y aprender de los mejores.', 'https://github.com/mariag', 'revisado'),
(4, 'Carlos Ruiz',    'V-27.331.118', 'cruiz@estudiante.upttmbi.edu.ve',     'Como estudiante de Trayecto IV he desarrollado proyectos en PHP con patrón MVC. Me interesa profundizar en la arquitectura Microkernel y contribuir al sistema institucional.', 'https://github.com/carlosr', 'pendiente'),
(5, 'Ana Castillo',   'V-29.002.445', 'acastillo@estudiante.upttmbi.edu.ve', 'He trabajado con LangChain y bases de datos vectoriales en mis proyectos personales. El proyecto Chavo IA se alinea perfectamente con mi investigación de tesis sobre sistemas RAG.', 'https://github.com/anacast', 'pendiente');
