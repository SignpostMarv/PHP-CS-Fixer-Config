FROM php:8.1.27-cli-alpine

COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

RUN \
    docker-php-ext-install  \
      opcache \
    && \
    docker-php-ext-configure opcache --enable-opcache
