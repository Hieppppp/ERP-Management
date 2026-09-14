# syntax=docker/dockerfile:1.7

FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Composer's current image runs PHP 8.4, while this project lockfile targets
# PHP 8.3. The runtime stage below is PHP 8.3 and includes the required GD extension.
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts \
    --ignore-platform-req=php --ignore-platform-req=ext-gd

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

FROM php:8.3-fpm-alpine AS runtime
WORKDIR /var/www

RUN apk add --no-cache \
        icu-libs libzip libpng libjpeg-turbo freetype oniguruma \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
        libxml2-dev oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" bcmath gd intl mbstring pcntl pdo_mysql xml zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/99-app.ini
COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build
COPY docker/php/entrypoint.sh /usr/local/bin/app-entrypoint

RUN chmod +x /usr/local/bin/app-entrypoint \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 9000
ENTRYPOINT ["app-entrypoint"]
CMD ["php-fpm"]
