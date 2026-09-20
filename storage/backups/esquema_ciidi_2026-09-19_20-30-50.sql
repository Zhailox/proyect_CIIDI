--
-- PostgreSQL database dump
--

\restrict JrXUbKrgJC9GRH8cNgVUOAecSzzbJ5ZQ3ImY4qkryaVsrYtUMO98COJf4MhQLox

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_id_rol_fkey;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_id_visitante_fkey;
ALTER TABLE IF EXISTS ONLY public.registro_actividad DROP CONSTRAINT IF EXISTS registro_actividad_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.recursos DROP CONSTRAINT IF EXISTS recursos_id_tipo_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_id_categoria_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_id_autor_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_tipo_tutor_id_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_id_tutor_fkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS fk_version_recurso;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_recurso_etiqueta;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_recurso;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_inv;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_estudiante;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_linea_investigacion;
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
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_recurso_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_investigacion_padre_fkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_id_docente_fkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_usuario_responsable_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_registro_actividad_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_recurso_fkey;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_update ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_insert ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_delete ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_insert ON public.recursos;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_delete ON public.recursos;
DROP INDEX IF EXISTS public.idx_recurso_clasif_linea;
DROP INDEX IF EXISTS public.idx_recurso_clasif_dimension;
DROP INDEX IF EXISTS public.idx_detalles_inv_ofertada;
ALTER TABLE IF EXISTS ONLY public.waf_rate_limiter DROP CONSTRAINT IF EXISTS waf_rate_limiter_pkey;
ALTER TABLE IF EXISTS ONLY public.visitantes DROP CONSTRAINT IF EXISTS visitantes_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_email_key;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_cedula_key;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS unique_postulacion;
ALTER TABLE IF EXISTS ONLY public.privilegios DROP CONSTRAINT IF EXISTS unique_nivel_privilegio;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_cedula_key;
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
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS recurso_etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS recurso_clasificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_codigo_seguimiento_key;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_pkey;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS postulaciones_estudiantes_pkey;
ALTER TABLE IF EXISTS ONLY public.password_resets DROP CONSTRAINT IF EXISTS password_resets_pkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.matriz_rbac DROP CONSTRAINT IF EXISTS matriz_rbac_pkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_pkey;
ALTER TABLE IF EXISTS ONLY public.investigaciones_ofertadas DROP CONSTRAINT IF EXISTS investigaciones_ofertadas_pkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS historico_versiones_pst_pkey;
ALTER TABLE IF EXISTS ONLY public.etiquetas DROP CONSTRAINT IF EXISTS etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.etiquetas DROP CONSTRAINT IF EXISTS etiquetas_nombre_key;
ALTER TABLE IF EXISTS ONLY public.editoriales DROP CONSTRAINT IF EXISTS editoriales_pkey;
ALTER TABLE IF EXISTS ONLY public.editoriales DROP CONSTRAINT IF EXISTS editoriales_nombre_key;
ALTER TABLE IF EXISTS ONLY public.dimensiones_operativas DROP CONSTRAINT IF EXISTS dimensiones_operativas_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_articulos DROP CONSTRAINT IF EXISTS detalles_revistas_pkey;
ALTER TABLE IF EXISTS ONLY public.detalles_proyectos DROP CONSTRAINT IF EXISTS detalles_proyectos_pkey;
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
ALTER TABLE IF EXISTS public.lineas_investigacion ALTER COLUMN id DROP DEFAULT;
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
DROP TABLE IF EXISTS public.recurso_etiquetas;
DROP TABLE IF EXISTS public.recurso_clasificaciones;
DROP TABLE IF EXISTS public.recurso_categorias;
DROP TABLE IF EXISTS public.recurso_autores;
DROP TABLE IF EXISTS public.proyecto_tutores;
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
DROP TABLE IF EXISTS public.matriz_rbac;
DROP SEQUENCE IF EXISTS public.lineas_investigacion_id_seq;
DROP TABLE IF EXISTS public.lineas_investigacion;
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
DROP TABLE IF EXISTS public.detalles_proyectos;
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


ALTER TYPE public.accion_acceso_enum OWNER TO postgres;

--
-- Name: accion_auditoria_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_auditoria_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE'
);


ALTER TYPE public.accion_auditoria_enum OWNER TO postgres;

--
-- Name: estado_curso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_curso_enum AS ENUM (
    'borrador',
    'publicado',
    'archivado'
);


ALTER TYPE public.estado_curso_enum OWNER TO postgres;

--
-- Name: estado_propuesta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_propuesta_enum AS ENUM (
    'pendiente',
    'aceptada',
    'rechazada'
);


ALTER TYPE public.estado_propuesta_enum OWNER TO postgres;

--
-- Name: nivel_academico_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.nivel_academico_enum AS ENUM (
    'TSU',
    'Pregrado',
    'Especializacion',
    'Maestria',
    'Doctorado'
);


ALTER TYPE public.nivel_academico_enum OWNER TO postgres;

--
-- Name: tipo_interaccion_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_enum AS ENUM (
    'like',
    'bookmark'
);


ALTER TYPE public.tipo_interaccion_enum OWNER TO postgres;

--
-- Name: tipo_interaccion_usuario_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_usuario_enum AS ENUM (
    'like',
    'guardado'
);


ALTER TYPE public.tipo_interaccion_usuario_enum OWNER TO postgres;

--
-- Name: tipo_pregunta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_pregunta_enum AS ENUM (
    'multiple',
    'v_f',
    'corta'
);


ALTER TYPE public.tipo_pregunta_enum OWNER TO postgres;

--
-- Name: fn_auditoria_recursos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_auditoria_recursos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    -- Intenta leer una variable de sesión configurada por el backend, si no hay, asigna NULL
    v_usuario_actual INT := NULLIF(current_setting('app.usuario_actual', true), '')::INT; 
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', OLD.id, 'DELETE', v_usuario_actual, jsonb_build_object('titulo', OLD.titulo, 'id_tipo_recurso', OLD.id_tipo_recurso), NULL, CURRENT_TIMESTAMP);
        RETURN OLD;
        
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('titulo', NEW.titulo, 'id_tipo_recurso', NEW.id_tipo_recurso, 'ejemplares_totales', NEW.ejemplares_totales), CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;


ALTER FUNCTION public.fn_auditoria_recursos() OWNER TO postgres;

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


ALTER FUNCTION public.fn_auditoria_usuarios() OWNER TO postgres;

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


ALTER PROCEDURE public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone) OWNER TO postgres;

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


ALTER TABLE public.accesos_recursos OWNER TO postgres;

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


ALTER SEQUENCE public.accesos_recursos_id_seq OWNER TO postgres;

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


ALTER TABLE public.auditoria OWNER TO postgres;

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


ALTER SEQUENCE public.auditoria_id_seq OWNER TO postgres;

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
    cedula character varying(20)
);


ALTER TABLE public.autores OWNER TO postgres;

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


ALTER SEQUENCE public.autores_id_seq OWNER TO postgres;

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


ALTER TABLE public.carreras OWNER TO postgres;

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


ALTER SEQUENCE public.carreras_id_seq OWNER TO postgres;

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


ALTER TABLE public.categorias OWNER TO postgres;

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


ALTER SEQUENCE public.categorias_id_seq OWNER TO postgres;

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


ALTER TABLE public.cursos OWNER TO postgres;

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


ALTER SEQUENCE public.cursos_id_seq OWNER TO postgres;

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


ALTER TABLE public.detalles_articulos OWNER TO postgres;

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


ALTER TABLE public.detalles_investigaciones OWNER TO postgres;

--
-- Name: detalles_proyectos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalles_proyectos (
    id_recurso integer NOT NULL,
    fecha_defensa date,
    nivel_academico public.nivel_academico_enum DEFAULT 'Pregrado'::public.nivel_academico_enum,
    resumen text,
    id_carrera integer,
    comunidad_beneficiada text,
    palabras_clave text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_investigacion_padre integer,
    trayecto character varying(50) DEFAULT 'Trayecto I'::character varying,
    url_repositorio text,
    obj_general text,
    activo boolean DEFAULT true
);


ALTER TABLE public.detalles_proyectos OWNER TO postgres;

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


ALTER TABLE public.dimensiones_operativas OWNER TO postgres;

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


ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNER TO postgres;

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


ALTER TABLE public.editoriales OWNER TO postgres;

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


ALTER SEQUENCE public.editoriales_id_seq OWNER TO postgres;

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


ALTER TABLE public.etiquetas OWNER TO postgres;

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


ALTER SEQUENCE public.etiquetas_id_seq OWNER TO postgres;

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


ALTER TABLE public.historico_versiones_pst OWNER TO postgres;

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


ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNER TO postgres;

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


ALTER TABLE public.investigaciones_ofertadas OWNER TO postgres;

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


ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNER TO postgres;

--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNED BY public.investigaciones_ofertadas.id;


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


ALTER TABLE public.lineas_investigacion OWNER TO postgres;

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


ALTER SEQUENCE public.lineas_investigacion_id_seq OWNER TO postgres;

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


ALTER TABLE public.matriz_rbac OWNER TO postgres;

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


ALTER TABLE public.notificaciones OWNER TO postgres;

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


ALTER SEQUENCE public.notificaciones_id_seq OWNER TO postgres;

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


ALTER TABLE public.password_resets OWNER TO postgres;

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


ALTER SEQUENCE public.password_resets_id_seq OWNER TO postgres;

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


ALTER TABLE public.postulaciones_estudiantes OWNER TO postgres;

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


ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNER TO postgres;

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


ALTER TABLE public.preferencias_usuario OWNER TO postgres;

--
-- Name: privilegios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.privilegios (
    privilegio_id integer NOT NULL,
    nivel_privilegio integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.privilegios OWNER TO postgres;

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


ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNER TO postgres;

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


ALTER TABLE public.propuestas_empresa OWNER TO postgres;

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


ALTER SEQUENCE public.propuestas_empresa_id_seq OWNER TO postgres;

--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.propuestas_empresa_id_seq OWNED BY public.propuestas_empresa.id;


--
-- Name: proyecto_tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proyecto_tutores (
    id_recurso integer NOT NULL,
    id_tutor integer NOT NULL,
    tipo_tutor_id integer
);


ALTER TABLE public.proyecto_tutores OWNER TO postgres;

--
-- Name: recurso_autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_autores (
    id_recurso integer NOT NULL,
    id_autor integer NOT NULL
);


ALTER TABLE public.recurso_autores OWNER TO postgres;

--
-- Name: recurso_categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL
);


ALTER TABLE public.recurso_categorias OWNER TO postgres;

--
-- Name: recurso_clasificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_clasificaciones (
    id_recurso integer NOT NULL,
    id_linea_investigacion integer NOT NULL,
    id_dimension_operativa integer
);


ALTER TABLE public.recurso_clasificaciones OWNER TO postgres;

--
-- Name: recurso_etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_etiquetas (
    id_recurso integer NOT NULL,
    id_etiqueta integer NOT NULL
);


ALTER TABLE public.recurso_etiquetas OWNER TO postgres;

--
-- Name: recursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recursos (
    id integer NOT NULL,
    titulo character varying(255) NOT NULL,
    id_tipo_recurso integer NOT NULL,
    anio_publicacion integer,
    ejemplares_totales integer DEFAULT 1,
    ejemplares_disponibles integer DEFAULT 1,
    archivo_pdf character varying(255)
);


ALTER TABLE public.recursos OWNER TO postgres;

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


ALTER SEQUENCE public.recursos_id_seq OWNER TO postgres;

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


ALTER TABLE public.registro_actividad OWNER TO postgres;

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


ALTER SEQUENCE public.registro_actividad_id_seq OWNER TO postgres;

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


ALTER TABLE public.roles OWNER TO postgres;

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


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

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


ALTER TABLE public.system_audit_log OWNER TO postgres;

--
-- Name: telemetria_cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.telemetria_cache (
    id integer DEFAULT 1 NOT NULL,
    datos jsonb
);


ALTER TABLE public.telemetria_cache OWNER TO postgres;

--
-- Name: tipo_recurso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_recurso (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_recurso OWNER TO postgres;

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


ALTER SEQUENCE public.tipo_recurso_id_seq OWNER TO postgres;

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


ALTER TABLE public.tipo_tutor OWNER TO postgres;

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


ALTER SEQUENCE public.tipo_tutor_id_seq OWNER TO postgres;

--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_tutor_id_seq OWNED BY public.tipo_tutor.id;


--
-- Name: tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tutores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


ALTER TABLE public.tutores OWNER TO postgres;

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


ALTER SEQUENCE public.tutores_id_seq OWNER TO postgres;

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


ALTER TABLE public.usuarios OWNER TO postgres;

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


ALTER SEQUENCE public.usuarios_id_seq OWNER TO postgres;

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


ALTER TABLE public.visitantes OWNER TO postgres;

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


ALTER SEQUENCE public.visitantes_id_seq OWNER TO postgres;

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


ALTER TABLE public.waf_rate_limiter OWNER TO postgres;

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
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


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
-- Name: detalles_proyectos detalles_proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_pkey PRIMARY KEY (id_recurso);


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
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


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
-- Name: detalles_proyectos detalles_proyectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE SET NULL;


--
-- Name: detalles_proyectos detalles_proyectos_id_investigacion_padre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_investigacion_padre_fkey FOREIGN KEY (id_investigacion_padre) REFERENCES public.recursos(id) ON DELETE SET NULL;


--
-- Name: detalles_proyectos detalles_proyectos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


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
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


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
-- Name: proyecto_tutores proyecto_tutores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.detalles_proyectos(id_recurso) ON DELETE CASCADE;


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
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

\unrestrict JrXUbKrgJC9GRH8cNgVUOAecSzzbJ5ZQ3ImY4qkryaVsrYtUMO98COJf4MhQLox

