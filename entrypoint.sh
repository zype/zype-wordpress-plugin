#!/bin/bash
# Trick to get host.docker.internal working on Linux Docker
# Idea taken from https://dev.to/bufferings/access-host-from-a-docker-container-4099
HOST_DOMAIN="host.docker.internal"
HOST_IP=`/sbin/ip route|awk '/default/ { print $3 }'`
echo "${HOST_IP} ${HOST_DOMAIN}" | tee -a /etc/hosts

echo 'starting Wordpress container'