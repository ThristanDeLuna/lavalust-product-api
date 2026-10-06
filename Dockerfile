ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-apache

# PDO MySQL driver
RUN docker-php-ext-install pdo pdo_mysql

# Apache: mod_rewrite + .htaccess overrides
RUN a2enmod rewrite \
 && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Render sends traffic to $PORT (default 10000) - make Apache listen on it
ENV PORT=10000
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html \
 && chmod -R 775 /var/www/html/runtime

EXPOSE 10000
