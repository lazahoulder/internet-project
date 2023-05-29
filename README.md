Mercato test applicatioin
==================================

Welcome to the official README file for Mercato test application! This document provides essential information about the
application, its purpose, features, installation instructions, and other useful details.

1. [ Overview. ](#overview-)
2. [ Dependencies. ](#dependencies-)
3. [ Installation. ](#installation-)

# Overview #

This is a simple application to manage team, playesr on team, buy player and sell player


# Dependencies #

We need docker and docker-compose for this application to have a complet installation env but we cal use an other lamp 
or xampp.

the docker env include:
* nginx
* php 8.2
* mysql 8

We also need internet connection cause we load library from cdn.
We use alpine js as javascript library and bootstrap 5.2 for css and templating.


## Installation ##

1. First of all, pull the project and make `cd` command on project.
2. Run `docker compose up -d` on the root of project to install all dependency and to create the container (skip this 
step if you don't use docker)
3. Install all dependencies using composer 
   * If using docker : `docker compose exec php-fpm composer install`
   * If not `composer install`
4. Run migration to setup databases (never forget the `docker compose exec php-fpm` directive before command if
using docker) : `docker compose exec php-fpm bin/console doctrine:migration:migrate`
5. Now load fixtures: `docker compose exec php-fpm bin/console doctrine:fixtures:load`
6. Setup file permission by running `docker-compose exec php-fpm chown -R www-data:www-data /application/public`


Now application is ready on [localhost:32000](http://localhost:32000) and you can use it as you like.

Thank you for your interest in this litle project and I hope you enjoy the coding style :).
If you have any questions or encounter any issues, please feel free to reach me on
[lazahoulder@gmail.com](mailto:lazahoulder@gmail.com) or on linkedin [Houlder Bariheriarikaza](https://www.linkedin.com/in/houlder-bariheriarilaza-7a6505115/)

