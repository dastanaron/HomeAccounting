Описание
=====================

Данная система, это замена аналогичной, сделанной на Yii2 ([старая система](https://github.com/dastanaron/HomeAccounting)).
В старой системе требуется множество доделок и переделок, кроме того, на Yii2 сложно отдельно
собирать фронтенд. 
Именно поэтому, я решил сделать все новые функции и доработки на новой системе,
как бы с чистого листа, учитывая ошибки прошлых разработок.

Используется:

<div class="used-frameworks">
    <img title="Laravel" src="https://camo.githubusercontent.com/5ceadc94fd40688144b193fd8ece2b805d79ca9b/68747470733a2f2f6c61726176656c2e636f6d2f6173736574732f696d672f636f6d706f6e656e74732f6c6f676f2d6c61726176656c2e737667" />
    <img title="Vue" src="https://raw.githubusercontent.com/github/explore/6c6508f34230f0ac0d49e847a326429eefbfc030/topics/vue/vue.png" width="64" />
    <img title="Vuetify" src="https://vuetifyjs.com/static/v-alt.svg" width="64" />
</div>

Как работает
----------------

Все довольно стандартно. Есть защищенные авторизацией API, которые выполняют взаимодействие frontend'а с 
базой данных. Расчеты и управление данными вынесено в личный кабинет и управляется скриптами на Vue. Доработок еще требуется много,
нужно еще придумать главную страницу, чтобы убрать стандартную Laravel, но пока чисто функциональное решение. 
Как только это станет чем-то стоящим можно сделать установку через композер. Пока установка будет проводиться через стандартное клонирование репозитория.

Установка
-------------

Клонируем репозиторий

```bash
git clone git@github.com:dastanaron/HomeAccountingLaravel.git

cd HomeAccountingLaravel

composer install

#Миграция баз данных, предварительно настраивается в файле .env, который можно сделать путем копирования
cp .env.example .env
./artisan migrate

#если требуется доработка фронтенда то еще 
npm install
```
Прописываем хосты на своем сервере или docker, и система готова к работе

Docker
-------

Возможен разворот проекта через докер. Нужно установить [docker](https://www.docker.com/)
и [docker-compose](https://docs.docker.com/compose/).

В дирректории docker есть файл example.env, необходимо выполнить (из папки проекта)
```bash
cp docker/example.env docker/.env
```
Затем открыть env файл и ввести там свои настройки для пароля BD,
для того, от какого пользователя будут работать сервисы fpm и nginx.
 
После так же из дирректории проекта выполнить:

```bash
./docker/bin/start
```
Контейнеры будут собраны и запущены. Все настройки можно посмотреть в конфигах docker-compose, в том числе
по тому какой домен присвоен машине и т.п. Если вам нужна тестовая база для экспериментов, напишите мне
на flow199@yandex.ru, с указанием что хотите сделать, я предоставлю ссылку на тестовую базу

Настройка обработки событий
============================

Пока используется системный крон, вскоре будет один общий laravel'овский для всех необходимых.

Пример:

```
*/1 * * * * /usr/bin/php /path-to-project/artisan webPush:notifications >> /path-to-log/logname.log

15 02 * * * /usr/bin/php /path-to-project/artisan calculate:monthDynamics >> /path-to-log/logname.log

10 18 * * * /usr/bin/php /path-to-project/artisan currency:parse >> /path-to-log/logname.log

```

