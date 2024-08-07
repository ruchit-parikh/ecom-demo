#!/usr/bin/env sh

docker-compose exec workspace bash -c "composer install"
docker-compose exec workspace bash -c "cp .env.docker.example .env"
docker-compose exec workspace bash -c "php artisan key:generate"
docker-compose exec workspace bash -c "php artisan migrate:fresh --seed --force --ansi"

# Generate a private and public key for your jwt tokens
passphrase=$(openssl rand -base64 32)
docker-compose exec workspace bash -c "openssl genpkey -algorithm RSA -out ./storage/keys/private_key.pem -aes256 -pass stdin <<< \"$passphrase\""
docker-compose exec workspace bash -c "openssl rsa -pubout -in ./storage/keys/private_key.pem -out ./storage/keys/public_key.pem -passin stdin <<< \"$passphrase\""
docker-compose exec workspace bash -c "chmod 600 ./storage/keys/private_key.pem"
docker-compose exec workspace bash -c "chmod 644 ./storage/keys/public_key.pem"
docker-compose exec workspace bash -c "echo JWT_PASSPHRASE=\"$passphrase\" >> .env"
echo "RSA keys for jwt token generated successfully!"

docker-compose exec workspace bash -c "php artisan cache:clear"
docker-compose exec workspace bash -c "php artisan route:clear"

# This will be only needed if not setup
docker-compose exec workspace bash -c "php artisan storage:link"
docker-compose exec workspace bash -c "php artisan ide-helper:generate"

# Copy .env of vue js project
docker-compose exec workspace bash -c "cp frontend/.env.example frontend/.env"
