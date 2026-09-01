<?php
require_once CORE_PATH . 'Database/QueryBuilder.php';

class ArticuloModel {
    
    public function obtenerUltimosArticulos(array $filtros = [], $pagina = 1, $porPagina = 20) {
        $db = Connection::getInstance();

        $pagina = max(1, (int) $pagina);
        $porPagina = max(1, (int) $porPagina);
        $offset = ($pagina - 1) * $porPagina;

        $condiciones = ['r.id_tipo_recurso = :tipo'];
        $parametros = [':tipo' => 3];

        if (!empty($filtros['q'])) {
            $texto = trim($filtros['q']);
            $condiciones[] = "(
                LOWER(r.titulo) LIKE LOWER(:q) OR
                LOWER(d.resumen) LIKE LOWER(:q) OR
                LOWER(a.nombre_completo) LIKE LOWER(:q) OR
                LOWER(c.nombre) LIKE LOWER(:q)
            )";
            $parametros[':q'] = '%' . $texto . '%';
        }

        if (!empty($filtros['year'])) {
            $condiciones[] = 'r.anio_publicacion = :year';
            $parametros[':year'] = (int) $filtros['year'];
        }

        if (!empty($filtros['categorias'])) {
            $categorias = array_map('intval', $filtros['categorias']);
            $placeholders = [];

            foreach ($categorias as $i => $id) {
                $key = ':cat' . $i;
                $placeholders[] = $key;
                $parametros[$key] = $id;
            }

            $condiciones[] = 'd.id_categoria IN (' . implode(', ', $placeholders) . ')';
        }

        if (!empty($filtros['etiquetas'])) {
            $etiquetas = array_map('intval', $filtros['etiquetas']);
            $placeholders = [];

            foreach ($etiquetas as $i => $id) {
                $key = ':tag' . $i;
                $placeholders[] = $key;
                $parametros[$key] = $id;
            }

            $condiciones[] = "EXISTS (
                SELECT 1
                FROM recurso_etiquetas re
                WHERE re.id_recurso = r.id
                AND re.id_etiqueta IN (" . implode(', ', $placeholders) . ")
            )";
        }

        $parametros[':limit'] = $porPagina;
        $parametros[':offset'] = $offset;

        $sql = "
            SELECT DISTINCT
                r.id,
                r.titulo,
                r.anio_publicacion,
                d.volumen,
                d.numero,
                d.imagen_portada,
                d.resumen,
                c.nombre AS categoria
            FROM recursos r
            INNER JOIN detalles_articulos d ON d.id_recurso = r.id
            LEFT JOIN categorias c ON c.id = d.id_categoria
            LEFT JOIN recurso_autores ra ON ra.id_recurso = r.id
            LEFT JOIN autores a ON a.id = ra.id_autor
            WHERE " . implode(' AND ', $condiciones) . "
            ORDER BY r.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($parametros);
        $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($articulos as &$articulo) {
            $stmtAutores = $db->prepare("
                SELECT a.nombre_completo
                FROM recurso_autores ra
                JOIN autores a ON a.id = ra.id_autor
                WHERE ra.id_recurso = ?
                ORDER BY a.nombre_completo ASC
            ");

            $stmtAutores->execute([$articulo['id']]);
            $autores = $stmtAutores->fetchAll(PDO::FETCH_COLUMN);

            $articulo['autores'] = $autores;
            $articulo['autores_text'] = !empty($autores) ? implode(', ', $autores) : 'Autor no registrado';
        }

        return $articulos;
    }

public function contarArticulos(array $filtros = []) {
    $db = Connection::getInstance();

    $condiciones = ['r.id_tipo_recurso = :tipo'];
    $parametros = [':tipo' => 3];

    if (!empty($filtros['q'])) {
        $texto = trim($filtros['q']);
        $condiciones[] = "(
            LOWER(r.titulo) LIKE LOWER(:q) OR
            LOWER(d.resumen) LIKE LOWER(:q) OR
            LOWER(a.nombre_completo) LIKE LOWER(:q) OR
            LOWER(c.nombre) LIKE LOWER(:q)
        )";
        $parametros[':q'] = '%' . $texto . '%';
    }

    if (!empty($filtros['year'])) {
        $condiciones[] = 'r.anio_publicacion = :year';
        $parametros[':year'] = (int) $filtros['year'];
    }

    if (!empty($filtros['categorias'])) {
        $categorias = array_map('intval', $filtros['categorias']);
        $placeholders = [];

        foreach ($categorias as $i => $id) {
            $key = ':cat' . $i;
            $placeholders[] = $key;
            $parametros[$key] = $id;
        }

        $condiciones[] = 'd.id_categoria IN (' . implode(', ', $placeholders) . ')';
    }

    if (!empty($filtros['etiquetas'])) {
        $etiquetas = array_map('intval', $filtros['etiquetas']);
        $placeholders = [];

        foreach ($etiquetas as $i => $id) {
            $key = ':tag' . $i;
            $placeholders[] = $key;
            $parametros[$key] = $id;
        }

        $condiciones[] = "EXISTS (
            SELECT 1
            FROM recurso_etiquetas re
            WHERE re.id_recurso = r.id
            AND re.id_etiqueta IN (" . implode(', ', $placeholders) . ")
        )";
    }

    $sql = "
        SELECT COUNT(*) AS total
        FROM (
            SELECT DISTINCT r.id
            FROM recursos r
            INNER JOIN detalles_articulos d ON d.id_recurso = r.id
            LEFT JOIN categorias c ON c.id = d.id_categoria
            LEFT JOIN recurso_autores ra ON ra.id_recurso = r.id
            LEFT JOIN autores a ON a.id = ra.id_autor
            WHERE " . implode(' AND ', $condiciones) . "
        ) AS sub
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute($parametros);
    return (int) $stmt->fetchColumn();
}

public function obtenerArticulosPaginados(array $filtros = [], $pagina = 1, $porPagina = 16) {
    $pagina = max(1, (int) $pagina);
    $porPagina = max(1, (int) $porPagina);

    $articulos = $this->obtenerUltimosArticulos($filtros, $pagina, $porPagina);
    $total = $this->contarArticulos($filtros);
    $paginas = max(1, (int) ceil($total / $porPagina));

    return [
        'articulos' => $articulos,
        'total' => $total,
        'paginas' => $paginas,
        'pagina' => $pagina,
        'porPagina' => $porPagina
    ];
}
public function obtenerCategorias() {
    $db = Connection::getInstance();
    $stmt = $db->prepare("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerEtiquetas() {
    $db = Connection::getInstance();
    $stmt = $db->prepare("SELECT id, nombre FROM etiquetas ORDER BY nombre ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function registrarArticulo($titulo, $resumen, $id_categoria, $id_editorial, $archivo_pdf, $anio_publicacion, $volumen, $numero, $issn, $nombreImagen, $autores, $autores_nuevos, $etiquetas) {
        $db = Connection::getInstance();
        
        try {
            $db->beginTransaction();
            $qb = new QueryBuilder();

            $id_recurso = $qb->tabla('recursos')->insert([
                'titulo' => $titulo,
                'id_tipo_recurso' => 3, 
                'anio_publicacion' => $anio_publicacion,
                'archivo_pdf' => $archivo_pdf
            ]);

            if (!$id_recurso) throw new Exception("Error al crear el recurso padre.");

            // 2. Insertar Detalles
            // Usamos PDO directo aquí porque la tabla detalles_articulos no tiene una columna serial llamada "id", 
            // su llave primaria es id_recurso. Si usamos el QueryBuilder, intentará devolver un "id" y PostgreSQL dará error.

            $stmtDetalle = $db->prepare("INSERT INTO detalles_articulos (id_recurso, id_editorial, volumen, numero, issn, id_categoria, imagen_portada, resumen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtDetalle->execute([
                $id_recurso, 
                $id_editorial ?: null, 
                $volumen ?: null, 
                $numero ?: null, 
                $issn ?: null, 
                $id_categoria ?: null, 
                $nombreImagen,
                $resumen
            ]);
            // 3. Procesar Autores
            $autores_finales = [];
            
            // Los que ya existían
            if (!empty($autores)) {
                foreach ($autores as $id_autor) {
                    if (is_numeric($id_autor)) {
                        $autores_finales[] = (int)$id_autor;
                    }
                }
            }
            
            // Creamos los autores nuevos ingresados al vuelo (Corrección PostgreSQL)
            if (!empty($autores_nuevos)) {
                // Preparamos la consulta pidiendo explícitamente que nos devuelva el ID generado
                $stmtNewAutor = $db->prepare("INSERT INTO autores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");
                
                foreach ($autores_nuevos as $json_autor) {
                    $datos = json_decode($json_autor, true);
                    if ($datos) {
                        $cedula = !empty($datos['cedula']) ? $datos['cedula'] : null;
                        
                        // Ejecutamos y capturamos el ID devuelto por Postgres
                        $stmtNewAutor->execute([$datos['nombre'], $cedula]);
                        $nuevo_id = $stmtNewAutor->fetchColumn();
                        
                        if ($nuevo_id) {
                            $autores_finales[] = $nuevo_id;
                        }
                    }
                }
            }

            // 4. Insertar Relación Recurso-Autores (Tabla Pivote)
            if (!empty($autores_finales)) {
                $stmtAutor = $db->prepare("INSERT INTO recurso_autores (id_recurso, id_autor) VALUES (?, ?)");
                foreach (array_unique($autores_finales) as $id_a) {
                    $stmtAutor->execute([$id_recurso, $id_a]);
                }
            }

            // 5. Insertar Etiquetas (Tabla Pivote)
            if (!empty($etiquetas)) {
                $stmtTag = $db->prepare("INSERT INTO recurso_etiquetas (id_recurso, id_etiqueta) VALUES (?, ?)");
                foreach ($etiquetas as $id_tag) {
                    $stmtTag->execute([$id_recurso, (int)$id_tag]);
                }
            }

            // Si todo salió bien, guardamos los cambios físicamente
            $db->commit();
            return true;

        } catch (Exception $e) {
            // Si algo explota, deshacemos todo
            $db->rollBack();
            throw $e; 
        }
    }
    public function eliminarArticulo($id_recurso) {
        $db = Connection::getInstance();
        
        // 1. Buscar si tiene una imagen física para borrarla del disco duro
        $stmt = $db->prepare("SELECT imagen_portada FROM detalles_articulos WHERE id_recurso = ?");
        $stmt->execute([$id_recurso]);
        $portada = $stmt->fetchColumn();

        // Solo borramos si existe, si no es la por defecto, y si NO es una URL externa (http)
        if ($portada && $portada !== 'default_article.jpg' && strpos($portada, 'http') !== 0) {
            $rutaFisica = __DIR__ . '/../../../public/uploads/articulos/' . $portada;
            if (file_exists($rutaFisica)) {
                unlink($rutaFisica); // Borra el archivo del servidor
            }
        }

        // 2. Eliminar de la base de datos.
        // Gracias a las restricciones ON DELETE CASCADE de PostgreSQL, borrar en 'recursos'
        // eliminará automáticamente sus datos en 'detalles_articulos', 'recurso_autores', etc.
        $qb = new QueryBuilder();
        return $qb->tabla('recursos')->where('id', '=', $id_recurso)->delete();
    }
public function obtenerArticuloPorId($id) {
    $qb = new QueryBuilder();

    $articulos = $qb->tabla('recursos r')
        ->select('
            r.id,
            r.titulo,
            r.anio_publicacion,
            r.archivo_pdf,
            d.volumen,
            d.numero,
            d.issn,
            d.id_categoria,
            d.id_editorial,
            d.imagen_portada,
            d.resumen,
            c.nombre as categoria
        ')
        ->join('detalles_articulos d', 'r.id = d.id_recurso')
        ->join('categorias c', 'd.id_categoria = c.id', 'LEFT')
        ->where('r.id', '=', (int) $id)
        ->where('r.id_tipo_recurso', '=', 3)
        ->limit(1)
        ->get();

    $articulo = $articulos[0] ?? null;

    if (!$articulo) {
        return null;
    }

    $db = Connection::getInstance();

    $stmt = $db->prepare("
        SELECT a.nombre_completo
        FROM recurso_autores ra
        JOIN autores a ON a.id = ra.id_autor
        WHERE ra.id_recurso = ?
        ORDER BY a.nombre_completo ASC
    ");

    $stmt->execute([(int)$id]);
    $autores = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $articulo['autores'] = $autores;
    $articulo['autores_text'] = !empty($autores) ? implode(', ', $autores) : 'Autor no registrado';

    return $articulo;
    }
public function obtenerAutoresDelArticulo($id_recurso) {
    $db = Connection::getInstance();
    $stmt = $db->prepare("
        SELECT ra.id_autor
        FROM recurso_autores ra
        WHERE ra.id_recurso = ?
        ORDER BY ra.id_autor ASC
    ");
    $stmt->execute([(int)$id_recurso]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

public function obtenerEtiquetasDelArticulo($id_recurso) {
    $db = Connection::getInstance();
    $stmt = $db->prepare("
        SELECT re.id_etiqueta
        FROM recurso_etiquetas re
        WHERE re.id_recurso = ?
        ORDER BY re.id_etiqueta ASC
    ");
    $stmt->execute([(int)$id_recurso]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

public function actualizarArticulo(
    $id,
    $titulo,
    $resumen,
    $id_categoria,
    $id_editorial,
    $archivo_pdf,
    $anio_publicacion,
    $volumen,
    $numero,
    $issn,
    $nombreImagen,
    $autores,
    $autores_nuevos,
    $etiquetas
){    $db = Connection::getInstance();

    try {
        $db->beginTransaction();

        $stmtRec = $db->prepare("
            UPDATE recursos
            SET titulo = ?, anio_publicacion = ?, archivo_pdf = ?
            WHERE id = ?
        ");
        $stmtRec->execute([$titulo, $anio_publicacion, $archivo_pdf, $id]);

        $stmtDet = $db->prepare("
            UPDATE detalles_articulos
            SET id_editorial = ?, volumen = ?, numero = ?, issn = ?, id_categoria = ?, imagen_portada = ?, resumen = ?
            WHERE id_recurso = ?
        ");
        $stmtDet->execute([$id_editorial ?: null, $volumen ?: null, $numero ?: null, $issn ?: null, $id_categoria ?: null, $nombreImagen, $resumen, $id]);

        $db->prepare("DELETE FROM recurso_autores WHERE id_recurso = ?")->execute([$id]);

        $autores_finales = [];

        if (!empty($autores)) {
            foreach ($autores as $id_autor) {
                if (is_numeric($id_autor)) {
                    $autores_finales[] = (int)$id_autor;
                }
            }
        }

        if (!empty($autores_nuevos)) {
            $stmtNewAutor = $db->prepare("INSERT INTO autores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");

            foreach ($autores_nuevos as $json_autor) {
                $datos = json_decode($json_autor, true);

                if ($datos) {
                    $cedula = !empty($datos['cedula']) ? $datos['cedula'] : null;

                    $stmtNewAutor->execute([$datos['nombre'], $cedula]);
                    $nuevo_id = $stmtNewAutor->fetchColumn();

                    if ($nuevo_id) {
                        $autores_finales[] = (int)$nuevo_id;
                    }
                }
            }
        }

        if (!empty($autores_finales)) {
            $stmtAutor = $db->prepare("INSERT INTO recurso_autores (id_recurso, id_autor) VALUES (?, ?)");
            foreach (array_unique($autores_finales) as $id_a) {
                $stmtAutor->execute([$id, $id_a]);
            }
        }
        $db->prepare("DELETE FROM recurso_etiquetas WHERE id_recurso = ?")->execute([$id]);
        if (!empty($etiquetas)) {
            $stmtTag = $db->prepare("INSERT INTO recurso_etiquetas (id_recurso, id_etiqueta) VALUES (?, ?)");
            foreach ($etiquetas as $id_tag) {
                $stmtTag->execute([$id, (int)$id_tag]);
            }
        }

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
}