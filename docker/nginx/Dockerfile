FROM nginx:1.17.10
LABEL maintainer="Stefan Gehrig <stefan.gehrig.hn@googlemail.com>"

RUN rm /etc/nginx/conf.d/default.conf \
    && mkdir /app \
    && mkdir /etc/nginx/ssl

COPY default.conf /etc/nginx/conf.d/iishf.conf
COPY iishf_test.crt /etc/nginx/ssl/iishf.crt
COPY iishf_test.key /etc/nginx/ssl/iishf.key

VOLUME /app
WORKDIR /app
