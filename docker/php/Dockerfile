# Container for PHP 5.6
#
# Version   0.0.1

FROM        mariorez/debian-php56
MAINTAINER  Mario Rezende <mariorez@gmail.com>

RUN apt-get update && apt-get install -y \
    git \
    php5-cli \
    php5-fpm \
    php5-mysql \
    php5-intl \
    php5-curl

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /etc/php5

RUN sed -i "s/;date.timezone =/date.timezone = America\/Sao_Paulo/" cli/php.ini \
    && sed -i "s/;date.timezone =/date.timezone = America\/Sao_Paulo/" fpm/php.ini \
    && sed -i "s/www-data/docker/g" fpm/pool.d/www.conf \
    && sed -i "s/listen = \/var\/run\/php5-fpm.sock/listen = 9000/" fpm/pool.d/www.conf

EXPOSE 9000

ENTRYPOINT ["php5-fpm", "--nodaemonize"]
