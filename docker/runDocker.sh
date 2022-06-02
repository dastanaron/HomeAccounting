#!/usr/bin/env bash

docker network create home_accounting || true

docker run -d --name mysql --network home_accounting --restart always -e MYSQL_ROOT_PASSWORD=mysqlpass -e MYSQL_DATABASE=home_accounting -p 3306:3306 -v /home/dastanaron/homeAccounting/data/mysql:/var/lib/mysql/ mysql:5.7

docker run -d --name rabbit --network home_accounting --restart always -e RABBITMQ_DEFAULT_USER=guest -e RABBITMQ_DEFAULT_PASS=guest -p 15672:15672 rabbitmq:3.7-management

#docker run -d --name home_accounting --network home_accounting --restart always

docker stop mysql && docker rm mysql
docker stop rabbit && docker rm rabbit
docker stop home_accounting && docker rm home_accounting