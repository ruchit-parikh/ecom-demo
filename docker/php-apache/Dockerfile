FROM php:8.3-apache as workspace

# Arguments defined in docker-compose.yml
ARG INSTALL_XDEBUG=true

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions bcmath pdo_mysql @composer-2

RUN apt-get update && apt-get install -y \
    bash \
    mariadb-client \
    libmariadb-dev \
    docker.io \
    openssl \
    && rm -rf /var/lib/apt/lists/*

# Install the xdebug extension if INSTALL_XDEBUG is true, you can set on docker-compose.yml
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    install-php-extensions xdebug-^3 \
;fi

RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite headers

# Set the Apache server name to suppress a warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

USER www-data

CMD ["apache2-foreground"]
