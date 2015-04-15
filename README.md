Symfony2Biso
============

Symfony2 using Business Isolation Layer


## Project configuration

**1) Run composer**:

```
php composer.phar install
```

**2) Environment configuration (use docker + docker-compose)

```
docker-compose up
```

**3) All of the migrations functionality is contained in a few console commands

```
doctrine:migrations
  :diff     Generate a migration by comparing your current database to your mapping information.
  :execute  Execute a single migration version up or down manually.
  :generate Generate a blank migration class.
  :migrate  Execute a migration to a specified version or the latest available version.
  :status   View the status of a set of migrations.
  :version  Manually add and delete migration versions from the version table.
```

**3.1) Examples:
***1) php app/console doctrine:migrations:status
***2) php app/console doctrine:migrations:generate
***3) php app/console doctrine:migrations:status --show-versions

php app/console doctrine:database:drop --force
php app/console doctrine:database:create

**4) Data Fixtures

```
app/console doctrine:fixtures:load

```

**5) Done, access using this url

```
http://symfony2biso.dev:8080/

```