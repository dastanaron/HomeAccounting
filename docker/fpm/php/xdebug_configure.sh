#!/usr/bin/env bash

if [ "$XDEBUG_ENABLED" = "true" ]; then
    echo 'configuring xdebug'
    echo xdebug.remote_enable=1 >> $xdebug_config_path
    echo xdebug.idekey=accounting >> $xdebug_config_path
    echo xdebug.remote_host=`ip route|awk '/default/ { print $3 }'` >> $xdebug_config_path
    echo xdebug.remote_connect_back=0 >> $xdebug_config_path
    echo '========== finish configure xdebug ==========='
else
    echo $XDEBUG_ENABLED
fi