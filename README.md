elastic blockchain explorer
=======

# installation

the best way is to use the docker installer : https://github.com/xel-software/xel-installer-docker

## advanced users : manual installation (tested on linux ubuntu 18.04)

:warning: this manual installation is for advanced users :warning:

### prerequisites

- php/redis and a webserver
- composer

### install

here an indicative list of steps to install, **be aware that this is a guideline, some steps might not work as expected** :

```
apt-get update
apt-get -y install apt-utils git curl gnupg zip unzip cron dos2unix
apt-get -y install mysql-server mysql-client
apt-get -y install nginx redis-server python-pip python-dev gcc
apt-get -y install php-fpm php-xml php-mbstring php-redis php-curl php-sqlite3 libsqlite3-0 libsqlite3-dev sqlite3
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
git clone --depth 1 https://github.com/xel-software/xel-block-explorer /var/www/xel-explorer

cd /var/www/xel-explorer
./deploy.sh
```

you might have to adjust `/var/www/xel-explorer/app/config/parameters.yml` with the proper parameters

here's a sample of an nginx config :

```
##
# Default server configuration
#
server {
	listen 80 default_server;
	listen [::]:80 default_server;

  root /var/www/xel-explorer/web;

  index index.html index.htm index.nginx-debian.html;

  location / {
    try_files $uri /app.php$is_args$args;
  }

  location ~ /\.git {
    deny all;
  }

  location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_intercept_errors on;
    proxy_connect_timeout 600;
    proxy_send_timeout 600;
    proxy_read_timeout 600;
    send_timeout 600;
    fastcgi_buffers 8 256k;
    fastcgi_buffer_size 512k;
    fastcgi_pass unix:/run/php/php7.2-fpm.sock;
  }

	server_name _;
}
```

# howto

- update top balances (you might want to use a cron job) :

`php bin/console elastic:createtopbalanceaccountsfile`

- update last transactions (you might want to use a cron job) :

`php bin/console elastic:createlasttransactionsfile`
