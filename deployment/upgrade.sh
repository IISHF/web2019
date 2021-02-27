#!/usr/bin/env bash

pushd $(dirname $0) >/dev/null
SCRIPTPATH=$(pwd -P)
popd >/dev/null

cd ${SCRIPTPATH}/../

/usr/local/bin/composer install --no-dev --prefer-dist --optimize-autoloader --apcu-autoloader
APP_ENV=dev ./bin/console cache:clear --no-warmup
APP_ENV=dev ./bin/console doctrine:migrations:migrate --no-interaction
rm -rf ./var/cache/*
APP_ENV=prod ./bin/console cache:clear --no-warmup
APP_ENV=prod ./bin/console cache:warmup

cd -

sudo service php7.4-fpm restart
