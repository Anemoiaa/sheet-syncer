FROM php:8.2-fpm

ARG UID=1000

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN <<-EOT bash
  set -ex

  useradd -u $UID -d /home/www www
  mkdir -p /home/www/.composer
  chown -R www:www /home/www

  rm -rf /var/www/*
  chown www:www /var/www/
EOT

WORKDIR /var/www
USER www:www

COPY --link --chown=www:www composer.json composer.lock ./

RUN set -ex; \
  composer install \
    --no-autoloader \
    --no-cache \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts

COPY --link --chown=www:www . .
