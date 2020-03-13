FROM alpine:3.10

ARG USER=docker
ARG UID=1000
ARG GID=1000

ENV IMAGE_USER=${USER}
ENV IMAGE_UID=${UID}
ENV IMAGE_GID=${GID}

# Install packages
RUN apk add --update \
    php7-cli \
    php7-ctype \
    php7-apcu \
    php7-curl \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-xml \
    php7-phar \
    php7-zlib \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-fileinfo \
    php7-simplexml \
    php7-tokenizer \
    php7-xmlreader \
    php7-xmlwriter \
    php7-zip \
    php7-pdo_pgsql \
    php7-pdo_mysql \
    php7-bcmath \
    php7-sockets \
    php7-session \
    php7-fpm \
    make \
    supervisor \
    curl \
    && addgroup --gid "${GID}" "${USER}" \
    && adduser \
        --disabled-password \
        --gecos "" \
        --home "$(pwd)" \
        --ingroup "${USER}" \
        --no-create-home \
        --uid "${UID}" \
        "${USER}" \
    && curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer \
    && chmod +x /usr/bin/composer

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY config/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apk add --update \
    php7-xdebug \
    && rm -frv /var/cache/apk/*

ADD config/xdebug.ini /etc/php7/conf.d/xdebug.ini

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R ${UID}:${GID} /run \
  && chmod -R 775 /run

# Switch to use a non-root user from here on
USER ${UID}:${GID}

WORKDIR /var/www/html

# Expose the port nginx is reachable on
EXPOSE 9001

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
