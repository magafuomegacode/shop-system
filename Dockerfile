# ===== Dockerfile for Duka System (Laravel + Blade) =====
# PHP 8.4 via serversideup/php

FROM serversideup/php:8.4-fpm-nginx

USER www-data

# Nakili faili zote za mradi
COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

# Copy start.sh kwenye entrypoint.d (inaendeshwa kabla ya Nginx)
COPY --chown=www-data:www-data .docker/entrypoint.d/ /etc/entrypoint.d/

# Ruhusu scripts kuwa executable
RUN chmod +x /etc/entrypoint.d/*.sh

# Expose port
EXPOSE 8080

# CMD ya default — /init inaanzisha Nginx + PHP-FPM
CMD ["/init"]