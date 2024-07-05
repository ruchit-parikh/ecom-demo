### Introduction

We have implemented Login/Logout and Users crud on this project's backend and on frontend customer orders listing, login/logout. For front end, you can use swagger to get user credentials from admin api which is live. Every thing is dockerized and has its unit test and feature tests. We are using PHP8.3 and Laravel 11, PHPUnit on backend and for frontend we are using Vue3, Typescript4, Vitest. We have integrated laravel pint, larastan, ide helper generator for code styling on backend and on vue, eslinter and formatter.

### Requirements

As we are setting up environment through docker, you will need it installed. You can refer official documentations for setting up docker/docker-compose on your machine from below link.
https://www.docker.com/get-started/

### How to Setup

- Clone this project from git and goto its directory
- Run command `docker-compose up -d` to start server.
- Run bash script `setup.sh` from root directory. On linux machine you can do it using `sh ./setup.sh` to setup and seed database and download dependencies
- You need to run `docker-compose exec workspace bash` to get inside docker and then you need to run `php artisan queue:work` to start worker if mails are being sent using queue.
- Your backend server will be started on `localhost:8000` and phpmyadmin on `localhost:8080`(username is root and password is smile) and for mail checking you can visit `localhost:8025`. You can check frontend on `localhost:3000`
- If anything fails due to network issues (In windows it may happen when need network access for first time) in between or due to blocked port; you can change port on `docker-compose.yml` file and restart docker and rerun setup.sh file. 

### In order to test
- To run tests you will need to setup test environment, run bash script `setup-test.sh` to set it up and make sure to specify credentials on `.env.testing` if needed to be altered
- You can run tests using commands: `docker-compose exec workspace bash` (To enter in docker container if not) and then `php artisan test` from inside docker.
- You can run frontend unit tests using command `docker-compose run npm npm run test:unit` from root directory (outside docker)

### Other
- In order to update with latest migrations and dependencies, run bash script `update.sh` and make sure to update relevant env files if needed
- For developers, you can update relevant xdebug.ini, php-docker.ini and my.cnf for updating mounted ini files in docker as per your need
- You can also run laravel pint, phpstan, and ide helper generator using their commands mentioned on their official docs
- You can format on vue using `docker-compose run npm npm run format` and for linting `docker-compose run npm npm run lint`
- For api documents, you will need to refer `api.docs.json` in root folder's public/docs directory. You can use swagger editor to view it.
