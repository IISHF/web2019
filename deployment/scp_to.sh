#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

source ${SCRIPTPATH}/config.sh

scp -r -i ${SCRIPTPATH}/keys/${KEY_FILE} ${1} ubuntu@${IP}:${2}
