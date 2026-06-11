<?php
/**
 * Nexon Cloud — Nextcloud system config overlay.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 * Based on Nextcloud Server (https://github.com/nextcloud/server)
 */
$CONFIG = [
  'default_phone_region' => 'IQ',
  'maintenance_window_start' => 1,
  'log_type' => 'file',
  'loglevel' => 2,
  'logtimezone' => getenv('TZ') ?: 'UTC',
  'filelocking.enabled' => true,
  'memcache.locking' => '\\OC\\Memcache\\Redis',
  'memcache.local' => '\\OC\\Memcache\\APCu',
  'redis' => [
    'host' => getenv('REDIS_HOST') ?: 'nextcloud-redis',
    'port' => 6379,
    'password' => getenv('REDIS_HOST_PASSWORD') ?: '',
  ],
  'trusted_proxies' => array_values(array_filter(array_map('trim', explode(',', getenv('NEXTCLOUD_TRUSTED_PROXIES') ?: '172.16.0.0/12,127.0.0.1,::1')))),
  'forwarded_for_headers' => ['HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED'],
  'serverid' => getenv('NEXTCLOUD_SERVER_ID') ?: 'nexon-cloud-primary',
  'overwriteprotocol' => 'https',
  'overwritehost' => getenv('CLOUD_DOMAIN') ?: '',
  'overwrite.cli.url' => getenv('OVERWRITECLIURL') ?: '',
  'default_language' => 'en',
  'default_locale' => 'en_US',
  'skeletondirectory' => '',
  'simpleSignUpLink.shown' => false,
  'knowledgebaseenabled' => false,
  'has_internet_connection' => false,
  'allow_local_remote_servers' => true,
  'app.mail.verify-tls-peer' => false,
  'mail_smtpstreamoptions' => [
    'ssl' => [
      'allow_self_signed' => true,
      'verify_peer' => false,
      'verify_peer_name' => false,
    ],
  ],
  'enabledPreviewProviders' => [
    'OC\\Preview\\BMP',
    'OC\\Preview\\PNG',
    'OC\\Preview\\JPEG',
    'OC\\Preview\\GIF',
    'OC\\Preview\\XBitmap',
    'OC\\Preview\\WebP',
    'OC\\Preview\\HEIC',
    'OC\\Preview\\TIFF',
    'OC\\Preview\\MarkDown',
    'OC\\Preview\\MP3',
    'OC\\Preview\\TXT',
    'OC\\Preview\\PDF',
  ],
];
