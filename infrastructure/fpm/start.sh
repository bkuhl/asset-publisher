#!/bin/sh

# Add private key to server
if [ "${PRIVATE_KEY}" != "**None**" ]; then
    echo "=> Found private key"
    mkdir -p /root/.ssh
    chmod 700 /root/.ssh
    touch /root/.ssh/id_rsa
    chmod 600 /root/.ssh/id_rsa
    echo "=> Adding private key to /root/.ssh/id_rsa"
    echo "${PRIVATE_KEY}" >> /root/.ssh/id_rsa
else
    echo "ERROR: No private keys found in \$PRIVATE_KEY"
    exit 1
fi

# Start FPM
php-fpm