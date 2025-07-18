FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Activar módulos necesarios de Apache
RUN a2enmod rewrite headers

# Copiar tu código fuente
COPY . /var/www/html/

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html

# Crear configuración para permitir .htaccess
RUN echo '<Directory /var/www/html/>' > /etc/apache2/conf-available/allow-htaccess.conf && \
    echo '    AllowOverride All' >> /etc/apache2/conf-available/allow-htaccess.conf && \
    echo '    Require all granted' >> /etc/apache2/conf-available/allow-htaccess.conf && \
    echo '</Directory>' >> /etc/apache2/conf-available/allow-htaccess.conf && \
    a2enconf allow-htaccess

# Establecer el directorio de trabajo
WORKDIR /var/www/html/

EXPOSE 80
