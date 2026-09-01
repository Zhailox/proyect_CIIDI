<?php
// modules/VinculacionEmpresarial/models/PropuestaEmpresaModel.php

require_once CORE_PATH . 'Database/QueryBuilder.php';

class PropuestaEmpresaModel {
    private $qb;

    public function __construct() {
        $this->qb = new QueryBuilder();
    }

    public function guardar($datos) {
        return $this->qb->tabla('propuestas_empresa')->insert($datos);
    }

    public function getTodas() {
        return $this->qb->tabla('propuestas_empresa')
            ->orderBy('fecha_creacion', 'DESC')
            ->get();
    }
    
    public function getAceptadas() {
        return $this->qb->tabla('propuestas_empresa')
            ->where('estado', '=', 'aceptada')
            ->orderBy('fecha_creacion', 'DESC')
            ->get();
    }

    public function getPendientes() {
        return $this->qb->tabla('propuestas_empresa')
            ->where('estado', '=', 'pendiente')
            ->orderBy('fecha_creacion', 'DESC')
            ->get();
    }

    public function actualizarEstado($id, $estado, $nivel_trayecto = null) {
        $datos = ['estado' => $estado];
        if ($nivel_trayecto !== null) {
            $datos['nivel_trayecto'] = $nivel_trayecto;
        }
        return $this->qb->tabla('propuestas_empresa')
            ->where('id', '=', $id)
            ->update($datos);
    }
}
