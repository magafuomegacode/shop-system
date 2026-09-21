# ===== Dockerfile for Duka System (Laravel + Blade) =====
# PHP 8.4 via serversideup/php

FROM serversideup/php:8.4-fpm-nginx

# ===== Nakili mradi kama www-data =====
COPY --chown=www-data:www-data . /var/www/html

# ===== Nakili start.sh kwenye entrypoint.d na kuweka permissions moja kwa moja =====
# --chmod=+x inafanya faili kuwa executable wakati wa COPY
COPY --chown=www-data:www-data --chmod=+x .docker/entrypoint.d/99-start.sh /etc/entrypoint.d/99-start.sh

# ===== Weka WORKDIR na USER =====
WORKDIR /var/www/html
USER www-data

EXPOSE 8080

CMD ["/init"]