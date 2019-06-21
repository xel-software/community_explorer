#!/bin/bash

echo "#####################"
echo "# STARTING SERVICES #"
echo "#####################"

/etc/init.d/redis-server restart
/etc/init.d/nginx restart
/etc/init.d/php7.2-fpm restart
/etc/init.d/cron restart

rm -Rf /xel-explorer/var/cache/*

cd /xel-explorer

echo "set config..."
if [ ! -z ${nxt_adminPassword+x} ]
then
  echo "set elastic_admin_passphrase"
  sed -i "/elastic_admin_passphrase: / s/: .*/: ${nxt_adminPassword}/" app/config/parameters.yml
fi
if [ ! -z ${explorer_elastic_daemon_address+x} ]
then
  echo "set elastic_daemon_address=${explorer_elastic_daemon_address}"
  sed -i "/elastic_daemon_address: / s/: .*/: ${explorer_elastic_daemon_address}/" app/config/parameters.yml
fi
if [ ! -z ${explorer_elastic_daemon_port+x} ]
then
  echo "set explorer_elastic_daemon_port=${explorer_elastic_daemon_port}"
  sed -i "/elastic_daemon_port: / s/: .*/: ${explorer_elastic_daemon_port}/" app/config/parameters.yml
fi
if [ ! -z ${explorer_main_account_passphrase+x} ]
then
  echo "set elastic_main_account_passphrase=${explorer_main_account_passphrase}"
  sed -i "/elastic_main_account_passphrase: / s/: .*/: ${explorer_main_account_passphrase}/" app/config/parameters.yml
fi
if [ ! -z ${explorer_main_account_address+x} ]
then
  echo "set elastic_main_account_address=${explorer_main_account_address}"
  sed -i "/elastic_main_account_address: / s/: .*/: ${explorer_main_account_address}/" app/config/parameters.yml
fi

if [ ! -z ${explorer_secret+x} ]
then
  echo "set secret"
  sed -i "/secret: / s/: .*/: ${explorer_secret}/" app/config/parameters.yml
else
  echo "set secret (generated)"
  UUID=$(cat /proc/sys/kernel/random/uuid)
  sed -i "/secret: / s/: .*/: ${UUID}/" app/config/parameters.yml
fi

echo "#####################"
echo "# STARTING EXPLORER #"
echo "#####################"
./deploy.sh

echo "set cronjobs..."
echo -e "*/2 * * * * cd /xel-explorer && php bin/console elastic:createtopbalanceaccountsfile \n*/2 * * * * cd /xel-explorer && php bin/console elastic:createlasttransactionsfile" | crontab -u www-data -

tail -f --retry /var/log/nginx/access.log
