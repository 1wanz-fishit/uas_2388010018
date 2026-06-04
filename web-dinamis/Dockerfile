FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files to the container
COPY . /var/www/html/

# Create uploads and assets directories if they don't exist
RUN mkdir -p /var/www/html/uploads
RUN mkdir -p /var/www/html/assets/images

# Set correct permissions so PHP can save uploaded photos
RUN chown -R www-data:www-data /var/www/html/uploads
RUN chmod -R 775 /var/www/html/uploads
