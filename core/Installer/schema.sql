-- ==========================================================================
-- Esquema Base CIIDI — MariaDB 10.6.24 (InnoDB / utf8mb4_unicode_ci)
-- ==========================================================================

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `accesos_recursos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_registro_actividad` INT NOT NULL,
    `id_recurso` INT NOT NULL,
    `accion` ENUM('visualizacion', 'descarga') DEFAULT 'visualizacion',
    `fecha_acceso` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `auditoria` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `tabla_afectada` VARCHAR(50) NOT NULL,
    `id_registro` INT NOT NULL,
    `accion` ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    `usuario_responsable` INT,
    `ip_origen` VARCHAR(45),
    `datos_anteriores` JSON,
    `datos_nuevos` JSON,
    `fecha_hora` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=666 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `autores` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre_completo` VARCHAR(150) NOT NULL,
    `cedula` VARCHAR(20) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `autores_cedula_key` (`cedula`),
    FULLTEXT KEY `idx_ft_autores_nombre` (`nombre_completo`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `carreras` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `carreras_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categorias` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `categorias_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cursos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_docente` INT NOT NULL,
    `titulo` VARCHAR(255) NOT NULL,
    `descripcion` TEXT,
    `imagen_portada` TEXT,
    `estado` ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador' NOT NULL,
    `nota_minima_aprobacion` DECIMAL(5,2) DEFAULT 70.00 NOT NULL,
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `slug` VARCHAR(255),
    `url_moodle` TEXT,
    `modalidad` VARCHAR(50) DEFAULT 'Virtual',
    `nivel` VARCHAR(50) DEFAULT 'Básico',
    `duracion` VARCHAR(80),
    `cupo_maximo` INT,
    `fecha_inicio` DATE,
    `fecha_fin` DATE,
    `url_video_preview` TEXT,
    `estado_inscripcion` VARCHAR(50) DEFAULT 'Abierta',
    `url_formulario_google` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `cursos_slug_key` (`slug`),
    FULLTEXT KEY `idx_ft_cursos` (`titulo`, `descripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cursos_dimensiones` (
    `id_curso` INT NOT NULL,
    `id_dimension` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_curso`, `id_dimension`),
    KEY `idx_cursos_dimensiones_curso` (`id_curso`),
    KEY `idx_cursos_dimensiones_dimension` (`id_dimension`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cursos_lineas` (
    `id_curso` INT NOT NULL,
    `id_linea` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_curso`, `id_linea`),
    KEY `idx_cursos_lineas_curso` (`id_curso`),
    KEY `idx_cursos_lineas_linea` (`id_linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cursos_preinscripciones` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_curso` INT NOT NULL,
    `nombre_completo` VARCHAR(255) NOT NULL,
    `correo_electronico` VARCHAR(255) NOT NULL,
    `telefono` VARCHAR(50),
    `horario_deseado` VARCHAR(255) NOT NULL,
    `mensaje` TEXT,
    `fecha_preinscripcion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_cursos_preinscripciones_curso` (`id_curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detalles_articulos` (
    `id_recurso` INT NOT NULL,
    `id_editorial` INT,
    `volumen` VARCHAR(50),
    `numero` VARCHAR(50),
    `issn` VARCHAR(20),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `imagen_portada` VARCHAR(500) DEFAULT 'default_article.jpg',
    `resumen` TEXT,
    `activo` TINYINT(1) DEFAULT 1,
    `id_categoria` INT,
    PRIMARY KEY (`id_recurso`),
    FULLTEXT KEY `idx_ft_detalles_articulos` (`resumen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detalles_investigaciones` (
    `id_recurso` INT NOT NULL,
    `planteamiento_problema` TEXT NOT NULL,
    `objetivo_general` TEXT NOT NULL,
    `id_investigacion_ofertada` INT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_recurso`),
    KEY `idx_detalles_inv_ofertada` (`id_investigacion_ofertada`),
    FULLTEXT KEY `idx_ft_detalles_investigaciones` (`planteamiento_problema`, `objetivo_general`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `detalles_proyectos` (
    `id_recurso` INT NOT NULL,
    `fecha_defensa` DATE,
    `nivel_academico` VARCHAR(50) DEFAULT 'Pregrado',
    `resumen` TEXT,
    `id_carrera` INT,
    `comunidad_beneficiada` TEXT,
    `palabras_clave` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `id_investigacion_padre` INT,
    `url_repositorio` TEXT,
    `obj_general` TEXT,
    `activo` TINYINT(1) DEFAULT 1,
    `id_trayecto` INT,
    `vistas` INT DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id_recurso`),
    KEY `idx_detalles_proyectos_trayecto` (`id_trayecto`),
    FULLTEXT KEY `idx_ft_detalles_proyectos` (`resumen`, `palabras_clave`, `obj_general`, `comunidad_beneficiada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `dimensiones_operativas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_linea` INT NOT NULL,
    `nombre` VARCHAR(150) NOT NULL,
    `descripcion` TEXT,
    `activo` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `editoriales` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `editoriales_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `etiquetas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    `color_hex` VARCHAR(7) DEFAULT '#0ea5e9',
    PRIMARY KEY (`id`),
    UNIQUE KEY `etiquetas_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `historico_versiones_pst` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_recurso` INT NOT NULL,
    `archivo_pdf` VARCHAR(500) NOT NULL,
    `usuario_id` INT,
    `motivo` VARCHAR(255) DEFAULT 'Actualización',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `investigaciones_dimensiones` (
    `id_investigacion` INT NOT NULL,
    `id_dimension` INT NOT NULL,
    PRIMARY KEY (`id_investigacion`, `id_dimension`),
    KEY `idx_inv_dim_dim` (`id_dimension`),
    KEY `idx_inv_dim_inv` (`id_investigacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `investigaciones_lineas` (
    `id_investigacion` INT NOT NULL,
    `id_linea` INT NOT NULL,
    PRIMARY KEY (`id_investigacion`, `id_linea`),
    KEY `idx_inv_lineas_inv` (`id_investigacion`),
    KEY `idx_inv_lineas_lin` (`id_linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `investigaciones_ofertadas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_profesor` INT NOT NULL,
    `titulo` VARCHAR(255) NOT NULL,
    `planteamiento_problema` TEXT NOT NULL,
    `objetivo_general` TEXT NOT NULL,
    `id_linea` INT NOT NULL,
    `id_dimension` INT,
    `cupos_disponibles` INT DEFAULT 3,
    `estado` VARCHAR(20) DEFAULT 'Abierta',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `id_propuesta_empresa` INT,
    CONSTRAINT `investigaciones_ofertadas_estado_check` CHECK (`estado` IN ('Abierta', 'Cerrada', 'En Desarrollo', 'Finalizada')),
    PRIMARY KEY (`id`),
    FULLTEXT KEY `idx_ft_inv_ofertadas` (`titulo`, `planteamiento_problema`, `objetivo_general`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `investigadores` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre_completo` VARCHAR(150) NOT NULL,
    `orcid` VARCHAR(50) NOT NULL,
    `biografia` TEXT,
    `pagina_web` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE KEY `investigadores_orcid_key` (`orcid`),
    FULLTEXT KEY `idx_ft_investigadores` (`nombre_completo`, `biografia`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lineas_investigacion` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(255) NOT NULL,
    `id_carrera` INT NOT NULL,
    `descripcion` TEXT,
    `activo` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`id`),
    FULLTEXT KEY `idx_ft_lineas_investigacion` (`nombre`, `descripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `matriz_rbac` (
    `nivel_privilegio` INT NOT NULL,
    `modulo` VARCHAR(100) NOT NULL,
    `permisos` JSON,
    PRIMARY KEY (`nivel_privilegio`, `modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `niveles_academicos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `codigo` VARCHAR(50) NOT NULL,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT,
    `requiere_trayecto` TINYINT(1) DEFAULT 0,
    `orden` INT DEFAULT 0,
    `activo` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `niveles_academicos_codigo_key` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notificaciones` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_usuario` INT,
    `titulo` VARCHAR(255),
    `mensaje` TEXT,
    `leido` TINYINT(1) DEFAULT 0,
    `fecha_hora` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `token_hash` VARCHAR(255) NOT NULL,
    `expiracion` DATETIME NOT NULL,
    `utilizado` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `postulaciones_estudiantes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_investigacion` INT NOT NULL,
    `id_estudiante` INT NOT NULL,
    `mensaje_motivacion` TEXT,
    `estado` VARCHAR(20) DEFAULT 'Pendiente',
    `fecha_postulacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_respuesta` DATETIME,
    `equipo_extra` JSON,
    `trayecto` SMALLINT,
    `trimestre` SMALLINT,
    `id_profesor_pst` INT,
    `aprobacion_profesor` VARCHAR(20) DEFAULT 'Pendiente' NOT NULL,
    `motivo_profesor` TEXT,
    `fecha_revision_profesor` DATETIME,
    `motivo_rechazo` TEXT,
    CONSTRAINT `chk_postulacion_aprobacion_profesor` CHECK (`aprobacion_profesor` IN ('Pendiente', 'Aprobado', 'Devuelto')),
    CONSTRAINT `chk_postulacion_trayecto` CHECK (`trayecto` IS NULL OR (`trayecto` >= 1 AND `trayecto` <= 99)),
    CONSTRAINT `chk_postulacion_trimestre` CHECK (`trimestre` IS NULL OR (`trimestre` >= 1 AND `trimestre` <= 3)),
    CONSTRAINT `postulaciones_estudiantes_estado_check` CHECK (`estado` IN ('Pendiente', 'Aceptado', 'Rechazado')),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_postulacion` (`id_investigacion`, `id_estudiante`),
    KEY `idx_postulaciones_profesor_pst` (`id_profesor_pst`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `preferencias_usuario` (
    `id_usuario` INT NOT NULL,
    `tema` VARCHAR(50) DEFAULT 'light',
    `notificaciones_sistema` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `privilegios` (
    `privilegio_id` INT NOT NULL AUTO_INCREMENT,
    `nivel_privilegio` INT DEFAULT 0 NOT NULL,
    PRIMARY KEY (`privilegio_id`),
    UNIQUE KEY `unique_nivel_privilegio` (`nivel_privilegio`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `profesor_dimension` (
    `id_usuario` INT NOT NULL,
    `id_dimension` INT NOT NULL,
    `id_linea` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_usuario`, `id_dimension`),
    KEY `idx_prof_dim_linea` (`id_linea`),
    KEY `idx_prof_dim_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `profesor_linea` (
    `id_usuario` INT NOT NULL,
    `id_linea` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_usuario`, `id_linea`),
    KEY `idx_profesor_linea_linea` (`id_linea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `profesores_pst` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_profesor` INT NOT NULL,
    `trayecto` SMALLINT NOT NULL,
    `trimestre` SMALLINT DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `chk_profesores_pst_trayecto` CHECK (`trayecto` >= 1 AND `trayecto` <= 99),
    CONSTRAINT `chk_profesores_pst_trimestre_null` CHECK (`trimestre` IS NULL OR (`trimestre` >= 1 AND `trimestre` <= 3)),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_profesores_pst` (`id_profesor`, `trayecto`, `trimestre`),
    KEY `idx_profesores_pst_tt` (`trayecto`, `trimestre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `propuestas_empresa` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre_empresa` VARCHAR(255) NOT NULL,
    `rif_empresa` VARCHAR(50),
    `persona_contacto` VARCHAR(150) NOT NULL,
    `telefono_contacto` VARCHAR(50) NOT NULL,
    `correo_contacto` VARCHAR(150) NOT NULL,
    `area_afectada` VARCHAR(100) NOT NULL,
    `descripcion_problema` TEXT NOT NULL,
    `estado` ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `nivel_trayecto` VARCHAR(50),
    `codigo_seguimiento` VARCHAR(20),
    `motivo_rechazo` TEXT,
    `campos_extra` JSON NOT NULL,
    `eliminado_en` DATETIME,
    `motivo_archivo` TEXT,
    `archivado_por` INT,
    `revertido_en` DATETIME,
    `motivo_reversion` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `propuestas_empresa_codigo_seguimiento_key` (`codigo_seguimiento`),
    KEY `idx_propuestas_empresa_eliminado_en` (`eliminado_en`),
    FULLTEXT KEY `idx_ft_propuestas_empresa` (`nombre_empresa`, `area_afectada`, `descripcion_problema`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proyecto_carreras_vinculadas` (
    `id_recurso` INT NOT NULL,
    `id_carrera` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_recurso`, `id_carrera`),
    KEY `idx_pcv_carrera` (`id_carrera`),
    KEY `idx_pcv_recurso` (`id_recurso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `proyecto_tutores` (
    `id_recurso` INT NOT NULL,
    `id_tutor` INT NOT NULL,
    `tipo_tutor_id` INT,
    PRIMARY KEY (`id_recurso`, `id_tutor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recurso_autores` (
    `id_recurso` INT NOT NULL,
    `id_autor` INT NOT NULL,
    PRIMARY KEY (`id_recurso`, `id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recurso_categorias` (
    `id_recurso` INT NOT NULL,
    `id_categoria` INT NOT NULL,
    PRIMARY KEY (`id_recurso`, `id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recurso_clasificaciones` (
    `id_recurso` INT NOT NULL,
    `id_linea_investigacion` INT NOT NULL,
    `id_dimension_operativa` INT,
    PRIMARY KEY (`id_recurso`, `id_linea_investigacion`),
    KEY `idx_recurso_clasif_dimension` (`id_dimension_operativa`),
    KEY `idx_recurso_clasif_linea` (`id_linea_investigacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recurso_etiquetas` (
    `id_recurso` INT NOT NULL,
    `id_etiqueta` INT NOT NULL,
    PRIMARY KEY (`id_recurso`, `id_etiqueta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recurso_investigadores` (
    `id_recurso` INT NOT NULL,
    `id_investigador` INT NOT NULL,
    PRIMARY KEY (`id_recurso`, `id_investigador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recursos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(255) NOT NULL,
    `id_tipo_recurso` INT NOT NULL,
    `anio_publicacion` INT,
    `archivo_pdf` VARCHAR(255),
    PRIMARY KEY (`id`),
    FULLTEXT KEY `idx_ft_recursos_titulo` (`titulo`)
) ENGINE=InnoDB AUTO_INCREMENT=307 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `registro_actividad` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_usuario` INT,
    `id_visitante` INT,
    `fecha_inicial` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `ultima_actividad` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `conteo_accesos` INT DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    `privilegio_id` INT DEFAULT 1 NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `roles_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_alertas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `fecha` DATETIME NOT NULL,
    `tipo` VARCHAR(40) NOT NULL,
    `severidad` VARCHAR(10) NOT NULL,
    `titulo` VARCHAR(200) NOT NULL,
    `detalle` VARCHAR(2000) DEFAULT '' NOT NULL,
    `ip` VARCHAR(64) DEFAULT '' NOT NULL,
    `usuario_id` INT,
    `estado` VARCHAR(10) DEFAULT 'abierta' NOT NULL,
    `atendida_por` VARCHAR(160),
    `atendida_en` DATETIME,
    PRIMARY KEY (`id`),
    KEY `idx_seg_lineas_alertas_estado` (`estado`, `fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_bloqueos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `ip` VARCHAR(64) NOT NULL,
    `motivo` VARCHAR(2000) DEFAULT '' NOT NULL,
    `origen` VARCHAR(10) DEFAULT 'manual' NOT NULL,
    `creado_en` DATETIME NOT NULL,
    `expira_en` DATETIME,
    `creado_por` VARCHAR(160) DEFAULT '' NOT NULL,
    `activo` TINYINT(1) DEFAULT 1 NOT NULL,
    `levantado_por` VARCHAR(160),
    `levantado_en` DATETIME,
    PRIMARY KEY (`id`),
    KEY `idx_seg_lineas_bloq_ip` (`ip`, `activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_excepciones` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `usuario_id` INT NOT NULL,
    `submodulo` VARCHAR(80) NOT NULL,
    `accion` VARCHAR(30) NOT NULL,
    `efecto` VARCHAR(10) NOT NULL,
    `motivo` VARCHAR(2000) DEFAULT '' NOT NULL,
    `expira_en` DATETIME,
    `creado_por` VARCHAR(160) DEFAULT '' NOT NULL,
    `creado_en` DATETIME NOT NULL,
    CONSTRAINT `seg_lineas_excepciones_efecto_check` CHECK (`efecto` IN ('permitir', 'denegar')),
    PRIMARY KEY (`id`),
    KEY `idx_seg_lineas_exc_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_logs` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `fecha` DATETIME NOT NULL,
    `severidad` VARCHAR(10) DEFAULT 'INFO' NOT NULL,
    `submodulo` VARCHAR(80) DEFAULT '' NOT NULL,
    `accion` VARCHAR(30) DEFAULT '' NOT NULL,
    `ruta` VARCHAR(160) DEFAULT '' NOT NULL,
    `resultado` VARCHAR(12) DEFAULT 'PERMITIDO' NOT NULL,
    `usuario_id` INT,
    `usuario_nombre` VARCHAR(160) DEFAULT '' NOT NULL,
    `nivel` INT DEFAULT 999 NOT NULL,
    `ip` VARCHAR(64) DEFAULT '' NOT NULL,
    `metodo` VARCHAR(10) DEFAULT 'GET' NOT NULL,
    `user_agent` VARCHAR(255) DEFAULT '' NOT NULL,
    `detalles` VARCHAR(2000) DEFAULT '' NOT NULL,
    `hash_anterior` VARCHAR(64) NOT NULL,
    `hash` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_seg_lineas_logs_fecha` (`fecha`),
    KEY `idx_seg_lineas_logs_ip_res` (`ip`, `resultado`, `fecha`),
    KEY `idx_seg_lineas_logs_usuario` (`usuario_id`, `fecha`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_permisos` (
    `nivel` INT NOT NULL,
    `submodulo` VARCHAR(80) NOT NULL,
    `accion` VARCHAR(30) NOT NULL,
    `permitido` TINYINT(1) NOT NULL,
    `actualizado_por` VARCHAR(160) DEFAULT '' NOT NULL,
    `actualizado_en` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (`nivel`, `submodulo`, `accion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_politicas` (
    `clave` VARCHAR(60) NOT NULL,
    `valor` JSON NOT NULL,
    `actualizado_por` VARCHAR(160) DEFAULT '' NOT NULL,
    `actualizado_en` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seg_lineas_rutas` (
    `ruta` VARCHAR(160) NOT NULL,
    `accion` VARCHAR(30) NOT NULL,
    `actualizado_por` VARCHAR(160) DEFAULT '' NOT NULL,
    `actualizado_en` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (`ruta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `solicitudes_docentes_pst` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_profesor` INT NOT NULL,
    `es_profesor_pst` TINYINT(1) DEFAULT 1 NOT NULL,
    `trayectos` JSON NOT NULL,
    `estado` VARCHAR(20) DEFAULT 'pendiente' NOT NULL,
    `motivo` TEXT,
    `fecha_solicitud` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_resolucion` DATETIME,
    `id_revisor` INT,
    CONSTRAINT `chk_solicitud_pst_estado` CHECK (`estado` IN ('pendiente', 'aprobado', 'rechazado')),
    PRIMARY KEY (`id`),
    KEY `idx_solicitudes_pst_estado` (`estado`),
    KEY `idx_solicitudes_pst_prof` (`id_profesor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `system_audit_log` (
    `id` VARCHAR(50) NOT NULL,
    `fecha_hora` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `nivel` VARCHAR(20),
    `modulo` VARCHAR(100),
    `accion` VARCHAR(100),
    `detalles` TEXT,
    `responsable` VARCHAR(150),
    `ip` VARCHAR(45),
    `hash_anterior` VARCHAR(64),
    `hash_integridad` VARCHAR(64),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `telemetria_cache` (
    `id` INT DEFAULT 1 NOT NULL,
    `datos` JSON,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipo_recurso` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    `descripcion` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tipo_recurso_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipo_tutor` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    `descripcion` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tipo_tutor_nombre_key` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `trayectos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_carrera` INT NOT NULL,
    `nombre` VARCHAR(50) NOT NULL,
    `numero` INT NOT NULL,
    `descripcion` TEXT,
    `activo` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_carrera_trayecto` (`id_carrera`, `numero`),
    KEY `idx_trayectos_carrera` (`id_carrera`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tutores` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre_completo` VARCHAR(150) NOT NULL,
    `cedula` VARCHAR(20),
    PRIMARY KEY (`id`),
    UNIQUE KEY `tutores_cedula_key` (`cedula`),
    FULLTEXT KEY `idx_ft_tutores_nombre` (`nombre_completo`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre_completo` VARCHAR(150) NOT NULL,
    `email` VARCHAR(100),
    `cedula` VARCHAR(20),
    `contrasena` VARCHAR(255),
    `id_rol` INT,
    `activo` TINYINT(1) DEFAULT 1,
    `reset_token` VARCHAR(255) DEFAULT NULL,
    `reset_expires` DATETIME,
    `telefono` VARCHAR(50) DEFAULT NULL,
    `email_verified` TINYINT(1) DEFAULT 0,
    `activation_token` VARCHAR(255) DEFAULT NULL,
    `estado_registro` VARCHAR(20) DEFAULT NULL,
    `es_profesor_pst` TINYINT(1) DEFAULT 0 NOT NULL,
    `es_investigador` TINYINT(1) DEFAULT 0 NOT NULL,
    `foto_perfil` VARCHAR(255) DEFAULT NULL,
    `orcid` VARCHAR(150) DEFAULT NULL,
    `sitio_web` VARCHAR(255) DEFAULT NULL,
    `grado_academico` VARCHAR(255) DEFAULT NULL,
    `biografia` TEXT,
    CONSTRAINT `chk_usuarios_estado_registro` CHECK (`estado_registro` IS NULL OR `estado_registro` IN ('pendiente', 'aprobado', 'rechazado')),
    PRIMARY KEY (`id`),
    UNIQUE KEY `usuarios_cedula_key` (`cedula`),
    UNIQUE KEY `usuarios_email_key` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `visitantes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT,
    `pagina_origen` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `waf_rate_limiter` (
    `ip` VARCHAR(45) NOT NULL,
    `tipo` VARCHAR(20) NOT NULL,
    `intentos` INT DEFAULT 0,
    `primer_intento` DATETIME,
    `ultimo_intento` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `bloqueado_hasta` DATETIME,
    `razon` TEXT,
    `datos_adicionales` JSON,
    `creado_el` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ip`, `tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `privilegios` VALUES
(1, 0),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(10, 6);

INSERT INTO `roles` VALUES
(1, 'Super Administrador', 1),
(3, 'Estudiantes', 6),
(4, 'Docentes', 3),
(2, 'Comité', 2);

INSERT INTO `matriz_rbac` VALUES
(1, 'Articulos', '{"crear": true, "editar": true, "auditar": true, "eliminar": true}'),
(1, 'Cursos', '{"crear": true, "editar": false, "auditar": false, "eliminar": false}'),
(1, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(1, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(1, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(1, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(2, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(2, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(2, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(2, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(2, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(2, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(3, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(3, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(3, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(3, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(3, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(3, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(4, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(4, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(4, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(4, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(4, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(4, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(5, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(5, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(5, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(5, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(5, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(5, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(6, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(6, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(6, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(6, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(6, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}'),
(6, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');

INSERT INTO `carreras` VALUES
(2, 'PNF en Electricidad', 'Ingeniería y TSU en Electricidad'),
(1, 'PNF en Informática', 'Ingeniería y TSU en Informática'),
(3, 'PNF en Administración', 'Licenciatura y TSU en Administración'),
(4, 'PNF en Agroalimentación', 'Ingeniería y TSU Agroalimentario'),
(5, 'PNF en Construcción Civil', 'Ingeniería y TSU en Construcción Civil');

INSERT INTO `trayectos` VALUES
(5, 2, 'Trayecto I', 1, 'Trayecto I del PNF en Electricidad', 1, '2026-09-28 23:01:27'),
(6, 2, 'Trayecto II', 2, 'Trayecto II del PNF en Electricidad', 1, '2026-09-28 23:01:27'),
(7, 2, 'Trayecto III', 3, 'Trayecto III del PNF en Electricidad', 1, '2026-09-28 23:01:27'),
(8, 2, 'Trayecto IV', 4, 'Trayecto IV del PNF en Electricidad', 1, '2026-09-28 23:01:27'),
(1, 1, 'Trayecto I', 1, 'Trayecto I del PNF en Informática', 1, '2026-09-28 23:01:27'),
(2, 1, 'Trayecto II', 2, 'Trayecto II del PNF en Informática', 1, '2026-09-28 23:01:27'),
(3, 1, 'Trayecto III', 3, 'Trayecto III del PNF en Informática', 1, '2026-09-28 23:01:27'),
(4, 1, 'Trayecto IV', 4, 'Trayecto IV del PNF en Informática', 1, '2026-09-28 23:01:27'),
(9, 3, 'Trayecto I', 1, 'Trayecto I del PNF en Administración', 1, '2026-09-28 23:01:27'),
(10, 3, 'Trayecto II', 2, 'Trayecto II del PNF en Administración', 1, '2026-09-28 23:01:27'),
(11, 3, 'Trayecto III', 3, 'Trayecto III del PNF en Administración', 1, '2026-09-28 23:01:27'),
(12, 3, 'Trayecto IV', 4, 'Trayecto IV del PNF en Administración', 1, '2026-09-28 23:01:27'),
(13, 4, 'Trayecto I', 1, 'Trayecto I del PNF en Agroalimentación', 1, '2026-09-28 23:01:27'),
(14, 4, 'Trayecto II', 2, 'Trayecto II del PNF en Agroalimentación', 1, '2026-09-28 23:01:27'),
(15, 4, 'Trayecto III', 3, 'Trayecto III del PNF en Agroalimentación', 1, '2026-09-28 23:01:27'),
(16, 4, 'Trayecto IV', 4, 'Trayecto IV del PNF en Agroalimentación', 1, '2026-09-28 23:01:27'),
(17, 5, 'Trayecto I', 1, 'Trayecto I del PNF en Construcción Civil', 1, '2026-09-28 23:01:27'),
(18, 5, 'Trayecto II', 2, 'Trayecto II del PNF en Construcción Civil', 1, '2026-09-28 23:01:27'),
(19, 5, 'Trayecto III', 3, 'Trayecto III del PNF en Construcción Civil', 1, '2026-09-28 23:01:27'),
(20, 5, 'Trayecto IV', 4, 'Trayecto IV del PNF en Construcción Civil', 1, '2026-09-28 23:01:27');

INSERT INTO `niveles_academicos` VALUES
(1, 'Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', 1, 1, 1, '2026-10-04 16:19:32', '2026-10-04 16:19:32'),
(2, 'Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', 0, 2, 1, '2026-10-04 16:19:32', '2026-10-04 16:53:32'),
(4, 'Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', 0, 4, 1, '2026-10-04 16:19:32', '2026-10-04 16:19:32'),
(3, 'Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', 0, 3, 1, '2026-10-04 16:19:32', '2026-10-04 16:19:32');

INSERT INTO `lineas_investigacion` VALUES
(10, 'REDES Y TELECOMUNICACIONES', 1, 'Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisión de datos, como también la detección de fallas dentro de una red.', 1),
(9, 'DESARROLLO DE APLICACIONES', 1, 'Desarrollar aplicaciones informáticas que respondan a las necesidades de gestión, control e intercambio de información en diversos entornos organizacionales, educativos y sociales, mediante el uso de tecnologías multiplataforma y arquitecturas orientadas a servicios, tanto en entornos locales como distribuidos.', 1),
(7, 'SISTEMAS DE INFORMACIÓN Y MODELADO DE DATOS', 1, 'Desarrollar y gestionar sistemas de información dentro del ámbito social. Aplicando soluciones efectivas para el uso adecuado y óptimo de los sistemas de información.', 1),
(8, 'EDUMÁTICA', 1, 'Aplicar las Tecnologías de la Información y Comunicación (TIC) para apoyar el proceso de aprendizaje, y así contribuir al mejoramiento de la educación en todos sus niveles.', 1);

INSERT INTO `dimensiones_operativas` VALUES
(12, 8, 'Tutoriales', 'Son programas que en mayor o menor medida dirigen el trabajo de los alumnos. Pretenden que, a partir de unas informaciones y mediante la realización de ciertas actividades, los estudiantes pongan en juego determinadas capacidades.', 1),
(16, 9, 'Aplicaciones cliente - servidor', 'Sistema distribuido entre múltiples procesadores donde hay clientes que solicitan servicios y servidores que los proporcionan. Separa los servicios situando cada uno en su plataforma más adecuada.', 1),
(7, 7, 'Sistemas de información web', 'Son primeramente sistemas de información que para su desarrollo se debe considerar la misma disciplina de construcción de sistemas de información no Web exitosos y de calidad, sirven para integrar procesos o sistemas dentro de una sola interfaz y a ellos se puede acceder por medio de una Intranet local o por la red global Internet van más allá de ser un conjunto de páginas Web.', 1),
(8, 7, 'Sistemas de información colaborativos', 'Son sistemas donde se pueden expresar ideas, experiencias, definiciones, entre otros; los cuales constituyen una red de distribución de la información en una organización o entre organizaciones.', 1),
(9, 7, 'Gestión tecnológica', 'Procesos relacionados con la implantación de sistemas, tales como, verificar e instalar nuevos equipos, entrenar a los usuarios, instalar nuevas aplicaciones, agregar nuevos módulos, además de comprobar el correcto funcionamiento de los componentes de un sistema de información que puede abarcar auditorías, técnicas de control, evaluación de la calidad.', 1),
(18, 10, 'Simulación y herramientas de simulación', 'Antes de iniciar el desarrollo de cualquier sistema complejo, los ingenieros suelen utilizar alguna herramienta de simulación o test donde sea posible modelizar y probar el sistema que está desarrollando. Reduce tiempo y chequea decisiones a priori.', 1),
(10, 8, 'Software educativo', 'Programas para el computador creados con la finalidad específica de ser utilizados como medio didáctico, es decir, para facilitar los procesos de enseñanza y de aprendizaje. Combina conocimiento educacional, comunicacional e informático.', 1),
(14, 8, 'Entornos interactivos de enseñanza', 'Proyectos donde el profesor y los alumnos se encuentran en lugares físicamente distintos. El proceso de enseñanza-aprendizaje se lleva a cabo a través de Internet, en cualquier momento y en cualquier lugar.', 1),
(17, 9, 'Servicios de integración para aplicaciones web', 'Medio para exponer y hacer disponible la funcionalidad de los sistemas de información mediante las tecnologías estándar Web, permitiendo reducción de la heterogeneidad por uso de tecnologías estándar.', 1),
(20, 9, 'Aplicaciones multiplataforma', 'Diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.', 1),
(21, 9, 'Aplicaciones web interactivas', 'Diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.', 1),
(6, 7, 'Sistemas de información con propiedades geográficas', 'Son sistemas que permiten evaluar propiedades geográficas de un entorno, generando información referente a una entidad geográfica desplegando imágenes e información en un hipermapa.', 1),
(13, 8, 'Juegos didácticos', 'El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploración y expresión, un instrumento para la organización y aplicación de habilidades y, un factor de socialización e integración.', 1),
(19, 10, 'Modelos de transmisión de datos', 'Se discute la conceptualización integral de un sistema de transmisión desde un marco común a diferentes tecnologías, tales como: sistemas de comunicación por cable, radio enlaces fijos, móviles y satelitales.', 1),
(22, 9, 'Aplicaciones móviles y ubicuas', 'Desarrollo de soluciones adaptadas a dispositivos móviles y contextos de movilidad, integrando sensores, geolocalización, notificaciones y conectividad. Se promueve la experiencia de usuario y el acceso remoto a servicios en tiempo real.', 1),
(23, 9, 'Modelado y gestión de datos', 'Diseño conceptual, lógico y físico de estructuras de datos que sustentan el funcionamiento de las aplicaciones. Incluye el uso de modelos entidad-relación, normalización, diseño de esquemas relacionales y no relacionales, así como la implementación en sistemas gestores de bases de datos. Esta dimensión garantiza la integridad, consistencia y eficiencia en el almacenamiento, recuperación y procesamiento de la información.', 1),
(24, 9, 'Seguridad y auditoría de aplicaciones', 'Incorporación de prácticas de desarrollo seguro, autenticación, autorización, cifrado y trazabilidad. Se abordan normativas como ISO/IEC 27001 y principios de privacidad por diseño, garantizando la integridad y confidencialidad de los sistemas.', 1),
(11, 8, 'Guías de estudio web', 'Representan un material instruccional utilizados para cursos de educación a distancia y como complemento a la educación presencial, lo cual provee una estructura para un curso.', 1),
(15, 8, 'Sistemas e-learning', 'Programas que faciliten la creación, adopción y distribución de contenidos, así como la adaptación del ritmo de aprendizaje y la disponibilidad de las herramientas de aprendizaje independientemente de límites horarios o geográficos.', 1),
(5, 7, 'Sistemas de información tradicionales', 'Está constituido por un conjunto de elementos de naturaleza diversa que incluyen: equipos, recursos humanos (usuario), datos e información y programas y aplicaciones; que interactúan entre sí dentro de una organización con el fin de apoyar las actividades y funciones que cumplan con los objetivos propuestos de la misma.', 1);

INSERT INTO `seg_lineas_politicas` VALUES
('ultima_purga', '"2026-11-02 23:40:20"', 'Miguel González (ID 7)', '2026-11-02 23:40:20');

ALTER TABLE `accesos_recursos` ADD CONSTRAINT `accesos_recursos_id_recurso_fkey` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `accesos_recursos` ADD CONSTRAINT `accesos_recursos_id_registro_actividad_fkey` FOREIGN KEY (`id_registro_actividad`) REFERENCES `registro_actividad` (`id`) ON DELETE CASCADE;
ALTER TABLE `auditoria` ADD CONSTRAINT `auditoria_usuario_responsable_fkey` FOREIGN KEY (`usuario_responsable`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
ALTER TABLE `cursos_dimensiones` ADD CONSTRAINT `cursos_dimensiones_id_curso_fkey` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `cursos_dimensiones` ADD CONSTRAINT `cursos_dimensiones_id_dimension_fkey` FOREIGN KEY (`id_dimension`) REFERENCES `dimensiones_operativas` (`id`) ON DELETE CASCADE;
ALTER TABLE `cursos` ADD CONSTRAINT `cursos_id_docente_fkey` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `cursos_lineas` ADD CONSTRAINT `cursos_lineas_id_curso_fkey` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `cursos_lineas` ADD CONSTRAINT `cursos_lineas_id_linea_fkey` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion` (`id`) ON DELETE CASCADE;
ALTER TABLE `cursos_preinscripciones` ADD CONSTRAINT `cursos_preinscripciones_id_curso_fkey` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `detalles_proyectos` ADD CONSTRAINT `detalles_proyectos_id_carrera_fkey` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id`) ON DELETE SET NULL;
ALTER TABLE `detalles_proyectos` ADD CONSTRAINT `detalles_proyectos_id_investigacion_padre_fkey` FOREIGN KEY (`id_investigacion_padre`) REFERENCES `recursos` (`id`) ON DELETE SET NULL;
ALTER TABLE `detalles_proyectos` ADD CONSTRAINT `detalles_proyectos_id_recurso_fkey` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `detalles_proyectos` ADD CONSTRAINT `detalles_proyectos_id_trayecto_fkey` FOREIGN KEY (`id_trayecto`) REFERENCES `trayectos` (`id`) ON DELETE SET NULL;
ALTER TABLE `detalles_articulos` ADD CONSTRAINT `detalles_revistas_id_editorial_fkey` FOREIGN KEY (`id_editorial`) REFERENCES `editoriales` (`id`) ON DELETE SET NULL;
ALTER TABLE `detalles_articulos` ADD CONSTRAINT `detalles_revistas_id_recurso_fkey` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `detalles_investigaciones` ADD CONSTRAINT `fk_detalles_investigaciones_ofertada` FOREIGN KEY (`id_investigacion_ofertada`) REFERENCES `investigaciones_ofertadas` (`id`) ON DELETE SET NULL;
ALTER TABLE `detalles_investigaciones` ADD CONSTRAINT `fk_detalles_investigaciones_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `dimensiones_operativas` ADD CONSTRAINT `fk_dimension_linea` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_clasificaciones` ADD CONSTRAINT `fk_dimension_operativa` FOREIGN KEY (`id_dimension_operativa`) REFERENCES `dimensiones_operativas` (`id`) ON DELETE SET NULL;
ALTER TABLE `recurso_etiquetas` ADD CONSTRAINT `fk_etiqueta_recurso` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE;
ALTER TABLE `investigaciones_ofertadas` ADD CONSTRAINT `fk_inv_dimension` FOREIGN KEY (`id_dimension`) REFERENCES `dimensiones_operativas` (`id`) ON DELETE SET NULL;
ALTER TABLE `investigaciones_ofertadas` ADD CONSTRAINT `fk_inv_linea` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion` (`id`) ON DELETE RESTRICT;
ALTER TABLE `investigaciones_ofertadas` ADD CONSTRAINT `fk_inv_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_investigadores` ADD CONSTRAINT `fk_investigador` FOREIGN KEY (`id_investigador`) REFERENCES `investigadores` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_clasificaciones` ADD CONSTRAINT `fk_linea_investigacion` FOREIGN KEY (`id_linea_investigacion`) REFERENCES `lineas_investigacion` (`id`) ON DELETE CASCADE;
ALTER TABLE `proyecto_carreras_vinculadas` ADD CONSTRAINT `fk_pcv_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id`) ON DELETE CASCADE;
ALTER TABLE `proyecto_carreras_vinculadas` ADD CONSTRAINT `fk_pcv_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `profesor_linea` ADD CONSTRAINT `fk_pl_linea` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion` (`id`) ON DELETE CASCADE;
ALTER TABLE `profesor_linea` ADD CONSTRAINT `fk_pl_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `postulaciones_estudiantes` ADD CONSTRAINT `fk_postulacion_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `postulaciones_estudiantes` ADD CONSTRAINT `fk_postulacion_inv` FOREIGN KEY (`id_investigacion`) REFERENCES `investigaciones_ofertadas` (`id`) ON DELETE CASCADE;
ALTER TABLE `postulaciones_estudiantes` ADD CONSTRAINT `fk_postulacion_profesor_pst` FOREIGN KEY (`id_profesor_pst`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
ALTER TABLE `profesores_pst` ADD CONSTRAINT `fk_profesores_pst_usuario` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `propuestas_empresa` ADD CONSTRAINT `fk_propuestas_archivado_por` FOREIGN KEY (`archivado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
ALTER TABLE `recurso_clasificaciones` ADD CONSTRAINT `fk_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_etiquetas` ADD CONSTRAINT `fk_recurso_etiqueta` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_investigadores` ADD CONSTRAINT `fk_recurso_inv` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `historico_versiones_pst` ADD CONSTRAINT `fk_version_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `investigaciones_dimensiones` ADD CONSTRAINT `investigaciones_dimensiones_id_dimension_fkey` FOREIGN KEY (`id_dimension`) REFERENCES `dimensiones_operativas` (`id`) ON DELETE CASCADE;
ALTER TABLE `investigaciones_dimensiones` ADD CONSTRAINT `investigaciones_dimensiones_id_investigacion_fkey` FOREIGN KEY (`id_investigacion`) REFERENCES `investigaciones_ofertadas` (`id`) ON DELETE CASCADE;
ALTER TABLE `investigaciones_lineas` ADD CONSTRAINT `investigaciones_lineas_id_investigacion_fkey` FOREIGN KEY (`id_investigacion`) REFERENCES `investigaciones_ofertadas` (`id`) ON DELETE CASCADE;
ALTER TABLE `investigaciones_lineas` ADD CONSTRAINT `investigaciones_lineas_id_linea_fkey` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion` (`id`) ON DELETE CASCADE;
ALTER TABLE `lineas_investigacion` ADD CONSTRAINT `lineas_investigacion_id_carrera_fkey` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id`) ON DELETE CASCADE;
ALTER TABLE `notificaciones` ADD CONSTRAINT `notificaciones_id_usuario_fkey` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `preferencias_usuario` ADD CONSTRAINT `preferencias_usuario_id_usuario_fkey` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `profesor_dimension` ADD CONSTRAINT `profesor_dimension_id_dimension_fkey` FOREIGN KEY (`id_dimension`) REFERENCES `dimensiones_operativas` (`id`) ON DELETE CASCADE;
ALTER TABLE `profesor_dimension` ADD CONSTRAINT `profesor_dimension_id_linea_fkey` FOREIGN KEY (`id_linea`) REFERENCES `lineas_investigacion` (`id`) ON DELETE CASCADE;
ALTER TABLE `profesor_dimension` ADD CONSTRAINT `profesor_dimension_id_usuario_fkey` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `proyecto_tutores` ADD CONSTRAINT `proyecto_tutores_id_recurso_fkey` FOREIGN KEY (`id_recurso`) REFERENCES `detalles_proyectos` (`id_recurso`) ON DELETE CASCADE;
ALTER TABLE `proyecto_tutores` ADD CONSTRAINT `proyecto_tutores_id_tutor_fkey` FOREIGN KEY (`id_tutor`) REFERENCES `tutores` (`id`) ON DELETE CASCADE;
ALTER TABLE `proyecto_tutores` ADD CONSTRAINT `proyecto_tutores_tipo_tutor_id_fkey` FOREIGN KEY (`tipo_tutor_id`) REFERENCES `tipo_tutor` (`id`) ON DELETE SET NULL;
ALTER TABLE `recurso_autores` ADD CONSTRAINT `recurso_autores_id_autor_fkey` FOREIGN KEY (`id_autor`) REFERENCES `autores` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_autores` ADD CONSTRAINT `recurso_autores_id_recurso_fkey` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_categorias` ADD CONSTRAINT `recurso_categorias_id_categoria_fkey` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;
ALTER TABLE `recurso_categorias` ADD CONSTRAINT `recurso_categorias_id_recurso_fkey` FOREIGN KEY (`id_recurso`) REFERENCES `recursos` (`id`) ON DELETE CASCADE;
ALTER TABLE `recursos` ADD CONSTRAINT `recursos_id_tipo_recurso_fkey` FOREIGN KEY (`id_tipo_recurso`) REFERENCES `tipo_recurso` (`id`) ON DELETE RESTRICT;
ALTER TABLE `registro_actividad` ADD CONSTRAINT `registro_actividad_id_usuario_fkey` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
ALTER TABLE `registro_actividad` ADD CONSTRAINT `registro_actividad_id_visitante_fkey` FOREIGN KEY (`id_visitante`) REFERENCES `visitantes` (`id`) ON DELETE SET NULL;
ALTER TABLE `solicitudes_docentes_pst` ADD CONSTRAINT `solicitudes_docentes_pst_id_profesor_fkey` FOREIGN KEY (`id_profesor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `solicitudes_docentes_pst` ADD CONSTRAINT `solicitudes_docentes_pst_id_revisor_fkey` FOREIGN KEY (`id_revisor`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
ALTER TABLE `trayectos` ADD CONSTRAINT `trayectos_id_carrera_fkey` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id`) ON DELETE CASCADE;
ALTER TABLE `usuarios` ADD CONSTRAINT `usuarios_id_rol_fkey` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE RESTRICT;

DROP TRIGGER IF EXISTS `tg_auditoria_recursos_delete`;
CREATE TRIGGER `tg_auditoria_recursos_delete`
BEFORE DELETE ON `recursos`
FOR EACH ROW
INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
VALUES (
    'recursos',
    OLD.`id`,
    'DELETE',
    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
    JSON_OBJECT('titulo', OLD.`titulo`, 'id_tipo_recurso', OLD.`id_tipo_recurso`),
    NULL,
    CURRENT_TIMESTAMP
);

DROP TRIGGER IF EXISTS `tg_auditoria_recursos_insert`;
CREATE TRIGGER `tg_auditoria_recursos_insert`
AFTER INSERT ON `recursos`
FOR EACH ROW
INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
VALUES (
    'recursos',
    NEW.`id`,
    'INSERT',
    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
    NULL,
    JSON_OBJECT('titulo', NEW.`titulo`, 'id_tipo_recurso', NEW.`id_tipo_recurso`),
    CURRENT_TIMESTAMP
);

DROP TRIGGER IF EXISTS `tg_auditoria_usuarios_delete`;
CREATE TRIGGER `tg_auditoria_usuarios_delete`
BEFORE DELETE ON `usuarios`
FOR EACH ROW
INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
VALUES (
    'usuarios',
    OLD.`id`,
    'DELETE',
    IF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED) = OLD.`id`, NULL, NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0)),
    JSON_OBJECT('nombre', OLD.`nombre_completo`, 'email', OLD.`email`, 'id_rol', OLD.`id_rol`),
    NULL,
    CURRENT_TIMESTAMP
);

DROP TRIGGER IF EXISTS `tg_auditoria_usuarios_insert`;
CREATE TRIGGER `tg_auditoria_usuarios_insert`
AFTER INSERT ON `usuarios`
FOR EACH ROW
INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
VALUES (
    'usuarios',
    NEW.`id`,
    'INSERT',
    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
    NULL,
    JSON_OBJECT('nombre', NEW.`nombre_completo`, 'email', NEW.`email`, 'id_rol', NEW.`id_rol`),
    CURRENT_TIMESTAMP
);

DROP TRIGGER IF EXISTS `tg_auditoria_usuarios_update`;
CREATE TRIGGER `tg_auditoria_usuarios_update`
AFTER UPDATE ON `usuarios`
FOR EACH ROW
INSERT INTO `auditoria` (`tabla_afectada`, `id_registro`, `accion`, `usuario_responsable`, `datos_anteriores`, `datos_nuevos`, `fecha_hora`)
VALUES (
    'usuarios',
    OLD.`id`,
    'UPDATE',
    NULLIF(CAST(NULLIF(@app_usuario_actual, '') AS UNSIGNED), 0),
    JSON_OBJECT('nombre', OLD.`nombre_completo`, 'id_rol', OLD.`id_rol`, 'activo', OLD.`activo`),
    JSON_OBJECT('nombre', NEW.`nombre_completo`, 'id_rol', NEW.`id_rol`, 'activo', NEW.`activo`),
    CURRENT_TIMESTAMP
);

SET FOREIGN_KEY_CHECKS = 1;
