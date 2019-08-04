FROM php:7.3-alpine3.9
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

# Install deps
RUN apk update && apk add --no-cache wkhtmltopdf ttf-droid libzip

# Install php dev dependencies
RUN apk add --no-cache --virtual .build-green-deps \
    git \
    unzip \
    curl \
    libzip-dev libxml2-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev libxpm-dev

# Configure php extensions
RUN docker-php-ext-install soap && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip

ENV NOT_INSTALL 1

COPY docker/config/opcache.ini $PHP_INI_DIR/conf.d/

COPY . /var/www/html/

# Install Packages
RUN curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    cd /var/www/html && \
    chmod -R 777 ./cache && \
    chmod -R 777 ./files && \
    composer install --no-interaction --no-dev -o -a

RUN apk del .build-green-deps && \
    rm -rf /var/cache/apk/*

WORKDIR /var/www/html

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000"]
