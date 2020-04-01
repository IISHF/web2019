#!/usr/bin/env bash

pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd -P`
popd > /dev/null

source ${SCRIPTPATH}/config.sh

ORGANIZATION=iishf
APPLICATION=iishf_public

DESCRIPTION=`LC_TIME="en_US.UTF-8" date "+backup %d %B %Y"`
NAME_PUBLIC=`date "+${APPLICATION}-%Y-%m-%d"`

SNAPSHOT_ID=`aws ec2 create-snapshot \
    --volume-id ${VOLUME_ID} \
    --description "${DESCRIPTION}" \
    --query "SnapshotId" \
    --output text`

AWS_PAGER="" \
aws ec2 create-tags \
    --resources ${SNAPSHOT_ID} \
    --tags Key=Name,Value=${NAME_PUBLIC} Key=Organization,Value=${ORGANIZATION}

echo ${SNAPSHOT_ID}
