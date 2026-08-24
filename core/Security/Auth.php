<?php
// core/Security/Auth.php

class Auth {
    
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Verifica si el usuario actual cumple con el nivel mínimo exigido.
     * Nivel 0 = Estudiante | Nivel 1 = Profesor | Nivel 2 = Bibliotecario | Nivel 3 = Admin
     */
    public static function requierePrivilegioMinimo(int $nivelExigido) {
        
        if (!self::check()) {
            // CORRECCIÓN: Sin barra al inicio
            header("Location: login"); 
            exit;
        }

        $nivelUsuario = $_SESSION['nivel_privilegio'] ?? -1;

        if ($nivelUsuario < $nivelExigido) {
            // CORRECCIÓN: Sin barra al inicio
            header("Location: login"); 
            exit;
        }

        return true;
    }

    /**
     * Devuelve los datos del usuario activo para usarlos en el Header o Sidebar
     */
    public static function usuario() {
        if (!self::check()) return null;

        return [
            'id' => $_SESSION['usuario_id'],
            'nombre' => $_SESSION['nombre_usuario'],
            'rol' => $_SESSION['rol_nombre'],
            'nivel' => $_SESSION['nivel_privilegio']
        ];
    }
}