### Introduction

We are going to have three major api apart from Login/Logout and Users on this project's backend. Those three will be Orders, Payment and File Download endpoints.

### Requirements

As we are setting up environment through docker, you will need it installed.

### How to Setup

- Clone this project from git
- Run command `docker-compose up -d` to start server on linux. Your server will be started on `localhost:8000` and phpmyadmin on `localhost:8080`
- Run bash script `setup.sh` from root directory. On linux machine you can do it using `sh ./setup.sh` to setup and seed database and download dependencies
- To run tests you will need to setup test environment, run bash script `test.sh` to set it up
- In order to update with latest migrations and dependencies, run bash script `update.sh` and make sure to update relevant env files if needed
- For developers, you can update relevant xdebug.ini, php-docker.ini and my.cnf for updating mounted ini files in docker as per your need
