# blog-symfony
Pet project blog on symphony, ^6 version

## Installation
* [Install docker-compose](https://docs.docker.com/compose/install/)
* Clone repository
* `docker-compose build`
* `docker-compose up -d` - run containers
* `docker-compose exec -it php bash` - get into the container
* `composer install`
* `bin/console doctrine:migrations:migrate`
* `bin/console doctrine:fixtures:load` - load fixtures

## Set up tests
* `docker-compose exec -it php bash` - get into the container
* `export APP_ENV=test` - change env to test
* `bin/console doctrine:database:create`
* `bin/console doctrine:migrations:migrate`
* `bin/console doctrine:fixtures:load`
* `APP_ENV=test ./vendor/bin/phpunit -c ./tests/phpunit.xml` - run all tests

## Xdebug settings
* In the PhpStorm settings go to `PHP → Servers`, add a server with name: `Server`, debugger: `Xdebug` host: `localhost`, port: `9003` , check the `Use path mapping` checkbox and map the local project directory to the path on the server `/var/www/html`
* Start Listening for PHP Debug Connection
* Set a breakpoint in the code and use Xdebug!

## Commands
* Use `php bin/console app:create-user` to create a random user
* Use `php bin/console app:delete-user` to delete a user by email
* Use `php bin/console app:delete-last-user` to delete last user in user table

## Cron
* To create crontab use `php bin/console cronos:dump`
* To run cron use `php bin/console cronos:replace` in the container
* To stop cron use `service cron stop`
