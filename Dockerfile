# ===== Dockerfile for Duka System (Laravel + Blade) =====
# PHP 8.4 via serversideup/php

FROM serversideup/php:8.4-fpm-nginx

# ===== Kama ROOT kwa hatua za kuandaa faili =====

# Nakili faili zote za mradi
COPY --chown=www-data:www-data . /var/www/html

# Copy start.sh kwenye entrypoint.d
COPY --chown=www-data:www-data .docker/entrypoint.d/ /etc/entrypoint.d/

# Ruhusu scripts kuwa executable (kama ROOT)
RUN chmod +x /etc/entrypoint.d/*.sh

# ===== Rudisha USER www-data kwa runtime =====
USER www-data

WORKDIR /var/www/html

# Expose port
EXPOSE 8080

# CMD ya default — /init inaanzisha Nginx + PHP-FPM
CMD ["/init"]