FROM php:8.2-apache

# Instalar extensiones necesarias para PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite (si luego usas .htaccess)
RUN a2enmod rewrite

# Copiar todo el proyecto al docroot de Apache
COPY . /var/www/html

# Directorio de trabajo
WORKDIR /var/www/html  