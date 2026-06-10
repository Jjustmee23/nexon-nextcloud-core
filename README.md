# Nexon Nextcloud Core (AGPL)

Public repository for all Nextcloud Server core modifications used by the Nextsolution Cloud (Nexon Solutions) platform.

**This repository must remain public at all times** to comply with the GNU Affero General Public License v3.0 (AGPL-3.0).

## Repository links

| Repository | Visibility | Purpose |
|---|---|---|
| [nexon-cloud](https://github.com/Jjustmee23/nexon-cloud) | Private | Platform infrastructure, Docker, scripts, branding, ops |
| [nexon-nextcloud-core](https://github.com/Jjustmee23/nexon-nextcloud-core) | **Public** | Nextcloud core modifications (AGPL) |

## What belongs here

Place in this public repo anything that modifies or extends Nextcloud Server core source code:

- Patches to `server/` PHP, JS, or CSS under AGPL
- Custom Nextcloud apps that ship as AGPL derivative works
- Core hooks, overrides, or forked server files
- Build scripts that embed modified core sources

## What stays in the private repo

Configuration-only overlays that do not modify AGPL-covered source:

- `custom.config.php` (system config overlay)
- Docker Compose service definitions
- Init scripts (`occ` commands, app enablement)
- Branding assets applied via theming API
- Infrastructure (Caddy, Keycloak, MinIO, mailcow integration)

If a change touches AGPL-covered Nextcloud core files, it **must** be published here before or together with deployment.

## Current contents

| Path | Description |
|---|---|
| `custom.config.php` | Redis locking, proxy, locale defaults for Iraq |
| `Dockerfile` | Extends `nextcloud:stable-apache` with config overlay |
| `modifications/` | Directory for future core patches (empty until first patch) |
| `docs/SOURCE-OFFER.md` | AGPL source offer and contact details |

## AGPL obligations

1. Keep this repository public and accessible.
2. Publish complete corresponding source for every AGPL-covered modification.
3. Include `LICENSE` and copyright notices in modified files.
4. Document each change in `CHANGELOG.md`.
5. Push to this repo **before** deploying modified core to production.

## Upstream

Based on [Nextcloud Server](https://github.com/nextcloud/server) (AGPL-3.0).
