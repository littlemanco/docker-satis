FROM quay.io/littlemanco/apache-php:7.1.1-1
MAINTAINER Andrew Howden <hello@andrewhowden.com>

ADD "satis" "/opt/satis"
ADD "public" "/var/www/html"