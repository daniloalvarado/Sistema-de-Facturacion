# Imagen base con PHP + Apache
FROM php:8.2-apache

# Instala la extensión mysqli (que tú usas)
RUN docker-php-ext-install mysqli

# Activa mod_rewrite por si usas .htaccess o rutas amigables
RUN a2enmod rewrite

# Copia todos tus archivos al contenedor
COPY . /var/www/html/

# Ajusta permisos (opcional, pero recomendado)
RUN chown -R www-data:www-data /var/www/html

# Puerto por defecto (Render usará el que necesite internamente)
EXPOSE 80
