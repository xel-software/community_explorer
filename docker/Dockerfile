FROM ubuntu:19.04
ENV DEBIAN_FRONTEND noninteractive
ENV ELASTIC_DIR=/xel-explorer

RUN apt-get update
RUN apt-get -y install apt-utils
RUN apt-get -y install git curl gnupg zip unzip cron dos2unix

RUN apt-get -y install nginx redis-server python-pip python-dev gcc
RUN apt-get -y install php-fpm php-xml php-mbstring php-redis php-curl php-sqlite3 libsqlite3-0 libsqlite3-dev sqlite3

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN git clone --depth 1 https://github.com/xel-software/xel-block-explorer ${ELASTIC_DIR}
COPY parameters.yml ${ELASTIC_DIR}/app/config/parameters.yml
COPY nginx.conf /etc/nginx/sites-available/default
RUN dos2unix ${ELASTIC_DIR}/app/config/parameters.yml
RUN dos2unix /etc/nginx/sites-available/default

RUN \
  sed -i 's/^\(bind .*\)$/# \1/' /etc/redis/redis.conf

RUN \
  mkdir /run/php && \
  chown -R www-data:www-data /run/php

RUN /etc/init.d/redis-server restart && \
  /etc/init.d/nginx restart && \
  /etc/init.d/php7.2-fpm restart && \
  cd $ELASTIC_DIR && ./deploy.sh && \
  chmod -R 777 /xel-explorer/var/cache

COPY start-explorer.sh ${ELASTIC_DIR}/start-explorer.sh
RUN dos2unix ${ELASTIC_DIR}/start-explorer.sh

EXPOSE 80

WORKDIR ${ELASTIC_DIR}
ENTRYPOINT bash start-explorer.sh
