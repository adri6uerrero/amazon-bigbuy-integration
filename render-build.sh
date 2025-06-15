#!/usr/bin/env bash

# Instala las dependencias de Composer
echo "Instalando dependencias de Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Copia el archivo .env si no existe
echo "Preparando archivo .env..."
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Genera la APP_KEY
echo "Generando APP_KEY..."
php artisan key:generate --force

# Ejecuta migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Despliegue listo para Render.com"
