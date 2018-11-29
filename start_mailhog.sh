#!/bin/bash

docker run \
    --rm \
    -ti \
    -p 8025:8025 \
    -p 1025:1025 \
    --name mailhog \
    mailhog/mailhog
