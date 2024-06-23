#!/usr/bin/env sh

docker-compose exec workspace bash -c "composer install"
docker-compose exec workspace bash -c "cp .env.docker.example .env.production"
docker-compose exec workspace bash -c "php artisan --env=production key:generate && exit"
docker-compose exec workspace bash -c "php artisan --env=production migrate:fresh --seed --force --ansi && exit"
docker-compose exec workspace bash -c "php artisan cache:clear && exit"
docker-compose exec workspace bash -c "php artisan route:clear && exit"
