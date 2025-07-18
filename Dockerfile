FROM php:8.2-apache

# Copia el código al contenedor
COPY . /var/www/html/

# Habilita módulos que podrías necesitar
RUN docker-php-ext-install mysqli

# Habilita rewrite (si usas URLs limpias)
RUN a2enmod rewrite
