#!/bin/sh
set -e

cp .env.example .env

php artisan key:generate --force
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force

chown -R www-data:www-data database

exec supervisord -c /etc/supervisord.conf
