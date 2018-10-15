FROM php:7.1-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apk update && apk add --no-cache \
    openssl \
    git \
    unzip \
    curl \
    libpng \
    libxml2-dev \
    zlib-dev \
    ca-certificates && \
    update-ca-certificates

# wkhtmltopdf
RUN apk add --update --no-cache \
    libgcc libstdc++ libx11 glib libxrender libxext libintl \
    libcrypto1.0 libssl1.0 \
    ttf-dejavu ttf-droid ttf-freefont ttf-liberation ttf-ubuntu-font-family && \
    wget https://raw.githubusercontent.com/madnight/docker-alpine-wkhtmltopdf/master/wkhtmltopdf --no-check-certificate && \
    mv wkhtmltopdf /bin && \
    chmod +x /bin/wkhtmltopdf

RUN apk add --no-cache --virtual .build-gd-deps \
    libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev zlib-dev libxpm-dev libwebp-dev zlib-dev libxpm-dev && \
    docker-php-ext-install gd && \
    apk del .build-gd-deps && \
    rm -rf /var/cache/apk/*

RUN docker-php-ext-install soap && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache && \
    docker-php-ext-install zip && \
    curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV NOT_INSTALL 1

COPY docker/config/opcache.ini $PHP_INI_DIR/conf.d/

COPY . /var/www/html/

RUN cd /var/www/html && \
    chmod -R 777 ./cache && \
    chmod -R 777 ./files && \
    composer install --no-interaction --no-dev --optimize-autoloader && \
    composer dump-autoload --optimize --no-dev --classmap-authoritative

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000"]