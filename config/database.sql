-- =========================================================
-- BASE DE DATOS: UPTMBI - Plataforma de Investigación
-- Universidad Politécnica Territorial del Estado Trujillo
-- "Mario Briceño Iragorry"
-- Importar desde PhpMyAdmin
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `uptmbi_investigacion`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `uptmbi_investigacion`;

-- -----------------------------------------------------------
-- 1. ROLES
-- -----------------------------------------------------------
CREATE TABLE `roles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`slug`, `name`) VALUES
('superadmin', 'Super Administrador'),
('investigator', 'Investigador'),
('teacher', 'Docente'),
('student', 'Estudiante');

-- -----------------------------------------------------------
-- 2. USUARIOS
-- -----------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role_id` INT UNSIGNED NOT NULL DEFAULT 4,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `department` VARCHAR(200) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`name`, `email`, `password_hash`, `role_id`, `bio`, `department`) VALUES
('Admin UPTMBI', 'admin@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'Administrador del sistema.', 'Dirección'),
('Dra. Elena Méndez', 'elena.mendez@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'Investigadora principal en IA aplicada a salud pública. Doctora en Ciencias de la Computación.', 'Informática'),
('Ing. Ricardo Torres', 'ricardo.torres@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'Especialista en robótica, sistemas autónomos y automatización industrial.', 'Electrónica'),
('MSc. Luis Sánchez', 'luis.sanchez@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'Experto en ciberseguridad y redes de telecomunicaciones.', 'Telemática'),
('Dra. Carmen Rivas', 'carmen.rivas@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'Investigadora en soberanía tecnológica y software libre.', 'Sistemas'),
('Prof. Andrés Colmenares', 'andres.colmenares@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'Docente de Matemáticas y Física. Colabora en proyectos de modelado estadístico.', 'Ciencias Básicas'),
('Lic. Sofía Ramírez', 'sofia.ramirez@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'Estudiante de Ingeniería en Informática, 7mo semestre. Interesada en IA.', 'Informática'),
('Br. Carlos Peña', 'carlos.pena@uptmbi.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'Estudiante de Telemática. Apasionado de la ciberseguridad.', 'Telemática');

-- -----------------------------------------------------------
-- 3. LÍNEAS DE INVESTIGACIÓN
-- -----------------------------------------------------------
CREATE TABLE `taxonomic_lines` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(10) DEFAULT '🔬',
  `color` VARCHAR(20) DEFAULT '#1d6fa4',
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `taxonomic_lines` (`name`, `description`, `icon`, `color`) VALUES
('IA & Machine Learning', 'Inteligencia artificial, aprendizaje automático y procesamiento del lenguaje natural.', '🤖', '#1565c0'),
('Robótica y Sistemas', 'Automatización industrial, sistemas embebidos y robótica aplicada.', '⚙️', '#0277bd'),
('Soberanía Tecnológica', 'Software libre, desarrollo local y tecnología para la gestión pública.', '🛡️', '#00838f'),
('Telemática', 'Redes de comunicación, ciberseguridad y telecomunicaciones.', '📡', '#2e7d32'),
('Gestión de TICs', 'Gestión de tecnologías de la información en organizaciones públicas.', '💼', '#6a1b9a'),
('Energías Renovables', 'Sistemas fotovoltaicos, eólicos y tecnologías limpias para el estado Trujillo.', '⚡', '#e65100');

-- -----------------------------------------------------------
-- 4. PROYECTOS DE INVESTIGACIÓN
-- -----------------------------------------------------------
CREATE TABLE `projects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `taxonomic_line_id` INT UNSIGNED NOT NULL,
  `investigator_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(300) NOT NULL,
  `abstract` TEXT NOT NULL,
  `objectives` TEXT DEFAULT NULL,
  `methodology` TEXT DEFAULT NULL,
  `status` ENUM('draft','active','completed','cancelled') DEFAULT 'active',
  `impact_score` DECIMAL(3,1) DEFAULT 0.0,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `start_date` DATE DEFAULT NULL,
  `end_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_proj_line` FOREIGN KEY (`taxonomic_line_id`) REFERENCES `taxonomic_lines`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_proj_inv` FOREIGN KEY (`investigator_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `projects` (`taxonomic_line_id`,`investigator_id`,`title`,`abstract`,`objectives`,`methodology`,`status`,`impact_score`,`start_date`,`end_date`) VALUES
(1, 2, 'Diagnóstico asistido por Redes Neuronales en Salud Pública',
 'Desarrollo de un sistema de apoyo al diagnóstico clínico basado en redes neuronales convolucionales para la detección temprana de enfermedades respiratorias en la población trujillana.',
 'Diseñar e implementar un modelo de IA con precisión diagnóstica superior al 90%. Reducir el tiempo de diagnóstico en centros de salud rurales.',
 'Investigación aplicada con datos anonimizados del ambulatorio Luis Razetti. Entrenamiento supervisado con TensorFlow. Validación con médicos especialistas.',
 'active', 9.8, '2024-01-15', '2025-12-31'),
(2, 3, 'Sistemas Autónomos de Monitoreo para Agricultura de Precisión',
 'Diseño y construcción de robots autónomos de bajo costo para el monitoreo de cultivos agrícolas en el estado Trujillo, integrando sensores IoT y visión artificial.',
 'Construir prototipo funcional de robot agrícola. Implementar sistema de detección de plagas por visión computacional.',
 'Metodología de diseño iterativo. Uso de Raspberry Pi y Arduino. Pruebas de campo en la Hacienda Las Mercedes, Valera.',
 'active', 9.2, '2024-03-01', '2026-02-28'),
(4, 4, 'Ciberseguridad en Infraestructuras de Redes Mesh Comunitarias',
 'Análisis e implementación de protocolos de seguridad para redes malladas comunitarias en zonas rurales del estado Trujillo con conectividad limitada.',
 'Evaluar vulnerabilidades en redes mesh existentes. Proponer e implementar protocolo de cifrado adaptado a bajo ancho de banda.',
 'Auditoría de seguridad en 5 comunidades piloto. Implementación de soluciones con OpenWRT. Evaluación de rendimiento.',
 'active', 8.5, '2023-09-01', '2025-08-31'),
(3, 5, 'Desarrollo de Software Libre para la Gestión Administrativa Local',
 'Creación de un sistema de gestión municipal basado íntegramente en tecnologías libres y soberanas, adaptado a las necesidades de las alcaldías del estado Trujillo.',
 'Migrar el 80% de los procesos administrativos de 3 alcaldías piloto a software libre.',
 'Entrevistas con funcionarios municipales. Desarrollo ágil con ciclos de 2 semanas. Stack: PHP, PostgreSQL, Linux.',
 'active', 9.5, '2024-06-01', '2026-05-31'),
(1, 2, 'Procesamiento de Lenguaje Natural para Documentos Jurídicos Venezolanos',
 'Desarrollo de modelos NLP entrenados con corpus jurídico venezolano para automatizar el análisis y clasificación de expedientes legales.',
 'Construir corpus jurídico con 50,000 documentos. Entrenar modelo BERT adaptado al castellano legal venezolano.',
 'Recolección y anotación de corpus. Fine-tuning de modelos preentrenados. Validación con expertos jurídicos.',
 'completed', 8.7, '2022-01-01', '2023-12-31'),
(6, 5, 'Implementación de Paneles Solares en Comunidades Rurales de Trujillo',
 'Proyecto de instalación y gestión de sistemas fotovoltaicos en 10 comunidades rurales del estado Trujillo sin acceso a la red eléctrica nacional.',
 'Instalar sistemas fotovoltaicos en 10 comunidades. Capacitar a 50 técnicos locales en mantenimiento.',
 'Diagnóstico energético comunitario. Diseño de sistemas adaptados. Instalación y capacitación.',
 'active', 8.0, '2024-08-01', '2026-07-31');

-- -----------------------------------------------------------
-- 5. AVANCES DE PROYECTOS
-- -----------------------------------------------------------
CREATE TABLE `project_advances` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `project_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `advance_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_adv_proj` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_adv_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `project_advances` (`project_id`,`user_id`,`title`,`content`,`advance_date`) VALUES
(1, 2, 'Recolección de datos completada', 'Se logró recolectar y anonimizar un total de 12,500 registros clínicos en colaboración con el ambulatorio Luis Razetti. El dataset fue validado por el comité de ética institucional.', '2024-04-01'),
(1, 2, 'Primera iteración del modelo CNN', 'El modelo de red neuronal convolucional alcanzó una precisión del 87.3% en el conjunto de validación. Se identificaron oportunidades de mejora en la capa de normalización por lotes.', '2024-07-15'),
(1, 2, 'Precisión objetivo alcanzada', 'Tras el ajuste de hiperparámetros y aumento del dataset con técnicas de data augmentation, el modelo alcanzó una precisión del 92.1%, superando el objetivo planteado.', '2024-11-20'),
(2, 3, 'Prototipo mecánico ensamblado', 'El primer prototipo del robot agrícola fue ensamblado exitosamente. Incorpora 4 motores DC, sensores ultrasónicos de distancia y una cámara de 12MP para visión artificial.', '2024-06-10'),
(2, 3, 'Pruebas de campo exitosas', 'Se realizaron las primeras pruebas de campo en La Hacienda Las Mercedes. El robot navigó de forma autónoma durante 3 horas sin intervención humana, cubriendo 0.8 hectáreas.', '2024-09-05'),
(4, 4, 'Auditoría de seguridad completada', 'Se auditaron 5 redes mesh comunitarias. Se detectaron 23 vulnerabilidades críticas, principalmente en la capa de autenticación y en protocolos de cifrado desactualizados.', '2024-02-28'),
(3, 5, 'Primer módulo desplegado en Boconó', 'El módulo de registro civil fue desplegado exitosamente en la Alcaldía de Boconó. 12 funcionarios fueron capacitados y el sistema está en producción desde hace 2 semanas.', '2025-01-10');

-- -----------------------------------------------------------
-- 6. COLABORACIONES EN PROYECTOS
-- -----------------------------------------------------------
CREATE TABLE `project_collaborations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `project_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `role_in_project` ENUM('advisor','assistant','co_investigator') NOT NULL,
  `status` ENUM('pending','accepted','rejected') DEFAULT 'pending',
  `message` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_proj_user` (`project_id`, `user_id`),
  CONSTRAINT `fk_col_proj` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_col_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `project_collaborations` (`project_id`,`user_id`,`role_in_project`,`status`,`message`) VALUES
(1, 6, 'assistant', 'accepted', 'Quiero apoyar en la limpieza y análisis del dataset.'),
(1, 7, 'assistant', 'pending', 'Tengo experiencia en Python y me gustaría colaborar.'),
(2, 6, 'advisor', 'accepted', 'Puedo apoyar con los cálculos de sensores.'),
(3, 8, 'assistant', 'accepted', 'Estoy interesado en ciberseguridad y quiero aprender.');

-- -----------------------------------------------------------
-- 7. PERFILES CURRICULARES
-- -----------------------------------------------------------
CREATE TABLE `curriculum_profiles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL UNIQUE,
  `summary` TEXT NOT NULL,
  `education` TEXT NOT NULL,
  `skills` TEXT NOT NULL,
  `publications` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_curr_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `curriculum_profiles` (`user_id`,`summary`,`education`,`skills`,`publications`) VALUES
(2, 'Doctora en Ciencias de la Computación con 12 años de experiencia en IA aplicada. Ha liderado 5 proyectos de investigación financiados a nivel nacional.',
 'Doctorado en Ciencias de la Computación - UCV (2012). Maestría en Inteligencia Artificial - USB (2008). Ingeniería en Informática - UPTTMBI (2005).',
 'Python, TensorFlow, PyTorch, Machine Learning, Deep Learning, NLP, Estadística Avanzada, Investigación Científica',
 'Méndez, E. (2023). CNN para diagnóstico clínico. Revista Venezolana de Computación. Méndez, E. (2021). NLP y documentos jurídicos. Congreso CLEI.'),
(3, 'Ingeniero en Electrónica con especialización en robótica y automatización. 8 años en investigación aplicada en sistemas autónomos.',
 'Maestría en Robótica - ULA (2016). Ingeniería en Electrónica - ULA (2012).',
 'Arduino, Raspberry Pi, ROS, Visión Artificial, OpenCV, C++, Python, IoT, Diseño CAD',
 'Torres, R. (2024). Robot agrícola autónomo de bajo costo. Proceedings IEEE LARC.'),
(4, 'Magíster en Telecomunicaciones y Ciberseguridad. Consultor para organismos públicos en seguridad de redes.',
 'Maestría en Telecomunicaciones - ULA (2018). Ingeniería en Telemática - UPTTMBI (2014).',
 'Ciberseguridad, Ethical Hacking, Wireshark, OpenWRT, Linux, Kali Linux, Redes Mesh, IPv6',
 'Sánchez, L. (2023). Seguridad en redes comunitarias. Revista REDES Venezuela.'),
(5, 'Doctora en Ciencias de la Computación. Activista de software libre. Ha coordinado la migración tecnológica de 8 organismos públicos venezolanos.',
 'Doctorado en Ciencias de la Computación - UCAB (2015). Ingeniería en Sistemas - UNEXPO (2009).',
 'PHP, Python, PostgreSQL, Linux, Debian, Gestión de Proyectos, Scrum, LibreOffice, Software Libre',
 'Rivas, C. (2024). Soberanía tecnológica en Venezuela. Congreso SoftwareLivre Brasil.');

-- -----------------------------------------------------------
-- 8. FORO - CATEGORÍAS
-- -----------------------------------------------------------
CREATE TABLE `forum_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(10) DEFAULT '💬',
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `order_num` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `forum_categories` (`name`, `description`, `icon`, `slug`, `order_num`) VALUES
('Investigaciones y Proyectos', 'Discusiones sobre proyectos de investigación en curso y finalizados.', '🔬', 'investigaciones', 1),
('Metodología Científica', 'Preguntas y recursos sobre métodos de investigación, estadística y análisis.', '📊', 'metodologia', 2),
('Convocatorias y Oportunidades', 'Becas, concursos, fondos de investigación y llamados a colaborar.', '📢', 'convocatorias', 3),
('Tecnología y Herramientas', 'Discusión sobre software, hardware y herramientas para investigación.', '🛠️', 'tecnologia', 4),
('General UPTMBI', 'Noticias, eventos y anuncios de la universidad.', '🏛️', 'general', 5);

-- -----------------------------------------------------------
-- 9. FORO - TEMAS
-- -----------------------------------------------------------
CREATE TABLE `forum_topics` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(300) NOT NULL,
  `content` TEXT NOT NULL,
  `views` INT UNSIGNED DEFAULT 0,
  `is_pinned` TINYINT(1) DEFAULT 0,
  `is_closed` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_topic_cat` FOREIGN KEY (`category_id`) REFERENCES `forum_categories`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_topic_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `forum_topics` (`category_id`,`user_id`,`title`,`content`,`views`,`is_pinned`) VALUES
(1, 2, '¿Cómo integrar datos de salud con modelos de IA respetando la privacidad?',
 'En nuestro proyecto de diagnóstico con redes neuronales, hemos tenido que trabajar con datos clínicos sensibles. Quiero compartir nuestra experiencia con técnicas de anonimización y aprendizaje federado. ¿Alguien más ha trabajado en esto?',
 245, 1),
(1, 3, 'Resultados preliminares: robot agrícola en La Hacienda Las Mercedes',
 'Acabo de publicar los avances del proyecto de robótica agrícola. Comparto aquí algunos videos y datos de las pruebas de campo. El robot logró detectar el 94% de las plantas enfermas en el área de prueba.',
 189, 0),
(2, 4, '¿Qué herramientas usan para el análisis estadístico en sus investigaciones?',
 'Estoy iniciando mi primera investigación formal y me pregunto qué software es más recomendable: R, SPSS, Python con SciPy... Agradezco sus experiencias y recomendaciones.',
 312, 0),
(3, 5, '[CONVOCATORIA] FONACIT abre fondo para proyectos de soberanía tecnológica 2025',
 'El FONACIT acaba de abrir la convocatoria para el Fondo de Innovación Tecnológica 2025. El plazo para aplicar es el 30 de junio. Aquí los detalles y requisitos para los grupos de investigación de la UPTMBI.',
 467, 1),
(4, 4, 'Configurando un laboratorio de ciberseguridad con Kali Linux y VMs',
 'Comparto mi guía paso a paso para configurar un entorno de laboratorio de ciberseguridad usando Kali Linux, VirtualBox y redes virtuales. Ideal para prácticas de ethical hacking de forma segura.',
 398, 0),
(5, 1, 'Bienvenidos a la Plataforma de Investigación UPTMBI',
 'Esta plataforma es el espacio digital para la comunidad científica de la Universidad Politécnica Territorial del Estado Trujillo. Aquí podrán compartir, colaborar y hacer crecer la investigación en nuestra institución.',
 1205, 1);

-- -----------------------------------------------------------
-- 10. FORO - RESPUESTAS
-- -----------------------------------------------------------
CREATE TABLE `forum_replies` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `topic_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_reply_topic` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `forum_replies` (`topic_id`,`user_id`,`content`) VALUES
(1, 4, 'Excelente tema, Dra. Méndez. En nuestro proyecto de ciberseguridad también nos topamos con datos sensibles de usuarios. Usamos diferential privacy y fue muy efectivo.'),
(1, 6, 'Muchas gracias por la información. ¿Tienen algún repositorio o documento que pueda consultar para implementar el aprendizaje federado con TensorFlow Federated?'),
(1, 2, 'Sofía, sí. Te comparto el enlace al repositorio de nuestro proyecto en GitLab institucional. También te recomiendo el paper de McMahan et al. (2017) sobre Federated Learning.'),
(2, 2, 'Excelentes resultados, Ricardo. ¿Qué tipo de algoritmo de detección de enfermedades usaron? ¿YOLO? ¿Detectron2?'),
(2, 3, 'Usamos YOLOv8 ajustado con un dataset propio de 3,000 imágenes de hojas enfermas. El entrenamiento duró 6 horas en una GPU RTX 3060.'),
(3, 2, 'Para estadística, R es insustituible para análisis formales. Pero Python con SciPy + Pandas es más versátil si ya vas a procesar datos con código.'),
(3, 5, 'Coincido con Elena. Para investigaciones sociales y humanísticas, SPSS sigue siendo estándar. Para ciencias exactas e ingenierías, R o Python son superiores.');
