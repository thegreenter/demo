FROM php:7.1-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apk update && apk add --no-cache \
    libgcc libstdc++ libx11 glib libxrender libxext libintl \
    libcrypto1.0 libssl1.0 \
    ttf-dejavu ttf-droid ttf-freefont ttf-liberation ttf-ubuntu-font-family && \
    apk add --no-cache --virtual .build-green-deps \
    openssl \
    git \
    unzip \
    curl \
    libxml2-dev \
    zlib-dev \
    ca-certificates \
    libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev zlib-dev libxpm-dev && \
    update-ca-certificates

# wkhtmltopdf
RUN wget https://raw.githubusercontent.com/madnight/docker-alpine-wkhtmltopdf/master/wkhtmltopdf --no-check-certificate && \
    mv wkhtmltopdf /bin && \
    chmod +x /bin/wkhtmltopdf

RUN docker-php-ext-install soap && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install opcache && \
    docker-php-ext-install gd && \
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

RUN apk del .build-green-deps && \
    rm -rf /var/cache/apk/*

WORKDIR /var/www/html

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000"]