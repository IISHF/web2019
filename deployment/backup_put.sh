#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

VAGRANTPATH=${SCRIPTPATH}/../vagrant

${SCRIPTPATH}/scp_to.sh public ${VAGRANTPATH}/dbbackup.gz /tmp/
