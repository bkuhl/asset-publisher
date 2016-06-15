#!/bin/sh

echo "Setting environment variables for php-fpm..."
export > /tmp/vars

php /var/www/html/infrastructure/env-vars-fpm.php

rm /tmp/vars