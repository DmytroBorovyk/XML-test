# Setup instructions

- run `git clone https://github.com/DmytroBorovyk/XML-test.git`

open IDE Project from existing files in this directory
- `cp .env.example .env`
- `docker compose up -d`
  
Wait until build.

- Inside container (see below)
  - run `php artisan optimize:clear`
  - make sure that there is no key
  - run `php artisan key:generate`
  - run `php artisan optimize`
  
## to open php container
run `docker exec -it xml-test-local-api bash`

## to run inside container 
  - run inside container `php artisan serve`

## to run tests inside container
  - run inside container `./vendor/bin/phpunit`
