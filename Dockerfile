FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Copier le code dans le container
COPY . /var/www/html

# Configurer Apache
RUN echo 'DocumentRoot /var/www/html/public' > /etc/apache2/sites-available/000-default.conf

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances PHP
WORKDIR /var/www/html
RUN composer install --no-dev

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
