<?php
// modules/Autenticacion/controllers/PerfilController.php
require_once CORE_PATH . 'Security/Auth.php';

class PerfilController {

    public function mostrarDashboard() {
        // 1. Pide privilegios solo superiores al del público general (que es 999)
        Auth::requierePrivilegioMinimo(998);
        
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