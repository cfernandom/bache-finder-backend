#!/bin/sh
set -e

# Ajusta permisos de las carpetas necesarias
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Waiting 15 seconds for MySQL..."
sleep 15

# Instala dependencias de Node.js y construye los assets
npm install
npm run build

composer update
php /var/www/html/artisan db:wipe
php /var/www/html/artisan migrate:refresh --seed
php /var/www/html/artisan key:generate
rm -rf /var/www/html/public/storage
php /var/www/html/artisan storage:link

echo "Laravel config success"

supervisord -c /etc/supervisor/laravel-worker.conf &
php-fpm
