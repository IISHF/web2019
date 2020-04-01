#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

echo "Dropping current iishf database if available"
sudo mysqladmin drop iishf
echo "Creating new iishf database"
sudo mysqladmin create iishf

echo "Unzipping"
gunzip /tmp/dbbackup.gz

echo "Importing into iishf database"
sudo mysql --default-character-set=utf8mb4 iishf < /tmp/dbbackup
