#!/bin/bash

echo "🛠️ Iniciando configuración del entorno local..."

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar .env si no existe
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generar clave de aplicación
php artisan key:generate

# Migrar base de datos
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar Laravel Reverb
php artisan reverb:start