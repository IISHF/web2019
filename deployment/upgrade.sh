#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

cd ${SCRIPTPATH}/../

/usr/local/bin/composer install --prefer-dist --optimize-autoloader --apcu-autoloader
./app/console cache:clear --env=dev --no-debug --no-warmup
./app/console doctrine:migrations:migrate --no-interaction
./app/console cache:clear --env=prod --no-debug --no-warmup
./app/console cache:warmup --env=prod --no-debug

cd -

sudo service php7.2-fpm restart
