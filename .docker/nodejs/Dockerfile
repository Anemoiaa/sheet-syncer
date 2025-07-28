FROM node:20-slim

RUN set -ex; \
    apt -y update; \
    apt -y install git

WORKDIR /var/www

COPY --link package.json package-lock.json ./

RUN npm ci

COPY --link . .

RUN npm run build
