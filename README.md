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

## Run tests
* `./vendor/bin/phpunit -c ./tests/phpunit.xml.dist`