<?php
// modules/Articulos/index.php

// Nos aseguramos de requerir la interfaz del core
require_once CORE_PATH . 'Interfaces/ModuleContract.php';

// Nombre de clase totalmente único para el módulo de la revista digital
class ArticulosModule implements ModuleContract {
    
    public function getNombre(): string {
        return 'Módulo de Revista Digital y Artículos';
    }

    public function getRutas(): array {
        return [
            // Ruta principal de la revista digital
            'articulos' => [
                'vista'  => __DIR__ . '/views/showcase_articulos.php', 
                'titulo' => 'Revista Digital - Portal de Artículos Científicos',
                'css'    => ['Articulos.css']
            ],
            // Ruta para ver las ediciones destacadas o números anteriores
            'revista-ediciones' => [
                'vista'  => __DIR__ . '/views/grid_revista.php', 
                'titulo' => 'Ediciones Destacadas - Archivo de la Revista',
                'css'    => ['Articulos.css']
            ],
            // Añadimos la ruta de lectura de un artículo individual para el desarrollo futuro
            'leer-articulo' => [
                'vista'  => __DIR__ . '/views/leer_articulo.php', 
                'titulo' => 'Lectura de Artículo - UPTTMBI',
                'css'    => ['Articulos.css']
            ]
        ];
    }

    public function getMenuConfig(): array {
        return [
            [
                'tipo'        => 'parent',
                'titulo'      => 'Artículos',
                'icono'       => 'ph-fill ph-newspaper',
                'enlace'      => 'articulos',
                // Rutas que mantendrán este bloque desplegado e iluminado en el menú
                'activadores' => ['articulos', 'revista-ediciones', 'leer-articulo'], 
                'subitems'    => [
                    ['ruta' => 'articulos', 'titulo' => 'Revista Digital'],
                    ['ruta' => 'revista-ediciones', 'titulo' => 'Ediciones Destacadas']
                ]
            ]
        ];
    }
    public function getDescripcion(): string {
        return 'Revista digital universitaria. Publicación de artículos científicos, crónicas y boletines del PNF de Informática.';
    }

    public function getDependencias(): array {
        // No depende de otros módulos para funcionar
        return []; 
    }
    public function getHomeConfig(): array {
        return [
            'tipo'        => 'standard', // Usará la franja HTML automatizada
            'icono'       => 'ph-fill ph-newspaper',
            'titulo'      => 'Revista Científica Universitaria',
            'descripcion' => 'Publicaciones académicas, crónicas y artículos destacados redactados por la comunidad de informática. Un espacio de divulgación tecnológica.',
            'enlace'      => 'showcase-articulos',
            'texto_boton' => 'LEER EDICIONES'
        ];
    }
    public function getHeaderConfig(): array {
        // El widget de notificaciones empresariales fue migrado al módulo VinculacionEmpresarial,
        // que es donde semánticamente corresponde (enlaza a 'banco-propuestas', ruta de ese módulo).
        return [];
    }
}

// Retornamos la instancia de la clase para que el Kernel la procese
return new ArticulosModule();