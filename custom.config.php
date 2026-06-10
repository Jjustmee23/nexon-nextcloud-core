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
  'trusted_proxies' => [
    'edge-caddy',
  ],
  'overwriteprotocol' => 'https',
  'overwritehost' => getenv('CLOUD_DOMAIN') ?: '',
  'overwrite.cli.url' => getenv('OVERWRITECLIURL') ?: '',
  'default_language' => 'en',
  'default_locale' => 'en_US',
  'skeletondirectory' => '',
];
