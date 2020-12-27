#!/bin/bash
ENV=/var/www/html/.env
EXAMPLE=/var/www/html/.env.example
    if [ -f "$ENV" ]
    then
        echo "$ENV exists, not creating"
    else
        echo "$ENV doesn't exist, checking for example to copy ..."
        if [ -f "$EXAMPLE" ]
        then
            echo "$EXAMPLE does exist, copying to .env"
            cp /var/www/html/.env.example /var/www/html/.env
        else
            echo ".env.example not found, .env not created"
        fi
    fi

echo "running composer install"
cd /var/www/html &&
composer install

echo "creating sqlite db"
touch /var/www/html/database/database.sqlite

echo "setting db permissions"
cd /var/www/html/database &&
chmod 777 database.sqlite

echo "running migrate install"
cd /var/www/html &&
php artisan migrate:install

echo "running migrate"
cd /var/www/html &&
php artisan migrate

echo "running items seeder"
cd /var/www/html &&
php artisan db:seed --class=ItemTableSeeder

apachectl -D FOREGROUND
