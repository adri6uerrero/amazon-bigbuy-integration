#!/bin/bash

# Script de configuración para despliegue en Render
echo "Instalación de dependencias..."
composer install --optimize-autoloader --no-dev

echo "Configuración de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Migración de la base de datos..."
php artisan migrate --force

echo "¡Despliegue completo!"
