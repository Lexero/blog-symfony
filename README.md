# blog-symfony
Pet project blog on symphony 6.2

## Installation
* [Install docker-compose](https://docs.docker.com/compose/install/)
* Clone repository
* `docker-compose build`
* `docker-compose up -d`
* `docker-compose exec php bash`
* `composer install`
* `bin/console doctrine:migrations:migrate`
* `bin/console doctrine:fixtures:load`

## Set up tests
* `docker-compose exec php bash`- get into the container
* `export APP_ENV=test` - change env to test
* `bin/console doctrine:database:create`
* `bin/console doctrine:migrations:migrate`
* `bin/console doctrine:fixtures:load`
* `APP_ENV=test ./vendor/bin/phpunit -c ./tests/phpunit.xml.dist`
