FROM php:7.4-alpine3.13
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

# Install deps
RUN apk update && apk add --no-cache wkhtmltopdf ttf-droid libzip

# Install php dev dependencies
RUN apk add --no-cache --virtual .build-green-deps \
    git \
    unzip \
    curl \
    libxml2-dev

# Configure php extensions
RUN docker-php-ext-install soap && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache

ENV DOCKER 1

COPY docker/config/opcache.ini $PHP_INI_DIR/conf.d/

COPY . /var/www/html/

# Install Packages
RUN curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    cd /var/www/html && \
    mkdir ./cache && chmod -R 777 ./cache && \
    mkdir ./files && chmod -R 777 ./files && \
    composer install --no-interaction --no-dev -o -a

RUN apk del .build-green-deps && \
    rm -rf /var/cache/apk/*

WORKDIR /var/www/html

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000"]
