## Installation

Project requirements: 
```shell
# docker
docker -v
Docker version 20.10.13, build a224086

# docker-compose
docker-compose -v
docker-compose version 1.29.2, build 5becea4c
```

Install the dependencies:

```shell
docker-compose up -d --build
```

Running tests:
```shell
docker-compose run --rm php-fpm php artisan test
```
