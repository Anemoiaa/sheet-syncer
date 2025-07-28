FROM php:8.2-fpm

ARG SUPERCRONIC_VERSION=v0.1.12
ARG UID=1000

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

RUN <<-EOT bash
  set -ex
  curl -sSfL https://github.com/aptible/supercronic/releases/download/${SUPERCRONIC_VERSION}/supercronic-linux-amd64 -o /usr/local/bin/supercronic
  chmod +x /usr/local/bin/supercronic
EOT

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN <<-EOT bash
  set -ex
  useradd -u $UID -d /home/www www
  mkdir -p /home/www/.composer
  chown -R www:www /home/www
  mkdir -p /var/www && chown www:www /var/www/
EOT

RUN mkdir -p /etc/supercronic && chown www:www /etc/supercronic && chmod 755 /etc/supercronic

COPY --link --chmod=644 --chown=www:www .docker/php/schedule /etc/supercronic/app

RUN supercronic -test /etc/supercronic/app

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
