FROM php:8.2-apache

RUN docker-php-ext-install mysqli

RUN a2enmod rewrite headers

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html/

# Habilitar uso de .htaccess
RUN echo "<Directory /var/www/html/> \
    AllowOverride All \
    Require all granted \
</Directory>" > /etc/apache2/conf-available/allow-htaccess.conf && \
    a2enconf allow-htaccess

EXPOSE 80
