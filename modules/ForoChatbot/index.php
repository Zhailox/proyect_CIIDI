<?php
// modules/ForoChatbot/index.php

// Requerimos la interfaz base del sistema
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

// Clase única para el módulo de IA Conversacional
class ForoChatbotModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de IA Conversacional y Foros (Chavo)';
    }

    public function getRutas(): array {
        return [
            'chavo' => [
                'vista'  => __DIR__ . '/views/showcase_foros.php', 
                'titulo' => 'Hilos de Discusión - Foro UPTTMBI',
                'css'    => ['ForoChatbot.css']
            ],
            // NUEVA RUTA: Para leer un hilo específico
            'leer-hilo' => [
                'vista'  => __DIR__ . '/views/leer_hilo.php', 
                'titulo' => 'Viendo Hilo - Foro Público',
                'css'    => ['ForoChatbot.css']
            ],
            'mis-chats' => [
                'vista'  => __DIR__ . '/views/lista_chats.php', 
                'titulo' => 'Mis Grupos de Proyecto',
                'css'    => ['ForoChatbot.css']
            ],
            'sala-chat' => [
                'vista'  => __DIR__ . '/views/sala_chat.php', 
                'titulo' => 'Chavo - IA Conversacional Local',
                'css'    => ['ForoChatbot.css']
            ]
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Foro',
                'icono'       => 'ph-fill ph-chats-teardrop',
                'enlace'      => 'chavo',
                // Rutas que mantienen iluminado este bloque en el menú
                'activadores' => ['chavo', 'sala-chat', 'mis-chats'],
                'subitems'    => [
                    ['ruta' => 'chavo', 'titulo' => 'Hilos de Discusión'],
                    ['ruta' => 'mis-chats', 'titulo' => 'Mis Proyectos']
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Ecosistema de tutorías, foros públicos y asistencia metodológica impulsada por LLM Local (Chavo).';
    }
    public function getDependencias(): array {
        return ['Autenticacion']; 
    }
    public function getHomeConfig(): array {
        return [
            'icono'       => 'ph-fill ph-chats-teardrop',
            'titulo'      => 'Comunidad y Tutorías',
            'descripcion' => 'Ecosistema de foros públicos, discusión de código y asistencia metodológica impulsada por inteligencia artificial.',
            'enlace'      => 'chavo',
            'texto_boton' => 'ENTRAR AL FORO',
            'destacado'   => false
        ];
    }
     public function getHeaderConfig(): array {
        return [];
    }
}

// Retornamos la instancia para que el Kernel la registre
return new ForoChatbotModule();