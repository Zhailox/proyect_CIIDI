<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\RoleMiddleware;
use App\Infrastructure\Database;

class ProjectController
{
    public function create()
    {
        // Solo investigadores pueden formular proyectos
        RoleMiddleware::check(['investigator', 'superadmin']);
        
        require __DIR__ . '/../../../templates/projects/create.php';
    }

    public function store()
    {
        RoleMiddleware::check(['investigator', 'superadmin']);

        $title = $_POST['title'] ?? '';
        $taxonomic_line_id = $_POST['taxonomic_line_id'] ?? '';
        $abstract = $_POST['abstract'] ?? '';
        
        // Simulación de guardado en BD con sentencias preparadas PDO
        try {
            $db = Database::getConnection();
            if ($db) {
                $stmt = $db->prepare("INSERT INTO projects (title, taxonomic_line_id, abstract, investigator_id, status) VALUES (?, ?, ?, ?, 'draft')");
                // investigator_id = 1 (Simulación de sesión actual)
                $stmt->execute([$title, $taxonomic_line_id, $abstract, 1]);
            }
        } catch (\Exception $e) {
            // Ignorar para la demo si la BD no está montada
        }

        $success = $title;
        require __DIR__ . '/../../../templates/projects/create.php';
    }
}
