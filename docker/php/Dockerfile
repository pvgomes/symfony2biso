# PHP 5.6 Container for Symfony 2 Biso
#
# Version   0.0.1

FROM pvgomes/php:5.6

COPY ./supervisor-consumers.conf /etc/supervisor/conf.d/

ENTRYPOINT ["php5-fpm", "--nodaemonize"]

export XDEBUG_CONFIG="idekey=PHPSTORM remote_host=172.17.42.1"
export PHP_IDE_CONFIG="serverName=symfony2biso.dev"