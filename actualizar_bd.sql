-- 1. Crear la secuencia para la tabla historico_versiones_pst
CREATE SEQUENCE IF NOT EXISTS public.historico_versiones_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

-- 2. Crear la tabla historico_versiones_pst
CREATE TABLE IF NOT EXISTS public.historico_versiones_pst (
    id integer NOT NULL DEFAULT nextval('public.historico_versiones_pst_id_seq'::regclass),
    id_recurso integer NOT NULL,
    archivo_pdf character varying(500) NOT NULL,
    usuario_id integer,
    motivo character varying(255) DEFAULT 'Actualización'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT historico_versiones_pst_pkey PRIMARY KEY (id),
    CONSTRAINT fk_version_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE
);

-- Vincular la secuencia a la columna
ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNED BY public.historico_versiones_pst.id;

-- 3. Crear la tabla recurso_categorias
CREATE TABLE IF NOT EXISTS public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL,
    CONSTRAINT recurso_categorias_pkey PRIMARY KEY (id_recurso, id_categoria),
    CONSTRAINT recurso_categorias_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categorias(id) ON DELETE CASCADE,
    CONSTRAINT recurso_categorias_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE
);
