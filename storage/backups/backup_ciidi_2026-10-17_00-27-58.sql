--
-- PostgreSQL database dump
--


-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_id_rol_fkey;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS trayectos_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_id_visitante_fkey;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.recursos DROP CONSTRAINT IF EXISTS recursos_id_tipo_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_id_categoria_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_id_autor_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_tipo_tutor_id_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_id_tutor_fkey;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS fk_version_recurso;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS fk_recurso_inv;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_recurso_etiqueta;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_recurso;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_inv;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_estudiante;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS fk_pcv_recurso;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS fk_pcv_carrera;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_linea_investigacion;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS fk_investigador;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS fk_inv_profesor;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS fk_inv_linea;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS fk_inv_dimension;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_etiqueta_recurso;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_dimension_operativa;
ALTER TABLE IF EXISTS ONLY public.dimensiones_operativas DROP CONSTRAINT IF EXISTS fk_dimension_linea;
ALTER TABLE IF EXISTS ONLY public.detalles_investigaciones DROP CONSTRAINT IF EXISTS fk_detalles_investigaciones_recurso;
ALTER TABLE IF EXISTS ONLY public.detalles_investigaciones DROP CONSTRAINT IF EXISTS fk_detalles_investigaciones_ofertada;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_id_editorial_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_id_docente_fkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_usuario_responsable_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_registro_actividad_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_recurso_fkey;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_update ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_insert ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_delete ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_insert ON public.recursos;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_delete ON public.recursos;
DROP INDEX IF EXISTS public.idx_trayectos_carrera;
DROP INDEX IF EXISTS public.idx_recurso_clasif_linea;
DROP INDEX IF EXISTS public.idx_recurso_clasif_dimension;
DROP INDEX IF EXISTS public.idx_pcv_recurso;
DROP INDEX IF EXISTS public.idx_pcv_carrera;
DROP INDEX IF EXISTS public.idx_detalles_inv_ofertada;
ALTER TABLE IF EXISTS ONLY public.waf_rate_limiter DROP CONSTRAINT IF EXISTS waf_rate_limiter_pkey;
ALTER TABLE IF EXISTS ONLY public.visitantes DROP CONSTRAINT IF EXISTS visitantes_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_email_key;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_cedula_key;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS uq_carrera_trayecto;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS unique_postulacion;
ALTER TABLE IF EXISTS ONLY public.privilegios DROP CONSTRAINT IF EXISTS unique_nivel_privilegio;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_cedula_key;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS trayectos_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_tutor DROP CONSTRAINT IF EXISTS tipo_tutor_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_tutor DROP CONSTRAINT IF EXISTS tipo_tutor_nombre_key;
ALTER TABLE IF EXISTS ONLY public.tipo_recurso DROP CONSTRAINT IF EXISTS tipo_recurso_pkey;
ALTER TABLE IF EXISTS ONLY public.tipo_recurso DROP CONSTRAINT IF EXISTS tipo_recurso_nombre_key;
ALTER TABLE IF EXISTS ONLY public.telemetria_cache DROP CONSTRAINT IF EXISTS telemetria_cache_pkey;
ALTER TABLE IF EXISTS ONLY public.system_audit_log DROP CONSTRAINT IF EXISTS system_audit_log_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_nombre_key;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_pkey;
ALTER TABLE IF EXISTS ONLY public.recursos DROP CONSTRAINT IF EXISTS recursos_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS recurso_investigadores_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS recurso_etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS recurso_clasificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_codigo_seguimiento_key;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_pkey;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS postulaciones_estudiantes_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS pk_proyecto_carreras_vinculadas;
ALTER TABLE IF EXISTS ONLY public.password_resets DROP CONSTRAINT IF EXISTS password_resets_pkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.niveles_academicos DROP CONSTRAINT IF EXISTS niveles_academicos_pkey;
ALTER TABLE IF EXISTS ONLY public.niveles_academicos DROP CONSTRAINT IF EXISTS niveles_academicos_codigo_key;
ALTER TABLE IF EXISTS ONLY public.matriz_rbac DROP CONSTRAINT IF EXISTS matriz_rbac_pkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_pkey;
ALTER TABLE IF EXISTS ONLY public.investigadores DROP CONSTRAINT IF EXISTS investigadores_pkey;
ALTER TABLE IF EXISTS ONLY public.investigadores DROP CONSTRAINT IF EXISTS investigadores_orcid_key;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS investigaciones_ofertadas_pkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS historico_versiones_pst_pkey;
ALTER TABLE IF EXISTS ONLY public.etiquetas DROP CONSTRAINT IF EXISTS etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.etiquetas DROP CONSTRAINT IF EXISTS etiquetas_nombre_key;
ALTER TABLE IF EXISTS ONLY public.editoriales DROP CONSTRAINT IF EXISTS editoriales_pkey;
ALTER TABLE IF EXISTS ONLY public.editoriales DROP CONSTRAINT IF EXISTS editoriales_nombre_key;
ALTER TABLE IF EXISTS ONLY public.dimensiones_operativas DROP CONSTRAINT IF EXISTS dimensiones_operativas_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_investigaciones DROP CONSTRAINT IF EXISTS detalles_investigaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_slug_key;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_pkey;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_nombre_key;
ALTER TABLE IF EXISTS ONLY public.carreras DROP CONSTRAINT IF EXISTS carreras_pkey;
ALTER TABLE IF EXISTS ONLY public.carreras DROP CONSTRAINT IF EXISTS carreras_nombre_key;
ALTER TABLE IF EXISTS ONLY public.autores DROP CONSTRAINT IF EXISTS autores_pkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_pkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_pkey;
ALTER TABLE IF EXISTS public.visitantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.usuarios ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tutores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.trayectos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_tutor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_recurso ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.registro_actividad ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.recursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.propuestas_empresa ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.privilegios ALTER COLUMN privilegio_id DROP DEFAULT;
ALTER TABLE IF EXISTS public.postulaciones_estudiantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.password_resets ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.notificaciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.niveles_academicos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.lineas_investigacion ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.investigadores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.investigaciones_ofertadas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.historico_versiones_pst ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.etiquetas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.editoriales ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.dimensiones_operativas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.categorias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.carreras ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.autores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.auditoria ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.accesos_recursos ALTER COLUMN id DROP DEFAULT;
DROP TABLE IF EXISTS public.waf_rate_limiter;
DROP SEQUENCE IF EXISTS public.visitantes_id_seq;
DROP TABLE IF EXISTS public.visitantes;
DROP SEQUENCE IF EXISTS public.usuarios_id_seq;
DROP TABLE IF EXISTS public.usuarios;
DROP SEQUENCE IF EXISTS public.tutores_id_seq;
DROP TABLE IF EXISTS public.tutores;
DROP SEQUENCE IF EXISTS public.trayectos_id_seq;
DROP TABLE IF EXISTS public.trayectos;
DROP SEQUENCE IF EXISTS public.tipo_tutor_id_seq;
DROP TABLE IF EXISTS public.tipo_tutor;
DROP SEQUENCE IF EXISTS public.tipo_recurso_id_seq;
DROP TABLE IF EXISTS public.tipo_recurso;
DROP TABLE IF EXISTS public.telemetria_cache;
DROP TABLE IF EXISTS public.system_audit_log;
DROP SEQUENCE IF EXISTS public.roles_id_seq;
DROP TABLE IF EXISTS public.roles;
DROP SEQUENCE IF EXISTS public.registro_actividad_id_seq;
DROP TABLE IF EXISTS public.registro_actividad;
DROP SEQUENCE IF EXISTS public.recursos_id_seq;
DROP TABLE IF EXISTS public.recursos;
DROP TABLE IF EXISTS public.recurso_investigadores;
DROP TABLE IF EXISTS public.recurso_etiquetas;
DROP TABLE IF EXISTS public.recurso_clasificaciones;
DROP TABLE IF EXISTS public.recurso_categorias;
DROP TABLE IF EXISTS public.recurso_autores;
DROP TABLE IF EXISTS public.proyecto_tutores;
DROP TABLE IF EXISTS public.proyecto_carreras_vinculadas;
DROP SEQUENCE IF EXISTS public.propuestas_empresa_id_seq;
DROP TABLE IF EXISTS public.propuestas_empresa;
DROP SEQUENCE IF EXISTS public.privilegios_privilegio_id_seq;
DROP TABLE IF EXISTS public.privilegios;
DROP TABLE IF EXISTS public.preferencias_usuario;
DROP SEQUENCE IF EXISTS public.postulaciones_estudiantes_id_seq;
DROP TABLE IF EXISTS public.postulaciones_estudiantes;
DROP SEQUENCE IF EXISTS public.password_resets_id_seq;
DROP TABLE IF EXISTS public.password_resets;
DROP SEQUENCE IF EXISTS public.notificaciones_id_seq;
DROP TABLE IF EXISTS public.notificaciones;
DROP SEQUENCE IF EXISTS public.niveles_academicos_id_seq;
DROP TABLE IF EXISTS public.niveles_academicos;
DROP TABLE IF EXISTS public.matriz_rbac;
DROP SEQUENCE IF EXISTS public.lineas_investigacion_id_seq;
DROP TABLE IF EXISTS public.lineas_investigacion;
DROP SEQUENCE IF EXISTS public.investigadores_id_seq;
DROP TABLE IF EXISTS public.investigadores;
DROP SEQUENCE IF EXISTS public.investigaciones_ofertadas_id_seq;
DROP TABLE IF EXISTS public.investigaciones_ofertadas;
DROP SEQUENCE IF EXISTS public.historico_versiones_pst_id_seq;
DROP TABLE IF EXISTS public.historico_versiones_pst;
DROP SEQUENCE IF EXISTS public.etiquetas_id_seq;
DROP TABLE IF EXISTS public.etiquetas;
DROP SEQUENCE IF EXISTS public.editoriales_id_seq;
DROP TABLE IF EXISTS public.editoriales;
DROP SEQUENCE IF EXISTS public.dimensiones_operativas_id_seq;
DROP TABLE IF EXISTS public.dimensiones_operativas;
DROP TABLE IF EXISTS public.detalles_investigaciones;
DROP TABLE IF EXISTS public.detalles_articulos;
DROP SEQUENCE IF EXISTS public.cursos_id_seq;
DROP TABLE IF EXISTS public.cursos;
DROP SEQUENCE IF EXISTS public.categorias_id_seq;
DROP TABLE IF EXISTS public.categorias;
DROP SEQUENCE IF EXISTS public.carreras_id_seq;
DROP TABLE IF EXISTS public.carreras;
DROP SEQUENCE IF EXISTS public.autores_id_seq;
DROP TABLE IF EXISTS public.autores;
DROP SEQUENCE IF EXISTS public.auditoria_id_seq;
DROP TABLE IF EXISTS public.auditoria;
DROP SEQUENCE IF EXISTS public.accesos_recursos_id_seq;
DROP TABLE IF EXISTS public.accesos_recursos;
DROP PROCEDURE IF EXISTS public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone);
DROP FUNCTION IF EXISTS public.fn_auditoria_usuarios();
DROP FUNCTION IF EXISTS public.fn_auditoria_recursos();
DROP TYPE IF EXISTS public.tipo_pregunta_enum;
DROP TYPE IF EXISTS public.tipo_interaccion_usuario_enum;
DROP TYPE IF EXISTS public.tipo_interaccion_enum;
DROP TYPE IF EXISTS public.nivel_academico_enum;
DROP TYPE IF EXISTS public.estado_propuesta_enum;
DROP TYPE IF EXISTS public.estado_curso_enum;
DROP TYPE IF EXISTS public.accion_auditoria_enum;
DROP TYPE IF EXISTS public.accion_acceso_enum;
--
-- Name: accion_acceso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_acceso_enum AS ENUM (
    'visualizacion',
    'descarga'
);



--
-- Name: accion_auditoria_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_auditoria_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE'
);



--
-- Name: estado_curso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_curso_enum AS ENUM (
    'borrador',
    'publicado',
    'archivado'
);



--
-- Name: estado_propuesta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_propuesta_enum AS ENUM (
    'pendiente',
    'aceptada',
    'rechazada'
);



--
-- Name: nivel_academico_enum; Type: TYPE; Schema: public; Owner: miki
--

CREATE TYPE public.nivel_academico_enum AS ENUM (
    'Pregrado',
    'Especializacion',
    'Maestria',
    'Doctorado'
);



--
-- Name: tipo_interaccion_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_enum AS ENUM (
    'like',
    'bookmark'
);



--
-- Name: tipo_interaccion_usuario_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_usuario_enum AS ENUM (
    'like',
    'guardado'
);



--
-- Name: tipo_pregunta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_pregunta_enum AS ENUM (
    'multiple',
    'v_f',
    'corta'
);



--
-- Name: fn_auditoria_recursos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_auditoria_recursos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_usuario_actual INT := NULLIF(current_setting('app.usuario_actual', true), '')::INT; 
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', OLD.id, 'DELETE', v_usuario_actual, jsonb_build_object('titulo', OLD.titulo, 'id_tipo_recurso', OLD.id_tipo_recurso), NULL, CURRENT_TIMESTAMP);
        RETURN OLD;
        
    ELSIF (TG_OP = 'INSERT') THEN
        -- Aquí se removió la referencia a NEW.ejemplares_totales que hacía explotar la BD
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('titulo', NEW.titulo, 'id_tipo_recurso', NEW.id_tipo_recurso), CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;



--
-- Name: fn_auditoria_usuarios(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_auditoria_usuarios() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_usuario_actual INT := NULLIF(current_setting('app.usuario_actual', true), '')::INT;
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('usuarios', OLD.id, 'DELETE', v_usuario_actual, jsonb_build_object('nombre', OLD.nombre_completo, 'email', OLD.email, 'id_rol', OLD.id_rol), NULL, CURRENT_TIMESTAMP);
        RETURN OLD;
        
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('usuarios', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('nombre', NEW.nombre_completo, 'email', NEW.email, 'id_rol', NEW.id_rol), CURRENT_TIMESTAMP);
        RETURN NEW;
        
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('usuarios', OLD.id, 'UPDATE', v_usuario_actual, 
                jsonb_build_object('nombre', OLD.nombre_completo, 'id_rol', OLD.id_rol, 'activo', OLD.activo), 
                jsonb_build_object('nombre', NEW.nombre_completo, 'id_rol', NEW.id_rol, 'activo', NEW.activo), 
                CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;



--
-- Name: insertarproyectoaleatorio(timestamp without time zone); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone)
    LANGUAGE plpgsql
    AS $$
DECLARE
    nuevo_id INT;
    nivel_academico_aleatorio nivel_academico_enum;
    carrera_aleatoria INT;
    titulo_base VARCHAR(255);
    palabras_clave_gen TEXT;
    resumen_texto TEXT;
    mes_actual INT;
    
    -- Arrays para simular el ELT de MySQL
    arr_niveles1 VARCHAR[] := ARRAY['TSU', 'Pregrado', 'Especializacion', 'Maestria'];
    arr_niveles2 VARCHAR[] := ARRAY['Especializacion', 'Maestria', 'Doctorado'];
    arr_niveles3 VARCHAR[] := ARRAY['TSU', 'Pregrado', 'Especializacion', 'Maestria', 'Doctorado'];
    
    arr_tit_acc VARCHAR[] := ARRAY['Sistema', 'Aplicación', 'Plataforma', 'Prototipo', 'Análisis', 'Diseño', 'Implementación', 'Optimización', 'Automatización', 'Evaluación', 'Desarrollo', 'Modelo'];
    arr_tit_obj VARCHAR[] := ARRAY['Gestión', 'Monitoreo', 'Control', 'Predicción', 'Seguridad', 'Reconocimiento', 'Clasificación', 'Procesamiento', 'Visualización', 'Comunicación', 'Bajo Costo', 'Alto Rendimiento'];
    arr_tit_dest VARCHAR[] := ARRAY['la Comunidad', 'UPTTMBI', 'el Sector Agroalimentario', 'Zonas Rurales', 'Instituciones Educativas', 'Pequeñas Empresas', 'el Área de Salud', 'el Transporte Público', 'la Industria 4.0', 'la Transformación Digital'];
    
    arr_carreras VARCHAR[] := ARRAY['Informática', 'Electricidad', 'Administración', 'Agroalimentación', 'Construcción Civil'];
BEGIN
    mes_actual := EXTRACT(MONTH FROM fecha_creada);

    -- Asignar nivel académico
    IF mes_actual IN (4,8) THEN
        nivel_academico_aleatorio := arr_niveles1[floor(random() * 4 + 1)::int]::nivel_academico_enum;
    ELSIF mes_actual = 12 THEN
        nivel_academico_aleatorio := arr_niveles2[floor(random() * 3 + 1)::int]::nivel_academico_enum;
    ELSE
        nivel_academico_aleatorio := arr_niveles3[floor(random() * 5 + 1)::int]::nivel_academico_enum;
    END IF;

    -- Ajustar pesos específicos
    IF random() < 0.4 THEN 
        nivel_academico_aleatorio := 'Pregrado';
    ELSIF random() < 0.25 THEN 
        nivel_academico_aleatorio := 'TSU';
    END IF;

    -- Carrera aleatoria
    carrera_aleatoria := floor(random() * 5 + 1)::int;

    -- Título dinámico
    titulo_base := arr_tit_acc[floor(random() * 12 + 1)::int] || ' de ' || 
                   arr_tit_obj[floor(random() * 12 + 1)::int] || ' para ' || 
                   arr_tit_dest[floor(random() * 10 + 1)::int];

    -- Resumen
    resumen_texto := 'Proyecto desarrollado en ' || arr_carreras[carrera_aleatoria] || '. Aborda problemáticas reales con enfoque práctico.';

    -- Insertar recurso y capturar el ID generado (RETURNING id)
    INSERT INTO recursos (titulo, id_tipo_recurso, anio_publicacion, ejemplares_totales, ejemplares_disponibles)
    VALUES (titulo_base, 1, EXTRACT(YEAR FROM fecha_creada), 1, 1)
    RETURNING id INTO nuevo_id;

    -- Insertar detalle proyecto
    INSERT INTO detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, resumen, id_carrera, comunidad_beneficiada, palabras_clave, created_at) 
    VALUES (nuevo_id, (fecha_creada + (floor(random() * 90)::int || ' days')::interval)::date, nivel_academico_aleatorio, resumen_texto, carrera_aleatoria, 'Comunidad Generica', 'tecnologia, innovacion', fecha_creada);

    -- Relacionar con un autor aleatorio o el autor por defecto 1
    INSERT INTO recurso_autores (id_recurso, id_autor)
    SELECT nuevo_id, id FROM autores WHERE id BETWEEN 14 AND 29 ORDER BY random() LIMIT 1;
    
    -- Si no insertó (por no hallar autor en ese rango), fuerza el autor 1
    IF NOT FOUND THEN
        INSERT INTO recurso_autores (id_recurso, id_autor) VALUES (nuevo_id, 1);
    END IF;
END;
$$;



SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: accesos_recursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.accesos_recursos (
    id integer NOT NULL,
    id_registro_actividad integer NOT NULL,
    id_recurso integer NOT NULL,
    accion public.accion_acceso_enum DEFAULT 'visualizacion'::public.accion_acceso_enum,
    fecha_acceso timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.accesos_recursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.accesos_recursos_id_seq OWNED BY public.accesos_recursos.id;


--
-- Name: auditoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auditoria (
    id integer NOT NULL,
    tabla_afectada character varying(50) NOT NULL,
    id_registro integer NOT NULL,
    accion public.accion_auditoria_enum NOT NULL,
    usuario_responsable integer,
    ip_origen character varying(45),
    datos_anteriores jsonb,
    datos_nuevos jsonb,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: auditoria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.auditoria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.auditoria_id_seq OWNED BY public.auditoria.id;


--
-- Name: autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.autores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20) DEFAULT NULL::character varying
);



--
-- Name: autores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.autores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: autores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.autores_id_seq OWNED BY public.autores.id;


--
-- Name: carreras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carreras (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);



--
-- Name: carreras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carreras_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: carreras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carreras_id_seq OWNED BY public.carreras.id;


--
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);



--
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- Name: cursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cursos (
    id integer NOT NULL,
    id_docente integer NOT NULL,
    titulo character varying(255) NOT NULL,
    descripcion text,
    imagen_portada text,
    estado public.estado_curso_enum DEFAULT 'borrador'::public.estado_curso_enum NOT NULL,
    nota_minima_aprobacion numeric(5,2) DEFAULT 70.00 NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    slug character varying(255),
    url_moodle text,
    modalidad character varying(50) DEFAULT 'Virtual'::character varying,
    nivel character varying(50) DEFAULT 'B sico'::character varying,
    duracion character varying(80),
    cupo_maximo integer,
    fecha_inicio date,
    fecha_fin date,
    url_video_preview text,
    estado_inscripcion character varying(50) DEFAULT 'Abierta'::character varying
);



--
-- Name: cursos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: cursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cursos_id_seq OWNED BY public.cursos.id;


--
-- Name: detalles_articulos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalles_articulos (
    id_recurso integer CONSTRAINT detalles_revistas_id_recurso_not_null NOT NULL,
    id_editorial integer,
    volumen character varying(50),
    numero character varying(50),
    issn character varying(20),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    imagen_portada text DEFAULT 'default_article.jpg'::character varying,
    resumen text,
    activo boolean DEFAULT true,
    id_categoria integer
);



--
-- Name: detalles_investigaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalles_investigaciones (
    id_recurso integer NOT NULL,
    planteamiento_problema text NOT NULL,
    objetivo_general text NOT NULL,
    id_investigacion_ofertada integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: dimensiones_operativas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dimensiones_operativas (
    id integer NOT NULL,
    id_linea integer NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true
);



--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dimensiones_operativas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNED BY public.dimensiones_operativas.id;


--
-- Name: editoriales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.editoriales (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);



--
-- Name: editoriales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.editoriales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: editoriales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.editoriales_id_seq OWNED BY public.editoriales.id;


--
-- Name: etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.etiquetas (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    color_hex character varying(7) DEFAULT '#0ea5e9'::character varying
);



--
-- Name: etiquetas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.etiquetas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: etiquetas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.etiquetas_id_seq OWNED BY public.etiquetas.id;


--
-- Name: historico_versiones_pst; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.historico_versiones_pst (
    id integer NOT NULL,
    id_recurso integer NOT NULL,
    archivo_pdf character varying(500) NOT NULL,
    usuario_id integer,
    motivo character varying(255) DEFAULT 'Actualizaci¢n'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.historico_versiones_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNED BY public.historico_versiones_pst.id;


--
-- Name: investigaciones_ofertadas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.investigaciones_ofertadas (
    id integer NOT NULL,
    id_profesor integer NOT NULL,
    titulo character varying(255) NOT NULL,
    planteamiento_problema text NOT NULL,
    objetivo_general text NOT NULL,
    id_linea integer NOT NULL,
    id_dimension integer,
    cupos_disponibles integer DEFAULT 3,
    estado character varying(20) DEFAULT 'Abierta'::character varying,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_propuesta_empresa integer,
    CONSTRAINT investigaciones_ofertadas_estado_check CHECK (((estado)::text = ANY (ARRAY[('Abierta'::character varying)::text, ('Cerrada'::character varying)::text, ('En Desarrollo'::character varying)::text, ('Finalizada'::character varying)::text])))
);



--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.investigaciones_ofertadas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNED BY public.investigaciones_ofertadas.id;


--
-- Name: investigadores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.investigadores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    orcid character varying(50) NOT NULL,
    biografia text,
    pagina_web character varying(255)
);



--
-- Name: investigadores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.investigadores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: investigadores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.investigadores_id_seq OWNED BY public.investigadores.id;


--
-- Name: lineas_investigacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lineas_investigacion (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    id_carrera integer NOT NULL,
    descripcion text,
    activo boolean DEFAULT true
);



--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lineas_investigacion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lineas_investigacion_id_seq OWNED BY public.lineas_investigacion.id;


--
-- Name: matriz_rbac; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matriz_rbac (
    nivel_privilegio integer NOT NULL,
    modulo character varying(100) NOT NULL,
    permisos jsonb
);



--
-- Name: niveles_academicos; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.niveles_academicos (
    id integer NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    requiere_trayecto boolean DEFAULT false,
    orden integer DEFAULT 0,
    activo boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.niveles_academicos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.niveles_academicos_id_seq OWNED BY public.niveles_academicos.id;


--
-- Name: notificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notificaciones (
    id integer NOT NULL,
    id_usuario integer,
    titulo character varying(255),
    mensaje text,
    leido boolean DEFAULT false,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: notificaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.notificaciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: notificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificaciones_id_seq OWNED BY public.notificaciones.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    id integer NOT NULL,
    email character varying(100) NOT NULL,
    token_hash character varying(255) NOT NULL,
    expiracion timestamp without time zone NOT NULL,
    utilizado boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: password_resets_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.password_resets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: password_resets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.password_resets_id_seq OWNED BY public.password_resets.id;


--
-- Name: postulaciones_estudiantes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.postulaciones_estudiantes (
    id integer NOT NULL,
    id_investigacion integer NOT NULL,
    id_estudiante integer NOT NULL,
    mensaje_motivacion text,
    estado character varying(20) DEFAULT 'Pendiente'::character varying,
    fecha_postulacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta timestamp without time zone,
    equipo_extra json,
    CONSTRAINT postulaciones_estudiantes_estado_check CHECK (((estado)::text = ANY (ARRAY[('Pendiente'::character varying)::text, ('Aceptado'::character varying)::text, ('Rechazado'::character varying)::text])))
);



--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.postulaciones_estudiantes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNED BY public.postulaciones_estudiantes.id;


--
-- Name: preferencias_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.preferencias_usuario (
    id_usuario integer NOT NULL,
    tema character varying(50) DEFAULT 'light'::character varying,
    notificaciones_sistema boolean DEFAULT true
);



--
-- Name: privilegios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.privilegios (
    privilegio_id integer NOT NULL,
    nivel_privilegio integer DEFAULT 0 NOT NULL
);



--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.privilegios_privilegio_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNED BY public.privilegios.privilegio_id;


--
-- Name: propuestas_empresa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.propuestas_empresa (
    id integer NOT NULL,
    nombre_empresa character varying(255) NOT NULL,
    rif_empresa character varying(50),
    persona_contacto character varying(150) NOT NULL,
    telefono_contacto character varying(50) NOT NULL,
    correo_contacto character varying(150) NOT NULL,
    area_afectada character varying(100) NOT NULL,
    descripcion_problema text NOT NULL,
    estado public.estado_propuesta_enum DEFAULT 'pendiente'::public.estado_propuesta_enum,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    nivel_trayecto character varying(50),
    codigo_seguimiento character varying(20),
    motivo_rechazo text
);



--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.propuestas_empresa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.propuestas_empresa_id_seq OWNED BY public.propuestas_empresa.id;


--
-- Name: proyecto_carreras_vinculadas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.proyecto_carreras_vinculadas (
    id_recurso integer NOT NULL,
    id_carrera integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: TABLE proyecto_carreras_vinculadas; Type: COMMENT; Schema: public; Owner: miki
--

COMMENT ON TABLE public.proyecto_carreras_vinculadas IS 'Relación N:M para vincular proyectos PST con una o varias carreras secundarias adicionales a su carrera base.';


--
-- Name: proyecto_tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proyecto_tutores (
    id_recurso integer NOT NULL,
    id_tutor integer NOT NULL,
    tipo_tutor_id integer
);



--
-- Name: recurso_autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_autores (
    id_recurso integer NOT NULL,
    id_autor integer NOT NULL
);



--
-- Name: recurso_categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL
);



--
-- Name: recurso_clasificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_clasificaciones (
    id_recurso integer NOT NULL,
    id_linea_investigacion integer NOT NULL,
    id_dimension_operativa integer
);



--
-- Name: recurso_etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_etiquetas (
    id_recurso integer NOT NULL,
    id_etiqueta integer NOT NULL
);



--
-- Name: recurso_investigadores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_investigadores (
    id_recurso integer NOT NULL,
    id_investigador integer NOT NULL
);



--
-- Name: recursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recursos (
    id integer NOT NULL,
    titulo character varying(255) NOT NULL,
    id_tipo_recurso integer NOT NULL,
    anio_publicacion integer,
    archivo_pdf character varying(255)
);



--
-- Name: recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.recursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.recursos_id_seq OWNED BY public.recursos.id;


--
-- Name: registro_actividad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registro_actividad (
    id integer NOT NULL,
    id_usuario integer,
    id_visitante integer,
    fecha_inicial timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    ultima_actividad timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    conteo_accesos integer DEFAULT 1
);



--
-- Name: registro_actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registro_actividad_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: registro_actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registro_actividad_id_seq OWNED BY public.registro_actividad.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    privilegio_id integer DEFAULT 1 CONSTRAINT roles_privilegios_id_not_null NOT NULL
);



--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: system_audit_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.system_audit_log (
    id character varying(50) NOT NULL,
    fecha_hora timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    nivel character varying(20),
    modulo character varying(100),
    accion character varying(100),
    detalles text,
    responsable character varying(150),
    ip character varying(45),
    hash_anterior character varying(64),
    hash_integridad character varying(64)
);



--
-- Name: telemetria_cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.telemetria_cache (
    id integer DEFAULT 1 NOT NULL,
    datos jsonb
);



--
-- Name: tipo_recurso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_recurso (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);



--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_recurso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_recurso_id_seq OWNED BY public.tipo_recurso.id;


--
-- Name: tipo_tutor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_tutor (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);



--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_tutor_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_tutor_id_seq OWNED BY public.tipo_tutor.id;


--
-- Name: trayectos; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.trayectos (
    id integer NOT NULL,
    id_carrera integer NOT NULL,
    nombre character varying(50) NOT NULL,
    numero integer NOT NULL,
    descripcion text,
    activo boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: trayectos_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.trayectos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: trayectos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.trayectos_id_seq OWNED BY public.trayectos.id;


--
-- Name: tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tutores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);



--
-- Name: tutores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tutores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: tutores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tutores_id_seq OWNED BY public.tutores.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    email character varying(100),
    cedula character varying(20),
    contrasena character varying(255),
    id_rol integer,
    activo boolean DEFAULT true,
    reset_token character varying(255) DEFAULT NULL::character varying,
    reset_expires timestamp without time zone,
    telefono character varying(50) DEFAULT NULL::character varying,
    email_verified boolean DEFAULT false,
    activation_token character varying(255) DEFAULT NULL::character varying
);



--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: visitantes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.visitantes (
    id integer NOT NULL,
    ip_address character varying(45) NOT NULL,
    user_agent text,
    pagina_origen character varying(255)
);



--
-- Name: visitantes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.visitantes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- Name: visitantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.visitantes_id_seq OWNED BY public.visitantes.id;


--
-- Name: waf_rate_limiter; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.waf_rate_limiter (
    ip character varying(45) NOT NULL,
    tipo character varying(20) NOT NULL,
    intentos integer DEFAULT 0,
    primer_intento timestamp without time zone,
    ultimo_intento timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    bloqueado_hasta timestamp without time zone,
    razon text,
    datos_adicionales jsonb,
    creado_el timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);



--
-- Name: accesos_recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos ALTER COLUMN id SET DEFAULT nextval('public.accesos_recursos_id_seq'::regclass);


--
-- Name: auditoria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria ALTER COLUMN id SET DEFAULT nextval('public.auditoria_id_seq'::regclass);


--
-- Name: autores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores ALTER COLUMN id SET DEFAULT nextval('public.autores_id_seq'::regclass);


--
-- Name: carreras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras ALTER COLUMN id SET DEFAULT nextval('public.carreras_id_seq'::regclass);


--
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- Name: cursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos ALTER COLUMN id SET DEFAULT nextval('public.cursos_id_seq'::regclass);


--
-- Name: dimensiones_operativas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas ALTER COLUMN id SET DEFAULT nextval('public.dimensiones_operativas_id_seq'::regclass);


--
-- Name: editoriales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales ALTER COLUMN id SET DEFAULT nextval('public.editoriales_id_seq'::regclass);


--
-- Name: etiquetas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas ALTER COLUMN id SET DEFAULT nextval('public.etiquetas_id_seq'::regclass);


--
-- Name: historico_versiones_pst id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst ALTER COLUMN id SET DEFAULT nextval('public.historico_versiones_pst_id_seq'::regclass);


--
-- Name: investigaciones_ofertadas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas ALTER COLUMN id SET DEFAULT nextval('public.investigaciones_ofertadas_id_seq'::regclass);


--
-- Name: investigadores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigadores ALTER COLUMN id SET DEFAULT nextval('public.investigadores_id_seq'::regclass);


--
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


--
-- Name: niveles_academicos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos ALTER COLUMN id SET DEFAULT nextval('public.niveles_academicos_id_seq'::regclass);


--
-- Name: notificaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_id_seq'::regclass);


--
-- Name: password_resets id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets ALTER COLUMN id SET DEFAULT nextval('public.password_resets_id_seq'::regclass);


--
-- Name: postulaciones_estudiantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes ALTER COLUMN id SET DEFAULT nextval('public.postulaciones_estudiantes_id_seq'::regclass);


--
-- Name: privilegios privilegio_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios ALTER COLUMN privilegio_id SET DEFAULT nextval('public.privilegios_privilegio_id_seq'::regclass);


--
-- Name: propuestas_empresa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa ALTER COLUMN id SET DEFAULT nextval('public.propuestas_empresa_id_seq'::regclass);


--
-- Name: recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos ALTER COLUMN id SET DEFAULT nextval('public.recursos_id_seq'::regclass);


--
-- Name: registro_actividad id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad ALTER COLUMN id SET DEFAULT nextval('public.registro_actividad_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: tipo_recurso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso ALTER COLUMN id SET DEFAULT nextval('public.tipo_recurso_id_seq'::regclass);


--
-- Name: tipo_tutor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor ALTER COLUMN id SET DEFAULT nextval('public.tipo_tutor_id_seq'::regclass);


--
-- Name: trayectos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos ALTER COLUMN id SET DEFAULT nextval('public.trayectos_id_seq'::regclass);


--
-- Name: tutores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores ALTER COLUMN id SET DEFAULT nextval('public.tutores_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Name: visitantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes ALTER COLUMN id SET DEFAULT nextval('public.visitantes_id_seq'::regclass);


--
-- Data for Name: accesos_recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: auditoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.auditoria VALUES (1, 'usuarios', 1, 'INSERT', NULL, NULL, NULL, '{"email": "andru@gmail.com", "id_rol": 1, "nombre": "Adrus"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (2, 'usuarios', 2, 'INSERT', NULL, NULL, NULL, '{"email": "lando@gmail.com", "id_rol": 2, "nombre": "lando"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (3, 'usuarios', 3, 'INSERT', NULL, NULL, NULL, '{"email": "miki@gmail.com", "id_rol": 3, "nombre": "miki"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (4, 'usuarios', 4, 'INSERT', NULL, NULL, NULL, '{"email": "ale@yaju.com", "id_rol": 3, "nombre": "ale"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (5, 'recursos', 1, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema de Reconocimiento Biométrico Facial para Comedor Universitario", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (6, 'recursos', 2, 'INSERT', NULL, NULL, NULL, '{"titulo": "Prototipo de Cerradura Digital con Matriz de Teclado y Arduino", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (7, 'recursos', 3, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos", "id_tipo_recurso": 2, "ejemplares_totales": 1}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (8, 'usuarios', 4, 'UPDATE', 1, NULL, '{"activo": 1, "id_rol": 3, "nombre": "ale"}', '{"activo": 1, "id_rol": 1, "nombre": "ale"}', '2026-03-23 16:14:24');
INSERT INTO public.auditoria VALUES (9, 'recursos', 4, 'INSERT', NULL, NULL, NULL, '{"titulo": "Impacto del Cambio Climático en Trujillo - Parte 8", "id_tipo_recurso": 2, "ejemplares_totales": 1}', '2026-03-23 16:56:13');
INSERT INTO public.auditoria VALUES (10, 'recursos', 5, 'INSERT', NULL, NULL, NULL, '{"titulo": "Simulación de Cargas Estáticas en Puentes - Parte 7", "id_tipo_recurso": 2, "ejemplares_totales": 1}', '2026-03-23 16:57:08');
INSERT INTO public.auditoria VALUES (11, 'recursos', 6, 'INSERT', NULL, NULL, NULL, '{"titulo": "Big Data en Finanzas Institucionales - Parte 9", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 16:58:11');
INSERT INTO public.auditoria VALUES (12, 'recursos', 7, 'INSERT', NULL, NULL, NULL, '{"titulo": "Optimización de CPU en Servidores Locales - Parte 7", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 16:58:11');
INSERT INTO public.auditoria VALUES (13, 'recursos', 8, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistemas de Riego Automatizado - Parte 5", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 16:58:11');
INSERT INTO public.auditoria VALUES (14, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "piña"}', '2026-06-18 18:46:17.662484');
INSERT INTO public.auditoria VALUES (15, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "piña"}', '2026-06-18 19:05:42.587427');
INSERT INTO public.auditoria VALUES (16, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "piña"}', '2026-06-18 19:54:46.993547');
INSERT INTO public.auditoria VALUES (17, 'usuarios', 7, 'INSERT', NULL, NULL, NULL, '{"email": "erwazaaaa@gmail.com", "id_rol": 3, "nombre": "Migel González"}', '2026-06-18 20:58:57.768568');
INSERT INTO public.auditoria VALUES (18, 'usuarios', 8, 'INSERT', NULL, NULL, NULL, '{"email": "yisu@gmail.com", "id_rol": 3, "nombre": "Yisu Monte"}', '2026-06-18 20:59:49.682537');
INSERT INTO public.auditoria VALUES (19, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Migel González"}', '{"activo": true, "id_rol": 1, "nombre": "Migel González"}', '2026-06-18 21:38:28.663879');
INSERT INTO public.auditoria VALUES (20, 'usuarios', 9, 'INSERT', NULL, NULL, NULL, '{"email": "iaiaia@gmail.com", "id_rol": 3, "nombre": "Pedro Perez"}', '2026-06-24 23:07:53.05286');
INSERT INTO public.auditoria VALUES (21, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "Piñin"}', '2026-06-24 23:57:15.201582');
INSERT INTO public.auditoria VALUES (22, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-24 23:57:20.104368');
INSERT INTO public.auditoria VALUES (23, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 2, "nombre": "Piñin"}', '2026-06-24 23:57:31.726744');
INSERT INTO public.auditoria VALUES (24, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-24 23:57:37.330082');
INSERT INTO public.auditoria VALUES (25, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-25 00:01:40.353031');
INSERT INTO public.auditoria VALUES (26, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-25 00:01:46.873155');
INSERT INTO public.auditoria VALUES (27, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:01:55.730238');
INSERT INTO public.auditoria VALUES (28, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:23:15.783421');
INSERT INTO public.auditoria VALUES (29, 'usuarios', 10, 'INSERT', NULL, NULL, NULL, '{"email": "wazaaa@gmail.com", "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-25 00:33:07.592137');
INSERT INTO public.auditoria VALUES (30, 'usuarios', 11, 'INSERT', NULL, NULL, NULL, '{"email": "123@gmail.com", "id_rol": 3, "nombre": "Juan"}', '2026-06-25 00:33:28.49575');
INSERT INTO public.auditoria VALUES (31, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:34:43.439159');
INSERT INTO public.auditoria VALUES (32, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:34:45.543113');
INSERT INTO public.auditoria VALUES (33, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": false, "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-25 00:34:51.608311');
INSERT INTO public.auditoria VALUES (34, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-25 00:35:18.586051');
INSERT INTO public.auditoria VALUES (35, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:57:51.974985');
INSERT INTO public.auditoria VALUES (36, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:57:57.479012');
INSERT INTO public.auditoria VALUES (37, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:58:00.692516');
INSERT INTO public.auditoria VALUES (38, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:58:05.014824');
INSERT INTO public.auditoria VALUES (39, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '2026-06-25 00:58:32.420893');
INSERT INTO public.auditoria VALUES (40, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Migel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-06-25 01:22:04.451131');
INSERT INTO public.auditoria VALUES (41, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-29 11:45:47.198869');
INSERT INTO public.auditoria VALUES (238, 'recursos', 131, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-02 19:58:49.193407');
INSERT INTO public.auditoria VALUES (42, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-29 12:10:37.626132');
INSERT INTO public.auditoria VALUES (43, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '2026-06-29 14:18:17.58523');
INSERT INTO public.auditoria VALUES (44, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}', '2026-06-29 14:18:34.834966');
INSERT INTO public.auditoria VALUES (45, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}', '2026-06-29 14:19:05.388306');
INSERT INTO public.auditoria VALUES (46, 'recursos', 21, 'INSERT', NULL, NULL, NULL, '{"titulo": "Betty yo a usted la amo", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-04 23:19:52.515406');
INSERT INTO public.auditoria VALUES (47, 'recursos', 22, 'INSERT', NULL, NULL, NULL, '{"titulo": "Don Pepe el de los Globos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 11:43:06.035947');
INSERT INTO public.auditoria VALUES (48, 'recursos', 23, 'INSERT', NULL, NULL, NULL, '{"titulo": "La Gran Verge", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 11:47:05.718779');
INSERT INTO public.auditoria VALUES (49, 'recursos', 24, 'INSERT', NULL, NULL, NULL, '{"titulo": "Pepe", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:02:34.181399');
INSERT INTO public.auditoria VALUES (50, 'recursos', 25, 'INSERT', NULL, NULL, NULL, '{"titulo": "Luisito comunicando", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:18:57.684684');
INSERT INTO public.auditoria VALUES (51, 'recursos', 26, 'INSERT', NULL, NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:24:53.394994');
INSERT INTO public.auditoria VALUES (52, 'recursos', 27, 'INSERT', NULL, NULL, NULL, '{"titulo": "Que la guagua", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:29:10.583616');
INSERT INTO public.auditoria VALUES (53, 'recursos', 28, 'INSERT', NULL, NULL, NULL, '{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:38:15.349753');
INSERT INTO public.auditoria VALUES (54, 'recursos', 22, 'DELETE', NULL, NULL, '{"titulo": "Don Pepe el de los Globos", "id_tipo_recurso": 3}', NULL, '2026-07-05 12:43:01.095251');
INSERT INTO public.auditoria VALUES (55, 'recursos', 26, 'DELETE', NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3}', NULL, '2026-07-05 12:43:29.378629');
INSERT INTO public.auditoria VALUES (58, 'recursos', 31, 'INSERT', NULL, NULL, NULL, '{"titulo": "Imitadora", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:57:22.541344');
INSERT INTO public.auditoria VALUES (59, 'recursos', 23, 'DELETE', NULL, NULL, '{"titulo": "La Gran Verge", "id_tipo_recurso": 3}', NULL, '2026-07-05 13:31:21.954982');
INSERT INTO public.auditoria VALUES (60, 'recursos', 32, 'INSERT', NULL, NULL, NULL, '{"titulo": "Manguagua 2", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 14:44:32.452702');
INSERT INTO public.auditoria VALUES (61, 'recursos', 33, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:04:26.857648');
INSERT INTO public.auditoria VALUES (62, 'recursos', 33, 'DELETE', NULL, NULL, '{"titulo": "Waos", "id_tipo_recurso": 3}', NULL, '2026-07-05 15:05:03.276405');
INSERT INTO public.auditoria VALUES (63, 'recursos', 34, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos 1", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:24:54.658482');
INSERT INTO public.auditoria VALUES (64, 'recursos', 35, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos 2", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:25:19.934157');
INSERT INTO public.auditoria VALUES (65, 'recursos', 36, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos 3", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:25:52.428559');
INSERT INTO public.auditoria VALUES (66, 'recursos', 37, 'INSERT', NULL, NULL, NULL, '{"titulo": "23123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:06.835471');
INSERT INTO public.auditoria VALUES (67, 'recursos', 38, 'INSERT', NULL, NULL, NULL, '{"titulo": "23", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:22.136069');
INSERT INTO public.auditoria VALUES (68, 'recursos', 39, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:32.188843');
INSERT INTO public.auditoria VALUES (69, 'recursos', 40, 'INSERT', NULL, NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:43.872553');
INSERT INTO public.auditoria VALUES (70, 'recursos', 41, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:28:49.036024');
INSERT INTO public.auditoria VALUES (71, 'recursos', 42, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:28:59.682383');
INSERT INTO public.auditoria VALUES (72, 'recursos', 43, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:29:13.986916');
INSERT INTO public.auditoria VALUES (73, 'recursos', 44, 'INSERT', NULL, NULL, NULL, '{"titulo": "auuuu", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 16:15:08.182516');
INSERT INTO public.auditoria VALUES (74, 'recursos', 45, 'INSERT', NULL, NULL, NULL, '{"titulo": "Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri", "id_tipo_recurso": 1, "ejemplares_totales": 2}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (75, 'recursos', 46, 'INSERT', NULL, NULL, NULL, '{"titulo": "Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (76, 'recursos', 47, 'INSERT', NULL, NULL, NULL, '{"titulo": "Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478", "id_tipo_recurso": 1, "ejemplares_totales": 3}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (77, 'recursos', 48, 'INSERT', NULL, NULL, NULL, '{"titulo": "Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (78, 'recursos', 49, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema de Informaci¢n Automatizado para la Gesti¢n de Inventario y Suministros M‚dicos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (79, 'recursos', 50, 'INSERT', NULL, NULL, NULL, '{"titulo": "Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (80, 'recursos', 51, 'INSERT', NULL, NULL, NULL, '{"titulo": "Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (81, 'recursos', 52, 'INSERT', NULL, NULL, NULL, '{"titulo": "Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (85, 'recursos', 56, 'INSERT', NULL, NULL, NULL, '{"titulo": "hola", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 18:14:56.263164');
INSERT INTO public.auditoria VALUES (86, 'recursos', 57, 'INSERT', NULL, NULL, NULL, '{"titulo": "hola adios", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 18:21:33.639701');
INSERT INTO public.auditoria VALUES (87, 'recursos', 56, 'DELETE', NULL, NULL, '{"titulo": "hola", "id_tipo_recurso": 1}', NULL, '2026-07-05 18:29:50.693972');
INSERT INTO public.auditoria VALUES (185, 'recursos', 105, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378657", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:37.294398');
INSERT INTO public.auditoria VALUES (120, 'recursos', 58, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-07 00:01:54.74783');
INSERT INTO public.auditoria VALUES (234, 'recursos', 128, 'INSERT', NULL, NULL, NULL, '{"titulo": "Materia: Seguridad Informática", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-02 17:22:38.760639');
INSERT INTO public.auditoria VALUES (121, 'recursos', 59, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:22:58.539505');
INSERT INTO public.auditoria VALUES (122, 'recursos', 60, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:12.838831');
INSERT INTO public.auditoria VALUES (123, 'recursos', 61, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:12.876413');
INSERT INTO public.auditoria VALUES (124, 'recursos', 62, 'INSERT', NULL, NULL, NULL, '{"titulo": "", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:12.883112');
INSERT INTO public.auditoria VALUES (125, 'recursos', 60, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:12.899565');
INSERT INTO public.auditoria VALUES (126, 'recursos', 61, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:12.919034');
INSERT INTO public.auditoria VALUES (127, 'recursos', 63, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:34.622457');
INSERT INTO public.auditoria VALUES (128, 'recursos', 64, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:34.655711');
INSERT INTO public.auditoria VALUES (129, 'recursos', 63, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:34.679754');
INSERT INTO public.auditoria VALUES (130, 'recursos', 64, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:34.704588');
INSERT INTO public.auditoria VALUES (131, 'recursos', 65, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:59.650776');
INSERT INTO public.auditoria VALUES (132, 'recursos', 66, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:59.678997');
INSERT INTO public.auditoria VALUES (133, 'recursos', 65, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:59.693745');
INSERT INTO public.auditoria VALUES (134, 'recursos', 66, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:59.706894');
INSERT INTO public.auditoria VALUES (135, 'recursos', 67, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:49:20.583041');
INSERT INTO public.auditoria VALUES (136, 'recursos', 68, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:49:20.633257');
INSERT INTO public.auditoria VALUES (137, 'recursos', 67, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:49:20.648244');
INSERT INTO public.auditoria VALUES (138, 'recursos', 68, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:49:20.66095');
INSERT INTO public.auditoria VALUES (139, 'recursos', 69, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:58:54.904249');
INSERT INTO public.auditoria VALUES (140, 'recursos', 62, 'DELETE', NULL, NULL, '{"titulo": "", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:59:05.308634');
INSERT INTO public.auditoria VALUES (141, 'recursos', 70, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:04:38.338495');
INSERT INTO public.auditoria VALUES (142, 'recursos', 71, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:04:38.361973');
INSERT INTO public.auditoria VALUES (143, 'recursos', 70, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:04:38.374265');
INSERT INTO public.auditoria VALUES (144, 'recursos', 71, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:04:38.384732');
INSERT INTO public.auditoria VALUES (145, 'recursos', 72, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:11:11.510708');
INSERT INTO public.auditoria VALUES (146, 'recursos', 73, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:18:06.569838');
INSERT INTO public.auditoria VALUES (147, 'recursos', 73, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:18:06.593982');
INSERT INTO public.auditoria VALUES (148, 'recursos', 74, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:21:29.064851');
INSERT INTO public.auditoria VALUES (149, 'recursos', 74, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:21:29.089783');
INSERT INTO public.auditoria VALUES (150, 'recursos', 75, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:29:50.185276');
INSERT INTO public.auditoria VALUES (151, 'recursos', 75, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:29:50.218869');
INSERT INTO public.auditoria VALUES (152, 'recursos', 76, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:45:40.223902');
INSERT INTO public.auditoria VALUES (153, 'recursos', 76, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:45:40.245315');
INSERT INTO public.auditoria VALUES (154, 'recursos', 77, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:57:10.465552');
INSERT INTO public.auditoria VALUES (155, 'recursos', 77, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:57:10.485941');
INSERT INTO public.auditoria VALUES (156, 'recursos', 78, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Carga por Lotes - 20260805134326", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:43:26.945146');
INSERT INTO public.auditoria VALUES (157, 'recursos', 79, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:45:15.201621');
INSERT INTO public.auditoria VALUES (158, 'recursos', 80, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.633265');
INSERT INTO public.auditoria VALUES (159, 'recursos', 81, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.72936');
INSERT INTO public.auditoria VALUES (160, 'recursos', 82, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.817964');
INSERT INTO public.auditoria VALUES (161, 'recursos', 83, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.91852');
INSERT INTO public.auditoria VALUES (162, 'recursos', 84, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:06.00888');
INSERT INTO public.auditoria VALUES (163, 'recursos', 85, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805135642", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:56:42.188313');
INSERT INTO public.auditoria VALUES (164, 'recursos', 86, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805140204", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 10:02:04.774776');
INSERT INTO public.auditoria VALUES (165, 'recursos', 87, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805143446", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 10:34:46.219889');
INSERT INTO public.auditoria VALUES (166, 'recursos', 88, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:26:58.264555');
INSERT INTO public.auditoria VALUES (167, 'recursos', 89, 'INSERT', NULL, NULL, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:35:39.007693');
INSERT INTO public.auditoria VALUES (168, 'recursos', 90, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:45:42.226083');
INSERT INTO public.auditoria VALUES (169, 'recursos', 91, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:52:47.26864');
INSERT INTO public.auditoria VALUES (170, 'recursos', 92, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 11:34:04.575523');
INSERT INTO public.auditoria VALUES (171, 'recursos', 93, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 11:34:42.145006');
INSERT INTO public.auditoria VALUES (172, 'recursos', 94, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 11:40:58.141559');
INSERT INTO public.auditoria VALUES (175, 'recursos', 97, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:03:37.275866');
INSERT INTO public.auditoria VALUES (176, 'recursos', 98, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comitésadsds Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:04:15.392022');
INSERT INTO public.auditoria VALUES (177, 'recursos', 98, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comitésadsds Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:10:31.909346');
INSERT INTO public.auditoria VALUES (178, 'recursos', 99, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:10:58.390178');
INSERT INTO public.auditoria VALUES (179, 'recursos', 100, 'INSERT', NULL, NULL, NULL, '{"titulo": "il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:14:42.285533');
INSERT INTO public.auditoria VALUES (180, 'recursos', 101, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378571", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:16:11.271762');
INSERT INTO public.auditoria VALUES (181, 'recursos', 102, 'INSERT', NULL, NULL, NULL, '{"titulo": "TEST PDO RETURNING TITLE 1786378596", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:16:36.642725');
INSERT INTO public.auditoria VALUES (182, 'recursos', 103, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378621", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:01.653978');
INSERT INTO public.auditoria VALUES (183, 'recursos', 104, 'INSERT', NULL, NULL, NULL, '{"titulo": "DEBUG TITLE 1786378652", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:32.045482');
INSERT INTO public.auditoria VALUES (184, 'recursos', 104, 'DELETE', NULL, NULL, '{"titulo": "DEBUG TITLE 1786378652", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:17:32.05166');
INSERT INTO public.auditoria VALUES (186, 'recursos', 106, 'INSERT', NULL, NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378674", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:54.876228');
INSERT INTO public.auditoria VALUES (187, 'recursos', 107, 'INSERT', NULL, NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378695", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:18:15.151824');
INSERT INTO public.auditoria VALUES (188, 'recursos', 102, 'DELETE', NULL, NULL, '{"titulo": "TEST PDO RETURNING TITLE 1786378596", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:18:40.558792');
INSERT INTO public.auditoria VALUES (189, 'recursos', 106, 'DELETE', NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378674", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:18:40.558792');
INSERT INTO public.auditoria VALUES (190, 'recursos', 107, 'DELETE', NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378695", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:18:40.558792');
INSERT INTO public.auditoria VALUES (239, 'recursos', 130, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-02 19:58:51.059906');
INSERT INTO public.auditoria VALUES (191, 'recursos', 108, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:20:17.959675');
INSERT INTO public.auditoria VALUES (192, 'recursos', 101, 'DELETE', NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378571", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:32:05.654115');
INSERT INTO public.auditoria VALUES (193, 'recursos', 103, 'DELETE', NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378621", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:32:05.654115');
INSERT INTO public.auditoria VALUES (194, 'recursos', 105, 'DELETE', NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378657", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:32:05.654115');
INSERT INTO public.auditoria VALUES (195, 'recursos', 109, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-11 10:10:03.006883');
INSERT INTO public.auditoria VALUES (196, 'recursos', 110, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-25 19:01:06.312899');
INSERT INTO public.auditoria VALUES (197, 'recursos', 111, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-25 19:01:06.640902');
INSERT INTO public.auditoria VALUES (198, 'recursos', 112, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-25 19:01:06.749048');
INSERT INTO public.auditoria VALUES (199, 'recursos', 113, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-27 09:08:37.073105');
INSERT INTO public.auditoria VALUES (200, 'recursos', 114, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-27 10:17:48.017574');
INSERT INTO public.auditoria VALUES (201, 'recursos', 115, 'INSERT', NULL, NULL, NULL, '{"titulo": "INFORME PST IV (1) (1)", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-27 10:46:00.573241');
INSERT INTO public.auditoria VALUES (202, 'recursos', 115, 'DELETE', NULL, NULL, '{"titulo": "INFORME PST IV (1) (1)", "id_tipo_recurso": 1}', NULL, '2026-08-29 18:33:19.816106');
INSERT INTO public.auditoria VALUES (203, 'recursos', 116, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-31 09:47:06.862144');
INSERT INTO public.auditoria VALUES (204, 'recursos', 117, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-31 10:08:06.559751');
INSERT INTO public.auditoria VALUES (205, 'recursos', 44, 'DELETE', NULL, NULL, '{"titulo": "auuuu", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:33.675276');
INSERT INTO public.auditoria VALUES (206, 'recursos', 43, 'DELETE', NULL, NULL, '{"titulo": "123123123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:35.310918');
INSERT INTO public.auditoria VALUES (207, 'recursos', 42, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:36.983224');
INSERT INTO public.auditoria VALUES (208, 'recursos', 41, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:38.628174');
INSERT INTO public.auditoria VALUES (209, 'recursos', 40, 'DELETE', NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:40.044516');
INSERT INTO public.auditoria VALUES (210, 'recursos', 39, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:41.532657');
INSERT INTO public.auditoria VALUES (211, 'recursos', 21, 'DELETE', NULL, NULL, '{"titulo": "La Bebecita Bebelin", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:43.99534');
INSERT INTO public.auditoria VALUES (212, 'recursos', 38, 'DELETE', NULL, NULL, '{"titulo": "23", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:45.967422');
INSERT INTO public.auditoria VALUES (213, 'recursos', 37, 'DELETE', NULL, NULL, '{"titulo": "23123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:47.549688');
INSERT INTO public.auditoria VALUES (214, 'recursos', 36, 'DELETE', NULL, NULL, '{"titulo": "Waos 3", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:49.179948');
INSERT INTO public.auditoria VALUES (215, 'recursos', 35, 'DELETE', NULL, NULL, '{"titulo": "Waos 2", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:50.605605');
INSERT INTO public.auditoria VALUES (216, 'recursos', 34, 'DELETE', NULL, NULL, '{"titulo": "Waos 1", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:52.414388');
INSERT INTO public.auditoria VALUES (217, 'recursos', 32, 'DELETE', NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:53.763311');
INSERT INTO public.auditoria VALUES (218, 'recursos', 31, 'DELETE', NULL, NULL, '{"titulo": "Imitadora", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:55.392376');
INSERT INTO public.auditoria VALUES (219, 'recursos', 28, 'DELETE', NULL, NULL, '{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:57.099607');
INSERT INTO public.auditoria VALUES (220, 'recursos', 27, 'DELETE', NULL, NULL, '{"titulo": "Que la guagua", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:22:29.21399');
INSERT INTO public.auditoria VALUES (221, 'recursos', 25, 'DELETE', NULL, NULL, '{"titulo": "Luisito comunicando", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:22:30.866944');
INSERT INTO public.auditoria VALUES (222, 'recursos', 24, 'DELETE', NULL, NULL, '{"titulo": "Pepe", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:22:32.369068');
INSERT INTO public.auditoria VALUES (223, 'recursos', 118, 'INSERT', NULL, NULL, NULL, '{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (224, 'recursos', 119, 'INSERT', NULL, NULL, NULL, '{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (225, 'recursos', 120, 'INSERT', NULL, NULL, NULL, '{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (226, 'recursos', 121, 'INSERT', NULL, NULL, NULL, '{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (227, 'recursos', 122, 'INSERT', NULL, NULL, NULL, '{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (228, 'recursos', 123, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:30:00.179124');
INSERT INTO public.auditoria VALUES (229, 'recursos', 123, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:38:17.131525');
INSERT INTO public.auditoria VALUES (233, 'recursos', 127, 'INSERT', NULL, NULL, NULL, '{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-02 15:10:22.258855');
INSERT INTO public.auditoria VALUES (235, 'recursos', 129, 'INSERT', NULL, NULL, NULL, '{"titulo": "Verde   Gestion de BD", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-02 17:24:52.328736');
INSERT INTO public.auditoria VALUES (236, 'recursos', 130, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 18:00:56.314983');
INSERT INTO public.auditoria VALUES (237, 'recursos', 131, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 18:02:41.164769');
INSERT INTO public.auditoria VALUES (240, 'recursos', 132, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-04 10:35:11.057661');
INSERT INTO public.auditoria VALUES (241, 'recursos', 133, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 13:50:26.015403');
INSERT INTO public.auditoria VALUES (242, 'recursos', 134, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 13:52:40.315328');
INSERT INTO public.auditoria VALUES (243, 'recursos', 134, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 13:53:04.485629');
INSERT INTO public.auditoria VALUES (244, 'recursos', 133, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 13:53:06.306206');
INSERT INTO public.auditoria VALUES (245, 'recursos', 135, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 13:54:05.876851');
INSERT INTO public.auditoria VALUES (246, 'recursos', 136, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 14:19:43.111334');
INSERT INTO public.auditoria VALUES (247, 'recursos', 137, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 14:22:18.231644');
INSERT INTO public.auditoria VALUES (248, 'recursos', 138, 'INSERT', NULL, NULL, NULL, '{"titulo": "w", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 14:23:15.04343');
INSERT INTO public.auditoria VALUES (249, 'recursos', 139, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 22:47:39.66015');
INSERT INTO public.auditoria VALUES (250, 'recursos', 140, 'INSERT', NULL, NULL, NULL, '{"titulo": "E", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 23:26:52.51645');
INSERT INTO public.auditoria VALUES (251, 'recursos', 136, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 23:56:47.967712');
INSERT INTO public.auditoria VALUES (252, 'recursos', 135, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 23:57:41.917589');
INSERT INTO public.auditoria VALUES (253, 'recursos', 141, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-05 00:06:53.601656');
INSERT INTO public.auditoria VALUES (254, 'recursos', 141, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:32.107364');
INSERT INTO public.auditoria VALUES (255, 'recursos', 140, 'DELETE', NULL, NULL, '{"titulo": "E", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:38.352733');
INSERT INTO public.auditoria VALUES (256, 'recursos', 139, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:40.723753');
INSERT INTO public.auditoria VALUES (257, 'recursos', 138, 'DELETE', NULL, NULL, '{"titulo": "w", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:43.029266');
INSERT INTO public.auditoria VALUES (258, 'recursos', 137, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:45.640379');
INSERT INTO public.auditoria VALUES (259, 'recursos', 142, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-07 15:37:00.278657');
INSERT INTO public.auditoria VALUES (260, 'recursos', 142, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-07 15:37:48.807771');
INSERT INTO public.auditoria VALUES (261, 'recursos', 143, 'INSERT', NULL, NULL, NULL, '{"titulo": "Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-07 15:40:32.098085');
INSERT INTO public.auditoria VALUES (262, 'recursos', 144, 'INSERT', NULL, NULL, NULL, '{"titulo": "Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-09 00:24:54.886595');
INSERT INTO public.auditoria VALUES (263, 'recursos', 145, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-09 00:36:32.789579');
INSERT INTO public.auditoria VALUES (264, 'recursos', 145, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-09 00:49:28.135237');
INSERT INTO public.auditoria VALUES (265, 'recursos', 146, 'INSERT', NULL, NULL, NULL, '{"titulo": "Modelamiento de confort adaptativo para un trapiche panelero", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-09 00:51:39.29162');
INSERT INTO public.auditoria VALUES (266, 'usuarios', 12, 'INSERT', NULL, NULL, NULL, '{"email": "andrusramirez2020@gmail.com", "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-09 19:26:21.824338');
INSERT INTO public.auditoria VALUES (267, 'recursos', 129, 'DELETE', NULL, NULL, '{"titulo": "Verde   Gestion de BD", "id_tipo_recurso": 1}', NULL, '2026-09-09 23:34:35.112448');
INSERT INTO public.auditoria VALUES (268, 'usuarios', 1, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Adrus"}', '{"activo": true, "id_rol": 1, "nombre": "Adrus"}', '2026-09-10 11:09:57.334');
INSERT INTO public.auditoria VALUES (269, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '2026-09-10 11:33:11.894408');
INSERT INTO public.auditoria VALUES (270, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-10 11:39:27.139879');
INSERT INTO public.auditoria VALUES (271, 'recursos', 147, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-10 21:02:31.80467');
INSERT INTO public.auditoria VALUES (272, 'recursos', 148, 'INSERT', NULL, NULL, NULL, '{"titulo": "VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-10 21:02:31.979685');
INSERT INTO public.auditoria VALUES (273, 'recursos', 149, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-10 21:02:32.217243');
INSERT INTO public.auditoria VALUES (274, 'recursos', 150, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-11 11:59:24.004409');
INSERT INTO public.auditoria VALUES (275, 'recursos', 151, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-11 15:27:57.58139');
INSERT INTO public.auditoria VALUES (276, 'usuarios', 13, 'INSERT', NULL, NULL, NULL, '{"email": "cepillin@gmail.com", "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 15:37:03.687571');
INSERT INTO public.auditoria VALUES (277, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 16:33:03.211027');
INSERT INTO public.auditoria VALUES (278, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:08.737789');
INSERT INTO public.auditoria VALUES (279, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:13.015607');
INSERT INTO public.auditoria VALUES (280, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:17.017251');
INSERT INTO public.auditoria VALUES (281, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:25.348502');
INSERT INTO public.auditoria VALUES (282, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:30.152351');
INSERT INTO public.auditoria VALUES (283, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 16:36:55.505126');
INSERT INTO public.auditoria VALUES (302, 'usuarios', 14, 'INSERT', NULL, NULL, NULL, '{"email": "676767@gmail.com", "id_rol": 3, "nombre": "Sixsevenaldo González"}', '2026-09-12 18:33:01.584383');
INSERT INTO public.auditoria VALUES (284, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:36:59.169874');
INSERT INTO public.auditoria VALUES (285, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 16:37:03.711934');
INSERT INTO public.auditoria VALUES (286, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 16:37:10.689659');
INSERT INTO public.auditoria VALUES (287, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 16:37:17.568688');
INSERT INTO public.auditoria VALUES (288, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 17:40:06.161413');
INSERT INTO public.auditoria VALUES (289, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 17:41:47.64796');
INSERT INTO public.auditoria VALUES (290, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 17:41:50.689906');
INSERT INTO public.auditoria VALUES (291, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:49.699268');
INSERT INTO public.auditoria VALUES (292, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:53.227644');
INSERT INTO public.auditoria VALUES (293, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:55.198334');
INSERT INTO public.auditoria VALUES (294, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:57.977504');
INSERT INTO public.auditoria VALUES (295, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": false, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 22:40:14.652503');
INSERT INTO public.auditoria VALUES (296, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 22:40:16.809997');
INSERT INTO public.auditoria VALUES (297, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:49:57.897673');
INSERT INTO public.auditoria VALUES (298, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:01.579119');
INSERT INTO public.auditoria VALUES (299, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:11.080621');
INSERT INTO public.auditoria VALUES (300, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": false, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:16.912961');
INSERT INTO public.auditoria VALUES (301, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:18.90332');
INSERT INTO public.auditoria VALUES (303, 'usuarios', 15, 'INSERT', NULL, NULL, NULL, '{"email": "DIOS@gmail.com", "id_rol": 1, "nombre": "DIOS"}', '2026-09-13 23:57:20.408394');
INSERT INTO public.auditoria VALUES (304, 'usuarios', 16, 'INSERT', NULL, NULL, NULL, '{"email": "orlando5711666@gmail.com", "id_rol": 3, "nombre": "no soy miguel"}', '2026-09-16 22:56:54.613604');
INSERT INTO public.auditoria VALUES (305, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '2026-09-19 14:19:20.874131');
INSERT INTO public.auditoria VALUES (306, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}', '2026-09-19 14:55:59.004653');
INSERT INTO public.auditoria VALUES (307, 'usuarios', 17, 'INSERT', NULL, NULL, NULL, '{"email": "andrusramirez2020@gmail.com", "id_rol": 3, "nombre": "30469331"}', '2026-09-19 14:57:10.314214');
INSERT INTO public.auditoria VALUES (308, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "30469331"}', '{"activo": true, "id_rol": 3, "nombre": "adru"}', '2026-09-19 14:59:29.871771');
INSERT INTO public.auditoria VALUES (309, 'usuarios', 15, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "DIOS"}', '{"activo": true, "id_rol": 1, "nombre": "DIOSs"}', '2026-09-19 15:13:32.480165');
INSERT INTO public.auditoria VALUES (310, 'usuarios', 15, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "DIOSs"}', '{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}', '2026-09-19 15:13:42.378327');
INSERT INTO public.auditoria VALUES (311, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "adru"}', '{"activo": true, "id_rol": 3, "nombre": "adruss"}', '2026-09-19 15:19:07.37854');
INSERT INTO public.auditoria VALUES (312, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "adruss"}', '{"activo": true, "id_rol": 2, "nombre": "adruss"}', '2026-09-19 15:19:14.229116');
INSERT INTO public.auditoria VALUES (313, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "adruss"}', '{"activo": true, "id_rol": 2, "nombre": "adrusss"}', '2026-09-20 16:36:49.932377');
INSERT INTO public.auditoria VALUES (314, 'recursos', 152, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-23 11:21:36.922266');
INSERT INTO public.auditoria VALUES (315, 'recursos', 152, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-23 11:22:57.677896');
INSERT INTO public.auditoria VALUES (316, 'recursos', 153, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-23 11:39:58.402099');
INSERT INTO public.auditoria VALUES (317, 'recursos', 153, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-23 11:45:02.317989');
INSERT INTO public.auditoria VALUES (318, 'recursos', 156, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', '2026-09-23 12:09:57.549672');
INSERT INTO public.auditoria VALUES (319, 'recursos', 157, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', '2026-09-23 12:10:19.262043');
INSERT INTO public.auditoria VALUES (320, 'recursos', 157, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-23 12:29:33.377305');
INSERT INTO public.auditoria VALUES (321, 'recursos', 158, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', '2026-09-23 12:29:57.034447');
INSERT INTO public.auditoria VALUES (322, 'recursos', 159, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', '2026-09-25 23:45:29.603658');
INSERT INTO public.auditoria VALUES (323, 'recursos', 160, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales (TEST_SIMULADO_1790394452)", "id_tipo_recurso": 1}', '2026-09-25 23:47:32.841904');
INSERT INTO public.auditoria VALUES (324, 'recursos', 160, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales (TEST_SIMULADO_1790394452)", "id_tipo_recurso": 1}', NULL, '2026-09-25 23:47:32.870735');
INSERT INTO public.auditoria VALUES (325, 'recursos', 161, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para elsdasdasdasdsa Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-26 00:01:30.60975');
INSERT INTO public.auditoria VALUES (326, 'recursos', 162, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', '2026-09-26 00:02:21.96766');
INSERT INTO public.auditoria VALUES (327, 'recursos', 163, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-26 00:03:12.854033');
INSERT INTO public.auditoria VALUES (328, 'usuarios', 19, 'INSERT', NULL, NULL, NULL, '{"email": "juanxzall0701@gmail.com", "id_rol": 2, "nombre": "Juan Salcedo"}', '2026-09-28 20:05:54.593867');
INSERT INTO public.auditoria VALUES (329, 'recursos', 162, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:20:08.135505');
INSERT INTO public.auditoria VALUES (330, 'recursos', 163, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:20:14.51703');
INSERT INTO public.auditoria VALUES (331, 'recursos', 147, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:22:34.941428');
INSERT INTO public.auditoria VALUES (332, 'recursos', 117, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:23:10.410984');
INSERT INTO public.auditoria VALUES (333, 'recursos', 159, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:27:16.267346');
INSERT INTO public.auditoria VALUES (334, 'recursos', 158, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:27:18.567257');
INSERT INTO public.auditoria VALUES (335, 'recursos', 164, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-09-28 23:19:51.125311');
INSERT INTO public.auditoria VALUES (336, 'recursos', 1, 'DELETE', NULL, NULL, '{"titulo": "Sistema de Reconocimiento Biométrico Facial para Comedor Universitario", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (337, 'recursos', 2, 'DELETE', NULL, NULL, '{"titulo": "Prototipo de Cerradura Digital con Matriz de Teclado y Arduino", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (338, 'recursos', 6, 'DELETE', NULL, NULL, '{"titulo": "Big Data en Finanzas Institucionales - Parte 9", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (339, 'recursos', 7, 'DELETE', NULL, NULL, '{"titulo": "Optimización de CPU en Servidores Locales - Parte 7", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (340, 'recursos', 8, 'DELETE', NULL, NULL, '{"titulo": "Sistemas de Riego Automatizado - Parte 5", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (341, 'recursos', 15, 'DELETE', NULL, NULL, '{"titulo": "Criptografía Cuántica Post-RSA - Parte 2", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (342, 'recursos', 17, 'DELETE', NULL, NULL, '{"titulo": "Criptografía Cuántica Post-RSA - Parte 5", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (343, 'recursos', 18, 'DELETE', NULL, NULL, '{"titulo": "Criptografía Cuántica Post-RSA - Parte 8", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (344, 'recursos', 19, 'DELETE', NULL, NULL, '{"titulo": "Software Libre para Bibliotecas - Parte 6", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (345, 'recursos', 45, 'DELETE', NULL, NULL, '{"titulo": "Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (346, 'recursos', 46, 'DELETE', NULL, NULL, '{"titulo": "Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (347, 'recursos', 47, 'DELETE', NULL, NULL, '{"titulo": "Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (348, 'recursos', 48, 'DELETE', NULL, NULL, '{"titulo": "Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (349, 'recursos', 50, 'DELETE', NULL, NULL, '{"titulo": "Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (350, 'recursos', 51, 'DELETE', NULL, NULL, '{"titulo": "Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (351, 'recursos', 52, 'DELETE', NULL, NULL, '{"titulo": "Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (352, 'recursos', 57, 'DELETE', NULL, NULL, '{"titulo": "hola adios", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (353, 'recursos', 58, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (354, 'recursos', 59, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (355, 'recursos', 69, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (356, 'recursos', 72, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (357, 'recursos', 78, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Carga por Lotes - 20260805134326", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (358, 'recursos', 79, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (359, 'recursos', 80, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (360, 'recursos', 81, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (426, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:41.676708');
INSERT INTO public.auditoria VALUES (361, 'recursos', 82, 'DELETE', NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (362, 'recursos', 83, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (363, 'recursos', 84, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (364, 'recursos', 85, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805135642", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (365, 'recursos', 86, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805140204", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (366, 'recursos', 87, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805143446", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (367, 'recursos', 88, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (368, 'recursos', 89, 'DELETE', NULL, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (369, 'recursos', 90, 'DELETE', NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (370, 'recursos', 91, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (371, 'recursos', 94, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (372, 'recursos', 93, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (373, 'recursos', 92, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (374, 'recursos', 112, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (375, 'recursos', 97, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (376, 'recursos', 99, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (377, 'recursos', 100, 'DELETE', NULL, NULL, '{"titulo": "il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (378, 'recursos', 108, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (379, 'recursos', 109, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (380, 'recursos', 110, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (381, 'recursos', 111, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (382, 'recursos', 113, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (383, 'recursos', 114, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (384, 'recursos', 116, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (385, 'recursos', 127, 'DELETE', NULL, NULL, '{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (386, 'recursos', 132, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (387, 'recursos', 148, 'DELETE', NULL, NULL, '{"titulo": "VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (388, 'recursos', 149, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (389, 'recursos', 49, 'DELETE', NULL, NULL, '{"titulo": "Sistema de Información Automatizado para la Gestión de Inventario y Suministros Médicos", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (390, 'recursos', 161, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para elsdasdasdasdsa Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (391, 'recursos', 128, 'DELETE', NULL, NULL, '{"titulo": "Materia: Seguridad Informáticasssssss", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (392, 'recursos', 164, 'DELETE', NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (393, 'recursos', 173, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (394, 'recursos', 174, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (395, 'recursos', 175, 'INSERT', NULL, NULL, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (396, 'recursos', 176, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (397, 'recursos', 177, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (398, 'recursos', 178, 'INSERT', NULL, NULL, NULL, '{"titulo": "DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (399, 'recursos', 179, 'INSERT', NULL, NULL, NULL, '{"titulo": "CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (400, 'recursos', 180, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda ''Tuti'' del Cantón Vinces", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (401, 'recursos', 181, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN Y GESTIÓN DE PROYECTOS DE GRADO", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (402, 'recursos', 181, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN Y GESTIÓN DE PROYECTOS DE GRADO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:36:50.181089');
INSERT INTO public.auditoria VALUES (403, 'recursos', 182, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-28 23:55:27.29017');
INSERT INTO public.auditoria VALUES (404, 'recursos', 183, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-28 23:55:52.326738');
INSERT INTO public.auditoria VALUES (405, 'recursos', 183, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:18:32.939693');
INSERT INTO public.auditoria VALUES (406, 'recursos', 182, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:18:42.813407');
INSERT INTO public.auditoria VALUES (407, 'recursos', 184, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-29 00:48:27.834532');
INSERT INTO public.auditoria VALUES (408, 'recursos', 184, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:48:57.405027');
INSERT INTO public.auditoria VALUES (409, 'recursos', 185, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-29 00:55:08.390241');
INSERT INTO public.auditoria VALUES (410, 'recursos', 185, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:55:19.687702');
INSERT INTO public.auditoria VALUES (411, 'recursos', 186, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-09-30 19:49:51.452795');
INSERT INTO public.auditoria VALUES (412, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzálezzzzz"}', '2026-10-03 12:40:18.137748');
INSERT INTO public.auditoria VALUES (413, 'usuarios', 2, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "lando"}', '{"activo": true, "id_rol": 2, "nombre": "lando"}', '2026-10-03 12:44:23.513489');
INSERT INTO public.auditoria VALUES (414, 'usuarios', 2, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "lando"}', '{"activo": true, "id_rol": 2, "nombre": "lando"}', '2026-10-03 12:44:23.520783');
INSERT INTO public.auditoria VALUES (415, 'usuarios', 20, 'INSERT', NULL, NULL, NULL, '{"email": "qa_test_ciidi_1791045863@upttmbi.edu.ve", "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:23.792227');
INSERT INTO public.auditoria VALUES (416, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:23.796502');
INSERT INTO public.auditoria VALUES (417, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:23.798405');
INSERT INTO public.auditoria VALUES (418, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:23.800171');
INSERT INTO public.auditoria VALUES (419, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:24.555246');
INSERT INTO public.auditoria VALUES (420, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', '2026-10-03 12:44:24.804573');
INSERT INTO public.auditoria VALUES (421, 'usuarios', 20, 'DELETE', NULL, NULL, '{"email": "qa_nuevo_1791045863@upttmbi.edu.ve", "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', NULL, '2026-10-03 12:44:24.813668');
INSERT INTO public.auditoria VALUES (422, 'usuarios', 3, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "miki"}', '{"activo": true, "id_rol": 3, "nombre": "miki"}', '2026-10-03 12:44:41.401758');
INSERT INTO public.auditoria VALUES (423, 'usuarios', 3, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "miki"}', '{"activo": true, "id_rol": 3, "nombre": "miki"}', '2026-10-03 12:44:41.408021');
INSERT INTO public.auditoria VALUES (424, 'usuarios', 21, 'INSERT', NULL, NULL, NULL, '{"email": "qa_test_ciidi_1791045881@upttmbi.edu.ve", "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:41.673185');
INSERT INTO public.auditoria VALUES (425, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:41.674697');
INSERT INTO public.auditoria VALUES (427, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:41.679952');
INSERT INTO public.auditoria VALUES (428, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:42.447266');
INSERT INTO public.auditoria VALUES (429, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', '2026-10-03 12:44:42.693185');
INSERT INTO public.auditoria VALUES (430, 'usuarios', 21, 'DELETE', NULL, NULL, '{"email": "qa_nuevo_1791045881@upttmbi.edu.ve", "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', NULL, '2026-10-03 12:44:42.704726');
INSERT INTO public.auditoria VALUES (431, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzálezzzzz"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:27:37.178154');
INSERT INTO public.auditoria VALUES (432, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:28:47.02622');
INSERT INTO public.auditoria VALUES (433, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:28:47.031874');
INSERT INTO public.auditoria VALUES (434, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:29:04.750162');
INSERT INTO public.auditoria VALUES (435, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:29:04.755525');
INSERT INTO public.auditoria VALUES (436, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:29:10.8743');
INSERT INTO public.auditoria VALUES (438, 'recursos', 188, 'INSERT', NULL, NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138031", "id_tipo_recurso": 1}', '2026-10-04 14:20:31.26127');
INSERT INTO public.auditoria VALUES (439, 'recursos', 188, 'DELETE', NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138031", "id_tipo_recurso": 1}', NULL, '2026-10-04 14:20:31.322724');
INSERT INTO public.auditoria VALUES (440, 'recursos', 189, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-10-04 14:23:02.803018');
INSERT INTO public.auditoria VALUES (441, 'recursos', 190, 'INSERT', NULL, NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138669", "id_tipo_recurso": 1}', '2026-10-04 14:31:09.763754');
INSERT INTO public.auditoria VALUES (442, 'recursos', 190, 'DELETE', NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138669", "id_tipo_recurso": 1}', NULL, '2026-10-04 14:31:09.821791');
INSERT INTO public.auditoria VALUES (443, 'recursos', 191, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-10-04 16:43:25.694943');
INSERT INTO public.auditoria VALUES (444, 'recursos', 192, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-10-04 17:14:02.756345');
INSERT INTO public.auditoria VALUES (452, 'recursos', 200, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dasdasdase Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', '2026-10-10 00:00:49.200289');
INSERT INTO public.auditoria VALUES (453, 'recursos', 201, 'INSERT', NULL, NULL, NULL, '{"titulo": "Distribución de perfiles y macropartículas en recubrimientos obtenidos por arco catódico continúo utilizando un concentrador magnético recto", "id_tipo_recurso": 3}', '2026-10-12 14:48:05.546878');
INSERT INTO public.auditoria VALUES (454, 'usuarios', 5, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "bibi"}', '{"activo": false, "id_rol": 3, "nombre": "[Archivado] bibi"}', '2026-10-15 20:53:10.93682');
INSERT INTO public.auditoria VALUES (455, 'usuarios', 22, 'INSERT', NULL, NULL, NULL, '{"email": "cepillinin@gmail.com", "id_rol": 3, "nombre": "Felipe"}', '2026-10-15 22:00:58.531497');
INSERT INTO public.auditoria VALUES (456, 'recursos', 156, 'DELETE', 7, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-10-17 00:10:53.864393');
INSERT INTO public.auditoria VALUES (457, 'recursos', 151, 'DELETE', 7, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-10-17 00:10:56.383811');
INSERT INTO public.auditoria VALUES (458, 'recursos', 150, 'DELETE', 7, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-10-17 00:21:36.486961');


--
-- Data for Name: autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.autores VALUES (2, 'Andrés David Parra Cabrera', 'V-31029492');
INSERT INTO public.autores VALUES (3, 'Jesús Alejandro Lobo Briceño', 'V-27677098');
INSERT INTO public.autores VALUES (4, 'Orlando José González Moreno', 'V-31168262');
INSERT INTO public.autores VALUES (5, 'Sebastián Jesús Blanco Rojas', 'V-30600412');


--
-- Data for Name: carreras; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.carreras VALUES (1, 'PNF en Informática', 'Ingeniería y TSU en Informática');
INSERT INTO public.carreras VALUES (2, 'PNF en Electricidad', 'Ingeniería y TSU en Electricidad');
INSERT INTO public.carreras VALUES (3, 'PNF en Administración', 'Licenciatura y TSU en Administración');
INSERT INTO public.carreras VALUES (4, 'PNF en Agroalimentación', 'Ingeniería y TSU Agroalimentario');
INSERT INTO public.carreras VALUES (5, 'PNF en Construcción Civil', 'Ingeniería y TSU en Construcción Civil');


--
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.categorias VALUES (1, 'Tecnología');
INSERT INTO public.categorias VALUES (3, 'Ingeniería');
INSERT INTO public.categorias VALUES (4, 'Sociales');
INSERT INTO public.categorias VALUES (5, 'Innovación');
INSERT INTO public.categorias VALUES (6, 'Ciencias Sociales');
INSERT INTO public.categorias VALUES (7, 'Salud y Biociencias');
INSERT INTO public.categorias VALUES (11, 'Salud');
INSERT INTO public.categorias VALUES (13, 'Matemáticas');
INSERT INTO public.categorias VALUES (14, 'Literatura');
INSERT INTO public.categorias VALUES (15, 'Psicología');
INSERT INTO public.categorias VALUES (16, 'Economía');
INSERT INTO public.categorias VALUES (17, 'Contaduría');
INSERT INTO public.categorias VALUES (18, 'Ingeniería Civil');


--
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cursos VALUES (1, 4, 'Introducción a la Metodología de la Investigación', 'Curso fundamental para comprender los métodos y técnicas de investigación científica aplicados al PNF en Informática. Incluye diseño experimental, recolección de datos y análisis estadístico básico.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (2, 4, 'Fundamentos de Inteligencia Artificial', 'Curso introductorio sobre los conceptos básicos de la IA, redes neuronales, aprendizaje automático y sus aplicaciones en el contexto venezolano.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (4, 1, 'tamaños de jose', 'los pn que jose ha tenido segun tamaño', NULL, 'borrador', 69.96, '2026-04-03 04:40:03', '2026-09-02 21:38:28.289267', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (3, 10, 'Normas APA y Redacción Científica', 'Aprende a redactar documentos académicos siguiendo las normas APA 7ma edición. Ideal para la elaboración de tu Proyecto Socio-Tecnológico.', NULL, 'archivado', 67.00, '2026-04-03 03:28:04', '2026-09-02 21:39:04.419332', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (6, 9, 'e', 'e', NULL, 'publicado', 70.00, '2026-09-02 21:40:20.207518', '2026-09-02 21:40:27.256632', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (7, 7, 'e', 'e', 'public/uploads/cursos/curso_1789800856_34a50e7c.webp', 'borrador', 70.00, '2026-09-19 02:54:16.395792', '2026-09-19 02:54:16.395792', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');


--
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_articulos VALUES (120, NULL, '93', '241', '0012-7353', '2026-07-09 20:16:51.643727', 'art_1783642611_6a5039f396bec.png', 'La industria de la construcción enfrenta un serio impacto ambiental por las altas emisiones del cemento, lo que impulsa la búsqueda de alternativas sostenibles como el concreto reforzado con fibras de polipropileno (FPP). Para ello se analizó su efecto en las propiedades del concreto a través de una revisión sistemática y filtrada de 66 artículos recientes entre los años 2021 y 2025 extraídos de Scopus, ScienceDirect y MDPI. Los estudios muestran que la FPP mejora la resistencia a compresión, flexión y tracción, especialmente en proporciones cercanas al 0.5%. También aumenta la durabilidad frente a agentes agresivos y mejora la microestructura al controlar grietas, aunque, puede reducir la trabajabilidad y aumentar la porosidad, efectos mitigables mediante el uso de fibras metálicas o adiciones puzolánicas. En conclusión, el uso de FPP es una opción viable para reducir el impacto ambiental del concreto y mejorar su desempeño cuando se aplica en proporciones adecuada', true, NULL);
INSERT INTO public.detalles_articulos VALUES (121, 8, '93', '241', '0012-7353', '2026-07-09 20:19:28.855723', 'art_1783642768_6a503a90ca313.png', 'La discapacidad motora en Colombia afecta a un porcentaje significativo de la población, constituye una problemática relevante de salud pública,  asociada  con  diversos  factores  del  país.  Este  proyecto  desarrolla  un  sistema  de  control  de  robots  asistenciales  controlados  por  señales electrooculográficas (EOG), logrando que aquellas personas con movilidad reducida tengan acceso a este tipo de tecnologías. Para el desarrollo se adquirieron señales con el hardware Bitalino para generar y normalizar un conjunto de datos, que luego se procesa con Python y Open Signals para establecer comandos confiables. El entorno de simulación se realizó en CoppeliaSim. Durante el proceso de desarrollo, se encontraron obstáculos como el ruido y la exactitud de las señales. No obstante, se ha terminado la interfaz y la conexión entre CoppeliaSim, Python y las señales EOG, permitiendo que el robot se mueva en tiempo real. En la actualidad, se realizan pruebas de funcionamiento, exactitud y precisión de los movimientos.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (119, 8, '93', '241', '0012-7353', '2026-07-09 20:13:49.50881', 'art_1783642429_6a50393d74626.png', 'Los techos verdes representan una estrategia pasiva eficaz para reducir la transferencia de calor hacia el interior de los edificios, especialmente en climas  cálidos  y  húmedos.  En  este  trabajo  se  presenta  un  modelo  dinámico  unidimensional  de  balance  de  calor  y  masa  para  evaluar  el  comportamiento térmico de un techo verde extensivo en condiciones de trópico húmedo. El modelo considera procesos de conducción, convección, radiación y transferencia de humedad, incorporando la evapotranspiración y parámetros de la vegetación dependientes de la especie. La calibración y simulación se realizaron usando datos experimentales obtenidos de una base experimental de techos verde ubicada en Tabasco, México, con las especies Tradescantia  spathaceay Tradescantia  pallida.  El  desempeño  del  sistema  se  evaluó  bajo  tres  escenarios  climáticos  representativos:  temporada de estiaje, temporada de lluvia y de frente frío. Los resultados muestran que la capa vegetal reduce la transferencia de calor hacia el interior del edificio, además de contribuir a la estabilización térmica del microclima del techo. El análisis de sensibilidad indica que parámetros asociados a la vegetación, en particular el índice de área foliar y la resistencia interna de las hojas, ejercen una influencia dominante en la respuesta del sistema. Aunque el modelo se limita al caso unidimensional y a especies específicas, constituye una herramienta útil para la evaluación del desempeño térmico de techos verdes en climas tropicales húmedos', true, NULL);
INSERT INTO public.detalles_articulos VALUES (143, 7, '93', '242', '0012-7353', '2026-09-07 15:40:32.098085', 'https://revistas.unal.edu.co/public/journals/21/submission_124890_112809_coverImage_es_ES.png', 'En una línea de transmisión de alta tensión de circuito único de 400 kV, la proximidad de los conductores de fase induce una corriente circulante en el conductor de tierra. Esta corriente forma un circuito cerrado a través del sistema de puesta a tierra de la base de la torre, que proporciona su ruta de retorno [1]. En este estudio, se modela y analiza la corriente circulante inducida. El modelado se realizó en el entorno MATLAB.Los resultados indican que la corriente circulante y la tensión inducida en el conductor de tierra presentan una relación aproximadamente lineal con las corrientes de los conductores de fase, y sus magnitudes se ven influenciadas por la resistencia de puesta a tierra de la torre R_g y la resistividad del suelo ρ. Además, las pérdidas de potencia en el conductor de tierra alcanzan niveles significativos en condiciones de alta corriente. Estos hallazgos resaltan la importancia de un diseño óptimo del sistema de puesta a tierra, una selección adecuada de las características del conductor de tierra y la implementación de métodos para reducir las corrientes circulantes con el fin de mejorar el rendimiento y reducir las pérdidas en las líneas de transmisión de alta tensión.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (122, 8, '93', '241', '0012-7353', '2026-07-09 20:08:48.117741', 'art_1783642127_6a50380feed3b.png', 'La  banca  móvil  se  ha  consolidado  como  una  herramienta  clave  para  la  inclusión  financiera,  particularmente  en  zonas  rurales  donde  las  barreras geográficas y de infraestructura limitan el acceso a servicios bancarios tradicionales. Este estudio analiza los determinantes de la aceptación de la banca móvil en ganaderos del occidente de Antioquia, Colombia, utilizando el modelo UTAUT. Se aplicó una metodología cuantitativa  basada  en  encuestas  estructuradas  a  132  productores  rurales,  evaluando  variables  como  la  expectativa  de  rendimiento,  la  expectativa de esfuerzo, la influencia social, el riesgo y la confianza. Los resultados revelan que la expectativa de rendimiento y la facilidad de uso son los principales factores que influyen en la adopción de la banca móvil, mientras que la confianza, el riesgo y la influencia social no  mostraron  un  impacto  significativo.  Estos  hallazgos  destacan  la  necesidad  de  desarrollar  estrategias  que  promuevan  el  acceso  a  plataformas digitales intuitivas y capacitaciones enfocadas en el uso de estas herramientas.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (118, 5, '87', '213', '0012-7353', '2026-07-09 15:19:57.897052', 'https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png', 'Este artículo propone una ampliación de las capacidades del middleware MiSCi, al agregar una nueva capa denominada Datos Enlazados, para  identificar,  describir,  conectar,  relacionar  y  explotar  los  distintos  datos  generados  por  los  usuarios  y  las  aplicaciones  de  la  ciudad  inteligente usando el paradigma de datos enlazados. Esta nueva capa está compuestas por distintos agentes que permiten automatizar las etapas  de  especificación,  modelado,  generación,  vinculación,  publicación  y  explotación  de  los  datos  basados  en  MEDAWEDE.  Dichos  agentes  pueden  enriquecer  ontologías  existentes  en  MiSCi,  generar  modelos  de  conocimiento  requeridos  por  los  servicios  de  MiSCi, generar datos para construir modelos de conocimiento para MiSCi, y recomendar información en contextos de incertidumbre a través de una inferencia híbrida basada en lógica descriptiva/dialéctica. Además en este trabajo se especifica un caso de estudio, donde se muestran las capacidades del MiSCi para manejar distintas situaciones críticas, apoyado en la nueva capa de enlazado de dato', true, NULL);
INSERT INTO public.detalles_articulos VALUES (201, 8, '87', '214', '0012-7353', '2026-10-12 14:48:05.546878', 'https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png', 'Películas de titanio y cobre fueron depositadas sobre substratos de acero inoxidable 316 usando la técnica de arco catódico continuo y con un concentrador magnético recto. Las muestras obtenidas a diferentes distancias cátodo-substrato, para posiciones en el interior del concentrador magnético. La morfología superficial fue obtenida por microscopia electrónica de barrido (SEM). El espesor de las películas fue medido por la masa depositada; los perfiles de los recubrimientos fueron estudiados con las medidas con un calotest y con un perfilómetro; el coeficiente de fricción y desgaste fueron caracterizados con un tribómetro; la dureza con un nanoindentador. Los resultados muestran que el espesor medio se incrementa por la acción del concentrador magnético, con una región central de máximo espesor y disminuyen hasta en un 50%   con la posición radial hacia los bordes para distancias de 1 cm. Los recubrimientos muestran la presencia de macropartículas. El numero de macropartículas, la rugosidad del recubrimiento y la tasa de deposición, también fueron analizadas; la tasa de deposición depende de la posición axial en el interior del ducto. El número de macropartículas disminuye cuando aumenta la distancia cátodo-substrato. La fricción y la tasa de desgaste disminuyen cuando el substrato se coloca mas lejos del cátodo, en el interior del ducto. Los valores de la dureza medidos con el nanoindentador son del orden al reportado en la literatura; los bajos coeficientes de fricción, el menor desgaste y los altos valores de la dureza, fueron obtenidos en la región donde el campo magnético es alto.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (146, NULL, '91', '232', '0012-735', '2026-09-09 00:51:39.29162', 'https://revistas.unal.edu.co/public/journals/21/submission_112625_95079_coverImage_es_ES.png', 'La producción de azúcar de caña no centrifugada, en Colombia se realiza en instalaciones de poscosecha que generan alta cantidad de calor y vapor, producto de la evaporación de los jugos de caña del proceso. Este estudio tuvo como objetivo mejorar las condiciones de confort de una instalación de este tipo en el municipio de Pacho, Cundinamarca, Colombia, a través de simulación bioclimática, donde se modificó el cerramiento en las paredes y en la ventana cenital. Se evalúo el confort térmico adaptativo, donde el mejor comportamiento bioclimático se  presentó  en  las  configuraciones  con  perímetro  abierto  y  ventana  cenital,  esto  debido  a  que  una  mayor  área  de  ventilación  y  efecto chimenea optimizan  la  transferencia  de  calor  y  masa;  así  mismo,  se  observó  que  hay  un  comportamiento  generalizado  de  incomodidad  térmica para los trabajadores en la zona térmica hornilla, debido a las altas emisiones de calor y vapor en esta zona', true, NULL);
INSERT INTO public.detalles_articulos VALUES (144, 8, '93', '242', '0012-7353', '2026-09-09 00:24:54.886595', 'https://revistas.unal.edu.co/public/journals/21/submission_124428_112347_coverImage_es_ES.png', 'Esta investigación desarrolló y evaluó un modelo de aprendizaje automático para optimizar la contratación de profesionales de ingeniería en  una universidad pública, reduciendo el tiempo de evaluación, los errores y la subjetividad en el análisis de currículums. Se empleó un enfoque cuantitativo, aplicado y cuasiexperimental, utilizando procesamiento de lenguaje natural (TF-IDF), clasificación KNN bajo el esquema One vs-Rest y tres conjuntos de datos de 10, 20 y 30 CV. La información fue procesada en Google Colab mediante etapas de limpieza, vectorización, entrenamiento y evaluación. El modelo alcanzó una precisión del 82 % en la clasificación de candidatos, priorizando de manera consistente a los postulantes según su grado académico y experiencia profesional. Además, redujo el tiempo promedio de evaluación de 15 a 2,5 minutos por CV y disminuyó la tasa de error a menos del 2 %, demostrando ser una herramienta eficiente, objetiva y escalable.', true, NULL);


--
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: dimensiones_operativas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.dimensiones_operativas VALUES (7, 7, 'Sistemas de información web', 'Son primeramente sistemas de información que para su desarrollo se debe considerar la misma disciplina de construcción de sistemas de información no Web exitosos y de calidad, sirven para integrar procesos o sistemas dentro de una sola interfaz y a ellos se puede acceder por medio de una Intranet local o por la red global Internet van m s all  de ser un conjunto de p ginas Web.', true);
INSERT INTO public.dimensiones_operativas VALUES (8, 7, 'Sistemas de información colaborativos', 'Son sistemas donde se pueden expresar ideas, experiencias, definiciones, entre otros; los cuales constituyen una red de distribución de la información en una organización o entre organizaciones.', true);
INSERT INTO public.dimensiones_operativas VALUES (9, 7, 'Gestión tecnológica', 'Procesos relacionados con la implantación de sistemas, tales como, verificar e instalar nuevos equipos, entrenar a los usuarios, instalar nuevas aplicaciones, agregar nuevos módulos, adem s de comprobar el correcto funcionamiento de los componentes de un sistema de información que puede abarcar auditorías, t‚cnicas de control, evaluación de la calidad.', true);
INSERT INTO public.dimensiones_operativas VALUES (10, 8, 'Software educativo', 'Programas para el computador creados con la finalidad especáfica de ser utilizados como medio did ctico, es decir, para facilitar los procesos de ense¤anza y de aprendizaje. Combina conocimiento educacional, comunicacional e inform tico.', true);
INSERT INTO public.dimensiones_operativas VALUES (11, 8, 'Guáas de estudio web', 'Representan un material instruccional utilizados para cursos de educación a distancia y como complemento a la educación presencial, lo cual provee una estructura para un curso.', true);
INSERT INTO public.dimensiones_operativas VALUES (12, 8, 'Tutoriales', 'Son programas que en mayor o menor medida dirigen el trabajo de los alumnos. Pretenden que, a partir de unas informaciones y mediante la realización de ciertas actividades, los estudiantes pongan en juego determinadas capacidades.', true);
INSERT INTO public.dimensiones_operativas VALUES (14, 8, 'Entornos interactivos de ense¤anza', 'Proyectos donde el profesor y los alumnos se encuentran en lugares fásicamente distintos. El proceso de ense¤anza-aprendizaje se lleva a cabo a trav‚s de Internet, en cualquier momento y en cualquier lugar.', true);
INSERT INTO public.dimensiones_operativas VALUES (15, 8, 'Sistemas e-learning', 'Programas que faciliten la creación, adopción y distribución de contenidos, asá como la adaptación del ritmo de aprendizaje y la disponibilidad de las herramientas de aprendizaje independientemente de lámites horarios o geogr ficos.', true);
INSERT INTO public.dimensiones_operativas VALUES (16, 9, 'Aplicaciones cliente - servidor', 'Sistema distribuido entre múltiples procesadores donde hay clientes que solicitan servicios y servidores que los proporcionan. Separa los servicios situando cada uno en su plataforma m s adecuada.', true);
INSERT INTO public.dimensiones_operativas VALUES (17, 9, 'Servicios de integración para aplicaciones web', 'Medio para exponer y hacer disponible la funcionalidad de los sistemas de información mediante las tecnologáas est ndar Web, permitiendo reducción de la heterogeneidad por uso de tecnologáas est ndar.', true);
INSERT INTO public.dimensiones_operativas VALUES (18, 10, 'Simulación y herramientas de simulación', 'Antes de iniciar el desarrollo de cualquier sistema complejo, los ingenieros suelen utilizar alguna herramienta de simulación o test donde sea posible modelizar y probar el sistema que est  desarrollando. Reduce tiempo y chequea decisiones a priori.', true);
INSERT INTO public.dimensiones_operativas VALUES (19, 10, 'Modelos de transmisión de datos', 'Se discute la conceptualización integral de un sistema de transmisión desde un marco común a diferentes tecnologáas, tales como: sistemas de comunicación por cable, radio enlaces fijos, móviles y satelitales.', true);
INSERT INTO public.dimensiones_operativas VALUES (20, 9, 'Aplicaciones multiplataforma', 'diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.', true);
INSERT INTO public.dimensiones_operativas VALUES (21, 9, 'Aplicaciones web interactivas', 'diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.', true);
INSERT INTO public.dimensiones_operativas VALUES (22, 9, 'Aplicaciones móviles y ubicuas', 'desarrollo de soluciones adaptadas a dispositivos móviles y contextos de movilidad, integrando sensores, geolocalización, notificaciones y conectividad. Se promueve la experiencia de usuario y el acceso remoto a servicios en tiempo real.', true);
INSERT INTO public.dimensiones_operativas VALUES (23, 9, 'Modelado y gestión de datos', 'diseño conceptual, lógico y físico de estructuras de datos que sustentan el funcionamiento de las aplicaciones, Incluye el uso de modelos entidad-relación, normalización, diseño de esquemas relacionales y no relacionales, así como la implementación en sistemas gestores de bases de datos. Esta dimensión garantiza la integridad, consistencia y eficiencia en el almacenamiento, recuperación y procesamiento de la información.', true);
INSERT INTO public.dimensiones_operativas VALUES (24, 9, 'Seguridad y auditoría de aplicaciones', 'incorporación de prácticas de desarrollo seguro, autenticación, autorización, cifrado y trazabilidad. Se abordan normativas como ISO/IEC 27001 y principios de privacidad por diseño, garantizando la integridad y confidencialidad de los sistemas.', true);
INSERT INTO public.dimensiones_operativas VALUES (5, 7, 'Sistemas de información tradicionales', 'Est  constituido por un conjunto de elementos de naturaleza diversa que incluyen: equipos, recursos humanos (usuario), datos e información y programas y aplicaciones; que interactúan entre si dentro de una organización con el fin de apoyar las actividades y funciones que cumplan con los objetivos propuestos de la misma.', true);
INSERT INTO public.dimensiones_operativas VALUES (6, 7, 'Sistemas de información con propiedades geogr ficas', 'Son sistemas que permiten evaluar propiedades geogr ficas de un entorno, generando información referente a una entidad geogr fica desplegando im genes e información en un hipermapa.', true);
INSERT INTO public.dimensiones_operativas VALUES (13, 8, 'Juegos did cticos', 'El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploración y expresión, un instrumento para la organización y aplicación de habilidades y, un factor de socialización e integración.', true);


--
-- Data for Name: editoriales; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.editoriales VALUES (1, 'IEEE');
INSERT INTO public.editoriales VALUES (3, 'Springer');
INSERT INTO public.editoriales VALUES (4, 'Elsevier');
INSERT INTO public.editoriales VALUES (5, 'UPTTMBI Ediciones');
INSERT INTO public.editoriales VALUES (6, 'UNESCO');
INSERT INTO public.editoriales VALUES (7, 'SciELO Venezuela');
INSERT INTO public.editoriales VALUES (8, 'DYNA');


--
-- Data for Name: etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.etiquetas VALUES (1, 'Inteligencia Artificial', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (3, 'Educación', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (4, 'Redes Neuronales', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (5, 'Desarrollo Web', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (8, 'Teoría Matemática', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (9, 'Modelo Económico', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (10, 'Construcción', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (17, 'Machine Learning', '#0ea5e9');


--
-- Data for Name: historico_versiones_pst; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.investigaciones_ofertadas VALUES (1, 7, 'asdasdasdasd', 'asdasdasdasdasdasd', 'asdasdasdasdasdasd', 9, NULL, 3, 'Abierta', '2026-09-09 23:15:22.674054', NULL);
INSERT INTO public.investigaciones_ofertadas VALUES (2, 7, 'Requerimiento: Sistema de Inventario', 'isuuuuuuu ordeña a carmencita

(Nivel Requerido: Trayecto I)', 'Dar respuesta y solución tecnológica a los requerimientos de Megacell', 7, 9, 3, 'Abierta', '2026-09-17 00:01:42.595736', 16);
INSERT INTO public.investigaciones_ofertadas VALUES (4, 7, 'asdasdasdasdsadadasdsa', 'asdasdasdasd', 'asdasdadasdas', 10, NULL, 3, 'En Desarrollo', '2026-09-29 00:05:26.495721', NULL);


--
-- Data for Name: investigadores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.investigadores VALUES (1, 'Ricardo José Dos Santos Guillén', '0000-0001-8752-8550', 'Ricardo José Dos Santos Guillén obtuvo el título en Ing. de Sistemas en 2006 y en MSc. en Computación en 2016, ambos en la Universidad de Los Andes-Venezuela. Actualmente es candidato a Dr. en Ciencias Aplicadas en Universidad de los Andes-Venezuela y profesor en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry", Trujillo-Venezuela. Es investigador en el Centro Estudios en Microelectrónica y Sistemas Distribuidos (CEMISID); y en Tepuy R+D Group, Artificial Intelligence Software Development. Su interés en investigación incluye inteligencia artificial, datos enlazados, big data, minería semántica, lógica dialéctica, analítica social, y ambientes inteligentes', 'https://ricardojdsg.github.io/');
INSERT INTO public.investigadores VALUES (2, 'Jose Aguilar Castro', '0000-0003-4194-6882', 'Jose Aguilar Castro obtuvo el título en Ing. de Sistemas en 1987 de la Universidad de Los Andes-Venezuela, MSc. en Ciencias de la Computación en 1991 en la Universidad Paul Sabatier-Francia, y Dr. en Ciencias de la Computación en 1995 de la Universidad René Descartes-Francia. Fue investigador postdoctoral en el Departamento de Ciencias de la Computación de la Universidad de Houston (1999-2000) y en el Laboratoire d''Analyse et d''Architecture des Systèmes, CNRS, Francia (2010-2011), e investigador prometeo en Ecuador (2014-2017). Actualmente es profesor titular del Departamento de Ciencias de la Computación de la Universidad de Los Andes, Mérida, Venezuela, e investigador invitado en la Universidad EAFIT, Medellín, Colombia. Es investigador en el CEMISID; y en Tepuy R+D Group. Además, es miembro de la Academia de Mérida y del Comité Técnico de Redes Neuronales del IEEE. Ha publicado más de 550 artículos y libros, en el campo de sistemas paralelos y distribuidos, inteligencia computacional, etc. Su interés en investigación incluye Inteligencia Artificial, Minería Semántica, Big Data, Sistema Emergente y Entornos Inteligentes.', NULL);
INSERT INTO public.investigadores VALUES (3, 'Taniana Rodríguez de Paredes', '0000-0002-0429-3141', 'Taniana Rodríguez de Paredes obtuvo el título de Ing. de Sistemas en 1993, MSc. en Computación en 2000 y Dr. en Ciencias Aplicadas en 2016, todos en la Universidad de Los Andes-Venezuela. Actualmente es profesora en el postgrado en Computación, de la Universidad de los Andes, Mérida-Venezuela. Es Investigadora en el CEMISID; y en Tepuy R+D Group. Su interés en investigación incluye Datos Enlazados, Aprendizaje de Ontologías, Minería Semántica y Procesamiento de Lenguaje Natural', NULL);
INSERT INTO public.investigadores VALUES (4, 'Armando Bermeo Acosta', '0000-0003-1610-4712', 'Armando Bermeo Acosta graduated as a physicist in 1980, Universidad del Valle; with master''s degrees in physical sciences, 1984, Universidad del Valle; Postgraduate degree in environment in 1999 from the Santiago de Cali University, Doctorate in Engineering 2014, Universidad del Valle. Professor at the Santiago de Cali University since 1980 and linked to the Faculty of Basic Sciences at the same University. research areas coatings, plasma physics and currently dedicated to technological appropriation for the design and construction of deposition equipment with cathode arc techniques', NULL);
INSERT INTO public.investigadores VALUES (5, 'Sandra Patricia Castro Narvaez', '0000-0002-2023-8935', 'Chemistry and Master of Science-Chemistry- from Universidad del Valle, a research professor with a background in the areas of analytical and instrumental chemistry. She makes contributions to the Research Group in Electrochemistry and Environment (GIEMA) in the lines of solution to environmental problems and study of new materials. In her academic-administrative journey she has contributed to the construction of high-relevance academic programs for the south of the west. Colombian stories such as a Master''s Degree in Industrial Chemistry, Microbiology, Pharmaceutical Chemistry and Veterinary Medicine. She has held management positions in the Chemistry and Dean''s program of the Faculty of Basic Sciences of the Universidad Santiago de Cali.', NULL);


--
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.lineas_investigacion VALUES (8, 'EDUMATICA', 1, 'Aplicar las Tecnologías de la Información y Comunicación (TIC) para apoyar el proceso de aprendizaje, y asá contribuir al mejoramiento de la educación en todos sus niveles.', true);
INSERT INTO public.lineas_investigacion VALUES (10, 'REDES Y TELECOMUNICACIONES', 1, 'Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisión de datos, como tambi‚n la detección de fallas dentro de una red.', true);
INSERT INTO public.lineas_investigacion VALUES (9, 'DESARROLLO DE APLICACIONES', 1, 'Desarrollar aplicaciones informáticas que respondan a las necesidades de gestión, control e intercambio de información en diversos entornos organizacionales, educativos y sociales, mediante el uso de tecnologías multiplataforma y arquitecturas orientadas a servicios, tanto en entornos locales como distribuidos.', true);
INSERT INTO public.lineas_investigacion VALUES (7, 'SISTEMAS DE INFORMACION Y MODELADO DE DATOS', 1, 'Desarrollar y gestionar sistemas de información dentro del  ámbito social. Aplicando soluciones efectivas para el uso adecuado y óptimo de los sistemas de información.', true);


--
-- Data for Name: matriz_rbac; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.matriz_rbac VALUES (1, 'Articulos', '{"crear": true, "editar": true, "auditar": true, "eliminar": true}');
INSERT INTO public.matriz_rbac VALUES (1, 'Cursos', '{"crear": true, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');


--
-- Data for Name: niveles_academicos; Type: TABLE DATA; Schema: public; Owner: miki
--

INSERT INTO public.niveles_academicos VALUES (1, 'Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', true, 1, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (3, 'Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', false, 3, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (4, 'Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', false, 4, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (2, 'Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', false, 2, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:53:32.699637');


--
-- Data for Name: notificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.notificaciones VALUES (1, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 1. El estado de la cuenta es: completamente Activa.', true, '2026-03-23 16:14:24', '2026-03-23 20:40:28');
INSERT INTO public.notificaciones VALUES (2, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 4. El estado de la cuenta es: completamente Activa.', true, '2026-03-23 20:10:36', '2026-03-23 20:40:28');
INSERT INTO public.notificaciones VALUES (3, 5, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 1. El estado de la cuenta es: completamente Activa.', true, '2026-03-23 21:42:42', '2026-03-23 21:42:42');
INSERT INTO public.notificaciones VALUES (4, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: Suspendida por completo.', true, '2026-04-02 02:13:17', '2026-04-02 02:13:17');
INSERT INTO public.notificaciones VALUES (5, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: completamente Activa.', true, '2026-04-02 02:13:22', '2026-04-02 02:13:22');
INSERT INTO public.notificaciones VALUES (6, 5, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: completamente Activa.', true, '2026-04-04 16:13:51', '2026-04-04 16:13:51');


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.password_resets VALUES (1, 'orlando5711666@gmail.com', '98ae6a718e630e4dffb2c144bbb36b095f55358c7299904798709c244db29659', '2026-09-17 00:03:43.94943', false, '2026-09-16 23:48:43.94943');
INSERT INTO public.password_resets VALUES (2, 'cepillinin@gmail.com', '7a210bb3f5bb6026643ef613f1ebe06da23ffdb0c803ef26172d2682c31ff520', '2026-10-17 00:09:23.260398', false, '2026-10-16 23:54:23.260398');


--
-- Data for Name: postulaciones_estudiantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.postulaciones_estudiantes VALUES (1, 1, 7, 'asdasdasd', 'Rechazado', '2026-09-09 23:16:44.679115', '2026-09-19 02:07:44.686527', NULL);
INSERT INTO public.postulaciones_estudiantes VALUES (2, 2, 7, '', 'Rechazado', '2026-09-18 23:33:33.698004', '2026-09-19 02:08:00.249562', '[]');


--
-- Data for Name: preferencias_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.preferencias_usuario VALUES (1, 'ocean', true);
INSERT INTO public.preferencias_usuario VALUES (3, 'sunset', true);
INSERT INTO public.preferencias_usuario VALUES (4, 'ocean', true);
INSERT INTO public.preferencias_usuario VALUES (6, 'sunset', true);


--
-- Data for Name: privilegios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.privilegios VALUES (1, 0);
INSERT INTO public.privilegios VALUES (2, 1);
INSERT INTO public.privilegios VALUES (3, 2);
INSERT INTO public.privilegios VALUES (4, 3);
INSERT INTO public.privilegios VALUES (5, 4);
INSERT INTO public.privilegios VALUES (6, 5);
INSERT INTO public.privilegios VALUES (10, 6);


--
-- Data for Name: propuestas_empresa; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.propuestas_empresa VALUES (14, 'Punto Yali', '27889926', 'Miki boss', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'vbnchngfhgjfhgj', 'aceptada', '2026-09-13 22:30:07.723734', 'Trayecto I', 'CIIDI-2026-29E32', NULL);
INSERT INTO public.propuestas_empresa VALUES (3, 'Punto Yali', '123', 'Yohan Estrada', '0416-6777467', 'yohan@gmail.com', 'facturacion', 'IAIAIAIA', 'rechazada', '2026-09-11 22:13:08.195366', NULL, '', 'CIIDI-2026-F421F');
INSERT INTO public.propuestas_empresa VALUES (5, 'Megacell', 'J-12045552-', 'Miki waza', '04121609721', 'lando1609721@gmail.com', 'inventario', 'Necesito un sistema que haga inventario', 'rechazada', '2026-08-25 17:16:56.513754', 'Trayecto II', NULL, NULL);
INSERT INTO public.propuestas_empresa VALUES (6, 'Megacell', 'J20789378', 'Miki waza', '0414-7573234', 'lando1609721@gmail.com', 'inventario', 'necesito un sistema para mi alacen', 'aceptada', '2026-09-09 21:47:51.4141', 'Trayecto I', 'CIIDI-2026-AF997', NULL);
INSERT INTO public.propuestas_empresa VALUES (7, 'Megacellll', 'J20789378', 'Miki waza', '04121609721', 'lando16097211@gmail.com', 'redes', '11111111111111', 'aceptada', '2026-09-10 23:12:34.730038', 'Trayecto I', 'CIIDI-2026-4050F', NULL);
INSERT INTO public.propuestas_empresa VALUES (8, 'Punto Yali', '27889926', 'Miki waza', '04121609721', 'orlando5711666@gmail.com', 'inventario', 'necesito ayuda con respecto a un sistema que me controle el inventario de los productos que vendo aca en el local', 'aceptada', '2026-09-13 18:02:49.521927', 'Trayecto I', 'CIIDI-2026-84BAE', NULL);
INSERT INTO public.propuestas_empresa VALUES (9, 'gregoria', '123456789', 'Miki waza', '04121609721', 'orlando5711666@gmail.com', 'facturacion', 'hola', 'aceptada', '2026-09-13 18:42:50.07038', 'Trayecto II', 'CIIDI-2026-4DDC5', NULL);
INSERT INTO public.propuestas_empresa VALUES (11, 'zambrano cell', '27889926', 'chailon', '04121609721', 'lando1609721@gmail.com', 'inventario', 'ailberth deje de robarse las pantallas pa su primo chamo', 'aceptada', '2026-09-13 18:55:13.333522', 'Trayecto III', 'CIIDI-2026-E5EAE', NULL);
INSERT INTO public.propuestas_empresa VALUES (10, 'carmencita coito', '12345567889', 'Miki boss', '04121609721', 'lando1609721@gmail.com', 'datos', 'yisus porfa ordeña a carmencita sisisisi', 'aceptada', '2026-09-13 18:53:14.455601', 'Trayecto IV', 'CIIDI-2026-D55D0', NULL);
INSERT INTO public.propuestas_empresa VALUES (4, 'Punto G De Yali', 'G-30676767-0', 'Iojan', '4147755888', 'Puntogdeyali@gmail.com', 'redes', 'Quiero un sistema de clasificacion de los pelos de mi anito riko mmm sisisii', 'aceptada', '2026-07-10 14:02:25.411413', 'Trayecto I', NULL, NULL);
INSERT INTO public.propuestas_empresa VALUES (1, 'Megacell', 'J-12045552-', 'ELLL PRIMOOOO', '04121609721', 'lando1609721@gmail.com', 'facturacion', 'NECESITAMOS UN SISTEMA PARA CLASIFICAR FEMBOY, FURROS Y KPOPERAS ', 'aceptada', '2026-07-10 00:05:36.297999', 'Trayecto I', NULL, NULL);
INSERT INTO public.propuestas_empresa VALUES (12, 'Megacell', 'J20789378', 'chailon', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'necesito un sistema para poder registrar los telefonos que estamo haciendo', 'aceptada', '2026-09-13 19:46:05.970023', 'Trayecto I', 'CIIDI-2026-F5A16', NULL);
INSERT INTO public.propuestas_empresa VALUES (13, 'Punto Yali', '20789378', 'Miki boss', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'caafagsdfddsfdsfdsf', 'aceptada', '2026-09-13 22:28:56.260924', 'Trayecto I', 'CIIDI-2026-EC9AC', NULL);
INSERT INTO public.propuestas_empresa VALUES (16, 'Megacell', '27889926', 'Miki waza', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'isuuuuuuu ordeña a carmencita', 'aceptada', '2026-09-16 23:57:18.499486', 'Trayecto I', 'CIIDI-2026-CAA12', NULL);
INSERT INTO public.propuestas_empresa VALUES (15, 'zambrano cell', '20789378', 'chailon', '04121609721', 'lando1609721@gmail.com', 'Mejora de sistema', 'ghfjhmfhjfgdjhfd', 'aceptada', '2026-09-13 22:30:21.539904', 'Trayecto I', 'CIIDI-2026-C8F0D', NULL);
INSERT INTO public.propuestas_empresa VALUES (2, '123', '123', '123', '123', '123@gmail.com', 'inventario', '123', 'aceptada', '2026-09-02 21:42:33.312089', 'Trayecto IV', NULL, NULL);


--
-- Data for Name: proyecto_carreras_vinculadas; Type: TABLE DATA; Schema: public; Owner: miki
--

INSERT INTO public.proyecto_carreras_vinculadas VALUES (189, 5, '2026-10-04 14:23:02.803018');
INSERT INTO public.proyecto_carreras_vinculadas VALUES (189, 2, '2026-10-04 14:23:02.803018');
INSERT INTO public.proyecto_carreras_vinculadas VALUES (192, 4, '2026-10-04 17:14:02.756345');
INSERT INTO public.proyecto_carreras_vinculadas VALUES (192, 2, '2026-10-04 17:14:02.756345');
INSERT INTO public.proyecto_carreras_vinculadas VALUES (200, 3, '2026-10-10 00:02:06.119834');
INSERT INTO public.proyecto_carreras_vinculadas VALUES (200, 4, '2026-10-10 00:02:06.119834');


--
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.proyecto_tutores VALUES (186, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (186, 18, 2);
INSERT INTO public.proyecto_tutores VALUES (186, 19, 4);
INSERT INTO public.proyecto_tutores VALUES (189, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (189, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (191, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (191, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (192, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (192, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (200, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (173, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (173, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (174, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (174, 18, 2);
INSERT INTO public.proyecto_tutores VALUES (174, 19, 4);
INSERT INTO public.proyecto_tutores VALUES (175, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (175, 22, 4);
INSERT INTO public.proyecto_tutores VALUES (176, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (177, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (177, 39, 4);


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_autores VALUES (200, 2);
INSERT INTO public.recurso_autores VALUES (200, 3);
INSERT INTO public.recurso_autores VALUES (200, 4);
INSERT INTO public.recurso_autores VALUES (200, 5);


--
-- Data for Name: recurso_categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_categorias VALUES (121, 18);
INSERT INTO public.recurso_categorias VALUES (121, 5);
INSERT INTO public.recurso_categorias VALUES (121, 7);
INSERT INTO public.recurso_categorias VALUES (120, 3);
INSERT INTO public.recurso_categorias VALUES (120, 13);
INSERT INTO public.recurso_categorias VALUES (120, 7);
INSERT INTO public.recurso_categorias VALUES (119, 7);
INSERT INTO public.recurso_categorias VALUES (119, 1);
INSERT INTO public.recurso_categorias VALUES (118, 7);
INSERT INTO public.recurso_categorias VALUES (118, 4);
INSERT INTO public.recurso_categorias VALUES (201, 3);
INSERT INTO public.recurso_categorias VALUES (201, 18);
INSERT INTO public.recurso_categorias VALUES (146, 3);
INSERT INTO public.recurso_categorias VALUES (144, 3);
INSERT INTO public.recurso_categorias VALUES (144, 13);
INSERT INTO public.recurso_categorias VALUES (144, 1);
INSERT INTO public.recurso_categorias VALUES (143, 3);
INSERT INTO public.recurso_categorias VALUES (143, 13);
INSERT INTO public.recurso_categorias VALUES (122, 6);
INSERT INTO public.recurso_categorias VALUES (122, 5);
INSERT INTO public.recurso_categorias VALUES (122, 7);
INSERT INTO public.recurso_categorias VALUES (122, 1);


--
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_clasificaciones VALUES (179, 9, 16);
INSERT INTO public.recurso_clasificaciones VALUES (186, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (189, 9, 22);
INSERT INTO public.recurso_clasificaciones VALUES (191, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (192, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (200, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (173, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (174, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (175, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (176, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (177, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (178, 9, 16);
INSERT INTO public.recurso_clasificaciones VALUES (180, 9, 16);


--
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_etiquetas VALUES (121, 10);
INSERT INTO public.recurso_etiquetas VALUES (120, 8);
INSERT INTO public.recurso_etiquetas VALUES (119, 3);
INSERT INTO public.recurso_etiquetas VALUES (119, 1);
INSERT INTO public.recurso_etiquetas VALUES (118, 1);
INSERT INTO public.recurso_etiquetas VALUES (201, 8);
INSERT INTO public.recurso_etiquetas VALUES (146, 10);
INSERT INTO public.recurso_etiquetas VALUES (144, 3);
INSERT INTO public.recurso_etiquetas VALUES (144, 1);
INSERT INTO public.recurso_etiquetas VALUES (143, 10);
INSERT INTO public.recurso_etiquetas VALUES (143, 1);
INSERT INTO public.recurso_etiquetas VALUES (122, 1);
INSERT INTO public.recurso_etiquetas VALUES (122, 4);


--
-- Data for Name: recurso_investigadores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_investigadores VALUES (118, 1);
INSERT INTO public.recurso_investigadores VALUES (118, 2);
INSERT INTO public.recurso_investigadores VALUES (118, 3);
INSERT INTO public.recurso_investigadores VALUES (201, 4);
INSERT INTO public.recurso_investigadores VALUES (201, 5);
INSERT INTO public.recurso_investigadores VALUES (201, 1);
INSERT INTO public.recurso_investigadores VALUES (146, 1);
INSERT INTO public.recurso_investigadores VALUES (144, 1);
INSERT INTO public.recurso_investigadores VALUES (143, 1);
INSERT INTO public.recurso_investigadores VALUES (122, 1);


--
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recursos VALUES (3, 'Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos', 2, 2026, NULL);
INSERT INTO public.recursos VALUES (4, 'Impacto del Cambio Climático en Trujillo - Parte 8', 2, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (5, 'Simulación de Cargas Estáticas en Puentes - Parte 7', 2, 2024, 'dummy.pdf');
INSERT INTO public.recursos VALUES (186, 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 'storage/documentos/pst/pst_optimizaci__n_del_sistema_de_i_1790812191_245.docx');
INSERT INTO public.recursos VALUES (9, 'Bioinformática y Análisis de ADN - Parte 5', 3, 2025, 'dummy.pdf');
INSERT INTO public.recursos VALUES (10, 'Inteligencia Artificial en Diagnóstico Médico - Parte 6', 2, 2025, 'dummy.pdf');
INSERT INTO public.recursos VALUES (11, 'Robótica Educativa para Escuelas - Parte 5', 3, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (12, 'Software Libre para Bibliotecas - Parte 1', 3, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (13, 'E-Learning para Zonas Desfavorecidas - Parte 1', 3, 2018, 'dummy.pdf');
INSERT INTO public.recursos VALUES (14, 'Telecomunicaciones de Fibra Óptica Rural - Parte 1', 2, 2022, 'dummy.pdf');
INSERT INTO public.recursos VALUES (16, 'Criptografía Cuántica Post-RSA - Parte 7', 3, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (20, 'Inteligencia Artificial en Diagnóstico Médico - Parte 1', 3, 2020, 'dummy.pdf');
INSERT INTO public.recursos VALUES (191, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1791146605_845.docx');
INSERT INTO public.recursos VALUES (200, 'Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dassadasdasdasdassadassadasasdasdasdase Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1791604849_856.docx');
INSERT INTO public.recursos VALUES (121, 'Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473');
INSERT INTO public.recursos VALUES (120, 'Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457');
INSERT INTO public.recursos VALUES (119, 'Entorno virtual de capacitación con EOG para manipular robots asistenciales', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135');
INSERT INTO public.recursos VALUES (179, 'CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO', 1, 2026, 'storage/documentos/pst/pst_configuracion_e_implementacion_1790648366_353.pdf');
INSERT INTO public.recursos VALUES (173, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1790646177_290.docx');
INSERT INTO public.recursos VALUES (174, 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 'storage/documentos/pst/pst_optimizaci__n_del_sistema_de_i_1790646177_402.docx');
INSERT INTO public.recursos VALUES (175, 'MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ', 1, 2026, 'storage/documentos/pst/pst_m__dulo_inteligente_basado_en__1790646177_217.docx');
INSERT INTO public.recursos VALUES (176, 'Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1790646177_161.docx');
INSERT INTO public.recursos VALUES (177, 'Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz', 1, 2026, 'storage/documentos/pst/pst_sistema_de_optimizaci__n_basad_1790646177_802.docx');
INSERT INTO public.recursos VALUES (178, 'DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL', 1, 2026, 'storage/documentos/pst/pst_dise__o_y_prototipo_de_una_apl_1790648365_813.pdf');
INSERT INTO public.recursos VALUES (180, 'Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda ''Tuti'' del Cantón Vinces', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_cliente_servid_1790648366_463.pdf');
INSERT INTO public.recursos VALUES (189, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1791138182_671.docx');
INSERT INTO public.recursos VALUES (192, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1791148442_219.docx');
INSERT INTO public.recursos VALUES (118, 'Middleware MiSCi para ciudades inteligentes extendido con datos enlazados', 3, 2020, 'https://revistas.unal.edu.co/index.php/dyna/article/view/83226');
INSERT INTO public.recursos VALUES (201, 'Distribución de perfiles y macropartículas en recubrimientos obtenidos por arco catódico continúo utilizando un concentrador magnético recto', 3, 2026, 'https://doi.org/10.15446/dyna.v87n214.83929');
INSERT INTO public.recursos VALUES (146, 'Modelamiento de confort adaptativo para un trapiche panelero', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/112625/91645');
INSERT INTO public.recursos VALUES (144, 'Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124428/98826');
INSERT INTO public.recursos VALUES (143, 'Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124890/98825');
INSERT INTO public.recursos VALUES (122, 'Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474');


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.registro_actividad VALUES (1, 1, NULL, '2026-03-23 14:49:58', '2026-03-23 14:49:58', 1);
INSERT INTO public.registro_actividad VALUES (3, 12, NULL, '2026-09-19 14:19:44.068925', '2026-09-19 14:20:25.834661', 2);
INSERT INTO public.registro_actividad VALUES (4, 17, NULL, '2026-09-19 14:57:43.371674', '2026-09-19 14:58:00.847148', 2);
INSERT INTO public.registro_actividad VALUES (5, 14, NULL, '2026-10-12 14:35:51.862914', '2026-10-15 22:49:13.185059', 3);
INSERT INTO public.registro_actividad VALUES (6, 22, NULL, '2026-10-15 22:01:05.388022', '2026-10-15 22:52:12.025006', 2);
INSERT INTO public.registro_actividad VALUES (2, 7, NULL, '2026-09-19 13:23:18.589777', '2026-10-16 23:58:18.16393', 64);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (1, 'Super Administrador', 1);
INSERT INTO public.roles VALUES (3, 'Estudiantes', 6);
INSERT INTO public.roles VALUES (2, 'Comité', 2);
INSERT INTO public.roles VALUES (4, 'Docentes', 3);


--
-- Data for Name: system_audit_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.system_audit_log VALUES ('log_6aae33579b38a', '2026-09-19 07:01:43', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', 'GENESIS_CIIDI_V1', '7ce1ff0dbf2ae1f6bcfd13e8e1f43e3f22eb9785c9790be455571ddfbc027aa5');
INSERT INTO public.system_audit_log VALUES ('log_6aae335f0804a', '2026-09-19 07:01:51', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', '7ce1ff0dbf2ae1f6bcfd13e8e1f43e3f22eb9785c9790be455571ddfbc027aa5', 'b92bffbeb139d260065cf1393677605a9164f8c200f4265b76d6a17ce53a05b2');
INSERT INTO public.system_audit_log VALUES ('log_6aae3381958c6', '2026-09-19 07:02:25', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', 'b92bffbeb139d260065cf1393677605a9164f8c200f4265b76d6a17ce53a05b2', '92bd5c01e500270acb0577a629f1ba38e01030a6c8f48011175fe43db49f11da');
INSERT INTO public.system_audit_log VALUES ('log_6aae33853f3ff', '2026-09-19 07:02:29', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', '92bd5c01e500270acb0577a629f1ba38e01030a6c8f48011175fe43db49f11da', '10728c9fbed4aaed7081884890652f4a51ca7732afdf7452bb54491753b978b8');
INSERT INTO public.system_audit_log VALUES ('log_6aae346b666a8', '2026-09-19 07:06:19', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_06-32-52.sql.gz', 'Miguel González (ID: 7)', '::1', '10728c9fbed4aaed7081884890652f4a51ca7732afdf7452bb54491753b978b8', '7165f8e5f3d33d1d9c132ee3905aaa19e0b505521014637fd448096702e3f29b');
INSERT INTO public.system_audit_log VALUES ('log_6aae346e42a0f', '2026-09-19 07:06:22', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_06-32-55.sql', 'Miguel González (ID: 7)', '::1', '7165f8e5f3d33d1d9c132ee3905aaa19e0b505521014637fd448096702e3f29b', '989e826b2ae9a6fe7bd9aaa9edc5514dbe52f58059ddd0041facdb691b27f740');
INSERT INTO public.system_audit_log VALUES ('log_6aae3470d43bb', '2026-09-19 07:06:24', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-19_07-06-24.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '989e826b2ae9a6fe7bd9aaa9edc5514dbe52f58059ddd0041facdb691b27f740', '63aac83ff563a917cade4fa0383272872eb32923d19eda5ce4f53e737d20e5b0');
INSERT INTO public.system_audit_log VALUES ('log_6aaebe659d9cc', '2026-09-19 16:55:01', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '63aac83ff563a917cade4fa0383272872eb32923d19eda5ce4f53e737d20e5b0', '349aec4bff15575b26a32e4e827f5fa0b09c9c67540455b521bc655719099d4b');
INSERT INTO public.system_audit_log VALUES ('log_6aaebe6f1390f', '2026-09-19 16:55:11', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-09-19_16-54-59.sql.gz', 'Miguel González (ID: 7)', '::1', '349aec4bff15575b26a32e4e827f5fa0b09c9c67540455b521bc655719099d4b', '801ddd3377f74f80faf0d829ed9a4936124a1fa57770ffe290c6644645fea72c');
INSERT INTO public.system_audit_log VALUES ('log_6aaebe75516f3', '2026-09-19 16:55:17', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-19_16-55-16.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '801ddd3377f74f80faf0d829ed9a4936124a1fa57770ffe290c6644645fea72c', 'b8aeed769123fca37fbec08b1a4ce40e9b6000dd7b4433dba98361d52cb82b53');
INSERT INTO public.system_audit_log VALUES ('log_6aaec0c63a16d', '2026-09-19 17:05:10', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_07-06-27.sql', 'Miguel González (ID: 7)', '::1', 'b8aeed769123fca37fbec08b1a4ce40e9b6000dd7b4433dba98361d52cb82b53', 'ac1dc2a4b53a1009983ef217a2f5cf8cb889ec700941cf1d5bc8911706c84f5b');
INSERT INTO public.system_audit_log VALUES ('log_6aaec0c9790a9', '2026-09-19 17:05:13', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_16-55-16.sql', 'Miguel González (ID: 7)', '::1', 'ac1dc2a4b53a1009983ef217a2f5cf8cb889ec700941cf1d5bc8911706c84f5b', 'dd3b69603536d8045b7c4ee32c271343ebfe05ecf606e4d214daf7bbe79c8ed3');
INSERT INTO public.system_audit_log VALUES ('log_6aaec0cea62f3', '2026-09-19 17:05:18', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-19_17-05-18.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', 'dd3b69603536d8045b7c4ee32c271343ebfe05ecf606e4d214daf7bbe79c8ed3', '65007dff8b8b666fd547968b7983bf2de71dfeadf9761976f4e2f049953aa973');
INSERT INTO public.system_audit_log VALUES ('log_6aaeca163cca5', '2026-09-19 17:44:54', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '65007dff8b8b666fd547968b7983bf2de71dfeadf9761976f4e2f049953aa973', '4586eb896d5ba339932b8a61996fcd3a3124b5071244225196efca54a791511b');
INSERT INTO public.system_audit_log VALUES ('log_6aaecf3f2e00d', '2026-09-19 18:06:55', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '4586eb896d5ba339932b8a61996fcd3a3124b5071244225196efca54a791511b', '8ed5ed8574e2e69fa95fd4553f8bae317f6d1e13610566c143f179c7792cfbc2');
INSERT INTO public.system_audit_log VALUES ('log_6aaecf464d72a', '2026-09-19 18:07:02', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', '8ed5ed8574e2e69fa95fd4553f8bae317f6d1e13610566c143f179c7792cfbc2', 'd337a13650b0f0a06ba7fd0b389310e945347643acc48af1cd935c86e8aa96a2');
INSERT INTO public.system_audit_log VALUES ('log_6aaed1b55d6b7', '2026-09-19 18:17:25', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', 'd337a13650b0f0a06ba7fd0b389310e945347643acc48af1cd935c86e8aa96a2', 'e7a9c3adc156c3bf8c972ed917cc540d8b56f0cc1b13eecf59feafe3ac16169f');
INSERT INTO public.system_audit_log VALUES ('log_6aaed233578db', '2026-09-19 18:19:31', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (ANDRUS). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'e7a9c3adc156c3bf8c972ed917cc540d8b56f0cc1b13eecf59feafe3ac16169f', 'f21c7a96beef9a891da0ce2d7a2b30973006c2d7442c23b8cf409bd795beeb24');
INSERT INTO public.system_audit_log VALUES ('log_6aaed96f372d3', '2026-09-19 18:50:23', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', 'f21c7a96beef9a891da0ce2d7a2b30973006c2d7442c23b8cf409bd795beeb24', 'c4db66aeafb4bab24307351232fe9c4c63483667607bdb6a29acf0d6f47e2f5a');
INSERT INTO public.system_audit_log VALUES ('log_6aaedabf05687', '2026-09-19 18:55:59', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #12 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', 'c4db66aeafb4bab24307351232fe9c4c63483667607bdb6a29acf0d6f47e2f5a', 'bb6acf5c46a0c61371a549aa4812747848aef5099d21cccd226e747f9980f0a2');
INSERT INTO public.system_audit_log VALUES ('log_6aaedb9bdf105', '2026-09-19 18:59:39', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (adru). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'bb6acf5c46a0c61371a549aa4812747848aef5099d21cccd226e747f9980f0a2', '5aa477e581f9db3485b2cf6b648fa17766329c7ed071923f65ad5e9e1b8dab9c');
INSERT INTO public.system_audit_log VALUES ('log_6aaedc24ecf08', '2026-09-19 19:01:56', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 10060 Additional SMTP info: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'Miguel González (ID: 7)', '::1', '5aa477e581f9db3485b2cf6b648fa17766329c7ed071923f65ad5e9e1b8dab9c', 'eebe886d838449eb8d4f3e270c5e1f7f4e4a6b57b3111486e3ecddb6fd7b632b');
INSERT INTO public.system_audit_log VALUES ('log_6aaedeb86eab2', '2026-09-19 19:12:56', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 10060 Additional SMTP info: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'Miguel González (ID: 7)', '::1', 'eebe886d838449eb8d4f3e270c5e1f7f4e4a6b57b3111486e3ecddb6fd7b632b', 'bed82f32e49dcb86f4e77232b6a578ef769f68953bbc1a19fe960dc00e1e44fe');
INSERT INTO public.system_audit_log VALUES ('log_6aaededc7c8cf', '2026-09-19 19:13:32', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 12000000 (DIOSs). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'bed82f32e49dcb86f4e77232b6a578ef769f68953bbc1a19fe960dc00e1e44fe', '242c4f4c685154d95531fb44b69690f12783afa64a474e79cbc38dee11698f99');
INSERT INTO public.system_audit_log VALUES ('log_6aaedee65ff6d', '2026-09-19 19:13:42', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #15 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', '242c4f4c685154d95531fb44b69690f12783afa64a474e79cbc38dee11698f99', '8264b8ad8fb7df55feea033a101e69b801e8296607aae5c606ff15eb32d51381');
INSERT INTO public.system_audit_log VALUES ('log_6aaee02b5ef05', '2026-09-19 19:19:07', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (adruss). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '8264b8ad8fb7df55feea033a101e69b801e8296607aae5c606ff15eb32d51381', '2bf175c5db6f82acf3aff448d875d6d13638f66295bb93f44f2aa5e2273f9270');
INSERT INTO public.system_audit_log VALUES ('log_6aaee0323afa9', '2026-09-19 19:19:14', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (adruss). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '2bf175c5db6f82acf3aff448d875d6d13638f66295bb93f44f2aa5e2273f9270', '507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80');
INSERT INTO public.system_audit_log VALUES ('log_6ab593eecb01f4.27328092', '2026-09-24 17:19:42', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80', 'c164b6c65778035a0544ddb503089c9730110a259266cd6a181feca17389ef54');
INSERT INTO public.system_audit_log VALUES ('log_6ab59ed3238506.36285869', '2026-09-24 18:06:11', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'c164b6c65778035a0544ddb503089c9730110a259266cd6a181feca17389ef54', '5bf4ddbf99d32f7e140d3b192d4539f00d0960d7ab5346e0a2ad8a7e4cd1a528');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a4aa2a2137.51481159', '2026-09-24 18:31:06', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '5bf4ddbf99d32f7e140d3b192d4539f00d0960d7ab5346e0a2ad8a7e4cd1a528', '6fc073f5d96e828c0caa98a5e7e123baf85a48f4466c139de387b3095f1956b0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a500ea1546.02583757', '2026-09-24 18:32:32', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '6fc073f5d96e828c0caa98a5e7e123baf85a48f4466c139de387b3095f1956b0', 'b7fbdef23f8d6bb1d3061ceb485e49318baa3d66380ca33458a925158a5b8f44');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5088d0500.29959545', '2026-09-24 18:32:40', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'b7fbdef23f8d6bb1d3061ceb485e49318baa3d66380ca33458a925158a5b8f44', '38ff319019928760775a7f24e568b701c723e6b79d731daa65044eda6c34c9fd');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a541bc6647.30407607', '2026-09-24 18:33:37', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '38ff319019928760775a7f24e568b701c723e6b79d731daa65044eda6c34c9fd', '9454cd2a733a389f806ed252c25b8a8cf27aa9101af2e61957c0c29ee9356dc8');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5cb411a84.57589918', '2026-09-24 18:35:55', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Usuario'' (ID: 0) cerró su sesión voluntariamente.', 'Anónimo / Sistema', '::1', '9454cd2a733a389f806ed252c25b8a8cf27aa9101af2e61957c0c29ee9356dc8', 'e38927a84ac7e9f65f483b42c161f5b4574f456b72cf20814b05ad3708d1eee8');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5d5d60719.47566487', '2026-09-24 18:36:05', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e38927a84ac7e9f65f483b42c161f5b4574f456b72cf20814b05ad3708d1eee8', '31bc726932df4ce9990bbe301fdf6945e15265649586daa966ea1a7a2d453e95');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5e98d5860.20797335', '2026-09-24 18:36:25', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '31bc726932df4ce9990bbe301fdf6945e15265649586daa966ea1a7a2d453e95', '8c93ada41b8a0a4c4cbf71ef039250a0001677c7225a3198cdfd18a416526ee3');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a60fd00c08.81125505', '2026-09-24 18:37:03', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '127.0.0.1', '8c93ada41b8a0a4c4cbf71ef039250a0001677c7225a3198cdfd18a416526ee3', '8452ae821eccf561e7e67876865e329fa67968fbb2e1a8220958762f617e3b05');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a69a3cf6f8.92796130', '2026-09-24 18:39:22', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '8452ae821eccf561e7e67876865e329fa67968fbb2e1a8220958762f617e3b05', '37031ecbe7b940a8c14a78f7fdda5bf094034df27b27a174acac85c783d9dfa5');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ab824ef6e1.77162492', '2026-09-24 19:00:18', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '37031ecbe7b940a8c14a78f7fdda5bf094034df27b27a174acac85c783d9dfa5', '17d6aa88a0e0810aa856b6105a92fe3f087c1757891a5d3b4f0acd5b9b69fa53');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ab9f7dc311.02229887', '2026-09-24 19:00:47', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '17d6aa88a0e0810aa856b6105a92fe3f087c1757891a5d3b4f0acd5b9b69fa53', '94bce1c39ee0d7681a12dc3ad0df642b46f235ebebbcb4eec5a564e852e34208');
INSERT INTO public.system_audit_log VALUES ('log_6ab5abba3afc76.06917239', '2026-09-24 19:01:14', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '94bce1c39ee0d7681a12dc3ad0df642b46f235ebebbcb4eec5a564e852e34208', '1243582a67b2ecea0b004d6391de77c30754d3fcfb77676b9698dc39fe9673b6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c20c587658.74181273', '2026-09-24 20:36:28', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '1243582a67b2ecea0b004d6391de77c30754d3fcfb77676b9698dc39fe9673b6', 'fc38857a09d1a7bd5ed9ed7ba12ce07679dfc9f56e18fcd7f6ed30b6ca0e8e82');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c2119c0646.75356991', '2026-09-24 20:36:33', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'fc38857a09d1a7bd5ed9ed7ba12ce07679dfc9f56e18fcd7f6ed30b6ca0e8e82', '45326978d7906c59688ecb52d4e1ea7cc6dc9129a36b8e9c5debb3d80dbcf3f7');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c23b56c262.01662172', '2026-09-24 20:37:15', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '45326978d7906c59688ecb52d4e1ea7cc6dc9129a36b8e9c5debb3d80dbcf3f7', '191fffdc6aa24c48338298ac5ad880071b659bcbc79cf83ebff05c0747864687');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c497d80b16.73582852', '2026-09-24 20:47:19', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '191fffdc6aa24c48338298ac5ad880071b659bcbc79cf83ebff05c0747864687', '555abac41003b5a2947ec3014614af60f786bf0292dfd73d85650d886d18844d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c49c012169.70473067', '2026-09-24 20:47:24', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '555abac41003b5a2947ec3014614af60f786bf0292dfd73d85650d886d18844d', 'b9314cb716a43f7707854c1af03db59af5aa4bb81c2b64429ed47a3c495fbf91');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d571944a33.87726796', '2026-09-24 21:59:13', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'b9314cb716a43f7707854c1af03db59af5aa4bb81c2b64429ed47a3c495fbf91', 'e953103b3232f485fa1e16f24bb819ec4626b9320e442da74400cbc010baf895');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dcb5b7e0d3.81594761', '2026-09-24 22:30:13', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'e953103b3232f485fa1e16f24bb819ec4626b9320e442da74400cbc010baf895', '60e6575d0765725a787ea20cea31a83593ffa9e0bfe19db7ae4b63b9ef27cf63');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dd88ba4952.54635639', '2026-09-24 22:33:44', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (325.13ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '60e6575d0765725a787ea20cea31a83593ffa9e0bfe19db7ae4b63b9ef27cf63', '46b0944850e1fef0f240c5a7219d7a3f5335a8d6cce0a194abae967643177305');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ebe769d5f8.32585648', '2026-09-24 23:35:03', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '46b0944850e1fef0f240c5a7219d7a3f5335a8d6cce0a194abae967643177305', 'bb0ca07139723430c908a6cd1ed96d8b4f945c5a438c702aed8a3d2350000248');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ebef7dc0c5.68948338', '2026-09-24 23:35:11', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'bb0ca07139723430c908a6cd1ed96d8b4f945c5a438c702aed8a3d2350000248', 'a73d37a7459c32144e7987adba6921cadd1b2558025f886bd1d893c457956e20');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ee9f6acaa8.80378739', '2026-09-24 23:46:39', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-20_21-00-03.sql', 'Miguel González (ID: 7)', '::1', 'a73d37a7459c32144e7987adba6921cadd1b2558025f886bd1d893c457956e20', '9f9da3cf8a845ed5fcaf7268b81b7c80432508bafaeaa45b8ec077f593497209');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eea5350548.04228995', '2026-09-24 23:46:45', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-23_12-31-32.sql', 'Miguel González (ID: 7)', '::1', '9f9da3cf8a845ed5fcaf7268b81b7c80432508bafaeaa45b8ec077f593497209', 'a52bae63cf20b2bb0509c95ed50717e786d6f5e6ec4be25e6cf3fe2a0b6a9698');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eea88755c2.71231592', '2026-09-24 23:46:48', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-09-24_17-19-41.sql.gz', 'Miguel González (ID: 7)', '::1', 'a52bae63cf20b2bb0509c95ed50717e786d6f5e6ec4be25e6cf3fe2a0b6a9698', 'a3c336121c91834c62dc07058358c7d24f3677b6a89f6829aea0bd9e8d8df8b0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eeabaf89e3.77502777', '2026-09-24 23:46:51', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-24_17-02-25.sql.gz', 'Miguel González (ID: 7)', '::1', 'a3c336121c91834c62dc07058358c7d24f3677b6a89f6829aea0bd9e8d8df8b0', '523cc34236028597efe5b15ef29178567ee028fa9e38740d2b53f9fd940f1923');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eeb4554ee6.59306678', '2026-09-24 23:47:00', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-24_23-46-59.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '523cc34236028597efe5b15ef29178567ee028fa9e38740d2b53f9fd940f1923', '7232d7def5c5964dd8ce0ab90266e5e4d20dc4eef27958ff112ba3a87c3a9704');
INSERT INTO public.system_audit_log VALUES ('log_6ab610b6a541b2.81849834', '2026-09-25 02:12:06', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-24_23-46-59.sql', 'Miguel González (ID: 7)', '::1', '7232d7def5c5964dd8ce0ab90266e5e4d20dc4eef27958ff112ba3a87c3a9704', '6d353e52b1959ac5aa133154c91ef17dd24d549557879faf8dd1c7d30967e47a');
INSERT INTO public.system_audit_log VALUES ('log_6ab70b912729a0.27256257', '2026-09-25 20:02:25', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '6d353e52b1959ac5aa133154c91ef17dd24d549557879faf8dd1c7d30967e47a', '3ceaaa8c731c76398df4a18c9ae76abf66a21c547258ca8e51edf2107d5a7f69');
INSERT INTO public.system_audit_log VALUES ('log_6ab71c65db02e8.54816092', '2026-09-25 21:14:13', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '3ceaaa8c731c76398df4a18c9ae76abf66a21c547258ca8e51edf2107d5a7f69', '19bc814ad5a3bff737006cf37e30277339a6d5b34d67381736171f5b887f773b');
INSERT INTO public.system_audit_log VALUES ('log_6ab71c756d2f31.50517848', '2026-09-25 21:14:29', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '19bc814ad5a3bff737006cf37e30277339a6d5b34d67381736171f5b887f773b', 'd0054623e3832e8ed7ad40d95985631e6ae2a64f029815c91255079667ea855a');
INSERT INTO public.system_audit_log VALUES ('log_6ab73a17887213.71832384', '2026-09-25 23:20:55', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd0054623e3832e8ed7ad40d95985631e6ae2a64f029815c91255079667ea855a', '42c1e4f565c91c4d7938aba471fbf1c43347605917a7607e17c15a8d0d2a552f');
INSERT INTO public.system_audit_log VALUES ('log_6ab73a1baa8920.77773463', '2026-09-25 23:20:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '42c1e4f565c91c4d7938aba471fbf1c43347605917a7607e17c15a8d0d2a552f', 'ffc3b2237e5faad0da47411bbe86443cf108296dc97d737d4b495a4a5939876e');
INSERT INTO public.system_audit_log VALUES ('log_6ab73d5913fb96.78987378', '2026-09-25 23:34:49', 'WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.', 'Miguel González (ID: 7)', '::1', 'ffc3b2237e5faad0da47411bbe86443cf108296dc97d737d4b495a4a5939876e', 'c960f6fc55094e10a9011cb694c74c94afbf492b93d046f41840f2b24554d3d4');
INSERT INTO public.system_audit_log VALUES ('log_6ab73fd9a749b1.66865439', '2026-09-25 23:45:29', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'' (ID: #159).', 'Miguel González (ID: 7)', '::1', 'c960f6fc55094e10a9011cb694c74c94afbf492b93d046f41840f2b24554d3d4', '0d39d3bcaa02c78214fe24d24f809e7742b14ab64c323cbde5f5a72bd1146307');
INSERT INTO public.system_audit_log VALUES ('log_6ab743ce003df8.01116084', '2026-09-26 00:02:22', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”'' (ID: #162).', 'Miguel González (ID: 7)', '::1', '0d39d3bcaa02c78214fe24d24f809e7742b14ab64c323cbde5f5a72bd1146307', '68b097b9624c380e488f522ccafdca642924cd727daab87fe4d5b9da4fa91757');
INSERT INTO public.system_audit_log VALUES ('log_6ab74400d95f48.22006442', '2026-09-26 00:03:12', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #163).', 'Miguel González (ID: 7)', '::1', '68b097b9624c380e488f522ccafdca642924cd727daab87fe4d5b9da4fa91757', 'eb4e28a14e71284e091db7c447eb543f50bcd752b5c7b9f21403c694654e44d5');
INSERT INTO public.system_audit_log VALUES ('log_6ab74420405498.05037611', '2026-09-26 00:03:44', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'eb4e28a14e71284e091db7c447eb543f50bcd752b5c7b9f21403c694654e44d5', 'f136fc7448f2920f72d994f3fd0f0d60d52ee7282ada3053ca62dfd8760d480b');
INSERT INTO public.system_audit_log VALUES ('log_6ab74424edf762.62531298', '2026-09-26 00:03:48', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f136fc7448f2920f72d994f3fd0f0d60d52ee7282ada3053ca62dfd8760d480b', '30af9e6fd073985bd4cc0923642e22bc4cc05079c8a027538fa1cc7757589670');
INSERT INTO public.system_audit_log VALUES ('log_6ab8130a6ed6c0.41201158', '2026-09-26 14:46:34', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '30af9e6fd073985bd4cc0923642e22bc4cc05079c8a027538fa1cc7757589670', '18c36835c1751b0f7fa3127da03de112da77a3cb38cf3c98ed1952d0e4300a26');
INSERT INTO public.system_audit_log VALUES ('log_6ab813205a1172.16639687', '2026-09-26 14:46:56', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '18c36835c1751b0f7fa3127da03de112da77a3cb38cf3c98ed1952d0e4300a26', 'b8f63e54c128cb9eb0f4f85a2d77b85f59d2ace3cc93d5d3f192e71cb83b51e4');
INSERT INTO public.system_audit_log VALUES ('log_6ab814627139a2.17495957', '2026-09-26 14:52:18', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'b8f63e54c128cb9eb0f4f85a2d77b85f59d2ace3cc93d5d3f192e71cb83b51e4', '07c9398c309fe1189fbba5a31d5944480c7a02f5d57cd58e90a10f206b68174b');
INSERT INTO public.system_audit_log VALUES ('log_6ab850ed39c7d0.55124032', '2026-09-26 19:10:37', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '07c9398c309fe1189fbba5a31d5944480c7a02f5d57cd58e90a10f206b68174b', 'a6a77e8e1e03eb61c49e509b71466de7e2e0c454f653685dd01420cab0422813');
INSERT INTO public.system_audit_log VALUES ('log_6ab851e5cd0946.14674728', '2026-09-26 19:14:45', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'a6a77e8e1e03eb61c49e509b71466de7e2e0c454f653685dd01420cab0422813', 'c2aaf13c9fdc6d10f980ee7132d5cfdb3af6c4555c63d983b9e7ba54a043805e');
INSERT INTO public.system_audit_log VALUES ('log_6ab851e7c0e2d4.82296632', '2026-09-26 19:14:47', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'c2aaf13c9fdc6d10f980ee7132d5cfdb3af6c4555c63d983b9e7ba54a043805e', '0eb362f61efdb465cc9f53731f0bced200777c9fa3fbcbff72b6a6536c989d5b');
INSERT INTO public.system_audit_log VALUES ('log_6ab851e94472b4.95421959', '2026-09-26 19:14:49', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '0eb362f61efdb465cc9f53731f0bced200777c9fa3fbcbff72b6a6536c989d5b', 'fdde9b686d8461b8c7eff092fc68c75d2f00b11e8dc62df98778e917985fc681');
INSERT INTO public.system_audit_log VALUES ('log_6ab851ea611c46.80912087', '2026-09-26 19:14:50', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'fdde9b686d8461b8c7eff092fc68c75d2f00b11e8dc62df98778e917985fc681', '0e0d6e08481995b3601f9098955eaae5e0196016bed172257809748fc4e548dd');
INSERT INTO public.system_audit_log VALUES ('log_6ab852ab554c79.73686163', '2026-09-26 19:18:03', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '0e0d6e08481995b3601f9098955eaae5e0196016bed172257809748fc4e548dd', '1fd667734c8070fcebb5c77e7e9b813f652f218e5dadc5c2c4b39e0f23258136');
INSERT INTO public.system_audit_log VALUES ('log_6ab852adc206f8.64334637', '2026-09-26 19:18:05', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '1fd667734c8070fcebb5c77e7e9b813f652f218e5dadc5c2c4b39e0f23258136', '3493dc92f747c8a3faf8c2fb3026078e60f6ad3fc024219b55cc05a11cb8e9b3');
INSERT INTO public.system_audit_log VALUES ('log_6ab852bb98a3d2.31949010', '2026-09-26 19:18:19', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '3493dc92f747c8a3faf8c2fb3026078e60f6ad3fc024219b55cc05a11cb8e9b3', 'ddd3f69e0146a824b9a9a3bcc61ad121652be7ae9bcb2bd65d4cf69b3b2d672c');
INSERT INTO public.system_audit_log VALUES ('log_6ab852c6e3d2d4.83193988', '2026-09-26 19:18:30', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', 'ddd3f69e0146a824b9a9a3bcc61ad121652be7ae9bcb2bd65d4cf69b3b2d672c', 'cc429cb286befeaffd47be2ceb15426c4eab711202574c1f064396b5ab110cfd');
INSERT INTO public.system_audit_log VALUES ('log_6abaf653d69cd4.91449578', '2026-09-28 19:20:51', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Usuario'' (ID: 7) cerró su sesión voluntariamente.', 'Anónimo / Sistema', '::1', 'cc429cb286befeaffd47be2ceb15426c4eab711202574c1f064396b5ab110cfd', '9d15d0848ee574955f81b62cfe73300491c3622e1a835114176b4ad752b5c3ec');
INSERT INTO public.system_audit_log VALUES ('log_6abaf659e980f7.77464928', '2026-09-28 19:20:57', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9d15d0848ee574955f81b62cfe73300491c3622e1a835114176b4ad752b5c3ec', 'de77e14ce29523c74199fa1dcf4b87ee14fe373a72562fe48b0cb40c46bcbada');
INSERT INTO public.system_audit_log VALUES ('log_6abb00739e9ad6.86798317', '2026-09-28 20:04:03', 'INFO', 'SuperAdmin', 'Configurar SMTP', 'Se actualizaron las credenciales del servidor SMTP institucional (cifrado AES-256).', 'Miguel González (ID: 7)', '::1', 'de77e14ce29523c74199fa1dcf4b87ee14fe373a72562fe48b0cb40c46bcbada', 'ff666bf04da76abc79d4ec5278eeb9bd0cb08b2f3caa283b4184ddc025183cc1');
INSERT INTO public.system_audit_log VALUES ('log_6abb0086ba4219.64363834', '2026-09-28 20:04:22', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed [Diagnóstico: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed]', 'Miguel González (ID: 7)', '::1', 'ff666bf04da76abc79d4ec5278eeb9bd0cb08b2f3caa283b4184ddc025183cc1', '5e7e31fab63291ba85a7149ec204321e87b8aefe7183f020c769814bfc0d2863');
INSERT INTO public.system_audit_log VALUES ('log_6abb009e0fa4d4.93609207', '2026-09-28 20:04:46', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed [Diagnóstico: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed]', 'Miguel González (ID: 7)', '::1', '5e7e31fab63291ba85a7149ec204321e87b8aefe7183f020c769814bfc0d2863', 'bfe4019951599b5f3c82808567be020b337ef2e7b039403f8999419c6ac8af6c');
INSERT INTO public.system_audit_log VALUES ('log_6abb00e29e3920.44297895', '2026-09-28 20:05:54', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: Juan Salcedo (C.I: V-31008131)', 'Miguel González (ID: 7)', '::1', 'bfe4019951599b5f3c82808567be020b337ef2e7b039403f8999419c6ac8af6c', '2d4240191cdf4997d139fb807fcceb9db9aa4e38587887daeda0c1455cd27d1c');
INSERT INTO public.system_audit_log VALUES ('log_6abb0445b7fc21.13482923', '2026-09-28 20:20:21', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #163 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '2d4240191cdf4997d139fb807fcceb9db9aa4e38587887daeda0c1455cd27d1c', '5fd523a3c2f9dbef1f88eac1ca893433a412b370af4c00fac39fd8f3184f7cbd');
INSERT INTO public.system_audit_log VALUES ('log_6abb05e4488908.23013098', '2026-09-28 20:27:16', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #159 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '5fd523a3c2f9dbef1f88eac1ca893433a412b370af4c00fac39fd8f3184f7cbd', '3f73986616271bc84d541dd9d6b53fac9081a05ad63088dea2ebabecea766b52');
INSERT INTO public.system_audit_log VALUES ('log_6abb05e690a664.94528393', '2026-09-28 20:27:18', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #158 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '3f73986616271bc84d541dd9d6b53fac9081a05ad63088dea2ebabecea766b52', '1d08104cce4e68e03ea0f7ba33e87fe7dccc7ffc00f3ed5265e3468d3c07241c');
INSERT INTO public.system_audit_log VALUES ('log_6abb14f1e8b789.04226522', '2026-09-28 21:31:29', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #128 modificado exitosamente: ''Materia: Seguridad Informáticasss''.', 'Miguel González (ID: 7)', '::1', '1d08104cce4e68e03ea0f7ba33e87fe7dccc7ffc00f3ed5265e3468d3c07241c', '634b97b673c3e3ed7fcd4559b8e9ca0e9be882e1e918b160d976a8c236b70c93');
INSERT INTO public.system_audit_log VALUES ('log_6abb14fedf6bd4.59270778', '2026-09-28 21:31:42', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #128 modificado exitosamente: ''Materia: Seguridad Informáticasssssss''.', 'Miguel González (ID: 7)', '::1', '634b97b673c3e3ed7fcd4559b8e9ca0e9be882e1e918b160d976a8c236b70c93', '18da5d8afa7da284279271ca5e2de3b72bea60b4ee95f553436d20da90b2d031');
INSERT INTO public.system_audit_log VALUES ('log_6abb25014d0803.91919015', '2026-09-28 22:40:01', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (429.43ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '18da5d8afa7da284279271ca5e2de3b72bea60b4ee95f553436d20da90b2d031', '56ddc06e39d3e60e5b3e3458d0b058d8d1da4831018c18dca2f3d38433f62a38');
INSERT INTO public.system_audit_log VALUES ('log_6abb28a869ee84.05577904', '2026-09-28 22:55:36', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-28_22-55-35.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '56ddc06e39d3e60e5b3e3458d0b058d8d1da4831018c18dca2f3d38433f62a38', '36b3c082ba301b71cfbc06109248da5d301b8bea81fe68fbcc74b5451a82d769');
INSERT INTO public.system_audit_log VALUES ('log_6abb2e572ef199.35321358', '2026-09-28 23:19:51', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0'' (ID: #164).', 'Miguel González (ID: 7)', '::1', '36b3c082ba301b71cfbc06109248da5d301b8bea81fe68fbcc74b5451a82d769', 'b8365716eda7ded6ad0c14f8c12745b712df2228af0a84f7a7f4dc296bbc45db');
INSERT INTO public.system_audit_log VALUES ('log_6abb3252338456.46689830', '2026-09-28 23:36:50', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #181 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'b8365716eda7ded6ad0c14f8c12745b712df2228af0a84f7a7f4dc296bbc45db', '31ae2bf7daf18c50af5e62ceb0626fd3cf93625219c360b420fa64587cac2a34');
INSERT INTO public.system_audit_log VALUES ('log_6abb32692036f8.48741519', '2026-09-28 23:37:13', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #179 modificado exitosamente: ''CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO''.', 'Miguel González (ID: 7)', '::1', '31ae2bf7daf18c50af5e62ceb0626fd3cf93625219c360b420fa64587cac2a34', '9672ec51fe00c5d5b47338dcd1316e52bef4633cf892cac735ffb004276df59c');
INSERT INTO public.system_audit_log VALUES ('log_6abb36af4e8757.34762637', '2026-09-28 23:55:27', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #182).', 'Miguel González (ID: 7)', '::1', '9672ec51fe00c5d5b47338dcd1316e52bef4633cf892cac735ffb004276df59c', 'c815718cb9c8f8da9aefc3e16ffe622a4d43246fd7c280bd9fd56703ae975299');
INSERT INTO public.system_audit_log VALUES ('log_6abb36c856a079.23449967', '2026-09-28 23:55:52', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #183).', 'Miguel González (ID: 7)', '::1', 'c815718cb9c8f8da9aefc3e16ffe622a4d43246fd7c280bd9fd56703ae975299', 'e65dbbfa927ccd7772f1b50324d809abb11293ea783176147ab1f82f55b1b0c9');
INSERT INTO public.system_audit_log VALUES ('log_6abb3c18ecb3c9.50352832', '2026-09-29 00:18:32', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #183 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'e65dbbfa927ccd7772f1b50324d809abb11293ea783176147ab1f82f55b1b0c9', '83656905f50467d5f21fbcd472bea9b4b138ef48034dbefdbeee7215d5ab33dc');
INSERT INTO public.system_audit_log VALUES ('log_6abb3c22cddb53.18114712', '2026-09-29 00:18:42', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #182 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '83656905f50467d5f21fbcd472bea9b4b138ef48034dbefdbeee7215d5ab33dc', '0e46abbec126274f43809ea281932b55be350a6112d9e75e4a37429344569a97');
INSERT INTO public.system_audit_log VALUES ('log_6abb431bd4e935.20488688', '2026-09-29 00:48:27', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #184).', 'Miguel González (ID: 7)', '::1', '0e46abbec126274f43809ea281932b55be350a6112d9e75e4a37429344569a97', '75ce1e34e0aaedcf429b6896f2693e67972de1c5edd3e3c037391776d3f4fe7e');
INSERT INTO public.system_audit_log VALUES ('log_6abb433969d1e8.66693670', '2026-09-29 00:48:57', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #184 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '75ce1e34e0aaedcf429b6896f2693e67972de1c5edd3e3c037391776d3f4fe7e', '267b97774f9b814dd108ebf82f41a462728987f6a2e4959df6b0b434bb8353d0');
INSERT INTO public.system_audit_log VALUES ('log_6abb44ac675bb9.68582634', '2026-09-29 00:55:08', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #185).', 'Miguel González (ID: 7)', '::1', '267b97774f9b814dd108ebf82f41a462728987f6a2e4959df6b0b434bb8353d0', '09ca0db4faacf65e12f4d2f75eb100ae29e6bda6d08575375e233028945e598b');
INSERT INTO public.system_audit_log VALUES ('log_6abb44b7afb3d0.87024796', '2026-09-29 00:55:19', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #185 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '09ca0db4faacf65e12f4d2f75eb100ae29e6bda6d08575375e233028945e598b', '120d7d4fe1581832c14b9fc29c958d6c132f4c37000d82d5830e6b20f0801e26');
INSERT INTO public.system_audit_log VALUES ('log_6abb4882ba9ba3.07735541', '2026-09-29 01:11:30', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-29_01-11-30.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '120d7d4fe1581832c14b9fc29c958d6c132f4c37000d82d5830e6b20f0801e26', 'f2058ac273df26e745030a1ce2a8695fcae69f6fa630d4b9cda49b230c553ea2');
INSERT INTO public.system_audit_log VALUES ('log_6abb4ffb730527.24406994', '2026-09-29 01:43:23', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (426.37ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'f2058ac273df26e745030a1ce2a8695fcae69f6fa630d4b9cda49b230c553ea2', 'ebcc36e79e70373588cbcee4429224e44fb76e7e660551b3db2aa14c8f50b405');
INSERT INTO public.system_audit_log VALUES ('log_6abb50140092f9.61063580', '2026-09-29 01:43:48', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'ebcc36e79e70373588cbcee4429224e44fb76e7e660551b3db2aa14c8f50b405', '1a18bdc61589f66e9549de22ea123e13c98c5f16cf3ca5bab04998c1ace07a06');
INSERT INTO public.system_audit_log VALUES ('log_6abb501c9cc977.29822340', '2026-09-29 01:43:56', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (446.92ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '1a18bdc61589f66e9549de22ea123e13c98c5f16cf3ca5bab04998c1ace07a06', '884f3f20fa5fcaf7a68f01db2d0644b0561fad43d906403116160d7c99d3c708');
INSERT INTO public.system_audit_log VALUES ('log_6abb502fefc296.31272536', '2026-09-29 01:44:15', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (423.66ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '884f3f20fa5fcaf7a68f01db2d0644b0561fad43d906403116160d7c99d3c708', 'a365eb9024fe26c9c16504255c0dc7af2a2460f7b26106b4829cd64d7cfbcf6c');
INSERT INTO public.system_audit_log VALUES ('log_6abb5048360852.15348832', '2026-09-29 01:44:40', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'a365eb9024fe26c9c16504255c0dc7af2a2460f7b26106b4829cd64d7cfbcf6c', 'ed1204356eb67e5f4e2f856e802b5ed4c6c9943a3ea9840fde651a113c8cd69e');
INSERT INTO public.system_audit_log VALUES ('log_6abb505267f512.32865247', '2026-09-29 01:44:50', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (542.28ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'ed1204356eb67e5f4e2f856e802b5ed4c6c9943a3ea9840fde651a113c8cd69e', '3747d1ba251fe6a955dc60588a6df3ce686547f7b9c28a955849d0e51b4e546b');
INSERT INTO public.system_audit_log VALUES ('log_6abb50563e6010.66502540', '2026-09-29 01:44:54', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Limpieza de Archivos Temporales & Caché'': ÉXITO (55.37ms): Limpieza completada. Depurados 1 archivos temporales y cachés.', 'Miguel González (ID: 7)', '::1', '3747d1ba251fe6a955dc60588a6df3ce686547f7b9c28a955849d0e51b4e546b', 'a69797197f87d22278df31f17fb95d93e33df9c7230cafe4c0ca1363a9ad638e');
INSERT INTO public.system_audit_log VALUES ('log_6abb5062f16395.04386794', '2026-09-29 01:45:06', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (441.05ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'a69797197f87d22278df31f17fb95d93e33df9c7230cafe4c0ca1363a9ad638e', '86dadae0b80c28d8c5ab08d8e51cd7d34a40795ef1e82e960c81f7abe0386761');
INSERT INTO public.system_audit_log VALUES ('log_6abb508a02b552.38635244', '2026-09-29 01:45:46', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', '86dadae0b80c28d8c5ab08d8e51cd7d34a40795ef1e82e960c81f7abe0386761', '3356d3a407fc5ccbf03ecefd5a91dccddcfb9572161e0337d6dee392c5552384');
INSERT INTO public.system_audit_log VALUES ('log_6abb50925ea7b6.59113930', '2026-09-29 01:45:54', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (404.51ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '3356d3a407fc5ccbf03ecefd5a91dccddcfb9572161e0337d6dee392c5552384', 'd7c0bd665c98d17513e911c092a3db5687e9c60ae6c980736bddb973b6308170');
INSERT INTO public.system_audit_log VALUES ('log_6abb513542c6a4.96699430', '2026-09-29 01:48:37', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''hola'' (tarea_1790660917_352) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'd7c0bd665c98d17513e911c092a3db5687e9c60ae6c980736bddb973b6308170', 'e9cf36e9dd77825062aab32a2ad46875d36f24cf249a0feb2053d86e73c7b98c');
INSERT INTO public.system_audit_log VALUES ('log_6abb513c3ca385.57807938', '2026-09-29 01:48:44', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''hola'': ERROR (440.94ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'e9cf36e9dd77825062aab32a2ad46875d36f24cf249a0feb2053d86e73c7b98c', 'd6b2b92024188706e966365749bf4f7108838a6eef0161892a199a980ee70843');
INSERT INTO public.system_audit_log VALUES ('log_6abb5398dfd1a1.13820354', '2026-09-29 01:58:48', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''hola'': ÉXITO (171.64ms): Comando ejecutado con éxito (Código 0).', 'Anónimo / Sistema', '::1', 'd6b2b92024188706e966365749bf4f7108838a6eef0161892a199a980ee70843', '7881cd7e4146b6552e161334d1fbbdf6c417606e90e12e2764c97ee27dd7e657');
INSERT INTO public.system_audit_log VALUES ('log_6abb53992629a5.36578563', '2026-09-29 01:58:49', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (187.27ms): Comando ejecutado con éxito (Código 0).', 'Anónimo / Sistema', '::1', '7881cd7e4146b6552e161334d1fbbdf6c417606e90e12e2764c97ee27dd7e657', '2ad72eb96b618d66a275db96bb5ce05d834c253ee9f5a1963391547a98c871e6');
INSERT INTO public.system_audit_log VALUES ('log_6abbc823dee0d0.34124357', '2026-09-29 10:16:03', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2ad72eb96b618d66a275db96bb5ce05d834c253ee9f5a1963391547a98c871e6', 'e20dcffe8fb3ab97e2b65f1345702ec93c6c3d800de31eab725784e04301fc15');
INSERT INTO public.system_audit_log VALUES ('log_6abbc8d0bc21f4.91419125', '2026-09-29 10:18:56', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'e20dcffe8fb3ab97e2b65f1345702ec93c6c3d800de31eab725784e04301fc15', 'd137633b21100c7211f3e9dc52b90856f1b0703d390595d463b1b92500e29868');
INSERT INTO public.system_audit_log VALUES ('log_6abbc8d41eec09.95002671', '2026-09-29 10:19:00', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'd137633b21100c7211f3e9dc52b90856f1b0703d390595d463b1b92500e29868', 'ba5e17c7ed3eb4f8a337abac7f5140aa1ae796de5f93e0885cc908c44a7edb6d');
INSERT INTO public.system_audit_log VALUES ('log_6abbc9265355d9.01353952', '2026-09-29 10:20:22', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'ba5e17c7ed3eb4f8a337abac7f5140aa1ae796de5f93e0885cc908c44a7edb6d', 'bf6cc138c0a051d2b2688c74d024b54848500f2f78867c24a6186c4b9e3a067b');
INSERT INTO public.system_audit_log VALUES ('log_6abd9d7ba34596.72048568', '2026-09-30 19:38:35', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'bf6cc138c0a051d2b2688c74d024b54848500f2f78867c24a6186c4b9e3a067b', '0e921a0506811fe414bf3286d183361e964999f81beee654bb8d515428625a40');
INSERT INTO public.system_audit_log VALUES ('log_6abda01f864d07.15614187', '2026-09-30 19:49:51', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0'' (ID: #186).', 'Miguel González (ID: 7)', '::1', '0e921a0506811fe414bf3286d183361e964999f81beee654bb8d515428625a40', 'f10750877195d170156035ff8dbba52556626024223555d34cf6af4e5649a16d');
INSERT INTO public.system_audit_log VALUES ('log_6abda27cbe1144.87841358', '2026-09-30 19:59:56', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 1.13ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', 'f10750877195d170156035ff8dbba52556626024223555d34cf6af4e5649a16d', '938d92c7add1807ae97b4ebfac0b81f0531192c1aa992958b960a45f89c65d79');
INSERT INTO public.system_audit_log VALUES ('log_6abda2f203f4c2.41849811', '2026-09-30 20:01:54', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-30_20-01-53.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '938d92c7add1807ae97b4ebfac0b81f0531192c1aa992958b960a45f89c65d79', 'f433908d6da6e28e10a57bdf951519a79d2fcc508a9ca66b583e14edae7775dc');
INSERT INTO public.system_audit_log VALUES ('log_6abda32829d946.58940367', '2026-09-30 20:02:48', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'f433908d6da6e28e10a57bdf951519a79d2fcc508a9ca66b583e14edae7775dc', '8c324541a1ad5416c1097e9ce93595abafb1b7e3db33d61ed4b173709408095b');
INSERT INTO public.system_audit_log VALUES ('log_6abda32f3a6772.91320492', '2026-09-30 20:02:55', 'CRITICAL', 'WAF_Security', 'Inyección SQL Interceptada', 'IP ::1 bloqueada por SQLi en parámetro ''password'': admin&#039; OR 1=1 --', 'Anónimo / Sistema', '::1', '8c324541a1ad5416c1097e9ce93595abafb1b7e3db33d61ed4b173709408095b', '3db074fa08be3bfd62bc5d051cc3d3473c398c1cc732816cb4611bfa1710cd31');
INSERT INTO public.system_audit_log VALUES ('log_6abda65b750c05.40259276', '2026-09-30 20:16:27', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '3db074fa08be3bfd62bc5d051cc3d3473c398c1cc732816cb4611bfa1710cd31', '47777696004338816bf2657fa77c33d9a54a540b370e9409e9d7d7454426e98e');
INSERT INTO public.system_audit_log VALUES ('log_6abda7d5a26db4.79353606', '2026-09-30 20:22:45', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '47777696004338816bf2657fa77c33d9a54a540b370e9409e9d7d7454426e98e', 'efff0eaae89f3f9ad8ed354ddd680a927b97f741671a56cd49c5096760ecb646');
INSERT INTO public.system_audit_log VALUES ('log_6ac12828467c24.98604997', '2026-10-03 12:07:04', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'efff0eaae89f3f9ad8ed354ddd680a927b97f741671a56cd49c5096760ecb646', 'edd17e1871510288f30d26b5844ed373d4bd31cae3534abb880b8e47cd6eca27');
INSERT INTO public.system_audit_log VALUES ('log_6ac12ff22e6ad2.23847671', '2026-10-03 12:40:18', 'INFO', 'Autenticacion', 'Nombre Modificado por SuperAdmin', 'El superusuario actualizó su nombre a ''Miguel Gonzálezzzzz'' (ID: 7).', 'Miguel Gonzálezzzzz (ID: 7)', '::1', 'edd17e1871510288f30d26b5844ed373d4bd31cae3534abb880b8e47cd6eca27', 'a85ba0dd3ab1ccacd5ae3daa3b432c7cf0ddf2545c65e48e19dd29e18e675197');
INSERT INTO public.system_audit_log VALUES ('log_6ac130e8c4df47.38223243', '2026-10-03 12:44:24', 'SECURITY', 'Autenticacion', 'Test Seguridad QA', 'Prueba automatizada de registro criptográfico.', 'Anónimo / Sistema', '127.0.0.1', 'a85ba0dd3ab1ccacd5ae3daa3b432c7cf0ddf2545c65e48e19dd29e18e675197', 'edc060158deb954b1e6e99fe82878294a912db597c00a7bd9263ccd95598be14');
INSERT INTO public.system_audit_log VALUES ('log_6ac130faa9ef13.77888729', '2026-10-03 12:44:42', 'SECURITY', 'Autenticacion', 'Test Seguridad QA', 'Prueba automatizada de registro criptográfico.', 'Anónimo / Sistema', '127.0.0.1', 'edc060158deb954b1e6e99fe82878294a912db597c00a7bd9263ccd95598be14', 'f8ec00caf82b79afb6e670730b18e5a26c59ca4c720615ea950e1e49d3cb930f');
INSERT INTO public.system_audit_log VALUES ('log_6ac131494b62b0.95522024', '2026-10-03 12:46:01', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel Gonzálezzzzz'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel Gonzálezzzzz (ID: 7)', '::1', 'f8ec00caf82b79afb6e670730b18e5a26c59ca4c720615ea950e1e49d3cb930f', '80b07da648fb55be759c9a831215eca6ba1e37e9ab41a3cfc850a1edb263a8f5');
INSERT INTO public.system_audit_log VALUES ('log_6ac13877298687.66874114', '2026-10-03 13:16:39', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel Gonzálezzzzz'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel Gonzálezzzzz (ID: 7)', '::1', '80b07da648fb55be759c9a831215eca6ba1e37e9ab41a3cfc850a1edb263a8f5', '1106bb48c7aa1fda3637120e7b7033df33d007656a6d5c3b1854ce09f26ff2bf');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b093afc00.43901612', '2026-10-03 13:27:37', 'INFO', 'Autenticacion', 'Nombre Modificado por SuperAdmin', 'El superusuario actualizó su nombre a ''Miguel González'' (ID: 7).', 'Miguel González (ID: 7)', '::1', '1106bb48c7aa1fda3637120e7b7033df33d007656a6d5c3b1854ce09f26ff2bf', '971473416dd92d6b1218124bfdff1002e4979709444e04e5f23b5917b233b806');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b4f07fca9.12965797', '2026-10-03 13:28:47', 'INFO', 'Autenticacion', 'Correo Modificado', 'El usuario ID #7 actualizó su correo a ''erwazaaa@gmail.com''. Se envió enlace de verificación.', 'Miguel González (ID: 7)', '::1', '971473416dd92d6b1218124bfdff1002e4979709444e04e5f23b5917b233b806', '5e43eae56a7eb996936271677ef26cf70d4dddcf03321c38717768f63c5d9171');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b60b8a348.04621923', '2026-10-03 13:29:04', 'INFO', 'Autenticacion', 'Correo Modificado', 'El usuario ID #7 actualizó su correo a ''erwesitodepollo@gmail.com''. Se envió enlace de verificación.', 'Miguel González (ID: 7)', '::1', '5e43eae56a7eb996936271677ef26cf70d4dddcf03321c38717768f63c5d9171', '1f0a3831019ccaa7e1768ccb172e8c0a8eccb51f8c224fda70c339b19787591c');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b66d6a3b3.39434338', '2026-10-03 13:29:10', 'INFO', 'Autenticacion', 'Verificación de Correo Solicitada', 'Enlace de verificación enviado a ''erwesitodepollo@gmail.com'' para usuario ID #7.', 'Miguel González (ID: 7)', '::1', '1f0a3831019ccaa7e1768ccb172e8c0a8eccb51f8c224fda70c339b19787591c', 'd3e8e61cdc7ed3368c286665a0c8c12cfb1f043628c3c295def4a93beb8bfd86');
INSERT INTO public.system_audit_log VALUES ('log_6ac13e5bc96068.22485896', '2026-10-03 13:41:47', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd3e8e61cdc7ed3368c286665a0c8c12cfb1f043628c3c295def4a93beb8bfd86', 'f704369bb897d951980def2cce3f16071cf384f9fd1bf942e0499784fdee55bf');
INSERT INTO public.system_audit_log VALUES ('log_6ac13e7ee99724.88249222', '2026-10-03 13:42:22', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f704369bb897d951980def2cce3f16071cf384f9fd1bf942e0499784fdee55bf', '9a726c985aa96ef36890ee2337389fde6cd177fc8529c430c375fdba242e76a6');
INSERT INTO public.system_audit_log VALUES ('log_6ac13e8f294e28.07445518', '2026-10-03 13:42:39', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9a726c985aa96ef36890ee2337389fde6cd177fc8529c430c375fdba242e76a6', 'e64cb0f4bb598e3241b2e47a64f9cdff9be135c76873f9bcd428c6f935848b5e');
INSERT INTO public.system_audit_log VALUES ('log_6ac13eb4a15e31.77988144', '2026-10-03 13:43:16', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'e64cb0f4bb598e3241b2e47a64f9cdff9be135c76873f9bcd428c6f935848b5e', '9d2cb788fdb2f0e33d31576f219024a72e545e40fb1dd73858838c251a9a6fbb');
INSERT INTO public.system_audit_log VALUES ('log_6ac13f64de6a85.11671560', '2026-10-03 13:46:12', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''no soy miguel'' (C.I: V-1234567).', 'Anónimo / Sistema', '::1', '9d2cb788fdb2f0e33d31576f219024a72e545e40fb1dd73858838c251a9a6fbb', '0ac0891790b300a322d0130cd3d0665818a37aa4b17c1206e84c4c9a111ac846');
INSERT INTO public.system_audit_log VALUES ('log_6ac13fc2b079e8.57780152', '2026-10-03 13:47:46', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '0ac0891790b300a322d0130cd3d0665818a37aa4b17c1206e84c4c9a111ac846', '178c45b96a140288f62d71a55703007d10e06b1cbb4590179c79589b33ff45c8');
INSERT INTO public.system_audit_log VALUES ('log_6ac13fcf644a83.29317983', '2026-10-03 13:47:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '178c45b96a140288f62d71a55703007d10e06b1cbb4590179c79589b33ff45c8', 'e8bf6c24dcef492672746f237babdb72a0cc17a3e5f2ad689aff1218e355512c');
INSERT INTO public.system_audit_log VALUES ('log_6ac1400c7d21c2.36177170', '2026-10-03 13:49:00', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'e8bf6c24dcef492672746f237babdb72a0cc17a3e5f2ad689aff1218e355512c', '21954097c4d1be1baa8bc37260d41820d40b1b14c256b57e6be6c2c98dd3841b');
INSERT INTO public.system_audit_log VALUES ('log_6ac140353a1e02.77789108', '2026-10-03 13:49:41', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12345678''.', 'Anónimo / Sistema', '::1', '21954097c4d1be1baa8bc37260d41820d40b1b14c256b57e6be6c2c98dd3841b', '2ce58546c6e534e7f65e60033a1062ffccdfc51508e62db7fabc9c5f05574a18');
INSERT INTO public.system_audit_log VALUES ('log_6ac1404be5aea6.42915402', '2026-10-03 13:50:03', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2ce58546c6e534e7f65e60033a1062ffccdfc51508e62db7fabc9c5f05574a18', '88fd9cee2a3b6cee15c71633e7255fb1e9f749165bf8b1207097963c0c7ad825');
INSERT INTO public.system_audit_log VALUES ('log_6ac140694bbc43.57753093', '2026-10-03 13:50:33', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '88fd9cee2a3b6cee15c71633e7255fb1e9f749165bf8b1207097963c0c7ad825', '45d319ee6c0208e219acc7368e6a59ef265ef7b8e296c04075d35708f0d3d48f');
INSERT INTO public.system_audit_log VALUES ('log_6ac140838b6c09.31008577', '2026-10-03 13:50:59', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '45d319ee6c0208e219acc7368e6a59ef265ef7b8e296c04075d35708f0d3d48f', 'b64d1269e34ea0db4543dabab9714b83382eb11f1b232f30231e05177a80260f');
INSERT INTO public.system_audit_log VALUES ('log_6ac140c6df7b33.58916003', '2026-10-03 13:52:06', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12345667''.', 'Anónimo / Sistema', '::1', 'b64d1269e34ea0db4543dabab9714b83382eb11f1b232f30231e05177a80260f', 'd671829d292704fa60fc8544c420c79bb5ea306bbb0a360d9d1474591e2b0616');
INSERT INTO public.system_audit_log VALUES ('log_6ac140dfba3a66.37695673', '2026-10-03 13:52:31', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12345567''.', 'Anónimo / Sistema', '::1', 'd671829d292704fa60fc8544c420c79bb5ea306bbb0a360d9d1474591e2b0616', '425b7064f6c8d97408badc8c3112cf0655cb54563c2c5f32ff9f5f541af78879');
INSERT INTO public.system_audit_log VALUES ('log_6ac1410c318ee8.12576183', '2026-10-03 13:53:16', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '425b7064f6c8d97408badc8c3112cf0655cb54563c2c5f32ff9f5f541af78879', '06ddda70b599a980f8b4fdd2526fdec2fff8b0634f55dcded7a9bc680aaf0fdd');
INSERT INTO public.system_audit_log VALUES ('log_6ac141235905d9.76423907', '2026-10-03 13:53:39', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '06ddda70b599a980f8b4fdd2526fdec2fff8b0634f55dcded7a9bc680aaf0fdd', '2915d31a89b4980dd668180e7b5535e1253b7b9c60caf6f2bd3b39de6319732d');
INSERT INTO public.system_audit_log VALUES ('log_6ac141b66375e1.41482376', '2026-10-03 13:56:06', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '2915d31a89b4980dd668180e7b5535e1253b7b9c60caf6f2bd3b39de6319732d', '05ee6ba847cc6c66940c7a4c30db8e771b3e21298acc4a3645bcf389813324a4');
INSERT INTO public.system_audit_log VALUES ('log_6ac141be53d3f9.97944045', '2026-10-03 13:56:14', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '05ee6ba847cc6c66940c7a4c30db8e771b3e21298acc4a3645bcf389813324a4', '5c87dd958af494d8e49198f1c6962dcae232bb2b33fe479bfc2be737ca15c96c');
INSERT INTO public.system_audit_log VALUES ('log_6ac141c612a8e7.73657758', '2026-10-03 13:56:22', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '5c87dd958af494d8e49198f1c6962dcae232bb2b33fe479bfc2be737ca15c96c', '9eb5ec4292371f625c4fafdb269ff5ee40341480183d55d704196a67a1d28a88');
INSERT INTO public.system_audit_log VALUES ('log_6ac14283b15563.91767580', '2026-10-03 13:59:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9eb5ec4292371f625c4fafdb269ff5ee40341480183d55d704196a67a1d28a88', '08488e8d77111cce7d7e77d79a5c85e94b2980ec3c442c5d91ecc6d7247d46e6');
INSERT INTO public.system_audit_log VALUES ('log_6ac1428d24f946.56878883', '2026-10-03 13:59:41', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '08488e8d77111cce7d7e77d79a5c85e94b2980ec3c442c5d91ecc6d7247d46e6', '26052b9f0a427ccdc83b9b209201ca182f360b393a7b5ac7e2a42b0da39023a5');
INSERT INTO public.system_audit_log VALUES ('log_6ac142965eccd4.57785406', '2026-10-03 13:59:50', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '26052b9f0a427ccdc83b9b209201ca182f360b393a7b5ac7e2a42b0da39023a5', '070d4aad6e4acc2b99a4f113d4fd943a11d1efcce282265548a2dd0674722d6b');
INSERT INTO public.system_audit_log VALUES ('log_6ac1429c97d6e1.59083773', '2026-10-03 13:59:56', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 1.64ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', '070d4aad6e4acc2b99a4f113d4fd943a11d1efcce282265548a2dd0674722d6b', 'ae8fe439a6967c852d5c4dfc38ae7bd1f4daf0cc22982ba9156b7b08088dd177');
INSERT INTO public.system_audit_log VALUES ('log_6ac142f633ae61.82043725', '2026-10-03 14:01:26', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'ae8fe439a6967c852d5c4dfc38ae7bd1f4daf0cc22982ba9156b7b08088dd177', '8baa96cd979c16422a45e56250438a1264fdc7df65e6535ff18c45c0ff5b3034');
INSERT INTO public.system_audit_log VALUES ('log_6ac14310748685.52547574', '2026-10-03 14:01:52', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '8baa96cd979c16422a45e56250438a1264fdc7df65e6535ff18c45c0ff5b3034', 'dc0e15d95a619461489b5011cb959d2f71317f90d016a7f0fcb35a436c5f59e9');
INSERT INTO public.system_audit_log VALUES ('log_6ac14351beb5d7.13362571', '2026-10-03 14:02:57', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'dc0e15d95a619461489b5011cb959d2f71317f90d016a7f0fcb35a436c5f59e9', '223b4207baab4fa533464869637b40a6ccfe0d5c8dc6551cf56dbd67d138a428');
INSERT INTO public.system_audit_log VALUES ('log_6ac143855b9691.65506208', '2026-10-03 14:03:49', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '223b4207baab4fa533464869637b40a6ccfe0d5c8dc6551cf56dbd67d138a428', '98c0243b8919aade73f84dd6114e296c5a89242eb70f91c8cab07f07ef21e100');
INSERT INTO public.system_audit_log VALUES ('log_6ac144082ef9e1.16827141', '2026-10-03 14:06:00', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '98c0243b8919aade73f84dd6114e296c5a89242eb70f91c8cab07f07ef21e100', 'd2167bf0405e407f2ab4cbb726fbec43a28fdd2103801dd12a6e3d7a4ac5a569');
INSERT INTO public.system_audit_log VALUES ('log_6ac144e48b8447.33270683', '2026-10-03 14:09:40', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd2167bf0405e407f2ab4cbb726fbec43a28fdd2103801dd12a6e3d7a4ac5a569', 'a91975ad95b8f7244cecff5d707332f4a77c34600c9675de93b4d41afa3f54da');
INSERT INTO public.system_audit_log VALUES ('log_6ac1450cb24579.82197712', '2026-10-03 14:10:20', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'a91975ad95b8f7244cecff5d707332f4a77c34600c9675de93b4d41afa3f54da', '81dd861687a81ea027b0cb5aa0cbd0706b00923bb7ff84a54bc79d9d20743bcc');
INSERT INTO public.system_audit_log VALUES ('log_6ac14681285926.78836938', '2026-10-03 14:16:33', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '81dd861687a81ea027b0cb5aa0cbd0706b00923bb7ff84a54bc79d9d20743bcc', 'a50e4258b98aad4192e1373d22d3bd7b2503370c440eeab52af0ef242aaedca0');
INSERT INTO public.system_audit_log VALUES ('log_6ac146b0ae9965.21887296', '2026-10-03 14:17:20', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'a50e4258b98aad4192e1373d22d3bd7b2503370c440eeab52af0ef242aaedca0', 'f3ce6c85bcdef9e8a2abb52e7d08d5e2a8aa92f684567a40ca066389c546845b');
INSERT INTO public.system_audit_log VALUES ('log_6ac146cc15a824.74334312', '2026-10-03 14:17:48', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f3ce6c85bcdef9e8a2abb52e7d08d5e2a8aa92f684567a40ca066389c546845b', '743d39561842c31beda2cb89af0265e2606255b7845a357de1aae58968b8fd53');
INSERT INTO public.system_audit_log VALUES ('log_6ac1492e3bc309.07890168', '2026-10-03 14:27:58', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '743d39561842c31beda2cb89af0265e2606255b7845a357de1aae58968b8fd53', '43d9e2aff5bf79925685d30111c367aa93ed5b1324f0c64d534248f186daf4dd');
INSERT INTO public.system_audit_log VALUES ('log_6ac1494129f614.76362695', '2026-10-03 14:28:17', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '43d9e2aff5bf79925685d30111c367aa93ed5b1324f0c64d534248f186daf4dd', 'e7bcb4877f35a9d28d7a22722bbbcc7a6bdc6fbbac37f448a667480ae96b252b');
INSERT INTO public.system_audit_log VALUES ('log_6ac14c4986d566.91856480', '2026-10-03 14:41:13', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e7bcb4877f35a9d28d7a22722bbbcc7a6bdc6fbbac37f448a667480ae96b252b', 'ae3228180f235b94592f0161fe27112c7cec427aa10cd39121614dafd7e724e5');
INSERT INTO public.system_audit_log VALUES ('log_6ac290242a51b7.18332813', '2026-10-04 13:43:00', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'ae3228180f235b94592f0161fe27112c7cec427aa10cd39121614dafd7e724e5', '03643a1c639caad9c33be13262c201e9c0cdcf734609a065d9af4be886764824');
INSERT INTO public.system_audit_log VALUES ('log_6ac2911ef16e10.78511822', '2026-10-04 13:47:10', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '03643a1c639caad9c33be13262c201e9c0cdcf734609a065d9af4be886764824', 'f37dff78ac16a97bef658a5f51addc2390769257cb9e9cbcbee643945264e8dc');
INSERT INTO public.system_audit_log VALUES ('log_6ac29731984260.15880698', '2026-10-04 14:13:05', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'f37dff78ac16a97bef658a5f51addc2390769257cb9e9cbcbee643945264e8dc', '0a0e6f1e32767a7d87036ecf8580409078411c882a7b8c1d149a330a5d410988');
INSERT INTO public.system_audit_log VALUES ('log_6ac297340360a6.85677947', '2026-10-04 14:13:08', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '0a0e6f1e32767a7d87036ecf8580409078411c882a7b8c1d149a330a5d410988', 'aaf1d797278c99556eaaabb42d9bba73b39d47bacea468b482045a3283570a11');
INSERT INTO public.system_audit_log VALUES ('log_6ac29734eeea69.29357751', '2026-10-04 14:13:08', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'aaf1d797278c99556eaaabb42d9bba73b39d47bacea468b482045a3283570a11', '26d3b0967555438a01f0f062b1b0794abac5124c1872e52e37a84ff2ae46f15b');
INSERT INTO public.system_audit_log VALUES ('log_6ac297391c07f7.58886272', '2026-10-04 14:13:13', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '26d3b0967555438a01f0f062b1b0794abac5124c1872e52e37a84ff2ae46f15b', 'ba643446bbe4c41bea9e4a2647725dbb473edf8f99935f2814bda222f9d54d00');
INSERT INTO public.system_audit_log VALUES ('log_6ac29986ccd537.36845720', '2026-10-04 14:23:02', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #189).', 'Miguel González (ID: 7)', '::1', 'ba643446bbe4c41bea9e4a2647725dbb473edf8f99935f2814bda222f9d54d00', 'dab79076d394b201f536cda5b59ee9b3b363430cd93b96550f6aea151824f029');
INSERT INTO public.system_audit_log VALUES ('log_6ac2b113ef3042.01181958', '2026-10-04 16:03:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'dab79076d394b201f536cda5b59ee9b3b363430cd93b96550f6aea151824f029', '9496218a32a4a0b263b92a1a6aec47922bb682d0af33b46ccb5ccee81b4f96dd');
INSERT INTO public.system_audit_log VALUES ('log_6ac2b118ce98f2.42047090', '2026-10-04 16:03:36', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9496218a32a4a0b263b92a1a6aec47922bb682d0af33b46ccb5ccee81b4f96dd', '3795a3337016be06159ef2c88700214f7ebb72f86197f74fe37728f29cd65783');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ba6db1d628.29952574', '2026-10-04 16:43:25', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #191).', 'Miguel González (ID: 7)', '::1', '3795a3337016be06159ef2c88700214f7ebb72f86197f74fe37728f29cd65783', '3d8b5474f1fb61079a3f46d09b385bc922ebe40b9dfa7505101e808a899e0a59');
INSERT INTO public.system_audit_log VALUES ('log_6ac2bc6db08428.55733278', '2026-10-04 16:51:57', 'INFO', 'RepositorioPST', 'Editar Nivel Académico', 'Se actualizó el nivel académico ID 2.', 'Miguel González (ID: 7)', '::1', '3d8b5474f1fb61079a3f46d09b385bc922ebe40b9dfa7505101e808a899e0a59', 'ecd4c736b36f877e6e53fbcc9636a1be9d7fd52fea0266a8e614f2fab88d53e0');
INSERT INTO public.system_audit_log VALUES ('log_6ac2bcccab46d8.98270583', '2026-10-04 16:53:32', 'INFO', 'RepositorioPST', 'Editar Nivel Académico', 'Se actualizó el nivel académico ID 2.', 'Miguel González (ID: 7)', '::1', 'ecd4c736b36f877e6e53fbcc9636a1be9d7fd52fea0266a8e614f2fab88d53e0', 'f0df1978205c0e3be5065a24fc9893100f220034700c1b664e05347884305804');
INSERT INTO public.system_audit_log VALUES ('log_6ac2c19ac0c805.51009240', '2026-10-04 17:14:02', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales'' (ID: #192).', 'Miguel González (ID: 7)', '::1', 'f0df1978205c0e3be5065a24fc9893100f220034700c1b664e05347884305804', '9130a1f630d54ad896f1cb6db74c8fdd7f633d2dacb86d31b8688ce38003b421');
INSERT INTO public.system_audit_log VALUES ('log_6ac2e9775836d0.04371946', '2026-10-04 20:04:07', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9130a1f630d54ad896f1cb6db74c8fdd7f633d2dacb86d31b8688ce38003b421', 'f2240b49535de448ce2d0ddc87389162d97c26a370d982cae91c1058782155b3');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ea533d0ee2.65428414', '2026-10-04 20:07:47', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'f2240b49535de448ce2d0ddc87389162d97c26a370d982cae91c1058782155b3', 'a11472988c3c54881059cd67096c54aa3245954de3294652d842b7a5f2e563e3');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ea95d8edd8.61477690', '2026-10-04 20:08:53', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'a11472988c3c54881059cd67096c54aa3245954de3294652d842b7a5f2e563e3', '5a3927efec7374bbc369174d1dcb7f62ad1fb1c387dd6f1dc9e7e3aa087dcbd5');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eade037e09.34485946', '2026-10-04 20:10:06', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '5a3927efec7374bbc369174d1dcb7f62ad1fb1c387dd6f1dc9e7e3aa087dcbd5', 'cf753724a9dd08c79e200483c0f3f13922c0c84301f2e258b33eaaf1453b3ac4');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eadecdaf28.99002115', '2026-10-04 20:10:06', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'cf753724a9dd08c79e200483c0f3f13922c0c84301f2e258b33eaaf1453b3ac4', '94a5745b202c0f0333b4da52e624ed8c6bf504828b5d6909fa17640ab6eed0ae');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eadfbca4d7.74874338', '2026-10-04 20:10:07', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '94a5745b202c0f0333b4da52e624ed8c6bf504828b5d6909fa17640ab6eed0ae', '0ebe5ab4ca10d22862aa7c9ec7694866a1c5f18e2c7d68045532c4b0d0bb165c');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eae0a654b1.94476951', '2026-10-04 20:10:08', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '0ebe5ab4ca10d22862aa7c9ec7694866a1c5f18e2c7d68045532c4b0d0bb165c', '9da52a0251efd7bb07c24cdd4da13dcba63d0c3d0b20c4fb4f9f7a9d701fb0e5');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec5601cdb9.62461585', '2026-10-04 20:16:22', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9da52a0251efd7bb07c24cdd4da13dcba63d0c3d0b20c4fb4f9f7a9d701fb0e5', '2f05425b7a9e41d4a2bf398c9c515bc76bfd453a9bf32401d4aa5aaa3ec04d9e');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec5d8bb483.27381483', '2026-10-04 20:16:29', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2f05425b7a9e41d4a2bf398c9c515bc76bfd453a9bf32401d4aa5aaa3ec04d9e', 'fc72247ad66020f08a8d2c52aa9efede5ed58fa362028152c404b26c49ad5c05');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec687a6a52.51050372', '2026-10-04 20:16:40', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'fc72247ad66020f08a8d2c52aa9efede5ed58fa362028152c404b26c49ad5c05', '855948127cbaba289450e5e763e1ff5c28f24e75120dd79ef760854988494034');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec69a91fc5.76197883', '2026-10-04 20:16:41', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '855948127cbaba289450e5e763e1ff5c28f24e75120dd79ef760854988494034', '371991b3f8bc2b0c3c2203924ae467d29338c4f934c30373591716552a4cdfb9');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec6a953041.71829326', '2026-10-04 20:16:42', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '371991b3f8bc2b0c3c2203924ae467d29338c4f934c30373591716552a4cdfb9', '916178f3021cd79d72b35126d7658a599465884928b856cf10697c6d7e62e768');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec6d032786.51154961', '2026-10-04 20:16:45', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '916178f3021cd79d72b35126d7658a599465884928b856cf10697c6d7e62e768', 'e2e5964dbd71bda9544c525c416f0bfd9d117fb372b5182418a6b241c5f2c4d1');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec262fd721.24836059', '2026-10-05 14:27:50', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e2e5964dbd71bda9544c525c416f0bfd9d117fb372b5182418a6b241c5f2c4d1', '1981741c5823b2a930ff831d43ff72ee0dd802037b433b57e897a53fab67a665');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec91266b08.85328438', '2026-10-05 14:29:37', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-10-05_14-29-36.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '1981741c5823b2a930ff831d43ff72ee0dd802037b433b57e897a53fab67a665', '3f952f8bc0c0055ff7f1b1a1f2b45d10dbd4163ab50f0897e4844c6190f8ed9d');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec98497d76.34827531', '2026-10-05 14:29:44', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-29_01-11-30.sql', 'Miguel González (ID: 7)', '::1', '3f952f8bc0c0055ff7f1b1a1f2b45d10dbd4163ab50f0897e4844c6190f8ed9d', 'f908ebf223ee2abcb7032793a1d55545ef46e0af6b8e811022828966edaff02e');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec9b89cf17.09967153', '2026-10-05 14:29:47', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-30_20-01-53.sql.gz', 'Miguel González (ID: 7)', '::1', 'f908ebf223ee2abcb7032793a1d55545ef46e0af6b8e811022828966edaff02e', 'e158d63bc615676ba7d3f81e618fb4fc76f38733455f23231616f794d178b070');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec9e2cf8c9.43547874', '2026-10-05 14:29:50', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-04_23-56-43.sql.gz', 'Miguel González (ID: 7)', '::1', 'e158d63bc615676ba7d3f81e618fb4fc76f38733455f23231616f794d178b070', '2f451489bf847d8ced60912f42a6072d58390d2affb03b022af3ab3c4effc8f9');
INSERT INTO public.system_audit_log VALUES ('log_6ac504fd9fcd12.90258639', '2026-10-06 10:26:05', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2f451489bf847d8ced60912f42a6072d58390d2affb03b022af3ab3c4effc8f9', '1f9dd73ecbb26cc6a6a47728aa1cb7bc924da7a2308951690ed04203db8ce514');
INSERT INTO public.system_audit_log VALUES ('log_6ac50b5f9de507.56076043', '2026-10-06 10:53:19', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '1f9dd73ecbb26cc6a6a47728aa1cb7bc924da7a2308951690ed04203db8ce514', '720a88401ea0dbedcfd613e884e9d3a0ac0a1d19b66c5556e5aabf775f5511c8');
INSERT INTO public.system_audit_log VALUES ('log_6ac50b75478373.62861792', '2026-10-06 10:53:41', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '720a88401ea0dbedcfd613e884e9d3a0ac0a1d19b66c5556e5aabf775f5511c8', '377a5e9353503df135791b1b62dfd2b0c113385c7d3889a8695ed99d0d31c1dd');
INSERT INTO public.system_audit_log VALUES ('log_6ac979343affe0.38714832', '2026-10-09 19:31:00', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '377a5e9353503df135791b1b62dfd2b0c113385c7d3889a8695ed99d0d31c1dd', '92d48746a48ff531888a0e2d4d9f48fcb8ae034fae23a70eb765525670cc59d9');
INSERT INTO public.system_audit_log VALUES ('log_6ac9798708df24.32604951', '2026-10-09 19:32:23', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo pre_restore_checkpoint_2026-10-09_19-32-20.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '92d48746a48ff531888a0e2d4d9f48fcb8ae034fae23a70eb765525670cc59d9', '8e95f5a92d4c11e96495d244d0fd338d8d9bd5228c1fff641a681cb23feb7bf0');
INSERT INTO public.system_audit_log VALUES ('log_6ac97987a9aad7.31544594', '2026-10-09 19:32:23', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '8e95f5a92d4c11e96495d244d0fd338d8d9bd5228c1fff641a681cb23feb7bf0', 'd9d80a66d4988e1bc0bd1a85b7ee5d60e564bd137d94e83fa7b8e28ab25f15a7');
INSERT INTO public.system_audit_log VALUES ('log_6ac9ab70a28e01.20857263', '2026-10-09 23:05:20', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'd9d80a66d4988e1bc0bd1a85b7ee5d60e564bd137d94e83fa7b8e28ab25f15a7', '9f9850a848e35e5e98d1671e0530cebf0d2bd66bd9fe6cf217d732f3c88c6a74');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b5f79df500.53282447', '2026-10-09 23:50:15', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '9f9850a848e35e5e98d1671e0530cebf0d2bd66bd9fe6cf217d732f3c88c6a74', 'c43a9049de3391783c54b1054d7df77d64d8a8dcd058af0c559ec47a294e862a');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b64962d632.68641383', '2026-10-09 23:51:37', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', 'c43a9049de3391783c54b1054d7df77d64d8a8dcd058af0c559ec47a294e862a', 'e67be910dcb20debf3341a48f440c928383d7fa260e31cc25a2d1646fe987814');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b64a16e1a9.75282571', '2026-10-09 23:51:38', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', 'e67be910dcb20debf3341a48f440c928383d7fa260e31cc25a2d1646fe987814', '1992d74b0025260870e358a8bd40477362a8bfd1d4376190dd0fa52311d973a2');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b64a320d37.17914146', '2026-10-09 23:51:38', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '1992d74b0025260870e358a8bd40477362a8bfd1d4376190dd0fa52311d973a2', '41bd184276a823a92447a856e59a19f9a9375363e923e1a067e3db2fcc25386f');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b660c4ce27.59338818', '2026-10-09 23:52:00', 'WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.', 'Miguel González (ID: 7)', '::1', '41bd184276a823a92447a856e59a19f9a9375363e923e1a067e3db2fcc25386f', '727ee25f9eb555cdd84ba8b5970462621de7bda3ca7847e91c0423dea1ff1f4f');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b67ccf2045.72642442', '2026-10-09 23:52:28', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '727ee25f9eb555cdd84ba8b5970462621de7bda3ca7847e91c0423dea1ff1f4f', '35892454cad819007e3000b92d91c6a94ec1cc50c05946020d243e8e4276845a');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b67d6c94f2.58970066', '2026-10-09 23:52:29', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '35892454cad819007e3000b92d91c6a94ec1cc50c05946020d243e8e4276845a', '2719ec60c88cad17696f5092e23cfc81fb7edf9bf9cd9b4d7d40dade86eec5db');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b67da24f28.27563813', '2026-10-09 23:52:29', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '2719ec60c88cad17696f5092e23cfc81fb7edf9bf9cd9b4d7d40dade86eec5db', 'fa0644f33eb5d0a430dbb84fafbbae73cbcf48feebfa79650e1599eb97758ccf');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b8454b22d9.58265842', '2026-10-10 00:00:05', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo pre_restore_checkpoint_2026-10-10_00-00-04.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', 'fa0644f33eb5d0a430dbb84fafbbae73cbcf48feebfa79650e1599eb97758ccf', 'dcf6556150e89f8a03051a0af0c56a8eedb1652ae36b33b6fc30c6ae86ad3822');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b845994c68.35650090', '2026-10-10 00:00:05', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', 'dcf6556150e89f8a03051a0af0c56a8eedb1652ae36b33b6fc30c6ae86ad3822', '6c2e6bae64273d5396dde10b2595420312ee3df6a8a8f46b04c387a45102e3d1');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b8713fddd7.55190235', '2026-10-10 00:00:49', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dasdasdase Contaduría Pública UPTT Mario Briceño Iragorry'' (ID: #200).', 'Miguel González (ID: 7)', '::1', '6c2e6bae64273d5396dde10b2595420312ee3df6a8a8f46b04c387a45102e3d1', '899a8ae2aa89780a17aed2098c6e4e47c705322daddbe1e689f986161defa10f');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b8be20ffb7.45647653', '2026-10-10 00:02:06', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #200 modificado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dassadasdasdasdassadassadasasdasdasdase Contaduría Pública UPTT Mario Briceño Iragorry''.', 'Miguel González (ID: 7)', '::1', '899a8ae2aa89780a17aed2098c6e4e47c705322daddbe1e689f986161defa10f', 'e561144bccb40e329f768747d4e910f104f5a0ab6604bf432472da6fc9c15da9');
INSERT INTO public.system_audit_log VALUES ('log_6acd0dab519cb8.24562207', '2026-10-12 12:41:15', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', 'e561144bccb40e329f768747d4e910f104f5a0ab6604bf432472da6fc9c15da9', '9b4f7e04b1a22e06d47b61a71459012a0366851cef1cfe0235d2ed026a40af0d');
INSERT INTO public.system_audit_log VALUES ('log_6acd1e4fb298e6.80953179', '2026-10-12 13:52:15', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #118 actualizado: ''Middleware MiSCi para ciudades inteligentes extendido con datos enlazados''.', 'Miguel González (ID: 7)', '::1', '9b4f7e04b1a22e06d47b61a71459012a0366851cef1cfe0235d2ed026a40af0d', 'e32e69d35e38c697ea1ddb895fe238ce3c7f2b4e637412dca81064c245c9d861');
INSERT INTO public.system_audit_log VALUES ('log_6acd288368cb90.30257089', '2026-10-12 14:35:47', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'e32e69d35e38c697ea1ddb895fe238ce3c7f2b4e637412dca81064c245c9d861', 'c5a9085efd33c219de148a5848607df146f7d7ffd9c24a73f905b05631ed7198');
INSERT INTO public.system_audit_log VALUES ('log_6acd2887d2fc81.78915986', '2026-10-12 14:35:51', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Sixsevenaldo González'' (C.I: V-67, Rol: Estudiantes).', 'Sixsevenaldo González (ID: 14)', '::1', 'c5a9085efd33c219de148a5848607df146f7d7ffd9c24a73f905b05631ed7198', 'b43187633c84f03ed1bbc3d2866ffcd6dd6055d89caaa7d6c3f631cb96604e2b');
INSERT INTO public.system_audit_log VALUES ('log_6acd2892cd4be7.07104382', '2026-10-12 14:36:02', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Sixsevenaldo González'' (ID: 14) cerró su sesión voluntariamente.', 'Sixsevenaldo González (ID: 14)', '::1', 'b43187633c84f03ed1bbc3d2866ffcd6dd6055d89caaa7d6c3f631cb96604e2b', 'bb6b167c08af4f56220be880d76825616a924f7ec6de7ebcb495e0bfd3ad2b3d');
INSERT INTO public.system_audit_log VALUES ('log_6acd2898043ea4.18790502', '2026-10-12 14:36:08', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'bb6b167c08af4f56220be880d76825616a924f7ec6de7ebcb495e0bfd3ad2b3d', 'cfda77a8553effe8970ccdec8eeb3988eece94f7f9b8419fb02078523ab74fad');
INSERT INTO public.system_audit_log VALUES ('log_6acd2a07945625.56814487', '2026-10-12 14:42:15', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'cfda77a8553effe8970ccdec8eeb3988eece94f7f9b8419fb02078523ab74fad', 'b480df9dcac78726135c972d6d9c1281eb01e715d59569f31c30433614d5941f');
INSERT INTO public.system_audit_log VALUES ('log_6acd2a19d044d7.63042038', '2026-10-12 14:42:33', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Sixsevenaldo González'' (C.I: V-67, Rol: Estudiantes).', 'Sixsevenaldo González (ID: 14)', '::1', 'b480df9dcac78726135c972d6d9c1281eb01e715d59569f31c30433614d5941f', 'a2b1385ba44721be7c45868cfcf3a3128ac2ec3d754b25fc1904708069b6a27b');
INSERT INTO public.system_audit_log VALUES ('log_6acd2a27ee3728.67192848', '2026-10-12 14:42:47', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Sixsevenaldo González'' (ID: 14) cerró su sesión voluntariamente.', 'Sixsevenaldo González (ID: 14)', '::1', 'a2b1385ba44721be7c45868cfcf3a3128ac2ec3d754b25fc1904708069b6a27b', '8ce2ab6219d5392ca196d4e3e79d01e4b55d1f9fc012d8809dc7fb0cb6dfdb52');
INSERT INTO public.system_audit_log VALUES ('log_6acd2a687693d5.16476178', '2026-10-12 14:43:52', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '8ce2ab6219d5392ca196d4e3e79d01e4b55d1f9fc012d8809dc7fb0cb6dfdb52', '38d6e0b6c199444f11322fad7947066427c64212350e2b42683e1f54944079ba');
INSERT INTO public.system_audit_log VALUES ('log_6acd2b65893768.97153947', '2026-10-12 14:48:05', 'INFO', 'Articulos', 'Publicar Artículo', 'Nuevo artículo publicado: ''Distribución de perfiles y macropartículas en recubrimientos obtenidos por arco catódico continúo utilizando un concentrador magnético recto'' (2026).', 'Miguel González (ID: 7)', '::1', '38d6e0b6c199444f11322fad7947066427c64212350e2b42683e1f54944079ba', 'f723394d549c54eaea9a15d472db4e3e25396ce751e8d9205dd8982cce159e7e');
INSERT INTO public.system_audit_log VALUES ('log_6ad1752f3a9241.94455873', '2026-10-15 20:51:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f723394d549c54eaea9a15d472db4e3e25396ce751e8d9205dd8982cce159e7e', '86c56df144b3d4e7153fc121a0b3a4085e7fbb96437b0fbbfbaa0afa939b5d6a');
INSERT INTO public.system_audit_log VALUES ('log_6ad17576ed21f5.68877231', '2026-10-15 20:53:10', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #5 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', '86c56df144b3d4e7153fc121a0b3a4085e7fbb96437b0fbbfbaa0afa939b5d6a', '88252f1a4d5fcd2b6c70504560c3d8400cd60cb03b9f45fb79a70b961706e4ed');
INSERT INTO public.system_audit_log VALUES ('log_6ad1855a82ede0.52563569', '2026-10-15 22:00:58', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: Felipe (C.I: V-6767)', 'Miguel González (ID: 7)', '::1', '88252f1a4d5fcd2b6c70504560c3d8400cd60cb03b9f45fb79a70b961706e4ed', 'b590a38c730d8a83395dcfb2ac5ba09e1500db765ff138d5936e2f0c7c39e38e');
INSERT INTO public.system_audit_log VALUES ('log_6ad1855cea6610.55350711', '2026-10-15 22:01:00', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'b590a38c730d8a83395dcfb2ac5ba09e1500db765ff138d5936e2f0c7c39e38e', '8037fde91a464c0ae2020ee78b967d4caae287503005482a2a3defce41601798');
INSERT INTO public.system_audit_log VALUES ('log_6ad185615f3398.46491668', '2026-10-15 22:01:05', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Felipe'' (C.I: V-6767, Rol: Estudiantes).', 'Felipe (ID: 22)', '::1', '8037fde91a464c0ae2020ee78b967d4caae287503005482a2a3defce41601798', '2200452fba1d6cfef7d5c67ea1dafafeba89a78f790e93dcf1146a9be2bd4ca1');
INSERT INTO public.system_audit_log VALUES ('log_6ad18625178222.78904664', '2026-10-15 22:04:21', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Felipe'' (ID: 22) cerró su sesión voluntariamente.', 'Felipe (ID: 22)', '::1', '2200452fba1d6cfef7d5c67ea1dafafeba89a78f790e93dcf1146a9be2bd4ca1', 'ab011da81223c730c3a1166b4685626637b4c49abed3cb10bfda82d3350080fd');
INSERT INTO public.system_audit_log VALUES ('log_6ad1862a0b6fa7.47135216', '2026-10-15 22:04:26', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'ab011da81223c730c3a1166b4685626637b4c49abed3cb10bfda82d3350080fd', '813eed349441985cec024f4b1e2841746f25f137269ba68d9f4d1cb0a8e4e8c1');
INSERT INTO public.system_audit_log VALUES ('log_6ad186612ecbb9.39867261', '2026-10-15 22:05:21', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '813eed349441985cec024f4b1e2841746f25f137269ba68d9f4d1cb0a8e4e8c1', '7e1248f26fb0a2b7e6edae1aa7ee01020161501444a7bdf2c7fe088449236343');
INSERT INTO public.system_audit_log VALUES ('log_6ad18665733b73.03490080', '2026-10-15 22:05:25', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '7e1248f26fb0a2b7e6edae1aa7ee01020161501444a7bdf2c7fe088449236343', '532dd4bb426b9d3d445a34ef07ae606a53f24967647e63224dca90cbd9033e67');
INSERT INTO public.system_audit_log VALUES ('log_6ad1866a483da0.68401817', '2026-10-15 22:05:30', 'WARNING', 'Autenticacion', 'Acceso Denegado Mantenimiento', 'Acceso denegado por mantenimiento para ''Felipe'' (C.I: V-6767).', 'Anónimo / Sistema', '::1', '532dd4bb426b9d3d445a34ef07ae606a53f24967647e63224dca90cbd9033e67', 'f34ad6b942516136d841d1c8bdeb5280cb8578ec83128a62ed9648677f5e5686');
INSERT INTO public.system_audit_log VALUES ('log_6ad186f8584ce5.88174372', '2026-10-15 22:07:52', 'WARNING', 'Autenticacion', 'Acceso Denegado Mantenimiento', 'Acceso denegado por mantenimiento para ''Felipe'' (C.I: V-6767).', 'Anónimo / Sistema', '::1', 'f34ad6b942516136d841d1c8bdeb5280cb8578ec83128a62ed9648677f5e5686', '3028e92c206303e88e79c6661c8c93053e3d62ecca2aa1326fe36374928cc5a8');
INSERT INTO public.system_audit_log VALUES ('log_6ad18dc9481086.63328390', '2026-10-15 22:36:57', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '3028e92c206303e88e79c6661c8c93053e3d62ecca2aa1326fe36374928cc5a8', '1bd6722f3cffa8500501f823348db939630bde3d7186f7ddfa8a41a9f1148672');
INSERT INTO public.system_audit_log VALUES ('log_6ad18dcf08e789.87446451', '2026-10-15 22:37:03', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 1.75ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', '1bd6722f3cffa8500501f823348db939630bde3d7186f7ddfa8a41a9f1148672', '7adfa2f752eb3b6a0cbc8016fce22558cfdbe81f28b77e3b2f273c256bc074db');
INSERT INTO public.system_audit_log VALUES ('log_6ad18dd8977dd4.19471738', '2026-10-15 22:37:12', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', '7adfa2f752eb3b6a0cbc8016fce22558cfdbe81f28b77e3b2f273c256bc074db', 'eb6a41566e093d010baf3600e545e8b8086f76afecd849b6200cc78fc5b70e9f');
INSERT INTO public.system_audit_log VALUES ('log_6ad18dda35f167.66929848', '2026-10-15 22:37:14', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'eb6a41566e093d010baf3600e545e8b8086f76afecd849b6200cc78fc5b70e9f', '137488ee2659c99c5e9ae400ce2574d253ba579987970067c0ed5a8aed869d07');
INSERT INTO public.system_audit_log VALUES ('log_6ad19044b16f16.38184565', '2026-10-15 22:47:32', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', '137488ee2659c99c5e9ae400ce2574d253ba579987970067c0ed5a8aed869d07', '4d886252766b42b587d86a2fb86ed96b821d3b37d3d5ed24de2495b92d24ce36');
INSERT INTO public.system_audit_log VALUES ('log_6ad19051ea9f68.61643958', '2026-10-15 22:47:45', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', '4d886252766b42b587d86a2fb86ed96b821d3b37d3d5ed24de2495b92d24ce36', '755773d6f46755e970546e2aec03546a2b01d5d9f21bafde73f7913f175f37fc');
INSERT INTO public.system_audit_log VALUES ('log_6ad190621976f6.93443919', '2026-10-15 22:48:02', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', '755773d6f46755e970546e2aec03546a2b01d5d9f21bafde73f7913f175f37fc', 'c175a3e7ca071b7661b7bd105e2782e69fd14cf98bccdba5261ed571c6349e53');
INSERT INTO public.system_audit_log VALUES ('log_6ad19067023e03.71040820', '2026-10-15 22:48:07', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', 'c175a3e7ca071b7661b7bd105e2782e69fd14cf98bccdba5261ed571c6349e53', 'b336326122aab1f5d63825211eeb27b9aace9112e27d49594bfd71b981141498');
INSERT INTO public.system_audit_log VALUES ('log_6ad1906c517348.92693890', '2026-10-15 22:48:12', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP ::1 bloqueada por 15 min tras 5 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '::1', 'b336326122aab1f5d63825211eeb27b9aace9112e27d49594bfd71b981141498', '2e753034ebed02361c1379508b8cb0e5edf6157f4b59c79a75efdb1fdd9e13b9');
INSERT INTO public.system_audit_log VALUES ('log_6ad1906c52ea94.71572519', '2026-10-15 22:48:12', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', '2e753034ebed02361c1379508b8cb0e5edf6157f4b59c79a75efdb1fdd9e13b9', 'e4c832cab4664d66806915e0bb22325982c85b56c0e502f8a7ce2f9d98303f34');
INSERT INTO public.system_audit_log VALUES ('log_6ad190850e65c1.88474596', '2026-10-15 22:48:37', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', 'e4c832cab4664d66806915e0bb22325982c85b56c0e502f8a7ce2f9d98303f34', '20a9fbfbec5298a6b91109060ff9ca4dfead673eaf252857c47f132957d25753');
INSERT INTO public.system_audit_log VALUES ('log_6ad190a92d4634.58641715', '2026-10-15 22:49:13', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Sixsevenaldo González'' (C.I: V-67, Rol: Estudiantes).', 'Sixsevenaldo González (ID: 14)', '::1', '20a9fbfbec5298a6b91109060ff9ca4dfead673eaf252857c47f132957d25753', '47ee89e5f0e01dc1b9fd2d39c35ec06e00c42dfe567bb11340ca7a6ec9d6c0ac');
INSERT INTO public.system_audit_log VALUES ('log_6ad19157a5b288.42435915', '2026-10-15 22:52:07', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Sixsevenaldo González'' (ID: 14) cerró su sesión voluntariamente.', 'Sixsevenaldo González (ID: 14)', '::1', '47ee89e5f0e01dc1b9fd2d39c35ec06e00c42dfe567bb11340ca7a6ec9d6c0ac', '8fd4459034f94436a002823455750c5aae8f6c67f082b3c92b0c209edebc9477');
INSERT INTO public.system_audit_log VALUES ('log_6ad1915c065984.28903954', '2026-10-15 22:52:12', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Felipe'' (C.I: V-6767, Rol: Estudiantes).', 'Felipe (ID: 22)', '::1', '8fd4459034f94436a002823455750c5aae8f6c67f082b3c92b0c209edebc9477', '15e19d130bb506d1c5c66c9fa9855ddcdbd49d163b32f633965b4fe1577f455c');
INSERT INTO public.system_audit_log VALUES ('log_6ad1956a51e136.84094651', '2026-10-15 23:09:30', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Felipe'' (ID: 22) cerró su sesión voluntariamente.', 'Felipe (ID: 22)', '::1', '15e19d130bb506d1c5c66c9fa9855ddcdbd49d163b32f633965b4fe1577f455c', '170d289f48102cd03ad780626e29cb878a7ff4869806aa30e8a830b3e59045ae');
INSERT INTO public.system_audit_log VALUES ('log_6ad1956f54bb02.31734538', '2026-10-15 23:09:35', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '170d289f48102cd03ad780626e29cb878a7ff4869806aa30e8a830b3e59045ae', '7ab5828a305aad771d3d9fc2fe3fa6cff052ae5f25c8e887b4d54dfb6925f793');
INSERT INTO public.system_audit_log VALUES ('log_6ad2ee4814d4a4.10027715', '2026-10-16 23:40:56', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Sixsevenaldo González'' (C.I: V-67).', 'Anónimo / Sistema', '::1', '7ab5828a305aad771d3d9fc2fe3fa6cff052ae5f25c8e887b4d54dfb6925f793', '18132a7758a365346bcc1e0bafe4cbfddef1c346fc14a6989ac4b28b1261f96d');
INSERT INTO public.system_audit_log VALUES ('log_6ad2ee5bc1bf20.06953099', '2026-10-16 23:41:15', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-66666666''.', 'Anónimo / Sistema', '::1', '18132a7758a365346bcc1e0bafe4cbfddef1c346fc14a6989ac4b28b1261f96d', '5491557743ce65ce6ba41cbdc8ce222b0bdc0f9267923d5fbea49dc45d5d301f');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f15cb15159.26129767', '2026-10-16 23:54:04', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '5491557743ce65ce6ba41cbdc8ce222b0bdc0f9267923d5fbea49dc45d5d301f', 'f352a83cfdeca77df9bc3f6393a173cec0f439abb14df7799bdf65398e215677');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f16528bdd9.94857352', '2026-10-16 23:54:13', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', 'f352a83cfdeca77df9bc3f6393a173cec0f439abb14df7799bdf65398e215677', '32c1673d30e3d36032d0e7081a6a987ec94c0e0b2334145e8595323e9929d2d2');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f16659db90.03647515', '2026-10-16 23:54:14', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '32c1673d30e3d36032d0e7081a6a987ec94c0e0b2334145e8595323e9929d2d2', '8eab23aefeccb9f9098dd6a0aacc239be7d00844b140e56b9b915af82af49c5c');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f16f768300.74340923', '2026-10-16 23:54:23', 'INFO', 'Autenticacion', 'Recuperación Solicitada', 'Enlace directo de recuperación generado para ''Felipe'' (cepillinin@gmail.com).', 'Anónimo / Sistema', '::1', '8eab23aefeccb9f9098dd6a0aacc239be7d00844b140e56b9b915af82af49c5c', '6ed40c6b1ee115b7ae88e8dd3f18f7b0a8a681d3005f92d89a4f21b06604acb2');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f25a284447.67085333', '2026-10-16 23:58:18', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '6ed40c6b1ee115b7ae88e8dd3f18f7b0a8a681d3005f92d89a4f21b06604acb2', '3474ba8fdc2b39709bae71838935755ef3da4f9bb95139e39df26bc8ec41af66');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f3d530ec66.65732103', '2026-10-17 00:04:37', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #201 actualizado: ''Distribución de perfiles y macropartículas en recubrimientos obtenidos por arco catódico continúo utilizando un concentrador magnético recto''.', 'Miguel González (ID: 7)', '::1', '3474ba8fdc2b39709bae71838935755ef3da4f9bb95139e39df26bc8ec41af66', 'a519ac44e1a31f721c9c6ec8b6deb73da7aa5b13c253ed501fd10522a16be7ce');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f54de12001.11623918', '2026-10-17 00:10:53', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #156 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', 'a519ac44e1a31f721c9c6ec8b6deb73da7aa5b13c253ed501fd10522a16be7ce', '8e1165459e2617f971026dc4b747963f73d52c87e38486793019dcf9ce6b6751');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f55060fc01.27801575', '2026-10-17 00:10:56', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #151 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', '8e1165459e2617f971026dc4b747963f73d52c87e38486793019dcf9ce6b6751', '7ceac3354f08e49fc1a62414564aa14291fd66a662891883e20b95ab0b372ea7');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f5524c69e3.80352650', '2026-10-17 00:10:58', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #150.', 'Miguel González (ID: 7)', '::1', '7ceac3354f08e49fc1a62414564aa14291fd66a662891883e20b95ab0b372ea7', '0d8bfcfdbbb5ad52f4bc468c38d540e53ffb79e1a0de7588084ffcd949a94ecd');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f588a8ddd6.77972470', '2026-10-17 00:11:52', 'WARNING', 'Articulos', 'Eliminar Categoría', 'Categoría ID #10 eliminada.', 'Miguel González (ID: 7)', '::1', '0d8bfcfdbbb5ad52f4bc468c38d540e53ffb79e1a0de7588084ffcd949a94ecd', '6f892d2e146652853e9be4e08c7215e9f5efe30746b91b1413e8b15e6bf06b5a');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f5a60d05c8.20207673', '2026-10-17 00:12:22', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #146 actualizado: ''Modelamiento de confort adaptativo para un trapiche panelero''.', 'Miguel González (ID: 7)', '::1', '6f892d2e146652853e9be4e08c7215e9f5efe30746b91b1413e8b15e6bf06b5a', '69bed43c3b24dc1de95563388be12f59b4f04ebb6fec8eb182108dcfa42e70db');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f5ac1fb158.92869985', '2026-10-17 00:12:28', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #144 actualizado: ''Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública''.', 'Miguel González (ID: 7)', '::1', '69bed43c3b24dc1de95563388be12f59b4f04ebb6fec8eb182108dcfa42e70db', '8a09f0c124bb9f476d15e8adc3cb5d345e8f7e7eaac1542b9539a19e366ef905');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f5b38d22a1.09033165', '2026-10-17 00:12:35', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #143 actualizado: ''Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión''.', 'Miguel González (ID: 7)', '::1', '8a09f0c124bb9f476d15e8adc3cb5d345e8f7e7eaac1542b9539a19e366ef905', '7238471014b89fcb3ddadf9d466496d62bbb1ddcc314b3d18779a4448495dbef');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f5ba367368.25058074', '2026-10-17 00:12:42', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #122 actualizado: ''Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto''.', 'Miguel González (ID: 7)', '::1', '7238471014b89fcb3ddadf9d466496d62bbb1ddcc314b3d18779a4448495dbef', '74130dbc5dae86e639bfe922c9866dcbcf4f3283ff22884be416dc948dee56b8');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f7d07a8287.24672958', '2026-10-17 00:21:36', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #150 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', '74130dbc5dae86e639bfe922c9866dcbcf4f3283ff22884be416dc948dee56b8', 'c129bdbb921c17b9b5e76390b01438b2b96045de6298fb08fd9ec1627189f8c8');
INSERT INTO public.system_audit_log VALUES ('log_6ad2f8d9769311.59576436', '2026-10-17 00:26:01', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 1.15ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', 'c129bdbb921c17b9b5e76390b01438b2b96045de6298fb08fd9ec1627189f8c8', '945abb6bdf1eb0a5909012804affa23e24e89ac731c0c648b641ffa36dc09353');


--
-- Data for Name: telemetria_cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.telemetria_cache VALUES (1, '{"timestamp": 1792211157, "storage_mb": 5.29, "files_count": 53}');


--
-- Data for Name: tipo_recurso; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tipo_recurso VALUES (1, 'PST / Trabajo de Grado', 'Proyectos Socio-Tecnológicos y Tesis');
INSERT INTO public.tipo_recurso VALUES (2, 'Investigación Docente', 'Papers y artículos de investigación del personal académico');
INSERT INTO public.tipo_recurso VALUES (3, 'Material de Apoyo / Didáctico', 'Recursos adicionales para estudiantes');


--
-- Data for Name: tipo_tutor; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tipo_tutor VALUES (1, 'Director', 'Director principal del proyecto');
INSERT INTO public.tipo_tutor VALUES (2, 'Coordinador', 'Asesor metodológico');
INSERT INTO public.tipo_tutor VALUES (3, 'Tutor Académico', 'Especialista en el área');
INSERT INTO public.tipo_tutor VALUES (4, 'Tutor Comunitario', 'Representante de la comunidad');


--
-- Data for Name: trayectos; Type: TABLE DATA; Schema: public; Owner: miki
--

INSERT INTO public.trayectos VALUES (1, 1, 'Trayecto I', 1, 'Trayecto I del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (2, 1, 'Trayecto II', 2, 'Trayecto II del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (3, 1, 'Trayecto III', 3, 'Trayecto III del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (4, 1, 'Trayecto IV', 4, 'Trayecto IV del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (5, 2, 'Trayecto I', 1, 'Trayecto I del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (6, 2, 'Trayecto II', 2, 'Trayecto II del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (7, 2, 'Trayecto III', 3, 'Trayecto III del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (8, 2, 'Trayecto IV', 4, 'Trayecto IV del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (9, 3, 'Trayecto I', 1, 'Trayecto I del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (10, 3, 'Trayecto II', 2, 'Trayecto II del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (11, 3, 'Trayecto III', 3, 'Trayecto III del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (12, 3, 'Trayecto IV', 4, 'Trayecto IV del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (13, 4, 'Trayecto I', 1, 'Trayecto I del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (14, 4, 'Trayecto II', 2, 'Trayecto II del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (15, 4, 'Trayecto III', 3, 'Trayecto III del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (16, 4, 'Trayecto IV', 4, 'Trayecto IV del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (17, 5, 'Trayecto I', 1, 'Trayecto I del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (18, 5, 'Trayecto II', 2, 'Trayecto II del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (19, 5, 'Trayecto III', 3, 'Trayecto III del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (20, 5, 'Trayecto IV', 4, 'Trayecto IV del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');


--
-- Data for Name: tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tutores VALUES (1, 'Lando', 'V-12345678');
INSERT INTO public.tutores VALUES (2, 'Mikeyisito', 'V-18765432');
INSERT INTO public.tutores VALUES (3, 'María Antonieta Pérez', 'V-15444333');
INSERT INTO public.tutores VALUES (7, 'aaaaa aaa', '22222');
INSERT INTO public.tutores VALUES (8, 'aaa aaaa', '33333');
INSERT INTO public.tutores VALUES (9, 'aaaaa aaaaaa', '444444');
INSERT INTO public.tutores VALUES (10, 'Karina Gutiérrez', '2222231312');
INSERT INTO public.tutores VALUES (11, 'asdasdas faasdas', '12312312');
INSERT INTO public.tutores VALUES (12, 'Karina Gutiérrez', '3123123');
INSERT INTO public.tutores VALUES (13, 'Prof. Tutor Académico Prueba', 'V-15888999');
INSERT INTO public.tutores VALUES (14, 'Dr. Asesor Edumático', 'V-12000333');
INSERT INTO public.tutores VALUES (15, 'Prof. Asesor', 'V-14555666');
INSERT INTO public.tutores VALUES (16, 'Prof. Asesor Prueba', 'V-11223344');
INSERT INTO public.tutores VALUES (17, 'Karla Rodríguez', NULL);
INSERT INTO public.tutores VALUES (18, 'Karina Araujo', NULL);
INSERT INTO public.tutores VALUES (19, 'Helen Gonzales', NULL);
INSERT INTO public.tutores VALUES (20, 'Msc Néstor Araujo', NULL);
INSERT INTO public.tutores VALUES (21, 'Msc Julio Abreu', NULL);
INSERT INTO public.tutores VALUES (22, 'Karina Gutierrez', NULL);
INSERT INTO public.tutores VALUES (25, 'asdasd', NULL);
INSERT INTO public.tutores VALUES (26, 'Ricardo Dos Santosss', NULL);
INSERT INTO public.tutores VALUES (27, 'Karina Gutiérrezxczxc', NULL);
INSERT INTO public.tutores VALUES (28, 'Ricardo Dos Santos', NULL);
INSERT INTO public.tutores VALUES (29, 'María Luisa Colmenares', NULL);
INSERT INTO public.tutores VALUES (30, 'Rossana Virgilio', NULL);
INSERT INTO public.tutores VALUES (31, 'Carlos Simancas', NULL);
INSERT INTO public.tutores VALUES (32, 'Tutor Prueba Academico', 'V-11111111');
INSERT INTO public.tutores VALUES (33, 'Tutor Prueba Institucional', 'V-22222222');
INSERT INTO public.tutores VALUES (34, 'Tutor Prueba Comunitario', 'V-33333333');
INSERT INTO public.tutores VALUES (35, 'Winston Méndez', NULL);
INSERT INTO public.tutores VALUES (36, 'Carmen Muchacho', NULL);
INSERT INTO public.tutores VALUES (37, 'Yajaira Franco', NULL);
INSERT INTO public.tutores VALUES (38, 'Mary Moreno', NULL);
INSERT INTO public.tutores VALUES (39, 'Estella Berríos', NULL);
INSERT INTO public.tutores VALUES (40, 'KarinAI', 'Karina');


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuarios VALUES (4, 'ale', 'ale@yaju.com', '44444444', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (8, 'Yisu Monte', 'yisu@gmail.com', '30866991', '$2y$10$jOukhIGIbdJCmpHdS.MqWusufmhQgHf.O9UByeqN.NFue38kT47xa', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (9, 'Pedro Perez', 'iaiaia@gmail.com', '4123123', '$2y$10$xOgs5kJnv17wwzjNtnNUguWc7pxdYv.lMZGFejPOz7fIgLNEybLgC', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (11, 'Juan', '123@gmail.com', '1234', '$2y$10$HBPGRak0eIYzElwfC.bGuOvgFOfK.GbG40ct2e7X9CS7OgMARJRcC', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (6, 'Piñin Piña', 'pina@hotmail.com', '1', '$2y$10$wqwwyjK8T7ccki5IeOK4ueZRlW8K3g2xC42ZyOG01kDru0CNhba/a', 4, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (10, 'Wazaaaa', 'wazaaa@gmail.com', '123', '$2y$10$G7tnCsgxNo7nFV93A4H7Ie86N2RYtbppgkB6iEPg.STWF4wn2qn7O', 4, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (1, 'Adrus', 'andru@gmail.com', '11111111', '$2y$10$1sBy413YpJ9MQGlRt/g6y.OGkfno7aRuKxShONKSeWrvhQNS53YDO', 1, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (14, 'Sixsevenaldo González', '676767@gmail.com', '67', '$2y$10$XIdtQdP6d.bZSGCINdEbIuTtuqee9E3EEKYp5Ogbm/I2JW5XQbP/O', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (13, 'Cepillíno', 'cepillin@gmail.com', '80', '$2y$10$ML6M4RYmR0f2yCoxOpRvaONIE/nUvwOmcsmGySeBOOBGjy9xUmbk6', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (16, 'no soy miguel', 'orlando5711666@gmail.com', '1234567', '$2y$10$g1LIDvziBpq3KrEilAhp3.RC1ziliq9hOpkdhWFISEjtJ.Y00SUHS', 3, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (12, '[Archivado] ANDRUS', 'andrusramirez2020@gmail.com_deleted_1789844158', '30469331_x9844158', '$2y$10$Vii.OSXjYhxOk.Xq.dx2EODQb3U9cahqa401C48PNPR3mlXdi85Ii', 2, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (15, '[Archivado] DIOSs', 'DIOS@gmail.com_deleted_1789845222', '12000000_x9845222', '$2y$10$eaICZWMAWr11BS/RL0ju0O49pEw.3lQIpEGrLoe6FweXprGxkjttu', 1, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (17, 'adrusss', 'andrusramirez2020@gmail.com', '30469331', '$2y$10$SFowO4NOxSgKqx35qYr7iOiJU2PJ6hJ.uTO2zdxSSXMQjX64sRwiu', 2, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (19, 'Juan Salcedo', 'juanxzall0701@gmail.com', 'V-31008131', '$2y$12$tjD8yhgVIWvwuJiEPZnYguCyS5EukGmORWC3srlfCHIAE0KQFr1ZG', 2, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (2, 'lando', 'lando@gmail.com', '22222222', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 2, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (3, 'miki', 'miki@gmail.com', '33333333', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (7, 'Miguel González', 'erwesitodepollo@gmail.com', '32621284', '$2y$10$tqm17pwan91BnMUfmCAB/O01faShLfeK3jo0jYVwpQcBpGr5iLiE.', 1, true, NULL, NULL, NULL, false, 'dc1c777c1cdc20c388ae51c653f684a4c5e4673e509adc494000642ed4e1cd0d');
INSERT INTO public.usuarios VALUES (5, '[Archivado] bibi', 'bibi@gmail.com_deleted_1792111990', '4444111_x2111990', '$2y$12$KgG./enVG4wsHsojaIJxJOTDNy7nri.a6oj9veFmtMGE1B/dlvfje', 3, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (22, 'Felipe', 'cepillinin@gmail.com', 'V-6767', '$2y$12$0xdPaM20c2YpU3s6kg/XeujfdPq2QDTCNjul24vOXfbo8lfKLS0sW', 3, true, NULL, NULL, NULL, false, NULL);


--
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: waf_rate_limiter; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.waf_rate_limiter VALUES ('127.0.0.1', 'whitelist', 0, NULL, '2026-09-30 20:14:31.796931', NULL, 'Desarrollador Local (Localhost IPv4)', '{"responsable": "SuperAdmin"}', '2026-09-30 20:14:31.796931');
INSERT INTO public.waf_rate_limiter VALUES ('::1', 'whitelist', 0, NULL, '2026-09-30 20:14:31.797469', NULL, 'Desarrollador Local (Localhost IPv6)', '{"responsable": "SuperAdmin"}', '2026-09-30 20:14:31.797469');


--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, true);


--
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 458, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 5, true);


--
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 18, true);


--
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cursos_id_seq', 7, true);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 24, true);


--
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 8, true);


--
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 17, true);


--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.historico_versiones_pst_id_seq', 1, true);


--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.investigaciones_ofertadas_id_seq', 4, true);


--
-- Name: investigadores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.investigadores_id_seq', 5, true);


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 11, true);


--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.niveles_academicos_id_seq', 6, true);


--
-- Name: notificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_id_seq', 6, true);


--
-- Name: password_resets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.password_resets_id_seq', 2, true);


--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.postulaciones_estudiantes_id_seq', 2, true);


--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 10, true);


--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propuestas_empresa_id_seq', 16, true);


--
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 201, true);


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 6, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_recurso_id_seq', 3, true);


--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_tutor_id_seq', 4, true);


--
-- Name: trayectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.trayectos_id_seq', 20, true);


--
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 40, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 22, true);


--
-- Name: visitantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.visitantes_id_seq', 1, true);


--
-- Name: accesos_recursos accesos_recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_pkey PRIMARY KEY (id);


--
-- Name: auditoria auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (id);


--
-- Name: autores autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_pkey PRIMARY KEY (id);


--
-- Name: carreras carreras_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_nombre_key UNIQUE (nombre);


--
-- Name: carreras carreras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_pkey PRIMARY KEY (id);


--
-- Name: categorias categorias_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nombre_key UNIQUE (nombre);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


--
-- Name: cursos cursos_slug_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_slug_key UNIQUE (slug);


--
-- Name: detalles_investigaciones detalles_investigaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT detalles_investigaciones_pkey PRIMARY KEY (id_recurso);


--
-- Name: detalles_articulos detalles_revistas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_pkey PRIMARY KEY (id_recurso);


--
-- Name: dimensiones_operativas dimensiones_operativas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT dimensiones_operativas_pkey PRIMARY KEY (id);


--
-- Name: editoriales editoriales_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_nombre_key UNIQUE (nombre);


--
-- Name: editoriales editoriales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_pkey PRIMARY KEY (id);


--
-- Name: etiquetas etiquetas_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_nombre_key UNIQUE (nombre);


--
-- Name: etiquetas etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_pkey PRIMARY KEY (id);


--
-- Name: historico_versiones_pst historico_versiones_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT historico_versiones_pst_pkey PRIMARY KEY (id);


--
-- Name: investigaciones_ofertadas investigaciones_ofertadas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT investigaciones_ofertadas_pkey PRIMARY KEY (id);


--
-- Name: investigadores investigadores_orcid_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_orcid_key UNIQUE (orcid);


--
-- Name: investigadores investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_pkey PRIMARY KEY (id);


--
-- Name: lineas_investigacion lineas_investigacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_pkey PRIMARY KEY (id);


--
-- Name: matriz_rbac matriz_rbac_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matriz_rbac
    ADD CONSTRAINT matriz_rbac_pkey PRIMARY KEY (nivel_privilegio, modulo);


--
-- Name: niveles_academicos niveles_academicos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_codigo_key UNIQUE (codigo);


--
-- Name: niveles_academicos niveles_academicos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_pkey PRIMARY KEY (id);


--
-- Name: notificaciones notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id);


--
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (id);


--
-- Name: proyecto_carreras_vinculadas pk_proyecto_carreras_vinculadas; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT pk_proyecto_carreras_vinculadas PRIMARY KEY (id_recurso, id_carrera);


--
-- Name: postulaciones_estudiantes postulaciones_estudiantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT postulaciones_estudiantes_pkey PRIMARY KEY (id);


--
-- Name: preferencias_usuario preferencias_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: propuestas_empresa propuestas_empresa_codigo_seguimiento_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_codigo_seguimiento_key UNIQUE (codigo_seguimiento);


--
-- Name: propuestas_empresa propuestas_empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_pkey PRIMARY KEY (id);


--
-- Name: proyecto_tutores proyecto_tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_pkey PRIMARY KEY (id_recurso, id_tutor);


--
-- Name: recurso_autores recurso_autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_pkey PRIMARY KEY (id_recurso, id_autor);


--
-- Name: recurso_categorias recurso_categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_pkey PRIMARY KEY (id_recurso, id_categoria);


--
-- Name: recurso_clasificaciones recurso_clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT recurso_clasificaciones_pkey PRIMARY KEY (id_recurso, id_linea_investigacion);


--
-- Name: recurso_etiquetas recurso_etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT recurso_etiquetas_pkey PRIMARY KEY (id_recurso, id_etiqueta);


--
-- Name: recurso_investigadores recurso_investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT recurso_investigadores_pkey PRIMARY KEY (id_recurso, id_investigador);


--
-- Name: recursos recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- Name: registro_actividad registro_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_pkey PRIMARY KEY (id);


--
-- Name: roles roles_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_nombre_key UNIQUE (nombre);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: system_audit_log system_audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.system_audit_log
    ADD CONSTRAINT system_audit_log_pkey PRIMARY KEY (id);


--
-- Name: telemetria_cache telemetria_cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.telemetria_cache
    ADD CONSTRAINT telemetria_cache_pkey PRIMARY KEY (id);


--
-- Name: tipo_recurso tipo_recurso_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_nombre_key UNIQUE (nombre);


--
-- Name: tipo_recurso tipo_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_pkey PRIMARY KEY (id);


--
-- Name: tipo_tutor tipo_tutor_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_nombre_key UNIQUE (nombre);


--
-- Name: tipo_tutor tipo_tutor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_pkey PRIMARY KEY (id);


--
-- Name: trayectos trayectos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_pkey PRIMARY KEY (id);


--
-- Name: tutores tutores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_cedula_key UNIQUE (cedula);


--
-- Name: tutores tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_pkey PRIMARY KEY (id);


--
-- Name: privilegios unique_nivel_privilegio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios
    ADD CONSTRAINT unique_nivel_privilegio UNIQUE (nivel_privilegio);


--
-- Name: postulaciones_estudiantes unique_postulacion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT unique_postulacion UNIQUE (id_investigacion, id_estudiante);


--
-- Name: trayectos uq_carrera_trayecto; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT uq_carrera_trayecto UNIQUE (id_carrera, numero);


--
-- Name: usuarios usuarios_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_cedula_key UNIQUE (cedula);


--
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: visitantes visitantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes
    ADD CONSTRAINT visitantes_pkey PRIMARY KEY (id);


--
-- Name: waf_rate_limiter waf_rate_limiter_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.waf_rate_limiter
    ADD CONSTRAINT waf_rate_limiter_pkey PRIMARY KEY (ip, tipo);


--
-- Name: idx_detalles_inv_ofertada; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_inv_ofertada ON public.detalles_investigaciones USING btree (id_investigacion_ofertada);


--
-- Name: idx_pcv_carrera; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_pcv_carrera ON public.proyecto_carreras_vinculadas USING btree (id_carrera);


--
-- Name: idx_pcv_recurso; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_pcv_recurso ON public.proyecto_carreras_vinculadas USING btree (id_recurso);


--
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


--
-- Name: idx_trayectos_carrera; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_trayectos_carrera ON public.trayectos USING btree (id_carrera);


--
-- Name: recursos tg_auditoria_recursos_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_delete BEFORE DELETE ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- Name: recursos tg_auditoria_recursos_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_insert AFTER INSERT ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- Name: usuarios tg_auditoria_usuarios_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_delete BEFORE DELETE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: usuarios tg_auditoria_usuarios_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_insert AFTER INSERT ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: usuarios tg_auditoria_usuarios_update; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_update AFTER UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: accesos_recursos accesos_recursos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: accesos_recursos accesos_recursos_id_registro_actividad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_registro_actividad_fkey FOREIGN KEY (id_registro_actividad) REFERENCES public.registro_actividad(id) ON DELETE CASCADE;


--
-- Name: auditoria auditoria_usuario_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_usuario_responsable_fkey FOREIGN KEY (usuario_responsable) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: cursos cursos_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: detalles_articulos detalles_revistas_id_editorial_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_editorial_fkey FOREIGN KEY (id_editorial) REFERENCES public.editoriales(id) ON DELETE SET NULL;


--
-- Name: detalles_articulos detalles_revistas_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: detalles_investigaciones fk_detalles_investigaciones_ofertada; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_ofertada FOREIGN KEY (id_investigacion_ofertada) REFERENCES public.investigaciones_ofertadas(id) ON DELETE SET NULL;


--
-- Name: detalles_investigaciones fk_detalles_investigaciones_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: dimensiones_operativas fk_dimension_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT fk_dimension_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_dimension_operativa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_dimension_operativa FOREIGN KEY (id_dimension_operativa) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- Name: recurso_etiquetas fk_etiqueta_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_etiqueta_recurso FOREIGN KEY (id_etiqueta) REFERENCES public.etiquetas(id) ON DELETE CASCADE;


--
-- Name: investigaciones_ofertadas fk_inv_dimension; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_dimension FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- Name: investigaciones_ofertadas fk_inv_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE RESTRICT;


--
-- Name: investigaciones_ofertadas fk_inv_profesor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_profesor FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: recurso_investigadores fk_investigador; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_investigador FOREIGN KEY (id_investigador) REFERENCES public.investigadores(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: proyecto_carreras_vinculadas fk_pcv_carrera; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_carrera FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: proyecto_carreras_vinculadas fk_pcv_recurso; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_inv; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_etiquetas fk_recurso_etiqueta; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_recurso_etiqueta FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_investigadores fk_recurso_inv; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_recurso_inv FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: historico_versiones_pst fk_version_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT fk_version_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: lineas_investigacion lineas_investigacion_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: notificaciones notificaciones_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: preferencias_usuario preferencias_usuario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_id_tutor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_tutor_fkey FOREIGN KEY (id_tutor) REFERENCES public.tutores(id) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_tipo_tutor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey FOREIGN KEY (tipo_tutor_id) REFERENCES public.tipo_tutor(id) ON DELETE SET NULL;


--
-- Name: recurso_autores recurso_autores_id_autor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_autor_fkey FOREIGN KEY (id_autor) REFERENCES public.autores(id) ON DELETE CASCADE;


--
-- Name: recurso_autores recurso_autores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_categorias recurso_categorias_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- Name: recurso_categorias recurso_categorias_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recursos recursos_id_tipo_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_id_tipo_recurso_fkey FOREIGN KEY (id_tipo_recurso) REFERENCES public.tipo_recurso(id) ON DELETE RESTRICT;


--
-- Name: registro_actividad registro_actividad_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: registro_actividad registro_actividad_id_visitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_visitante_fkey FOREIGN KEY (id_visitante) REFERENCES public.visitantes(id) ON DELETE SET NULL;


--
-- Name: trayectos trayectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

-- ============================================================================
-- COMPATIBILIDAD MULTIPLATAFORMA LINUX DEBIAN / POSTGRESQL (UNACCENT & PGVECTOR)
-- ============================================================================
DO $$
BEGIN
    CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;
EXCEPTION WHEN OTHERS THEN
    NULL;
END $$;

DO $$
BEGIN
    CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;
EXCEPTION WHEN OTHERS THEN
    NULL;
END $$;

DO $$
BEGIN
    PERFORM public.unaccent('prueba');
EXCEPTION WHEN undefined_function THEN
    CREATE OR REPLACE FUNCTION public.unaccent(text)
    RETURNS text
    LANGUAGE sql
    IMMUTABLE STRICT
    AS $func$
        SELECT translate(
            $1,
            'áàäâãåéèëêíìïîóòöôõúùüûñçÁÀÄÂÃÅÉÈËÊÍÌÏÎÓÒÖÔÕÚÙÜÛÑÇ',
            'aaaaaaeeeeiiiiooooouuuuncAAAAAAEEEEIIIIOOOOOUUUUNC'
        );
    $func$;
END $$;

CREATE OR REPLACE FUNCTION public.normalizar_busqueda_pst(input_text TEXT)
RETURNS TEXT
LANGUAGE sql
IMMUTABLE
AS $$
    SELECT LOWER(public.unaccent(COALESCE(input_text, '')));
$$;

