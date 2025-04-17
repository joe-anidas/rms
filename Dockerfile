FROM php:7.4-apache

# Enable mysqli
RUN docker-php-ext-install mysqli

# Copy project files
COPY . /var/www/html/

# Enable .htaccess and rewrite mod
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
