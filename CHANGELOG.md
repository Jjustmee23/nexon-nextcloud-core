# Changelog — Nexon Nextcloud Core (AGPL)

All AGPL-covered Nextcloud core modifications must be recorded here.

Format: [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)

## [Unreleased]

### Added
- Initial public AGPL repository structure
- `custom.config.php` overlay: Redis locking, trusted proxy, Iraq locale defaults
- `Dockerfile` extending official `nextcloud:stable-apache` image
- AGPL source offer documentation

### Changed
- `custom.config.php`: `enabledPreviewProviders` for HEIC/TIFF/XBitmap Mail attachment previews (Viewer app)
- `custom.config.php`: `trusted_proxies` uses Docker CIDR ranges; `forwarded_for_headers`, `serverid`

### Removed
- (none yet)

## [0.1.0] - 2026-06-10

### Added
- Repository bootstrap for Nextsolution Cloud (Nexon Solutions) pilot
