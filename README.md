### Introduction

We are going to have three major api apart from Login/Logout and Users on this project's backend. Those three will be Orders, Payment and File Download endpoints.

### Requirements

As we are setting up environment through docker, you will need it installed.

### How to Setup

- Clone this project from git
- Run command `docker-compose up -d` to start server on linux. Your server will be started on `localhost:8000` and phpmyadmin on `localhost:8080` and for mail checking you can visit `localhost:8025`
- Run bash script `setup.sh` from root directory. On linux machine you can do it using `sh ./setup.sh` to setup and seed database and download dependencies
- To run tests you will need to setup test environment, run bash script `test.sh` to set it up and make sure to specify environment `testing` while running them
- In order to update with latest migrations and dependencies, run bash script `update.sh` and make sure to update relevant env files if needed
- You need to run `docker-compose exec workspace bash` to get inside docker and then you need to run `php artisan queue:work` to start if mails are being sent using queue.
- For developers, you can update relevant xdebug.ini, php-docker.ini and my.cnf for updating mounted ini files in docker as per your need
- You can also run laravel pint, phpstan, and ide helper generator using their commands mentioned on their official docs
- For api documents, you will need to refer `api.docs.json` in root folder's public/docs directory
