#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

sudo mysqldump \
    --skip-opt \
    --add-drop-table \
    --create-options \
    --quick \
    --extended-insert \
    --set-charset \
    --disable-keys \
    --hex-blob \
    --flush-logs \
    --lock-all-tables \
    --routines \
    --triggers \
    --default-character-set=utf8mb4 \
    iishf \
    | gzip > /tmp/dbbackup.gz
