# Container for Nginx
#
# Version   0.0.1

FROM pvgomes/nginx:latest

COPY ./symfony2biso.conf /etc/nginx/sites-available/

RUN ln -nfs /etc/nginx/sites-available/symfony2biso.conf /etc/nginx/sites-enabled/symfony2biso.conf \
    && rm sites-enabled/domain.conf

EXPOSE 80

ENTRYPOINT ["nginx"]
