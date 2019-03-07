#!/bin/sh

BASE_DIR=$(dirname $(readlink -f "$0"))
if [ "$1" != "test" ]; then
    psql -h localhost -U segundo -d segundo < $BASE_DIR/segundo.sql
fi
psql -h localhost -U segundo -d segundo_test < $BASE_DIR/segundo.sql
