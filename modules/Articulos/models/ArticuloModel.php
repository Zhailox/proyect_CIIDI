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
                EXISTS (
                    SELECT 1 FROM recurso_autores ra 
                    JOIN autores a ON ra.id_autor = a.id 
                    WHERE ra.id_recurso = r.id AND LOWER(a.nombre_completo) LIKE LOWER(:q)
                ) OR
                EXISTS (
                    SELECT 1 FROM recurso_categorias rc 
                    JOIN categorias c ON rc.id_categoria = c.id 
                    WHERE rc.id_recurso = r.id AND LOWER(c.nombre) LIKE LOWER(:q)
                )
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

            $condiciones[] = "EXISTS (
                SELECT 1
                FROM recurso_categorias rc
                WHERE rc.id_recurso = r.id
                AND rc.id_categoria IN (" . implode(', ', $placeholders) . ")
            )";
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
                COALESCE((
                    SELECT STRING_AGG(DISTINCT c2.nombre, ', ' ORDER BY c2.nombre)
                    FROM recurso_categorias rc2
                    LEFT JOIN categorias c2 ON c2.id = rc2.id_categoria
                    WHERE rc2.id_recurso = r.id
                ), 'Sin categoría') AS categoria
            FROM recursos r
            INNER JOIN detalles_articulos d ON d.id_recurso = r.id
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
                EXISTS (
                    SELECT 1 FROM recurso_autores ra 
                    JOIN autores a ON ra.id_autor = a.id 
                    WHERE ra.id_recurso = r.id AND LOWER(a.nombre_completo) LIKE LOWER(:q)
                ) OR
                EXISTS (
                    SELECT 1 FROM recurso_categorias rc 
                    JOIN categorias c ON rc.id_categoria = c.id 
                    WHERE rc.id_recurso = r.id AND LOWER(c.nombre) LIKE LOWER(:q)
                )
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

        $condiciones[] = "EXISTS (
            SELECT 1
            FROM recurso_categorias rc
            WHERE rc.id_recurso = r.id
            AND rc.id_categoria IN (" . implode(', ', $placeholders) . ")
        )";
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
    public function registrarArticulo($titulo, $resumen, $categorias, $id_editorial, $archivo_pdf, $anio_publicacion, $volumen, $numero, $issn, $nombreImagen, $autores, $autores_nuevos, $etiquetas) {
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

            $stmtDetalle = $db->prepare("INSERT INTO detalles_articulos (
        id_recurso, id_editorial, volumen, numero, issn, imagen_portada, resumen
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtDetalle->execute([
                $id_recurso, 
                $id_editorial ?: null, 
                $volumen ?: null, 
                $numero ?: null, 
                $issn ?: null, 
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
                $stmtNewAutor = $db->prepare("INSERT INTO autores (nombre_completo, cedula) VALUES (?, ?) RETURNING id");
                
                // Extraemos todos los autores a la RAM una sola vez para iterar sobre ellos
                $stmtTodos = $db->query("SELECT id, nombre_completo FROM autores");
                $todosAutores = $stmtTodos->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($autores_nuevos as $json_autor) {
                    $datos = json_decode($json_autor, true);
                    if ($datos && !empty($datos['nombre'])) {
                        $nom = trim($datos['nombre']);
                        $cedula = !empty($datos['cedula']) ? trim($datos['cedula']) : null;
                        $autorId = null;

                        // A. Búsqueda exacta por cédula
                        if ($cedula) {
                            $stmt = $db->prepare("SELECT id FROM autores WHERE cedula = ?");
                            $stmt->execute([$cedula]);
                            $autorId = $stmt->fetchColumn();
                        }

                        // B. Búsqueda exacta por nombre
                        if (!$autorId) {
                            $stmt = $db->prepare("SELECT id FROM autores WHERE LOWER(TRIM(nombre_completo)) = LOWER(?)");
                            $stmt->execute([$nom]);
                            $autorId = $stmt->fetchColumn();
                        }

                        // C. Búsqueda difusa (Soundex + Levenshtein <= 2)
                        if (!$autorId) {
                            $normNom = $this->normalizarString($nom);
                            
                            foreach ($todosAutores as $candAut) {
                                $normCand = $this->normalizarString($candAut['nombre_completo']);
                                if ($normNom === $normCand || (levenshtein($normNom, $normCand) <= 2 && soundex($normNom) === soundex($normCand))) {
                                    $autorId = (int)$candAut['id'];
                                    break;
                                }
                            }
                        }

                        // D. Si definitivamente no existe, lo insertamos
                        if (!$autorId) {
                            $stmtNewAutor->execute([$nom, $cedula]);
                            $autorId = $stmtNewAutor->fetchColumn();
                            
                            // Lo añadimos a la lista local para no volver a insertarlo si viene repetido en el mismo envío
                            if ($autorId) {
                                $todosAutores[] = ['id' => $autorId, 'nombre_completo' => $nom];
                            }
                        }

                        if ($autorId) {
                            $autores_finales[] = (int)$autorId;
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
            if (!empty($categorias)) {
                $stmtCat = $db->prepare("INSERT INTO recurso_categorias (id_recurso, id_categoria) VALUES (?, ?)");
                foreach (array_unique(array_map('intval', $categorias)) as $catId) {
                    $stmtCat->execute([$id_recurso, $catId]);
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
        $db = Connection::getInstance();

        $stmt = $db->prepare("
            SELECT
                r.id,
                r.titulo,
                r.anio_publicacion,
                r.archivo_pdf,
                d.volumen,
                d.numero,
                d.issn,
                d.id_editorial,
                e.nombre AS editorial,
                d.imagen_portada,
                d.resumen,
                COALESCE((
                    SELECT STRING_AGG(DISTINCT c2.nombre, ', ' ORDER BY c2.nombre)
                    FROM recurso_categorias rc2
                    JOIN categorias c2 ON c2.id = rc2.id_categoria
                    WHERE rc2.id_recurso = r.id
                ), 'Sin categoría') AS categoria
            FROM recursos r
            JOIN detalles_articulos d ON d.id_recurso = r.id
            LEFT JOIN editoriales e ON d.id_editorial = e.id
            WHERE r.id = ?
              AND r.id_tipo_recurso = 3
            LIMIT 1
        ");

        $stmt->execute([(int) $id]);
        $articulo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$articulo) return null;

        $stmtAutores = $db->prepare("
            SELECT a.nombre_completo
            FROM recurso_autores ra
            JOIN autores a ON a.id = ra.id_autor
            WHERE ra.id_recurso = ?
            ORDER BY a.nombre_completo ASC
        ");
        $stmtAutores->execute([(int) $id]);
        $autores = $stmtAutores->fetchAll(PDO::FETCH_COLUMN);

        $stmtCategorias = $db->prepare("SELECT id_categoria FROM recurso_categorias WHERE id_recurso = ?");
        $stmtCategorias->execute([(int) $id]);

        $articulo['autores'] = $autores;
        $articulo['autores_text'] = !empty($autores) ? implode(', ', $autores) : 'Autor no registrado';
        $articulo['categorias'] = array_map('intval', $stmtCategorias->fetchAll(PDO::FETCH_COLUMN));

        return $articulo;
    }

    public function getArticulosSimilares(int $idArticulo, int $limit = 3): array {
        if ($limit <= 0) return [];
        $db = Connection::getInstance();
        $sql = "SELECT DISTINCT r.id, r.titulo, r.anio_publicacion, d.resumen, d.imagen_portada,
                       COALESCE((SELECT c2.nombre FROM recurso_categorias rc2 JOIN categorias c2 ON c2.id = rc2.id_categoria WHERE rc2.id_recurso = r.id LIMIT 1), 'Artículo') AS categoria
                FROM recursos r
                JOIN detalles_articulos d ON r.id = d.id_recurso
                JOIN recurso_categorias rc ON r.id = rc.id_recurso
                WHERE r.id_tipo_recurso = 3 AND r.id != ?
                AND rc.id_categoria IN (SELECT id_categoria FROM recurso_categorias WHERE id_recurso = ?)
                ORDER BY r.id DESC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idArticulo, $idArticulo, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $categorias,
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
) {
    $db = Connection::getInstance();

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
            SET id_editorial = ?, volumen = ?, numero = ?, issn = ?, imagen_portada = ?, resumen = ?
            WHERE id_recurso = ?
        ");
        $stmtDet->execute([
            $id_editorial ?: null,
            $volumen ?: null,
            $numero ?: null,
            $issn ?: null,
            $nombreImagen,
            $resumen,
            $id
        ]);

        // Limpiar relaciones viejas antes de volver a insertar
        $db->prepare("DELETE FROM recurso_autores WHERE id_recurso = ?")->execute([$id]);
        $db->prepare("DELETE FROM recurso_etiquetas WHERE id_recurso = ?")->execute([$id]);
        $db->prepare("DELETE FROM recurso_categorias WHERE id_recurso = ?")->execute([$id]);

        // Insertar categorías nuevas
        if (!empty($categorias)) {
            $stmtCat = $db->prepare("INSERT INTO recurso_categorias (id_recurso, id_categoria) VALUES (?, ?)");
            foreach (array_unique(array_map('intval', $categorias)) as $catId) {
                $stmtCat->execute([$id, $catId]);
            }
        }

        // Insertar autores nuevos
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

        // Insertar etiquetas nuevas
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
public function obtenerCatalogoPaginado($tabla, $buscar = '', $pagina = 1, $porPagina = 5) {
        $db = Connection::getInstance();
        $offset = ($pagina - 1) * $porPagina;
        
        $where = "";
        if ($buscar !== '') {
            // Usamos LOWER para que la búsqueda ignore mayúsculas y minúsculas
            $where = "WHERE LOWER(nombre) LIKE LOWER(:buscar)";
        }

        // 1. Contar el total para la paginación
        $stmtTotal = $db->prepare("SELECT COUNT(*) FROM $tabla $where");
        if ($buscar !== '') {
            $stmtTotal->bindValue(':buscar', "%" . trim($buscar) . "%", PDO::PARAM_STR);
        }
        $stmtTotal->execute();
        $total = (int)$stmtTotal->fetchColumn();

        // 2. Extraer los datos limitados
        $sql = "SELECT id, nombre FROM $tabla $where ORDER BY nombre ASC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        
        if ($buscar !== '') {
            $stmt->bindValue(':buscar', "%" . trim($buscar) . "%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'paginas' => max(1, (int)ceil($total / $porPagina)),
            'pagina_actual' => $pagina
        ];
    }

    public function buscarAutoresGestor($buscar) {
        if (trim($buscar) === '') return []; // Si no hay búsqueda, devolvemos vacío
        
        $db = Connection::getInstance();
        $termino = "%" . trim($buscar) . "%";
        // Buscamos por nombre o por cédula limitando a 20 resultados para no saturar
        $stmt = $db->prepare("SELECT id, nombre_completo, cedula FROM autores WHERE LOWER(nombre_completo) LIKE LOWER(:b) OR LOWER(cedula) LIKE LOWER(:b) ORDER BY nombre_completo ASC LIMIT 20");
        $stmt->bindValue(':b', $termino, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function crearCategoria($nombre) {
    $db = Connection::getInstance();
    $stmt = $db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
    $stmt->execute([trim($nombre)]);
    return true;
}

public function crearEtiqueta($nombre) {
    $db = Connection::getInstance();
    $stmt = $db->prepare("INSERT INTO etiquetas (nombre) VALUES (?)");
    $stmt->execute([trim($nombre)]);
    return true;
}

public function crearEditorial($nombre) {
    $db = Connection::getInstance();
    $stmt = $db->prepare("INSERT INTO editoriales (nombre) VALUES (?)");
    $stmt->execute([trim($nombre)]);
    return true;
}

public function eliminarCategoria($id) {
    $db = Connection::getInstance();
    $db->prepare("DELETE FROM recurso_categorias WHERE id_categoria = ?")->execute([$id]);
    $db->prepare("DELETE FROM categorias WHERE id = ?")->execute([$id]);
    return true;
}
public function obtenerCategoriasDelArticulo($id_recurso) {
    $db = Connection::getInstance();
    $stmt = $db->prepare("
        SELECT rc.id_categoria
        FROM recurso_categorias rc
        WHERE rc.id_recurso = ?
        ORDER BY rc.id_categoria ASC
    ");
    $stmt->execute([(int)$id_recurso]);
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
// --- MÉTODOS FALTANTES DE ELIMINACIÓN ---
    public function eliminarEtiqueta($id) {
        $db = Connection::getInstance();
        $db->prepare("DELETE FROM recurso_etiquetas WHERE id_etiqueta = ?")->execute([$id]);
        $db->prepare("DELETE FROM etiquetas WHERE id = ?")->execute([$id]);
        return true;
    }

    public function eliminarEditorial($id) {
        $db = Connection::getInstance();
        // Ponemos el ID en NULL en los artículos para que no se queden sin datos o exploten
        $db->prepare("UPDATE detalles_articulos SET id_editorial = NULL WHERE id_editorial = ?")->execute([$id]);
        $db->prepare("DELETE FROM editoriales WHERE id = ?")->execute([$id]);
        return true;
    }

    // --- MÉTODOS NUEVOS DE ACTUALIZACIÓN (EDICIÓN) ---
    public function actualizarCategoria($id, $nombre) {
        $db = Connection::getInstance();
        $db->prepare("UPDATE categorias SET nombre = ? WHERE id = ?")->execute([trim($nombre), $id]);
        return true;
    }

    public function actualizarEtiqueta($id, $nombre) {
        $db = Connection::getInstance();
        $db->prepare("UPDATE etiquetas SET nombre = ? WHERE id = ?")->execute([trim($nombre), $id]);
        return true;
    }

    public function actualizarEditorial($id, $nombre) {
        $db = Connection::getInstance();
        $db->prepare("UPDATE editoriales SET nombre = ? WHERE id = ?")->execute([trim($nombre), $id]);
        return true;
    }

    public function actualizarAutor($id, $nombre, $cedula) {
        $db = Connection::getInstance();
        // Actualizamos nombre y cédula (si la cédula está vacía la guardamos como NULL)
        $db->prepare("UPDATE autores SET nombre_completo = ?, cedula = ? WHERE id = ?")
           ->execute([trim($nombre), trim($cedula) ?: null, $id]);
        return true;
    }
    
    private function normalizarString(string $str): string {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $unwanted = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u', 'ñ'=>'n',
            'Á'=>'a', 'É'=>'e', 'Í'=>'i', 'Ó'=>'o', 'Ú'=>'u', 'Ü'=>'u', 'Ñ'=>'n'
        ];
        $str = strtr($str, $unwanted);
        return preg_replace('/[^a-z0-9\s]/', '', $str);
    }
}
