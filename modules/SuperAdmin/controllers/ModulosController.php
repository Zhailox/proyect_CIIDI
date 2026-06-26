<?php
// modules/SuperAdmin/controllers/ModulosController.php

class ModulosController {
    
    public function index() {
        // En la vida real, esto lo harías llamando a: Kernel::getInstance()->getModuleManager()->getAll();
        
        $modulosRegistrados = [
            'core' => [
                'icono' => '🔐', 'nombre' => 'Autenticación y RBAC', 
                'desc' => 'Control de acceso, JWT y recuperación.', 
                'estado' => 'core',
                'dependencias_count' => 7 // Todos dependen de él
            ],
            'repo_pst' => [
                'icono' => '📚', 'nombre' => 'Repositorio PST', 
                'desc' => 'Motor de búsqueda neuronal y gestión documental.', 
                'estado' => 'online',
                'dependencias_count' => 2 
            ],
            'empresas' => [
                'icono' => '🏢', 'nombre' => 'Vinculación Empresarial', 
                'desc' => 'Banco de propuestas y puente productivo.', 
                'estado' => 'online',
                'dependencias_count' => 3 
            ],
            'chavo_ia' => [
                'icono' => '🤖', 'nombre' => 'ForoChatbot (Chavo)', 
                'desc' => 'Tutorías, foros públicos y asistencia LLM.', 
                'estado' => 'online',
                'dependencias_count' => 0 // Módulo aislado, apagarlo no rompe otros
            ],
            'cursos' => [
                'icono' => '🎓', 'nombre' => 'Cartelera de Formación', 
                'desc' => 'Oferta de cursos y promoción académica.', 
                'estado' => 'offline',
                'dependencias_count' => 0
            ]
        ];

        // Se envía la variable a la vista (usando tu motor de plantillas o un simple include)
        require_once __DIR__ . '/../views/gestor_modulos.php';
    }
}