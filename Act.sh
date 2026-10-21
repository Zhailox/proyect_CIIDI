#!/bin/bash

# ==============================================================================
# Script de Despliegue: Entorno FFI y ONNX para PHP 8.4 en Debian
# Ejecutar desde la raíz del proyecto donde está el composer.json
# ==============================================================================

echo "⚙️ Iniciando configuración del entorno para PHP 8.4..."

# 1. Instalar la extensión FFI (el manejador de paquetes de Debian usa la versión mayor.menor)
sudo apt-get update
sudo apt-get install -y php8.4-ffi

# 2. Habilitar ffi.enable = true en CLI y Apache
# Se crea un archivo custom para sobreescribir la seguridad sin tocar el php.ini base
echo "Inyectando directivas de FFI..."
echo "ffi.enable = true" | sudo tee /etc/php/8.4/cli/conf.d/99-onnx-ffi.ini > /dev/null
echo "ffi.enable = true" | sudo tee /etc/php/8.4/apache2/conf.d/99-onnx-ffi.ini > /dev/null

# 3. Reiniciar Apache para que el motor web reconozca el cambio
echo "🔄 Reiniciando el servicio Apache2..."
sudo systemctl restart apache2

# 4. Instalar dependencias de Composer (excluyendo paquetes de desarrollo)
echo "📦 Descargando librerías y motor C++ de ONNX..."
composer install --no-dev --optimize-autoloader

echo "✅ ¡Despliegue completado! El Monstruo está configurado en el servidor."