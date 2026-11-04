<?php
// core/Database/QueryBuilder.php
require_once __DIR__ . '/Connection.php';

class QueryBuilder {
    
    public $db;
    protected $tabla = '';
    protected $joins = [];
    protected $condiciones = [];
    protected $parametros = [];
    protected $columnas = '*';
    protected $limite = '';
    protected $orden = '';
    protected $grupo = '';
    protected $offset = '';

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Limpia completamente el estado interno del QueryBuilder.
     */
    public function reset(): self {
        $this->tabla = '';
        $this->joins = [];
        $this->condiciones = [];
        $this->parametros = [];
        $this->columnas = '*';
        $this->limite = '';
        $this->orden = '';
        $this->grupo = '';
        $this->offset = '';
        return $this;
    }

    /**
     * Define la tabla sobre la cual se operará y reinicia el estado.
     */
    public function tabla(string $nombre_tabla): self {
        $this->reset();
        $nombre_tabla = preg_replace('/^public\./i', '', trim($nombre_tabla));
        $this->tabla = preg_replace('/[^a-zA-Z0-9_\.\s]/', '', $nombre_tabla);
        return $this;
    }
    
    /**
     * Define las columnas a seleccionar.
     */
    public function select(string $columnas): self {
        $this->columnas = $columnas;
        return $this;
    }

    /**
     * Añade una condición WHERE con parámetros seguros y validación de operadores.
     */
    public function where(string $columna, string $operador, $valor): self {
        $operadoresValidos = ['=', '!=', '<>', '<', '>', '<=', '>=', 'LIKE', 'ILIKE', 'NOT LIKE', 'IS', 'IS NOT', 'IN', 'NOT IN'];
        $opSanitized = strtoupper(trim($operador));
        if (!in_array($opSanitized, $operadoresValidos, true)) {
            $opSanitized = '=';
        }
        if ($opSanitized === 'ILIKE') {
            $opSanitized = 'LIKE';
        }
        if (is_bool($valor)) {
            $valor = $valor ? 1 : 0;
        }
        $colSanitized = preg_replace('/[^a-zA-Z0-9_\.]/', '', $columna);
        $this->condiciones[] = "$colSanitized $opSanitized ?";
        $this->parametros[] = $valor;
        return $this;
    }

    /**
     * Añade una condición WHERE cruda con bindings.
     */
    public function whereRaw(string $sql, array $bindings = []): self {
        $sql = preg_replace('/\bILIKE\b/i', 'LIKE', $sql);
        $this->condiciones[] = $sql;
        foreach ($bindings as $binding) {
            $this->parametros[] = is_bool($binding) ? ($binding ? 1 : 0) : $binding;
        }
        return $this;
    }

    public function orderBy(string $columna, string $direccion = 'ASC'): self {
        $direccion = strtoupper(trim($direccion)) === 'DESC' ? 'DESC' : 'ASC';
        $colSanitized = preg_replace('/[^a-zA-Z0-9_\.]/', '', $columna);
        if (!empty($colSanitized)) {
            $this->orden = "ORDER BY $colSanitized $direccion";
        }
        return $this;
    }

    public function limit(int $cantidad): self {
        $this->limite = "LIMIT " . (int)$cantidad;
        return $this;
    }

    public function offset(int $cantidad): self {
        $this->offset = "OFFSET " . (int)$cantidad;
        return $this;
    }

    public function groupBy(string $columnas): self {
        $colSanitized = preg_replace('/[^a-zA-Z0-9_,\.\s]/', '', $columnas);
        if (!empty($colSanitized)) {
            $this->grupo = "GROUP BY $colSanitized";
        }
        return $this;
    }

    public function join(string $tabla_join, string $condicion, string $tipo = 'INNER'): self {
        $tipoSanitized = strtoupper($tipo);
        if (!in_array($tipoSanitized, ['INNER', 'LEFT', 'RIGHT'], true)) {
            $tipoSanitized = 'INNER';
        }
        $tabla_join = preg_replace('/\bpublic\./i', '', $tabla_join);
        $this->joins[] = "$tipoSanitized JOIN $tabla_join ON $condicion";
        return $this;
    }

    public function get(): array {
        if (!$this->db) return [];

        $sql = "SELECT {$this->columnas} FROM {$this->tabla}";
        
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
        
        return $stmt->fetchAll() ?: [];
    }

    public function first(): ?array {
        $this->limit(1);
        $resultados = $this->get();
        return !empty($resultados) ? $resultados[0] : null;
    }

    public function count(): int {
        if (!$this->db) return 0;

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

    public function insert(array $datos) {
        if (!$this->db) return false;

        $columnasLimpias = array_map(fn($col) => preg_replace('/[^a-zA-Z0-9_]/', '', (string)$col), array_keys($datos));
        $columnas = implode(", ", $columnasLimpias);
        $placeholders = implode(", ", array_fill(0, count($datos), "?"));
        
        $sql = "INSERT INTO {$this->tabla} ($columnas) VALUES ($placeholders)";
        
        $stmt = $this->db->prepare($sql);
        $valores = array_map(
            fn($v) => is_bool($v) ? ($v ? 1 : 0) : $v,
            array_values($datos)
        );
        $ok = $stmt->execute($valores);
        if (!$ok) {
            return false;
        }
        
        $lastId = $this->db->lastInsertId();
        return ($lastId !== false && $lastId !== '0' && $lastId !== '') ? $lastId : true;
    }

    public function update(array $datos): bool {
        if (!$this->db) return false;

        if (empty($this->condiciones)) {
            throw new Exception("Advertencia de Seguridad: Intentando hacer UPDATE sin condiciones (WHERE).");
        }

        $set_clause = [];
        $valores_update = [];

        foreach ($datos as $columna => $valor) {
            $colSanitized = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$columna);
            $set_clause[] = "$colSanitized = ?";
            $valores_update[] = is_bool($valor) ? ($valor ? 1 : 0) : $valor;
        }

        $sql = "UPDATE {$this->tabla} SET " . implode(", ", $set_clause);
        $sql .= " WHERE " . implode(" AND ", $this->condiciones);

        $parametros_finales = array_merge($valores_update, $this->parametros);

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($parametros_finales);
    }

    public function delete(): bool {
        if (!$this->db) return false;

        if (empty($this->condiciones)) {
            throw new Exception("Advertencia de Seguridad: Intentando hacer DELETE sin condiciones (WHERE).");
        }

        $sql = "DELETE FROM {$this->tabla} WHERE " . implode(" AND ", $this->condiciones);
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($this->parametros);
    }
}
