ARG PROJECT_IMAGE_PREFIX=sheet/app/

FROM ${PROJECT_IMAGE_PREFIX}myapp-nodejs:latest AS nodejs_image

FROM ${PROJECT_IMAGE_PREFIX}myapp-fpm:latest

COPY --from=nodejs_image /var/www/public/ /var/www/public/

USER www:www
