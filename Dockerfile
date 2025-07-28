FROM myapp-nodejs:latest AS nodejs_image

FROM myapp-fpm:latest

COPY --from=nodejs_image /var/www/public/ /var/www/public/

USER www:www
