#!/bin/bash

sleep 20
php /var/www/html/artisan migrate
apache2-foreground