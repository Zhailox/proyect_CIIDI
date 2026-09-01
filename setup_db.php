<?php
require_once __DIR__ . '/core/Database/Connection.php';

try {
    $db = Connection::getInstance();
    $sql = "
    DO $$ BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'estado_propuesta_enum') THEN
            CREATE TYPE estado_propuesta_enum AS ENUM ('pendiente', 'aceptada', 'rechazada');
        END IF;
    END $$;

    CREATE TABLE IF NOT EXISTS propuestas_empresa (
        id SERIAL PRIMARY KEY,
        nombre_empresa VARCHAR(255) NOT NULL,
        rif_empresa VARCHAR(50),
        persona_contacto VARCHAR(150) NOT NULL,
        telefono_contacto VARCHAR(50) NOT NULL,
        correo_contacto VARCHAR(150) NOT NULL,
        area_afectada VARCHAR(100) NOT NULL,
        descripcion_problema TEXT NOT NULL,
        estado estado_propuesta_enum DEFAULT 'pendiente',
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        nivel_trayecto VARCHAR(50)
    );
    ";
    $db->exec($sql);
    echo "Tabla propuestas_empresa creada con exito.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
