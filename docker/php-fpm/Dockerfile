FROM maxrollundev/php-fpm-dev-8.0

USER root

RUN apt-get --allow-releaseinfo-change update && \
    apt-get install -y vim libzip-dev zip && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip

COPY php-fpm.conf /usr/local/etc/php-fpm.conf
COPY conf.d /usr/local/etc/php/conf.d

WORKDIR /var/www/app

CMD ["php-fpm", "-R"]

# -R - allow run as root
