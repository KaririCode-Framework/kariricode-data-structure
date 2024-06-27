ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-alpine

RUN apk update && apk add --no-cache $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY .docker/php/kariricode-php.ini /usr/local/etc/php/conf.d/

# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk del --purge $PHPIZE_DEPS && rm -rf /var/cache/apk/*

# Mantém o contêiner ativo sem fazer nada
CMD tail -f /dev/null
