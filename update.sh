#!/usr/bin/env sh

docker-compose exec workspace bash -c "composer install"
docker-compose exec workspace bash -c "php artisan migrate --seed --ansi && exit"
docker-compose exec workspace bash -c "php artisan cache:clear && exit"
docker-compose exec workspace bash -c "php artisan route:clear && exit"
docker-compose exec workspace bash -c "php artisan ide-helper:generate"
