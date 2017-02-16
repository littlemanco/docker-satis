#!/bin/bash
#
# Entrypoint for the docker container. This executes the satis build when the container is first run, so the user
# doesn't have to immediately poll the "/rebuild" endpoint of the server.
# 
# @copyright 2017 littleman.co
# @author    Andrew Howden <hello@andrewhowden.com>
# @license   MIT
#

# Exec satis
php /opt/satis/bin/satis build /etc/satis/satis.json /var/www/html/

# Exec apache
/usr/sbin/apache2ctl -DFOREGROUND
