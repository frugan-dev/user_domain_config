[![Build Status](https://github.com/frugan-dev/user_domain_config/actions/workflows/release.yml/badge.svg)](https://github.com/frugan-dev/user_domain_config/actions/workflows/release.yml)
[![Packagist Version](https://img.shields.io/packagist/v/frugan-dev/user_domain_config)](https://packagist.org/packages/frugan-dev/user_domain_config)
[![GitHub Release](https://img.shields.io/github/v/release/frugan-dev/user_domain_config)](https://github.com/frugan-dev/user_domain_config/releases)
[![License](https://img.shields.io/github/license/frugan-dev/user_domain_config)](https://github.com/frugan-dev/user_domain_config/blob/main/COPYING)

# Per-User-Domain Configuration Plugin for Roundcube

Loads a configuration file for the domain of the address the user logs in
with, on top of the main one: `alice@example.com` gets `example.com.inc.php`.
One webmail can then serve mailboxes that live on different servers, or give
each domain its own settings.

Roundcube's own
[Multi-Domain Setup](https://github.com/roundcube/roundcubemail/wiki/Configuration:-Multi-Domain-Setup)
picks the file from the host name the webmail is reached on. This plugin
picks it from the username, with the same file naming, so every domain can
share a single webmail host.

---

## How it works

- On the login request the domain comes from the username typed in the form,
  before the password is checked: that is what lets the file choose the IMAP
  server. On the following requests it comes from the username of the
  session.
- The domain is what follows the last `@`, in lowercase, and it must be a
  valid host name, otherwise nothing is loaded. The value becomes part of a
  file path: a slash or a `..` would let the login form choose the file.
- `<domain>.inc.php` is loaded with Roundcube's own configuration loader, from
  the same directories as `config.inc.php` (those in `RCUBE_CONFIG_PATH`,
  then `config/`). Its settings override the main ones.
- No file for the domain, no change.

## Requirements

- Roundcube 1.5 or newer (CI runs 1.6 and 1.7)
- PHP 7.1 or newer

## Installation

### Composer

From the Roundcube root:

```bash
composer require frugan-dev/user_domain_config
```

Then enable it in `config/config.inc.php`, if the installer did not:

```php
$config['plugins'] = ['user_domain_config'];
```

### Official Docker image

```yaml
services:
  roundcube:
    image: roundcube/roundcubemail:1.7.x-apache
    environment:
      ROUNDCUBEMAIL_COMPOSER_PLUGINS: frugan-dev/user_domain_config
      ROUNDCUBEMAIL_PLUGINS: user_domain_config
      RCUBE_CONFIG_PATH: /var/roundcube/domains
    volumes:
      - ./domains:/var/roundcube/domains:ro
```

Keep the per-domain files out of `/var/roundcube/config`: the entrypoint of
the image includes every `*.php` file there, for every user.

## Configuration

One file per domain, named after it:

```php
<?php

// example.com.inc.php
$config['imap_host'] = 'ssl://imap.example.com:993';
$config['smtp_host'] = 'ssl://smtp.example.com:465';
$config['product_name'] = 'Example Mail';
```

Any setting of `config.inc.php` can go there. `imap_host` and `smtp_host` are
the names since Roundcube 1.6; 1.5 calls them `default_host` and
`smtp_server`.

## Development

Everything runs in Docker. The stack in `docker-compose.yml` is the official
Roundcube image with this checkout mounted as a plugin, and
[GreenMail](https://greenmail-mail-test.github.io/greenmail/) as IMAP and SMTP
server, which accepts any user and password.

```bash
docker compose up -d --wait
tests/smoke.sh
```

Log in at <http://localhost:8080> as anyone@example.test, with any password:
the page shows the product name set in `tests/fixtures/domains`.
`tests/smoke.sh` runs the same login with several usernames, path traversal
attempts included, and needs only `curl`. Another Roundcube version:

```bash
docker compose down
ROUNDCUBE_TAG=1.6.x-apache docker compose up -d --wait
```

Formatting is checked by [Pint](https://laravel.com/docs/pint):

```bash
docker compose run --rm composer install
docker compose run --rm composer lint
docker compose run --rm composer lint:fix
```

## Release

Every push to `main` runs CI, then
[semantic-release](https://semantic-release.gitbook.io): the commit types
decide the version (see [CONTRIBUTING](.github/CONTRIBUTING.md)), and the
release is a `vX.Y.Z` tag with its notes on the
[releases page](https://github.com/frugan-dev/user_domain_config/releases).
Packagist picks up the tag on its own.

## Related

- [roundcube-plugin-user-config](https://github.com/sbehuret/roundcube-plugin-user-config),
  which inspired this plugin
- [roundcube-plugin-identity-from-config](https://github.com/foundata/roundcube-plugin-identity-from-config)

## Contributing

For your contributions please use:

- [Conventional Commits](https://www.conventionalcommits.org)
- [Pull request workflow](https://docs.github.com/en/get-started/exploring-projects-on-github/contributing-to-a-project)

See [CONTRIBUTING](.github/CONTRIBUTING.md) for detailed guidelines.

## Sponsor

[<img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" width="200" alt="Buy Me A Coffee">](https://buymeacoff.ee/frugan)

## License

(ɔ) Copyleft 2024 [Frugan](https://frugan.it).
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/), see the
[COPYING](COPYING) file.
