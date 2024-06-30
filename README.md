### Introduction

We are going to have three major api apart from Login/Logout and Users on this project's backend. Those three will be Orders, Payment and File Download endpoints.

### Requirements

As we are setting up environment through docker, you will need it installed.

### How to Setup

- Clone this project from git
- Run command `docker-compose up -d` to start server on linux. Your backend server will be started on `localhost:8000` and phpmyadmin on `localhost:8080` and for mail checking you can visit `localhost:8025`. You can check frontend on `localhost:3000`
- Run bash script `setup.sh` from root directory. On linux machine you can do it using `sh ./setup.sh` to setup and seed database and download dependencies
- You need to run `docker-compose exec workspace bash` to get inside docker and then you need to run `php artisan queue:work` to start if mails are being sent using queue.

### In order to test
- To run tests you will need to setup test environment, run bash script `test.sh` to set it up and make sure to specify credentials on `.env.testing` if needed to be altered
- You can run tests using command `php artisan test` after running `docker-compose exec workspace bash` (Enter in docker container if not)
- You can run frontend unit tests using command `docker-compose run npm npm run test:unit`

### Other
- In order to update with latest migrations and dependencies, run bash script `update.sh` and make sure to update relevant env files if needed
- For developers, you can update relevant xdebug.ini, php-docker.ini and my.cnf for updating mounted ini files in docker as per your need
- You can also run laravel pint, phpstan, and ide helper generator using their commands mentioned on their official docs
- You can format using `docker-compose run npm npm run format` and for linting `docker-compose run npm npm run lint`
- For api documents, you will need to refer `api.docs.json` in root folder's public/docs directory
