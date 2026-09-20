# ===== Dockerfile for Duka System (Laravel + Blade) =====
# PHP 8.4 via serversideup/php

FROM serversideup/php:8.4-fpm-nginx

USER www-data

# Nakili faili zote za mradi
COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

# Ruhusu start.sh kuwa executable
RUN chmod +x /var/www/html/start.sh

# Expose port
EXPOSE 8080

# Override CMD — endesha start.sh kwanza, kisha /init
CMD ["sh", "-c", "/var/www/html/start.sh && exec /init"]