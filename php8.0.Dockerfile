FROM php:8.0.30-cli-alpine

COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

RUN \
    docker-php-ext-install  \
      opcache \
    && \
    docker-php-ext-configure opcache --enable-opcache
