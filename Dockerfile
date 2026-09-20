# ===== Dockerfile for Duka System (Laravel + Blade) =====
# PHP 8.4 — inahitajika na Symfony packages

FROM serversideup/php:8.4-fpm-nginx

USER www-data

# Nakili faili zote za mradi
COPY --chown=www-data:www-data . /var/www/html

# Weka working directory
WORKDIR /var/www/html

# Ruhusu start.sh kuwa executable
RUN chmod +x /var/www/html/start.sh

# Expose port (serversideup inatumia 8080)
EXPOSE 8080