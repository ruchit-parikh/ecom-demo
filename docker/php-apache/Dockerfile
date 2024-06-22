# Use PHP with Apache as the base image
FROM php:8.3-apache as backend

# Arguments defined in docker-compose.yml
ARG INSTALL_XDEBUG=true

# Install extension installer and PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions bcmath pdo_mysql @composer-2

# Install extra packages using Debian package manager
RUN apt-get update && apt-get install -y \
    bash \
    mariadb-client \
    libmariadb-dev \
    docker.io \
    && rm -rf /var/lib/apt/lists/*

# Install the xdebug extension if INSTALL_XDEBUG is true
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    install-php-extensions xdebug-^3 \
;fi

# Copy xdebug configuration for remote debugging
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Set up document root and permissions
RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html

# Enable Apache mod_rewrite and other necessary modules
RUN a2enmod rewrite headers

# Set the Apache server name to suppress a warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Switch to www-data user for subsequent commands
USER www-data

# Start of the workspace stage
FROM backend as workspace

# The CMD to start Apache server
CMD ["apache2-foreground"]
