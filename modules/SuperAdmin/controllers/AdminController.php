<?php

class AdminController {
    
    public function mostrarPanelAdministrativo() {
        
        // EL CANDADO REAL: Si alguien llega aquí por la URL sin ser nivel 3, es expulsado al login.
        Auth::requierePrivilegioMinimo(3);
        
        // Si pasa la validación, el código normal continúa
        $datosGraficas = $this->adminModel->obtenerEstadisticas();
        
        return [
            'stats' => $datosGraficas
        ];
    }
}
?>