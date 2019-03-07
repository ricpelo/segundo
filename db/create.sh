#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE segundo_test;"
    psql -U postgres -c "CREATE USER segundo PASSWORD 'segundo' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists segundo
    sudo -u postgres dropdb --if-exists segundo_test
    sudo -u postgres dropuser --if-exists segundo
    sudo -u postgres psql -c "CREATE USER segundo PASSWORD 'segundo' SUPERUSER;"
    sudo -u postgres createdb -O segundo segundo
    sudo -u postgres psql -d segundo -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O segundo segundo_test
    sudo -u postgres psql -d segundo_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:segundo:segundo"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
