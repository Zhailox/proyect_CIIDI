<?php
// core/Interfaces/ModuleContract.php

interface ModuleContract {

    // Nombre del módulo
    public function getNombre(): string;

    // Rutas que manneja el módulo
    public function getRutas(): array;

    // La configuración para el sidebar
    public function getMenuConfig(): array;
    
    // Se obtiene el nombre del módulo para el gestor del superadmin
    public function getDescripcion(): string;
    
    // Se pone un array para saber la lista de dependencias de cada módulo por separado
    public function getDependencias(): array; 
    
    //Lo de las vistas del la página de inicio
    public function getHomeConfig(): array;

    //Lo del header
    public function getHeaderConfig(): array;
}
