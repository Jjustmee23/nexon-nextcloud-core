FROM nextcloud:stable-apache

COPY custom.config.php /usr/src/nextcloud/config/custom.config.php
