#!/usr/bin/env sh

docker-compose exec workspace bash -c "composer install"
docker-compose exec workspace bash -c "cp .env.docker.example .env.production"
docker-compose exec workspace bash -c "php artisan --env=production key:generate"
docker-compose exec workspace bash -c "php artisan --env=production migrate:fresh --seed --force --ansi"

# Generate a private and public key for your jwt tokens
passphrase=$(openssl rand -base64 32)
docker-compose exec workspace bash -c "openssl genpkey -algorithm RSA -out ./storage/keys/private_key.pem -aes256 -pass stdin <<< \"$passphrase\""
docker-compose exec workspace bash -c "openssl rsa -pubout -in ./storage/keys/private_key.pem -out ./storage/keys/public_key.pem -passin stdin <<< \"$passphrase\""
docker-compose exec workspace bash -c "chmod 600 ./storage/keys/private_key.pem"
docker-compose exec workspace bash -c "chmod 644 ./storage/keys/public_key.pem"
docker-compose exec workspace bash -c "echo JWT_PASSPHRASE=\"$passphrase\" >> .env.production"
echo "RSA keys for jwt token generated successfully!"

docker-compose exec workspace bash -c "php artisan cache:clear"
docker-compose exec workspace bash -c "php artisan route:clear"
