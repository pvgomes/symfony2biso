# Container for Nginx
#
# Version   0.0.1

FROM        mariorez/debian-php56
MAINTAINER  Mario Rezende <mariorez@gmail.com>

RUN apt-get update && apt-get install -y nginx

WORKDIR /etc/nginx

# Change Nginx default user (www-data) to docker
RUN sed -i "s/www-data/docker/" nginx.conf \
    && echo "daemon off;" >> nginx.conf \
    && rm sites-enabled/default

COPY ./symfony2biso.conf /etc/nginx/sites-available/

RUN ln -nfs /etc/nginx/sites-available/symfony2biso.conf /etc/nginx/sites-enabled/symfony2biso.conf

EXPOSE 80

ENTRYPOINT ["nginx"]
