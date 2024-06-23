#!/usr/bin/env sh

docker-compose exec workspace bash -c "cp .env.testing.example .env.testing"
docker-compose exec workspace bash -c "php artisan --env=testing key:generate"
docker-compose exec workspace bash -c "php artisan --env=testing migrate:fresh --seed --force --ansi"
docker-compose exec workspace bash -c "php artisan --env=testing db:seed --class=TestSeeder --ansi"

# Generate a private and public key for your jwt tokens
passphrase=$(openssl rand -base64 32)
docker-compose exec workspace bash -c "openssl genpkey -algorithm RSA -out ./storage/keys/private_key_test.pem -aes256 -pass stdin <<< \"$passphrase\""
docker-compose exec workspace bash -c "openssl rsa -pubout -in ./storage/keys/private_key_test.pem -out ./storage/keys/public_key_test.pem -passin stdin <<< \"$passphrase\""
docker-compose exec workspace bash -c "chmod 600 ./storage/keys/private_key_test.pem"
docker-compose exec workspace bash -c "chmod 644 ./storage/keys/public_key_test.pem"
docker-compose exec workspace bash -c "echo JWT_PASSPHRASE=\"$passphrase\" >> .env.testing"
echo "RSA keys for jwt token generated successfully!"

docker-compose exec workspace bash -c "php artisan cache:clear"
