# MixiPHP

![Static Badge](https://img.shields.io/badge/1.0.0-Main-blue)
![Static Badge](https://img.shields.io/badge/gitlab-version-red?logo=gitlab&link=https%3A%2F%2Fgitlab.com%2Fldcmleo%2Fmixiphp)

A lightweight php framework with MVC Architecture. <br>
MixiPHP is created for using with lightweight projects or even with school projects and is not intended to be used on a large scale projects. <br>
MixiPHP it's strongly inspired by [Laravel](https://laravel.com/) and therefore it can be a good starting point to learn how to use it, otherwise it will be easy for you to use.

### Dependencies
MixiPHP works with the next dependencies:
| Docker Image | Version |
|--------------|---------|
| mariadb      | latest  |
| [ldcmleo1360/mixiphp](https://hub.docker.com/r/ldcmleo1360/mixiphp) | latest  |
| phpmyadmin   | latest  |

MixiPHP use common mariadb and phpmyadmin for database, but use a custom httpd image and it can be found on [docker hub](https://hub.docker.com/r/ldcmleo1360/mixiphp).

## Installation

### Before install

First make sure you have installed [Docker](https://www.docker.com/) with **docker-compose**.

---

Clone this repository to your system with:
```bash
git clone https://github.com/ldcmleo/mixiphp.git
```
It create a new folder called `mixiphp` then go to:
```bash
cd mixiphp/docker
```
Inside `mixiphp/docker` folder can be found file `compose.yml`, now edit it and make sure all directions are pointed to the specific folder that you installed the project:
```yml
    volumes:
      - "/YOUR/FOLDER/TO/mixiphp/public:/var/www/html"
      - "/YOUR/FOLDER/TO/mixiphp/core:/var/www/mixi/core"
      - "/YOUR/FOLDER/TO/mixiphp/app:/var/www/mixi/app"
      - "/YOUR/FOLDER/TO/mixiphp/config:/var/www/mixi/config"
      - "/YOUR/FOLDER/TO/mixiphp/routes:/var/www/mixi/routes"
```
now you just need to run:
```bash
docker-compose up -d
```
