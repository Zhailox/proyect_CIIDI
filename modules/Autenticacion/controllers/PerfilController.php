<?php
// modules/Autenticacion/controllers/PerfilController.php
require_once CORE_PATH . 'Security/Auth.php';

class PerfilController {

    public function mostrarDashboard() {
        // 1. EL GUARDIÁN: Validamos seguridad ANTES de imprimir HTML
        Auth::requierePrivilegioMinimo(0);
        
        // 2. Extraemos los datos del usuario activo
        $usuario = Auth::usuario();
        
        // 3. Lógica de negocio (Ej: Separar el nombre)
        $primerNombre = explode(' ', trim($usuario['nombre']))[0];
        $usuario['primer_nombre'] = $primerNombre;
        
        // 4. Retornamos las variables. El Kernel las inyectará en 'mi_dashboard.php'
        return [
            'usuarioActual' => $usuario
        ];
    }
}