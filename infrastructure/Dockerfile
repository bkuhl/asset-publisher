FROM realpage/fpm-nginx:7

RUN \
    # fix permissions locally
    #sed -ri 's/^www-data:x:82:82:/www-data:x:1000:1000:/' /etc/passwd \
    #&& sed -ri 's/^www-data:x:82:/www-data:x:50:/' /etc/group \

    # fix permissions in CI
    sed -ri 's/^www-data:x:82:82:/www-data:x:1001:1001:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1001:/' /etc/group \

    # fix permissions in CI
    && apk add --update --no-cache \
        # needed for mcrypt
        libmcrypt \

        # needed for composer
        git zip unzip \

        # needed to clone repos
        openssh \

    # php extensions the app will need
    && docker-php-ext-install mcrypt mbstring pdo_mysql

# Copy the application files to the container
ADD . /var/www/html
ADD infrastructure/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
ADD infrastructure/nginx/nginx.conf /etc/nginx/nginx.conf

RUN \
    # install composer
    wget -O /usr/bin/composer http://getcomposer.org/composer.phar \
    && chmod +x /usr/bin/composer \
    && mkdir -p /home/www-data/.composer \

    # parallel dependency installation
    && composer global require hirak/prestissimo \

    # give www-data the keys
    && chown -R www-data:www-data /var/www/html /usr/bin/composer /home/www-data \

    && chmod -R 744 /home/www-data/.composer /var/www/html/storage/git

WORKDIR /var/www/html

USER www-data

RUN chmod +x infrastructure/start.sh \
    && chmod +x infrastructure/env_init.sh \

    # production-ready dependencies
    && composer install  --no-interaction --no-dev --prefer-dist

USER root

# override base container's entrypoint so we can use CMD
ENTRYPOINT []

# Add private key and then start the process monitor
CMD /var/www/html/infrastructure/start.sh;/init