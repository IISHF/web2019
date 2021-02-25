#!/usr/bin/env bash

pushd $(dirname $0) >/dev/null
SCRIPTPATH=$(pwd -P)
popd >/dev/null

BUILD_PATH=${SCRIPTPATH}/../public/build

yarn --cwd ${SCRIPTPATH}/../ build

${SCRIPTPATH}/scp_to.sh ${BUILD_PATH} /home/ubuntu/iishf/public
