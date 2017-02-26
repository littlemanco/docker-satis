FROM quay.io/littlemanco/apache-php:7.1.1-1
MAINTAINER Andrew Howden <hello@andrewhowden.com>

ENV SERVER_NAME="repos.example.com"
ENV DOCUMENT_ROOT="/opt/satis/public"

# Note: Apache likely expects to be able to push mail to this address, however will not be able to as there is no
# sendmail binary in the container
ENV SERVER_ADMIN="webmaster@repos.example.com"

ADD "app" "/opt/satis"
ADD "etc/apache2/sites-enabled" "/etc/apache2/sites-enabled"

VOLUME "/etc/satis/"