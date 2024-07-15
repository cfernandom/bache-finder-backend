#!/bin/sh
   set -e

   composer update
   php /var/www/html/artisan db:wipe
   php /var/www/html/artisan migrate:refresh --seed
   php /var/www/html/artisan key:generate
   rm -rf /var/www/html/public/storage
   php /var/www/html/artisan storage:link

   supervisord -c /etc/supervisor/laravel-worker.conf
   php-fpm