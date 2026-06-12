FROM nextcloud:stable-apache

COPY custom.config.php /usr/src/nextcloud/config/custom.config.php
COPY apps/nexon_platform /usr/src/nextcloud/apps/nexon_platform
