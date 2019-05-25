# Для начала указываем исходный образ, он будет использован как основа
FROM nginx:latest
# Необязательная строка с указанием автора образа
MAINTAINER dastanaron <flow199@yandex.ru>

ARG USER
ARG DOMAIN

RUN useradd -ms /bin/bash ${USER}

COPY nginx.conf /etc/nginx/
RUN sed -i -e "s/{USER}/${USER}/g" /etc/nginx/nginx.conf

COPY conf.d/default.conf /etc/nginx/conf.d/
COPY conf.d/accounting.conf /etc/nginx/conf.d/
RUN sed -i -e "s/{DOMAIN}/${DOMAIN}/g" /etc/nginx/conf.d/accounting.conf

RUN mkdir -p -v /etc/nginx/ssl
COPY conf.d/cert.crt /etc/nginx/ssl/
COPY conf.d/cert.key /etc/nginx/ssl/

RUN mkdir -p -v /usr/local/test-base/

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

STOPSIGNAL SIGTERM

CMD ["nginx", "-g", "daemon off;"]