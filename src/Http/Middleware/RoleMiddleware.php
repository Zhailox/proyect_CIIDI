<?php

declare(strict_types=1);

namespace App\Http\Middleware;

class RoleMiddleware
{
    /**
     * Valida que el usuario actual tenga al menos uno de los roles permitidos.
     * @param array $allowedRoles Ej: ['investigator', 'superadmin']
     */
    public static function check(array $allowedRoles): void
    {
        // En una app real, obtendríamos el ID del usuario de la sesión y buscaríamos sus roles en la BD (tabla user_roles)
        // Para esta iteración simulamos un usuario logueado.
        session_start();
        
        // Simulación: Por defecto, todos pueden pasar en la demostración local.
        $userRoles = $_SESSION['user_roles'] ?? ['investigator', 'student', 'superadmin', 'teacher'];

        $hasAccess = false;
        foreach ($userRoles as $role) {
            if (in_array($role, $allowedRoles)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            http_response_code(403);
            die("Error 403: Acceso denegado. Se requiere el rol: " . implode(' o ', $allowedRoles));
        }
    }
}
