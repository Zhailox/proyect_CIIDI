<?php
// core/Database/QueryBuilder.php
require_once __DIR__ . '/Connection.php';

class QueryBuilder {
    
    protected $db;
    protected $tabla;
    protected $joins = [];
    protected $condiciones = [];
    protected $parametros = [];
    protected $columnas = '*';
    protected $limite = '';
    protected $orden = '';
    protected $grupo = '';
    protected $offset = '';

    public function __construct() {
        // Inyectamos la conexión Singleton automáticamente
        $this->db = Connection::getInstance();
    }

    /**
     * Define la tabla sobre la cual se operará y reinicia el estado.
     */
    public function tabla(string $nombre_tabla) {
        $this->tabla = $nombre_tabla;
        $this->condiciones = [];
        $this->parametros = [];
        $this->columnas = '*';
        $this->limite = '';
        $this->orden = '';
        $this->grupo = '';
        $this->offset = '';
        return $this; // Permite encadenamiento: $qb->tabla('usuarios')->where(...)
    }
    
    /**
     * Define las columnas a seleccionar.
     */
    public function select(string $columnas) {
        $this->columnas = $columnas;
        return $this;
    }
    
    

    /**
     * Añade una condición WHERE con parámetros seguros.
     */
    public function where(string $columna, string $operador, $valor) {
        $this->condiciones[] = "$columna $operador ?";
        $this->parametros[] = $valor;
        return $this;
    }

    /**
     * Añade una condición WHERE cruda con bindings.
     */
    public function whereRaw(string $sql, array $bindings = []) {
        $this->condiciones[] = $sql;
        $this->parametros = array_merge($this->parametros, $bindings);
        return $this;
    }

    /**
     * Ordenamiento (ORDER BY)
     */
    public function orderBy(string $columna, string $direccion = 'ASC') {
        $this->orden = "ORDER BY $columna $direccion";
        return $this;
    }

    /**
     * Límite (LIMIT)
     */
    public function limit(int $cantidad) {
        $this->limite = "LIMIT $cantidad";
        return $this;
    }

    /**
     * Desplazamiento (OFFSET)
     */
    public function offset(int $cantidad) {
        $this->offset = "OFFSET $cantidad";
        return $this;
    }

    /**
     * Agrupamiento (GROUP BY)
     */
    public function groupBy(string $columnas) {
        $this->grupo = "GROUP BY $columnas";
        return $this;
    }

    /**
     * Ejecuta una consulta SELECT y devuelve todos los resultados.
     */
    // NUEVO: Método para encadenar INNER JOIN, LEFT JOIN, etc.
    public function join(string $tabla_join, string $condicion, string $tipo = 'INNER') {
        $this->joins[] = "$tipo JOIN $tabla_join ON $condicion";
        return $this;
    }

    // ACTUALIZACIÓN: Le enseñamos al método get() a armar los JOINs, GROUP BY y OFFSET
    public function get() {
        $sql = "SELECT {$this->columnas} FROM {$this->tabla}";
        
        // Inyectamos los JOINs antes del WHERE
        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }
        
        if (!empty($this->condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $this->condiciones);
        }
        
        if (!empty($this->grupo)) {
            $sql .= " {$this->grupo}";
        }
        
        if (!empty($this->orden)) {
            $sql .= " {$this->orden}";
        }

        if (!empty($this->limite)) {
            $sql .= " {$this->limite}";
        }

        if (!empty($this->offset)) {
            $sql .= " {$this->offset}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->parametros);
        
        return $stmt->fetchAll();
    }

    /**
     * Ejecuta una consulta SELECT y devuelve solo el primer registro.
     */
    public function first() {
        $this->limit(1);
        $resultados = $this->get();
        return !empty($resultados) ? $resultados[0] : null;
    }
    public function count(): int {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla}";
        
        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }
        
        if (!empty($this->condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $this->condiciones);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->parametros);
        
        $resultado = $stmt->fetch();
        return $resultado ? (int) $resultado['total'] : 0;
    }
    /**
     * Inserta un nuevo registro y devuelve el ID generado.
     * Adaptado para PostgreSQL usando la cláusula RETURNING.
     */
    public function insert(array $datos) {
        $columnas = implode(", ", array_keys($datos));
        $placeholders = implode(", ", array_fill(0, count($datos), "?"));
        
        $sql = "INSERT INTO {$this->tabla} ($columnas) VALUES ($placeholders) RETURNING id";
        
        $stmt = $this->db->prepare($sql);
        $valores = array_values($datos);
        $stmt->execute($valores);
        
        $resultado = $stmt->fetch();
        return $resultado ? $resultado['id'] : false;
    }

    /**
     * Actualiza registros que coincidan con las condiciones.
     */
    public function update(array $datos) {
        if (empty($this->condiciones)) {
            throw new Exception("Advertencia de Seguridad: Intentando hacer UPDATE sin condiciones (WHERE).");
        }

        $set_clause = [];
        $valores_update = [];

        foreach ($datos as $columna => $valor) {
            $set_clause[] = "$columna = ?";
            $valores_update[] = $valor;
        }

        $sql = "UPDATE {$this->tabla} SET " . implode(", ", $set_clause);
        $sql .= " WHERE " . implode(" AND ", $this->condiciones);

        // Unimos los valores del SET con los valores del WHERE
        $parametros_finales = array_merge($valores_update, $this->parametros);

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($parametros_finales);
    }

    /**
     * Elimina registros que coincidan con las condiciones.
     */
    public function delete() {
        if (empty($this->condiciones)) {
            throw new Exception("Advertencia de Seguridad: Intentando hacer DELETE sin condiciones (WHERE).");
        }

        $sql = "DELETE FROM {$this->tabla} WHERE " . implode(" AND ", $this->condiciones);
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($this->parametros);
    }
}