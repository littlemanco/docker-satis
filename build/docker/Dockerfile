FROM quay.io/littlemanco/apache-php:7.1.2-2
MAINTAINER Andrew Howden <hello@andrewhowden.com>

ENV SERVER_NAME="repos.example.com"

ADD "app" "/opt/satis"
ADD "etc/apache2/sites-enabled" "/etc/apache2/sites-enabled"

VOLUME "/etc/satis/"