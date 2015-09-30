Symfony2Biso
============

Symfony2 using Business Isolation Layer for B2B E-commerce integration


## Project configuration

**1) Run composer**:

```
php composer.phar install
```

**2) Environment configuration (use docker + docker-compose)**:

```
docker-compose up
```

**3) Create database and update schema

```
php app/console doctrine:database:create
php app/console doctrine:schema:update

```

**4) Data Fixtures**:

```
app/console doctrine:fixtures:load

```

**5) Done, access using this url**:

```
http://symfony2biso.dev:8080/

```