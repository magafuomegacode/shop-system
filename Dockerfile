# ===== Dockerfile for Duka System (Laravel + Blade) =====
# PHP 8.4 — inahitajika na Symfony packages (v8.1.7)

FROM richarvey/nginx-php-fpm:php8.4

# Set webroot kuwa public directory ya Laravel
ENV WEBROOT /var/www/html/public

# Ruhusu Composer kuendesha kama root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Weka PHP memory limit (muhimu kwa composer)
ENV PHP_MEMORY_LIMIT 512M

# Nakili faili zote za mradi
COPY . /var/www/html

# Weka working directory
WORKDIR /var/www/html

# Ruhusu start.sh kuwa executable
RUN chmod +x /var/www/html/start.sh

# Expose port (Render/Railway ita-assign port yake mwenyewe)
EXPOSE 80