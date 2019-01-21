#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

CERT_PATH=${SCRIPTPATH}

openssl req \
    -x509 \
    -nodes \
    -days 365 \
    -newkey rsa:4096 \
    -keyout ${CERT_PATH}/iishf-test.key \
    -out ${CERT_PATH}/iishf-test.crt
