#!/bin/bash

APP_DIR=$(pwd)
APP_OWNER="www-data"
COMPOSER=$(which composer)
CHOWN=$(which chown)

echo "load geoip database, it can take a while..."
php "$APP_DIR/bin/console" doctrine:fixtures:load -q >/dev/null
echo "geoip database loaded !"
