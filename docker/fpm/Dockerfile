# Для начала указываем исходный образ, он будет использован как основа
FROM php:7.1-fpm
# Необязательная строка с указанием автора образа
MAINTAINER dastanaron <flow199@yandex.ru>

ARG USER

# RUN выполняет идущую за ней команду в контексте нашего образа.
# В данном случае мы установим некоторые зависимости и модули PHP.
# Для установки модулей используем команду docker-php-ext-install.
# На каждый RUN создается новый слой в образе, поэтому рекомендуется объединять команды.
RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt mbstring mysqli pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) bcmath

RUN pecl install xdebug-2.7.1 \
        && docker-php-ext-enable xdebug

# Куда же без composer'а.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -ms /bin/bash ${USER}

COPY php-fpm.d/www.conf /usr/local/etc/php-fpm.d/

RUN sed -i -e "s/{USER}/${USER}/g" /usr/local/etc/php-fpm.d/www.conf

#RUN apt install supervisor

# Запускаем контейнер
# Из документации: The main purpose of a CMD is to provide defaults for an executing container. These defaults can include an executable,
# or they can omit the executable, in which case you must specify an ENTRYPOINT instruction as well.
CMD ["php-fpm"]