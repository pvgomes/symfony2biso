Symfony2Biso
============

Symfony2 using Business Isolation Layer for B2B E-commerce integration following Hexagonal Architecture Approach


## Project configuration

**1) Environment configuration (use docker + docker-compose)**:

```
docker-compose up -d
```

**2) Run composer inside container, following this code**:

```
docker exec -it symfony2biso_php_1 bash
su docker
cd /var/www/symfony2biso/
php composer.phar install
```

**3) Create database and update schema**:

```
php app/console doctrine:database:create
php app/console doctrine:schema:update

```

**4) Run data Fixtures**:

```
app/console doctrine:fixtures:load

```

**5) Done, access using this url**:

```
http://symfony2biso.dev:8080/
http://symfony2biso.dev:8080/api/doc

```