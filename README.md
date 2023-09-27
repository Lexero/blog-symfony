# blog-symfony
Pet project blog on symphony "^6"

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

## Xdebug settings
* In the PhpStorm settings go to `PHP → Servers`, add a server with name: `Server`, debugger: `Xdebug` host: `localhost`, port: `9003` , check the `Use path mapping` checkbox and map the local project directory to the path on the server `/var/www/html`
* Start Listening for PHP Debug Connection
* Set a breakpoint in the code and use Xdebug!
