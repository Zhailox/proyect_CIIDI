<?php

declare(strict_types=1);

namespace App\Http;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $uri, string $method)
    {
        // Limpiamos la ruta
        $path = $uri;
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            
            // Si es un arreglo (Controlador, Método)
            if (is_array($handler)) {
                $controller = new $handler[0]();
                $methodName = $handler[1];
                return $controller->$methodName();
            }
            
            // Si es una función anónima (callable)
            return call_user_func($handler);
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Not Found";
    }
}
