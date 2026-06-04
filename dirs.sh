#!/bin/bash

# 1. ZONA PÚBLICA
echo "Creando directorios públicos..."
mkdir -p public/assets/{css,js,img,fonts}

# 2. EL MOTOR (CORE)
echo "Creando directorios del motor principal..."
mkdir -p core/{System,Http,Security,Database,Interfaces,Views}

# 3. LOS MÓDULOS (PARQUETES)
echo "Creando la estructura de los módulos..."
mkdir -p modules/Autenticacion/{controllers,models,views}
mkdir -p modules/RepositorioPST/{assets,controllers,services,models,views}
mkdir -p modules/Investigaciones/{controllers,models,views}
mkdir -p modules/Articulos/{controllers,models,views}
mkdir -p modules/VinculacionEmpresarial/{controllers,models,views}
mkdir -p modules/Cursos/{controllers,models,views}
mkdir -p modules/ForoChatbot/{assets,controllers,services,models,views}
mkdir -p modules/SuperAdmin/{controllers,views}

# 4. LA BÓVEDA (STORAGE)
echo "Creando la bóveda de almacenamiento..."
mkdir -p storage/documentos/{pst,articulos,respaldos_empresas}
mkdir -p storage/{backups,logs}

echo "¡Esqueleto de carpetas creado al 100%! Cero archivos invasivos."