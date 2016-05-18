FROM realpage/nginx:1.9
MAINTAINER Foundation DevOps foundation-devops@realpage.com
LABEL com.realpage.nginx.version="1.9"

# Copy the application public files to the container
ADD public /var/www/html/public
ADD infrastructure/nginx /etc/nginx/conf.d

WORKDIR /var/www/html

RUN chown -R nginx:nginx public/
