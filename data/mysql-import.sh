#!/bin/sh

set -e

host="$1"

>&2 echo "Waiting for database server to be up"
until mysql -h"$host" -uroot -e '\q'; do
    sleep 0.25
done
>&2 echo "Database is up - importing data"

exec mysql -h"$host" -uroot < /tmp/db.sql
