#!/bin/bash
composer install
php bin/console cache:clear --env=dev --no-warmup
php bin/console cache:clear --env=prod --no-warmup
php bin/console assets:install web --symlink
php bin/console doctrine:cache:clear-result
php bin/console doctrine:cache:clear-query
php bin/console doctrine:cache:clear-metadata
php bin/console doctrine:generate:entities Elastic --no-backup
php bin/console doctrine:schema:update --force
#composer dump-autoload --optimize
