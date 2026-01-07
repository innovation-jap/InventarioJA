FROM php:8.2-apache

# Instalar dependencias para la extensión de MongoDB
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev pkg-config libssl-dev

# Instalar y habilitar la extensión de MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Habilitar mod_rewrite
RUN a2enmod rewrite

COPY . /var/www/html
WORKDIR /var/www/html   