# RENDER USA DOCKER Y NO PHP
FROM php:8.2-apache

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install mysqli

# Habilitar módulos de Apache requeridos
RUN a2enmod rewrite headers

# Copiar todo tu código al contenedor
COPY . /var/www/html/

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html/

EXPOSE 80
