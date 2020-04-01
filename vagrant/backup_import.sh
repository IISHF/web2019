#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

sudo mysqladmin drop iishf
sudo mysqladmin create iishf

gunzip ${SCRIPTPATH}/dbbackup.gz

sudo mysql --default-character-set=utf8mb4 iishf < ${SCRIPTPATH}/dbbackup
