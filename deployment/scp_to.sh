#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

source ${SCRIPTPATH}/config.sh

scp -i ${SCRIPTPATH}/keys/${KEY_FILE} ${2} ubuntu@${IP}:${3}
