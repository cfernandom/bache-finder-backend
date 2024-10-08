FROM php:8.3-fpm

WORKDIR /var/www/html
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update
RUN apt-get install -y wget gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin  \
    libpng-dev librsvg2-bin fswatch ffmpeg nano

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions bcmath mbstring intl mysqli pdo pdo_mysql opcache ldap readline pgsql sqlite3 gd curl imap xml zip bcmath soap memcached

# Descarga y ejecuta el script de configuración de NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_22.x -o nodesource_setup.sh && \
    bash nodesource_setup.sh && \
    rm nodesource_setup.sh

# Instala Node.js y npm
RUN apt-get update && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
RUN apt-get -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY php.ini "${PHP_INI_DIR}/conf.d/99-app.ini"

#si quieres habilitar el cron

#RUN apt-get update && apt-get install -y cron
#RUN echo "* * * * * root php /var/www/html/artisan schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab
#RUN touch /var/log/cron.log

#si necesitas instalar supervisor
RUN apt-get update && apt-get install -y supervisor
COPY laravel-worker.conf "/etc/supervisor"

# Copiar el script de entrada
COPY entrypoint.sh /usr/local/bin/

# Usa el script de entrada como punto de entrada
# CMD ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
