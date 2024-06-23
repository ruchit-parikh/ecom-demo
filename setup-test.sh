#!/usr/bin/env sh

docker-compose exec workspace bash -c "cp .env.testing.example .env.testing"
docker-compose exec workspace bash -c "php artisan --env=testing key:generate && exit"
docker-compose exec workspace bash -c "php artisan --env=testing migrate:fresh --seed --force --ansi && exit"
docker-compose exec workspace bash -c "php artisan --env=testing db:seed --class=TestSeeder --ansi && exit"
docker-compose exec workspace bash -c "php artisan cache:clear && exit"
